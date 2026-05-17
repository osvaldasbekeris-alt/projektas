<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

require_once '../classes/User.php';
require_once '../classes/PasswordGenerator.php';
require_once '../classes/Encryptor.php';
require_once '../classes/Database.php';

$sugeneruotas = '';
$klaida = '';

if (isset($_POST['generuoti'])) {
    $ilgis    = (int)$_POST['ilgis'];
    $mazosios = (int)$_POST['mazosios'];
    $didziosios = (int)$_POST['didziosios'];
    $skaiciai = (int)$_POST['skaiciai'];
    $spec     = (int)$_POST['spec'];

    if (($mazosios + $didziosios + $skaiciai + $spec) != $ilgis) {
        $klaida = 'Simbolių suma turi sutapti su ilgiu!';
    } else {
        $gen = new PasswordGenerator($ilgis, $mazosios, $didziosios, $skaiciai, $spec);
        $sugeneruotas = $gen->generate();
    }
}

if (isset($_POST['issaugoti'])) {
    $encryptor = new Encryptor();
    $stmt = Database::getInstance()->prepare("SELECT encrypted_key FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $raktas = $encryptor->decrypt($row['encrypted_key'], $_SESSION['plain_password']);

    (new User())->savePassword($_SESSION['user_id'], $_POST['title'], $_POST['password'], $raktas);
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html><html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Naujas slaptažodis</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Generuoti slaptažodį</h2>
    <a href="dashboard.php">← Atgal</a><br><br>
    <?php if ($klaida): ?><p class="klaida"><?= $klaida ?></p><?php endif; ?>
    <form method="POST">
        <label>Ilgis:</label>
        <input type="number" name="ilgis" value="<?= $_POST['ilgis'] ?? 9 ?>" min="4">
        <label>Mažosios raidės:</label>
        <input type="number" name="mazosios" value="<?= $_POST['mazosios'] ?? 2 ?>" min="0">
        <label>Didžiosios raidės:</label>
        <input type="number" name="didziosios" value="<?= $_POST['didziosios'] ?? 3 ?>" min="0">
        <label>Skaičiai:</label>
        <input type="number" name="skaiciai" value="<?= $_POST['skaiciai'] ?? 2 ?>" min="0">
        <label>Spec. simboliai:</label>
        <input type="number" name="spec" value="<?= $_POST['spec'] ?? 2 ?>" min="0">
        <br><br>
        <button name="generuoti" value="1">Generuoti</button>
    </form>

    <?php if ($sugeneruotas): ?>
    <br><p>Sugeneruotas: <strong style="font-size:1.3em;color:#4CAF50"><?= htmlspecialchars($sugeneruotas) ?></strong></p>
    <form method="POST">
        <input type="hidden" name="issaugoti" value="1">
        <label>Pavadinimas (pvz. Gmail):</label>
        <input type="text" name="title" required>
        <label>Slaptažodis:</label>
        <input type="text" name="password" value="<?= htmlspecialchars($sugeneruotas) ?>" required>
        <br><br>
        <button class="btn-blue">Išsaugoti</button>
    </form>
    <?php endif; ?>
</div>
</body>
</html>