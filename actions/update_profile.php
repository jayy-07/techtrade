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
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    // Validate inputs
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
        exit;
    }

    // Check if email already exists (excluding current user)
    if ($userController->emailExists($email, $_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Email is already in use.']);
        exit;
    }

    $result = $userController->updateProfile([
        'user_id' => $_SESSION['user_id'],
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'phone' => $phone
    ]);

    if ($result) {
        // Update session variables
        $_SESSION['user_email'] = $email;
        $_SESSION['full_name'] = $firstName . ' ' . $lastName;
        $_SESSION['user_phone'] = $phone;

        echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update profile.']);
    }
} 