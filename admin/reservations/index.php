<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

// Fetch all reservations with user & room info
$stmt = $pdo->query("
    SELECT r.id, u.name AS user_name, u.email AS user_email,
           ro.room_number, ro.type AS room_type,
           r.check_in, r.check_out, r.created_at
    FROM reservations r
    JOIN users u ON r.user_id = u.id
    JOIN rooms ro ON r.room_id = ro.id
    ORDER BY r.id DESC
");
$reservations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Reservations</title>
</head>
<body>
<h1>Reservations</h1>
<a href="create.php">+ Add Reservation</a> | <a href="../../index.php">Dashboard</a>
<table border="1" cellpadding="5">
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Room</th>
    <th>Check In</th>
    <th>Check Out</th>
    <th>Created At</th>
    <th>Actions</th>
</tr>
<?php foreach($reservations as $res): ?>
<tr>
    <td><?= e($res['id']) ?></td>
    <td><?= e($res['user_name']) ?> (<?= e($res['user_email']) ?>)</td>
    <td><?= e($res['room_number']) ?> - <?= e($res['room_type']) ?></td>
    <td><?= e($res['check_in']) ?></td>
    <td><?= e($res['check_out']) ?></td>
    <td><?= e($res['created_at']) ?></td>
    <td>
        <a href="edit.php?id=<?= e($res['id']) ?>">Edit</a> |
        <a href="delete.php?id=<?= e($res['id']) ?>" onclick="return confirm('Delete this reservation?')">Delete</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
