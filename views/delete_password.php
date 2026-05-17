<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

require_once '../classes/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    (new User())->deletePassword((int)$_POST['id'], $_SESSION['user_id']);
}
header('Location: dashboard.php');
exit;