<?php
// Start session
session_start();
ob_start();

// Function to check if a user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Function to get the current user ID
function get_user_id() {
    return $_SESSION['user_id'] ?? null;
}

// Function to check user role
function check_user_role($role) {
    return $_SESSION['user_role'] === $role;
}

// Function to redirect to a page
function redirect($url) {
    header("Location: $url");
    exit;
}
?>
