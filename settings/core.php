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

function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login/login.php");
        exit;
    }
}

function check_admin() {
    check_login();
    if ($_SESSION['role'] !== 'Administrator') {
        header("Location: ../view/home.php");
        exit;
    }
}

function check_seller() {
    check_login();
    if ($_SESSION['role'] !== 'Seller') {
        header("Location: ../view/home.php");
        exit;
    }
}

function check_customer() {
    check_login();
    if ($_SESSION['role'] !== 'Customer') {
        if ($_SESSION['role'] === 'Administrator') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../seller/dashboard.php");
        }
        exit;
    }
}
?>
