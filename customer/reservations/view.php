<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_login();

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: index.php"); exit; }

$user_id = current_user_id();
$stmt = $pdo->prepare("
    SELECT r.*, rm.room_number, rm.type, rm.price
    FROM reservations r
    JOIN rooms rm ON r.room_id = rm.id
    WHERE r.id = ? AND r.user_id = ?
");
$stmt->execute([$id, $user_id]);
$res = $stmt->fetch();
if (!$res) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html>
<head><title>View Reservation</title></head>
<body>
<h1>Reservation #<?= e($res['id']) ?></h1>
<p><strong>Room:</strong> <?= e($res['room_number']) ?> (<?= e($res['type']) ?>)</p>
<p><strong>Check-in:</strong> <?= e($res['check_in']) ?></p>
<p><strong>Check-out:</strong> <?= e($res['check_out']) ?></p>
<p><strong>Total:</strong> $<?= number_format($res['total_amount'], 2) ?></p>
<p><strong>Status:</strong> <?= e($res['status']) ?></p>

<p><a href="index.php">Back</a></p>
</body>
</html>
