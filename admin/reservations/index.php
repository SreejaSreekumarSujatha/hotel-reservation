<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

$stmt = $pdo->query("
    SELECT r.id, u.name AS user_name, rm.room_number, rm.type, 
           r.check_in, r.check_out, r.total_amount
    FROM reservations r
    JOIN users u ON r.user_id = u.id
    JOIN rooms rm ON r.room_id = rm.id
    ORDER BY r.id DESC
");
$reservations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reservations</title>
</head>
<body>
<h1>Reservations</h1>
<p><a href="create_form.php">+ Add New Reservation</a></p>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Room</th>
        <th>Type</th>
        <th>Check-in</th>
        <th>Check-out</th>
        <th>Total Amount</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($reservations as $res): ?>
    <tr>
        <td><?= htmlspecialchars($res['id']) ?></td>
        <td><?= htmlspecialchars($res['user_name']) ?></td>
        <td><?= htmlspecialchars($res['room_number']) ?></td>
        <td><?= htmlspecialchars($res['type']) ?></td>
        <td><?= htmlspecialchars($res['check_in']) ?></td>
        <td><?= htmlspecialchars($res['check_out']) ?></td>
        <td>$<?= number_format($res['total_amount'], 2) ?></td>
        <td>
            <a href="edit_form.php?id=<?= $res['id'] ?>">Edit</a> | 
            <a href="delete.php?id=<?= $res['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
