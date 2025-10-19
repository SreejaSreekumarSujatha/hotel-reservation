<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_login();

$user_id = current_user_id();
$stmt = $pdo->prepare("
    SELECT r.id, rm.room_number, rm.type, r.check_in, r.check_out, r.total_amount, r.status
    FROM reservations r
    JOIN rooms rm ON r.room_id = rm.id
    WHERE r.user_id = ?
    ORDER BY r.id DESC
");
$stmt->execute([$user_id]);
$reservations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>My Reservations</title></head>
<body>
<h1>My Reservations</h1>
<p><a href="create.php">+ New Reservation</a> | <a href="../index.php">Back to Dashboard</a></p>

<table border="1" cellpadding="5">
<tr>
    <th>ID</th><th>Room</th><th>Type</th><th>Check-in</th><th>Check-out</th>
    <th>Total</th><th>Status</th><th>Actions</th>
</tr>
<?php foreach ($reservations as $r): ?>
<tr>
    <td><?= e($r['id']) ?></td>
    <td><?= e($r['room_number']) ?></td>
    <td><?= e($r['type']) ?></td>
    <td><?= e($r['check_in']) ?></td>
    <td><?= e($r['check_out']) ?></td>
    <td>$<?= number_format($r['total_amount'], 2) ?></td>
    <td><?= e($r['status']) ?></td>
    <td>
        <a href="view.php?id=<?= e($r['id']) ?>">View</a> |
        <?php if ($r['status'] === 'confirmed'): ?>
            <a href="cancel.php?id=<?= e($r['id']) ?>" onclick="return confirm('Cancel this reservation?')">Cancel</a>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
