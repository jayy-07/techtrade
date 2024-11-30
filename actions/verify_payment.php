<?php
session_start();
require_once '../classes/Payment.php';
require_once '../classes/Cart.php';
require_once '../controllers/OrderController.php';

if (!isset($_GET['reference'])) {
    header("Location: ../view/cart.php");
    exit;
}

try {
    $payment = new Payment();
    $reference = $_GET['reference'];
    
    error_log("Verifying payment for reference: " . $reference);
    
    // Verify the payment with Paystack
    $verificationData = $payment->verifyPayment($reference);
    error_log("Verification data: " . print_r($verificationData, true));
    
    if ($verificationData && $verificationData['status'] === 'success') {
        // Extract order_id from reference (TRX_timestamp_orderid)
        $parts = explode('_', $reference);
        $order_id = end($parts);
        
        error_log("Updating order status for order ID: " . $order_id);
        
        // Update order payment status first
        $orderController = new OrderController();
        $success = $orderController->updateOrderStatus($order_id, 'Completed');
        
        if (!$success) {
            throw new Exception("Failed to update order status");
        }
        
        // Store payment transaction data
        $payment->storeTransaction([
            'order_id' => $order_id,
            'reference' => $reference,
            'amount' => $verificationData['amount'],
            'status' => 'Completed',
            'transaction_data' => json_encode($verificationData)
        ]);
        
        // Clear the user's cart
        $cart = new Cart();
        $cart->clearCart($_SESSION['user_id']);
        
        // Redirect to success page
        header("Location: ../view/order_success.php?order_id=" . $order_id);
        exit;
    } else {
        error_log("Payment verification failed");
        header("Location: ../view/checkout.php?error=payment_failed");
        exit;
    }
} catch (Exception $e) {
    error_log("Payment verification error: " . $e->getMessage());
    header("Location: ../view/checkout.php?error=verification_failed");
    exit;
} 