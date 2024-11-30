<?php
require_once '../settings/core.php';

session_start();
session_destroy();
header('Location: ../index.php');
exit;