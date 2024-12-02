<?php
require_once '../settings/core.php';
require_once '../controllers/UserController.php';

// Check if user is logged in before proceeding
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to continue.']);
    exit;
}

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize user controller
    $userController = new UserController();
    
    // Sanitize input fields by removing whitespace
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $regionId = trim($_POST['region_id']);

    // Validate that all required fields are provided
    if (empty($address) || empty($city) || empty($regionId)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // Ensure region ID is a valid number
    if (!is_numeric($regionId)) {
        echo json_encode(['success' => false, 'message' => 'Invalid region selected.']);
        exit;
    }

    // Attempt to update address in database
    $result = $userController->updateAddress([
        'user_id' => $_SESSION['user_id'],
        'address' => $address,
        'city' => $city,
        'region_id' => $regionId
    ]);

    // Return success/failure response
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Address updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update address.']);
    }
} 