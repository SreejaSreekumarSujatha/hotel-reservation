<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

// Fetch all rooms
$stmt = $pdo->query("SELECT id, room_number, type, price, status, created_at FROM rooms ORDER BY id DESC");
$rooms = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Room List</title>
</head>
<body>
<h1>All Rooms</h1>
<a href="create.php">+ Add Room</a> | <a href="../../index.php">Dashboard</a>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Room Name</th>
        <th>Type</th>
        <th>Price</th>
        <th>Status</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
    <?php foreach($rooms as $room): ?>
    <tr>
        <td><?= e($room['id']) ?></td>
        <td><?= e($room['room_number']) ?></td>
        <td><?= e($room['type']) ?></td>
        <td>$<?= number_format($room['price'], 2) ?></td>
        <td><?= e(ucfirst($room['status'])) ?></td>
        <td><?= e($room['created_at']) ?></td>
        <td>
            <a href="edit.php?id=<?= e($room['id']) ?>">Edit</a> |
            <a href="delete.php?id=<?= e($room['id']) ?>" onclick="return confirm('Delete this room?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
