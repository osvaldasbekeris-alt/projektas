<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../classes/User.php';
$klaida = '';

if (isset($_POST['prisijungti_btn'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $userObj = new User();
    $user = $userObj->login($username, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['plain_password'] = $password;
        header('Location: dashboard.php');
        exit;
    } else {
        $klaida = 'Neteisingas vardas arba slaptažodis!';
    }
}
?>
<!DOCTYPE html><html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Prisijungimas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Prisijungimas</h2>
    <?php if (isset($_GET['registered'])): ?>
        <p class="sekme">Registracija sėkminga! Galite prisijungti.</p>
    <?php endif; ?>
    <?php if ($klaida): ?>
        <p class="klaida"><?= htmlspecialchars($klaida) ?></p>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <label>Vartotojo vardas:</label>
        <input type="text" name="username" required>
        <label>Slaptažodis:</label>
        <input type="password" name="password" required>
        <br><br>
        <button type="submit" name="prisijungti_btn">Prisijungti</button>
    </form>
    <br>
    <a href="register.php">Neturiu paskyros → Registruotis</a>
</div>
</body>
</html>