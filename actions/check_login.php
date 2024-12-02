<?php
require_once '../settings/core.php';
header('Content-Type: application/json');

echo json_encode([
    'logged_in' => isset($_SESSION['user_id']),
    'user_id' => $_SESSION['user_id'] ?? null
]);
