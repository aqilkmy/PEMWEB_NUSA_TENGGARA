<?php
session_start();
define('APP_ACCESS', true);

require_once '../config/config.php';
require_once '../includes/functions.php';

// Destroy session
session_destroy();

// Redirect to login
redirect(BASE_URL . 'auth/login.php');
