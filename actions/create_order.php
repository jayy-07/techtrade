<?php
require_once '../settings/core.php';
require_once '../controllers/OrderController.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

try {
    $orderController = new OrderController();
    
    $result = $orderController->createOrder(
        $_SESSION['user_id'],
        $_POST['address'],
        $_POST['email'],
        $_POST['phone']
    );

    if ($result['success']) {
        $order = $orderController->getOrder($result['order_id']);
        echo json_encode([
            'success' => true,
            'order_id' => $result['order_id'],
            'email' => $order['email'],
            'amount' => $order['total_amount']
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => $result['error']]);
    }
} catch (Exception $e) {
    error_log("Error in create_order.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Failed to create order']);
} 