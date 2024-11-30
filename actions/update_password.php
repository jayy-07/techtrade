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
    
    $currentPassword = trim($_POST['current_password']);
    $newPassword = trim($_POST['new_password']);

    // Validate inputs
    if (empty($currentPassword) || empty($newPassword)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // Validate password pattern
    $pattern = "/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{6,})\S$/";
    if (!preg_match($pattern, $newPassword)) {
        echo json_encode([
            'success' => false, 
            'message' => 'Password must be at least 6 characters with 1 uppercase letter, 1 lowercase letter, and 1 number. No spaces.'
        ]);
        exit;
    }

    // Verify current password
    if (!$userController->verifyPassword($_SESSION['user_id'], $currentPassword)) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
        exit;
    }

    // Update password
    $result = $userController->updatePassword($_SESSION['user_id'], $newPassword);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Password updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update password.']);
    }
} 