<?php
/**
 * Payment Initialization Script
 * 
 * This script handles the initialization of payments through Paystack payment gateway.
 * It creates an order and initializes the payment process for the user's cart items.
 */

session_start();
require_once '../classes/Payment.php';
require_once '../classes/Cart.php'; 
require_once '../controllers/OrderController.php';

// Set response header to JSON
header('Content-Type: application/json');

try {
    // Initialize required objects
    $cart = new Cart();
    $payment = new Payment();
    $orderController = new OrderController();

    // Get the total amount in user's cart
    $cartTotal = $cart->getCartTotal($_SESSION['user_id']);
    
    // Validate cart total is greater than zero
    if ($cartTotal['final_total'] <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid cart total']);
        exit;
    }

    // Prepare order data from cart and POST parameters
    $orderData = [
        'user_id' => $_SESSION['user_id'],
        'total_amount' => $cartTotal['final_total'],
        'trade_in_credit' => $cartTotal['total_trade_in'],
        'shipping_address' => $_POST['address'],
        'phone_number' => $_POST['phone']
    ];

    // Create new order in database
    $order_id = $orderController->createOrder($orderData);

    // Verify order creation was successful
    if (!$order_id) {
        echo json_encode(['success' => false, 'message' => 'Failed to create order']);
        exit;
    }

    // Initialize payment with Paystack
    $paymentData = $payment->initializePayment(
        $order_id,
        $_POST['email'],
        $cartTotal['final_total']
    );

    // Return payment authorization URL if successful
    if ($paymentData) {
        echo json_encode([
            'success' => true,
            'authorization_url' => $paymentData['authorization_url']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Payment initialization failed']);
    }

} catch (Exception $e) {
    // Log any errors and return generic error message
    error_log("Payment initialization error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
} 