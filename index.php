<?php
session_start();

if (isset($_SESSION['user_id'])) {
  header("Location: views/dashboard.php");
  exit;
}

header("Location: login/login.php");
exit;
?>
