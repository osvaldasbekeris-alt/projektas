<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: views/login.php');
    exit;
}

if (isset($_SESSION['user_id'])) {
    header('Location: views/dashboard.php');
} else {
    header('Location: views/login.php');
}
exit;