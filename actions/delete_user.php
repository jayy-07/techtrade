<?php
// Include the UserController class
require_once '../controllers/UserController.php';

// Create a new instance of UserController
$controller = new UserController();

// Check if the request is POST and user_id is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    // Convert user_id to integer for safety
    $userId = intval($_POST['user_id']);

    // Attempt to delete the user
    if ($controller->deleteUser($userId)) {
        // Return success response if user was deleted
        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    } else {
        // Return error response if deletion failed
        echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
    }
} else {
    // Return error for invalid requests (not POST or missing user_id)
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
