<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

require_once '../classes/User.php';
$klaida = '';
$sekme = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['naujas'] != $_POST['pakartoti']) {
        $klaida = 'Nauji slaptažodžiai nesutampa!';
    } elseif (strlen($_POST['naujas']) < 6) {
        $klaida = 'Naujas slaptažodis per trumpas!';
    } else {
        $userObj = new User();
        if ($userObj->changePassword($_SESSION['user_id'], $_POST['senas'], $_POST['naujas'])) {
            $_SESSION['plain_password'] = $_POST['naujas'];
            $sekme = 'Slaptažodis sėkmingai pakeistas!';
        } else {
            $klaida = 'Senas slaptažodis neteisingas!';
        }
    }
}
?>
<!DOCTYPE html><html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Keisti slaptažodį</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Keisti slaptažodį</h2>
    <a href="dashboard.php">← Atgal</a><br><br>
    <?php if ($klaida): ?><p class="klaida"><?= htmlspecialchars($klaida) ?></p><?php endif; ?>
    <?php if ($sekme): ?><p class="sekme"><?= $sekme ?></p><?php endif; ?>
    <form method="POST">
        <label>Senas slaptažodis:</label>
        <input type="password" name="senas" required>
        <label>Naujas slaptažodis:</label>
        <input type="password" name="naujas" required>
        <label>Pakartoti:</label>
        <input type="password" name="pakartoti" required>
        <br><br>
        <button type="submit">Keisti</button>
    </form>
</div>
</body>
</html>