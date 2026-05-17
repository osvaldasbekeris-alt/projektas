<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../classes/User.php';
$klaida = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $klaida = 'Visi laukai privalomi!';
    } elseif (strlen($password) < 6) {
        $klaida = 'Slaptažodis turi būti bent 6 simboliai!';
    } else {
        $user = new User();
        if ($user->register($username, $password)) {
            header('Location: login.php?registered=1');
            exit;
        } else {
            $klaida = 'Toks vartotojas jau egzistuoja!';
        }
    }
}
?>
<!DOCTYPE html><html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Registracija</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Registracija</h2>
    <?php if ($klaida): ?>
        <p class="klaida"><?= htmlspecialchars($klaida) ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Vartotojo vardas:</label>
        <input type="text" name="username" required>
        <label>Slaptažodis:</label>
        <input type="password" name="password" required>
        <br><br>
        <button type="submit">Registruotis</button>
    </form>
    <br>
    <a href="login.php">Jau turiu paskyrą → Prisijungti</a>
</div>
</body>
</html>