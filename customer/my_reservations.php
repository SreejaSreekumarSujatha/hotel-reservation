<?php
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT r.id, rm.name AS room_name, rm.type AS room_type,
           r.check_in, r.check_out, r.total_amount
    FROM reservations r
    JOIN rooms rm ON r.room_id = rm.id
    WHERE r.user_id = ?
    ORDER BY r.check_in DESC
");
$stmt->execute([$user_id]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Reservations</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin-top: 20px; }
        th, td { padding: 8px 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
<h1>My Reservations</h1>

<?php if (empty($reservations)): ?>
    <p>You have no reservations yet.</p>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>Room Name</th>
            <th>Type</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reservations as $res): ?>
            <tr>
                <td><?= htmlspecialchars($res['room_name']) ?></td>
                <td><?= htmlspecialchars($res['room_type']) ?></td>
                <td><?= htmlspecialchars($res['check_in']) ?></td>
                <td><?= htmlspecialchars($res['check_out']) ?></td>
                <td>$<?= number_format($res['total_amount'], 2) ?></td>
                <td><a href="cancel_reservation.php?id=<?= $res['id'] ?>" onclick="return confirm('Cancel this reservation?')">Cancel</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<p><a href="index.php">Back to Room List</a></p>
</body>
</html>
