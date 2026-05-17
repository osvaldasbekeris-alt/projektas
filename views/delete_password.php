<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

require_once '../classes/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    (new User())->deletePassword((int)$_POST['id'], $_SESSION['user_id']);
}
header('Location: dashboard.php');
exit;