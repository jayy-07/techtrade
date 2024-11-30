<?php
session_start();
require_once '../classes/Payment.php';
require_once '../classes/Cart.php';
require_once '../controllers/OrderController.php';

header('Content-Type: application/json');

try {
    $cart = new Cart();
    $payment = new Payment();
    $orderController = new OrderController();

    // Get cart total
    $cartTotal = $cart->getCartTotal($_SESSION['user_id']);
    
    if ($cartTotal['final_total'] <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid cart total']);
        exit;
    }

    //print_r($_POST);

    // Create order first
    $orderData = [
        'user_id' => $_SESSION['user_id'],
        'total_amount' => $cartTotal['final_total'],
        'trade_in_credit' => $cartTotal['total_trade_in'],
        'shipping_address' => $_POST['address'],
        'phone_number' => $_POST['phone']
    ];

    // Create order using the controller
    $order_id = $orderController->createOrder($orderData);

    if (!$order_id) {
        echo json_encode(['success' => false, 'message' => 'Failed to create order']);
        exit;
    }

    // Initialize Paystack payment
    $paymentData = $payment->initializePayment(
        $order_id,
        $_POST['email'],
        $cartTotal['final_total']
    );

    if ($paymentData) {
        echo json_encode([
            'success' => true,
            'authorization_url' => $paymentData['authorization_url']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Payment initialization failed']);
    }

} catch (Exception $e) {
    error_log("Payment initialization error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
} 