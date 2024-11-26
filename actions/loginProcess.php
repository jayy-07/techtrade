<?php
session_start(); // Ensure the session is started

include '../controllers/LoginController.php';

// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new LoginController();
    $result = $controller->login($_POST);
    echo json_encode($result);
}
