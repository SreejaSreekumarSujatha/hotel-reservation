<?php
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$room_id = $_GET['room_id'] ?? null;
if (!$room_id) {
    die("Invalid room.");
}

// Fetch room details
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    die("Room not found.");
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $check_in = new DateTime($_POST['check_in']);
    $check_out = new DateTime($_POST['check_out']);
    $interval = $check_in->diff($check_out);
    $days = $interval->days;

    if ($days <= 0) {
        $error = "Check-out must be after check-in.";
    } else {
        $total_amount = $room['price_per_night'] * $days;

        $stmt = $pdo->prepare("INSERT INTO reservations (user_id, room_id, check_in, check_out, total_amount) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION['user_id'],
            $room_id,
            $check_in->format('Y-m-d'),
            $check_out->format('Y-m-d'),
            $total_amount
        ]);

        $success = "Booking successful! Total: $" . number_format($total_amount, 2);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Room</title>
</head>
<body>
<h1>Book: <?= htmlspecialchars($room['name']) ?> (<?= htmlspecialchars($room['type']) ?>)</h1>
<p>Price per night: $<?= number_format($room['price_per_night'], 2) ?></p>

<?php if ($error): ?><p style="color:red"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<?php if ($success): ?><p style="color:green"><?= htmlspecialchars($success) ?></p><?php endif; ?>

<form method="POST">
    <label>Check-in:</label><br>
    <input type="date" name="check_in" required><br><br>

    <label>Check-out:</label><br>
    <input type="date" name="check_out" required><br><br>

    <button type="submit">Book Now</button>
</form>

<p><a href="index.php">Back to Rooms</a></p>
</body>
</html>
