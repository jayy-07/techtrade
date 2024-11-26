<?php
require_once '../controllers/UserController.php';

ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '../error/php-error.log');
error_reporting(E_ALL);

$controller = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'update':
            $userId = intval($_POST['user_id']);
            $role = $_POST['role'] ?? '';

            if ($controller->updateUserRole($userId, $role)) {
                echo json_encode(["success" => true, "message" => "User updated successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to update user."]);
            }
            break;

        case 'delete':
            $userId = intval($_POST['user_id']);

            if ($controller->deleteUser($userId)) {
                echo json_encode(["success" => true, "message" => "User deleted successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to delete user."]);
            }
            break;

        default:
            http_response_code(400);
            echo json_encode(["error" => "Invalid action"]);
            break;
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
}
