<?php
require_once '../settings/core.php';
require_once '../controllers/UserController.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to continue.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController = new UserController();
    
    // Sanitize inputs
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $regionId = trim($_POST['region_id']);

    // Validate inputs
    if (empty($address) || empty($city) || empty($regionId)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // Validate region_id is numeric
    if (!is_numeric($regionId)) {
        echo json_encode(['success' => false, 'message' => 'Invalid region selected.']);
        exit;
    }

    $result = $userController->updateAddress([
        'user_id' => $_SESSION['user_id'],
        'address' => $address,
        'city' => $city,
        'region_id' => $regionId
    ]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Address updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update address.']);
    }
} 