<?php
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Fetch all available rooms
$stmt = $pdo->query("SELECT id, name, type, price_per_night FROM rooms ORDER BY id DESC");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Available Rooms</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin-top: 20px; }
        th, td { padding: 8px 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
<h1>Available Rooms</h1>

<?php if (empty($rooms)): ?>
    <p>No rooms available right now.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Room Name</th>
                <th>Type</th>
                <th>Price / Night</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rooms as $room): ?>
            <tr>
                <td><?= htmlspecialchars($room['name']) ?></td>
                <td><?= htmlspecialchars($room['type']) ?></td>
                <td>$<?= number_format($room['price_per_night'], 2) ?></td>
                <td><a href="book_room.php?room_id=<?= $room['id'] ?>">Book</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<p><a href="../logout.php">Logout</a></p>
</body>
</html>
