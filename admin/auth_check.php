<?php
// Admin authentication check
if (!defined('APP_ACCESS')) {
    die('Direct access not permitted');
}

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'auth/login.php');
    exit();
}
