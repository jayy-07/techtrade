<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');



require_once '../classes/Payment.php';
require_once '../classes/Cart.php';
require_once '../controllers/OrderController.php';

// Redirect if no reference parameter is provided
if (!isset($_GET['reference'])) {
    header("Location: ../view/cart.php");
    exit;
}

try {
    // Initialize payment object
    $payment = new Payment();
    $reference = $_GET['reference'];
    
    // Log payment verification attempt
    error_log("Verifying payment for reference: " . $reference);
    
    // Verify the payment with Paystack API
    $verificationData = $payment->verifyPayment($reference);
    error_log("Verification data: " . print_r($verificationData, true));
    
    // If payment verification is successful
    if ($verificationData && $verificationData['status'] === 'success') {
        // Extract order ID from reference string (format: TRX_timestamp_orderid)
        $parts = explode('_', $reference);
        $order_id = end($parts);
        
        error_log("Updating order status for order ID: " . $order_id);
        
        // Update the order status to completed
        $orderController = new OrderController();
        $success = $orderController->updateOrderStatus($order_id, 'Completed');
        
        if (!$success) {
            // Log the issue but don't throw an exception
            error_log("Warning: Initial attempt to update order status failed for order ID: " . $order_id);
            
            // Retry the update (optional)
            $retrySuccess = $orderController->updateOrderStatus($order_id, 'Completed');
            
            if (!$retrySuccess) {
                error_log("Error: Failed to update order status after retry for order ID: " . $order_id);
                // Continue with the process instead of throwing exception
            }
        }
        
        // Continue with storing transaction and clearing cart
        $payment->storeTransaction([
            'order_id' => $order_id,
            'reference' => $reference,
            'amount' => $verificationData['amount'],
            'status' => 'Completed',
            'transaction_data' => json_encode($verificationData)
        ]);
        
        // Clear items from user's cart after successful payment
        $cart = new Cart();
        $cart->clearCart($_SESSION['user_id']);
        
        // Redirect to success page with order ID
        header("Location: ../view/order_success.php?order_id=" . $order_id);
        exit;
    } else {
        // Log and redirect if payment verification fails
        error_log("Payment verification failed");
        header("Location: ../view/checkout.php?error=payment_failed");
        exit;
    }
} catch (Exception $e) {
    // Log any errors and redirect to checkout with error message
    error_log("Payment verification error: " . $e->getMessage());
    header("Location: ../view/checkout.php?error=verification_failed");
    exit;
} 