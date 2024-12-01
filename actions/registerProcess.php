<?php

include '../controllers/RegisterController.php';

// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new RegisterController();
    $result = $controller->register($_POST);
    echo json_encode($result);
}
