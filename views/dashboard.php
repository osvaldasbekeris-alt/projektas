<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

require_once '../classes/User.php';
require_once '../classes/Encryptor.php';
require_once '../classes/Database.php';

$encryptor = new Encryptor();
$stmt = Database::getInstance()->prepare("SELECT encrypted_key FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$raktas = $encryptor->decrypt($row['encrypted_key'], $_SESSION['plain_password']);
$slaptazodziai = (new User())->getPasswords($_SESSION['user_id']);
?>
<!DOCTYPE html><html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Sveiki, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
    <nav>
        <a href="add_password.php">+ Naujas slaptažodis</a>
        <a href="change_password.php">Keisti slaptažodį</a>
        <a href="../index.php?logout=1" style="color:red">Atsijungti</a>
    </nav>
    <h3>Mano slaptažodžiai</h3>
    <?php if (empty($slaptazodziai)): ?>
        <p>Kol kas nėra išsaugotų slaptažodžių.</p>
    <?php else: ?>
    <table>
        <tr><th>#</th><th>Pavadinimas</th><th>Slaptažodis</th><th>Data</th><th>Veiksmai</th></tr>
        <?php foreach ($slaptazodziai as $i => $p): ?>
        <tr>
            <td><?= $i+1 ?></td>
            <td><?= htmlspecialchars($p['title']) ?></td>
            <td><?= htmlspecialchars($encryptor->decrypt($p['encrypted_password'], $raktas)) ?></td>
            <td><?= $p['created_at'] ?></td>
            <td>
                <form method="POST" action="delete_password.php" style="display:inline">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <button class="btn-red" onclick="return confirm('Tikrai trinti?')">Trinti</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>
</body>
</html>