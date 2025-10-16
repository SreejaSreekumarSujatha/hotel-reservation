<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: index.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ?");
$stmt->execute([$id]);
$res = $stmt->fetch();
if (!$res) { header("Location: index.php"); exit; }

$users = $pdo->query("SELECT id, name FROM users WHERE role = 'customer'")->fetchAll();
$rooms = $pdo->query("SELECT id, room_number, type FROM rooms")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Reservation</title>
</head>
<body>
<h1>Edit Reservation</h1>
<form method="POST" action="edit.php">
    <input type="hidden" name="id" value="<?= $res['id'] ?>">

    <label>Customer:</label><br>
    <select name="user_id" required>
        <?php foreach ($users as $u): ?>
        <option value="<?= $u['id'] ?>" <?= $res['user_id']==$u['id']?'selected':'' ?>>
            <?= htmlspecialchars($u['name']) ?>
        </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Room:</label><br>
    <select name="room_id" required>
        <?php foreach ($rooms as $r): ?>
        <option value="<?= $r['id'] ?>" <?= $res['room_id']==$r['id']?'selected':'' ?>>
            <?= htmlspecialchars($r['room_number']) ?> - <?= htmlspecialchars($r['type']) ?>
        </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Check-in:</label><br>
    <input type="date" name="check_in" value="<?= $res['check_in'] ?>" required><br><br>

    <label>Check-out:</label><br>
    <input type="date" name="check_out" value="<?= $res['check_out'] ?>" required><br><br>

    <button type="submit">Update</button>
</form>

<p><a href="index.php">Back</a></p>
</body>
</html>
