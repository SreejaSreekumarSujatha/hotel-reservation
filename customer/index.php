<?php
require __DIR__ . '/../includes/db_connect.php';
require __DIR__ . '/../includes/functions.php';
require_login();

$user_id = current_user_id();

// Fetch upcoming reservations
$stmt = $pdo->prepare("
    SELECT r.id, rm.room_number, r.check_in, r.check_out, r.status
    FROM reservations r
    JOIN rooms rm ON r.room_id = rm.id
    WHERE r.user_id = ? AND r.check_in >= CURDATE()
    ORDER BY r.check_in ASC
");
$stmt->execute([$user_id]);
$reservations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Customer Dashboard</title></head>
<body>
<h1>Welcome, <?= e($_SESSION['user']['name']) ?></h1>
<p><a href="reservations/index.php">My Reservations</a> | 
<a href="profile/edit.php">My Profile</a> | 
<a href="logout.php">Logout</a></p>

<h2>Upcoming Reservations</h2>
<?php if ($reservations): ?>
<table border="1" cellpadding="5">
<tr><th>ID</th><th>Room</th><th>Check-in</th><th>Check-out</th><th>Status</th></tr>
<?php foreach ($reservations as $r): ?>
<tr>
    <td><?= e($r['id']) ?></td>
    <td><?= e($r['room_number']) ?></td>
    <td><?= e($r['check_in']) ?></td>
    <td><?= e($r['check_out']) ?></td>
    <td><?= e($r['status']) ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php else: ?>
<p>No upcoming reservations.</p>
<?php endif; ?>
</body>
</html>
