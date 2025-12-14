<?php
session_start();
define('APP_ACCESS', true);

require_once '../config/config.php';
require_once '../includes/functions.php';

session_destroy();
redirect(BASE_URL . 'auth/login.php');
