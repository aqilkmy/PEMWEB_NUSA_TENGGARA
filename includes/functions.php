<?php
// Prevent direct access
defined('APP_ACCESS') or die('Direct access not permitted');

// Sanitize input
function clean($data)
{
    global $conn;
    if ($data === null) {
        return '';
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return mysqli_real_escape_string($conn, $data);
}

// Redirect
function redirect($url)
{
    header("Location: " . $url);
    exit();
}

// Check if logged in
function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

// Check if admin
function is_admin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Get user ID
function user_id()
{
    return $_SESSION['user_id'] ?? null;
}

// Get user name
function user_name()
{
    return $_SESSION['nama'] ?? 'Guest';
}

// Format date
function format_date($date)
{
    $bulan = [
        '01' => 'Jan',
        '02' => 'Feb',
        '03' => 'Mar',
        '04' => 'Apr',
        '05' => 'Mei',
        '06' => 'Jun',
        '07' => 'Jul',
        '08' => 'Agu',
        '09' => 'Sep',
        '10' => 'Okt',
        '11' => 'Nov',
        '12' => 'Des'
    ];

    $d = date('d', strtotime($date));
    $m = date('m', strtotime($date));
    $y = date('Y', strtotime($date));

    return $d . ' ' . $bulan[$m] . ' ' . $y;
}

// Truncate text
function truncate($text, $length = 100)
{
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

// Validate email
function is_valid_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Upload image
function upload_image($file, $old_file = '')
{
    // Check if file is null or not set
    if ($file === null || !isset($file['error'])) {
        return $old_file;
    }
    
    // Check if file uploaded
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return $old_file;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['error' => 'Upload gagal'];
    }

    // Check file size
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['error' => 'File terlalu besar. Maksimal 15MB'];
    }

    // Check file type
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        return ['error' => 'Hanya file JPG, PNG, GIF yang diperbolehkan'];
    }

    // Validate image
    $check = getimagesize($file['tmp_name']);
    if ($check === false) {
        return ['error' => 'File bukan gambar'];
    }

    // Generate filename
    $filename = uniqid() . '_' . time() . '.' . $ext;
    $target = UPLOAD_DIR . $filename;

    // Delete old file
    if (!empty($old_file) && file_exists($old_file)) {
        unlink($old_file);
    }

    // Move file
    if (move_uploaded_file($file['tmp_name'], $target)) {
        //return $target;
        return 'uploads/' . $filename;
    }

    return ['error' => 'Gagal upload file'];
}

// Delete image
function delete_image($filepath)
{
    if (!empty($filepath) && file_exists($filepath)) {
        return unlink($filepath);
    }
    return false;
}

// Hash password (SHA256 untuk kompatibilitas)
function hash_pass($password)
{
    return hash('sha256', $password);
}

// Active menu class
function active_menu($page)
{
    $current = basename($_SERVER['PHP_SELF']);
    return ($current === $page) ? 'active' : '';
}

// Get Google Maps embed URL
function maps_embed($url, $location)
{
    if (empty($url) || strpos($url, 'goo.gl') !== false) {
        return "https://maps.google.com/maps?q=" . urlencode($location) . "&output=embed";
    }

    if (strpos($url, 'output=embed') !== false) {
        return $url;
    }

    if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $m)) {
        return "https://maps.google.com/maps?q={$m[1]},{$m[2]}&output=embed";
    }

    return "https://maps.google.com/maps?q=" . urlencode($location) . "&output=embed";
}

// Flash messages
function set_flash($type, $message)
{
    $_SESSION['flash'][$type] = $message;
}

function get_flash($type)
{
    if (isset($_SESSION['flash'][$type])) {
        $msg = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $msg;
    }
    return null;
}
