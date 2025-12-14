<?php
// Prevent direct access
defined('APP_ACCESS') or die('Direct access not permitted');

// Development mode flag
define('IS_DEV', true); // Set FALSE untuk production

// Base URL Configuration
if (IS_DEV) {
    // Development - PHP built-in server
    define('BASE_URL', 'http://localhost:3000/');
} else {
    // Production - Auto detect
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $folder = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    define('BASE_URL', $protocol . $host . $folder);
}

// Paths
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

define('ASSET_DIR', 'assets/');

// App Info
define('APP_NAME', 'WonderfulNTT');

// Upload Settings
define('MAX_FILE_SIZE', 15 * 1024 * 1024); // 15MB

// Contact Info
define('CONTACT_EMAIL', 'wonderfulntt@gmail.com');
define('CONTACT_PHONE', '0812-3456-7890');
define('CONTACT_ADDRESS', 'Jl. Penuh Kepercayaan No. 67');

// Error Reporting (development)
if (IS_DEV) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
