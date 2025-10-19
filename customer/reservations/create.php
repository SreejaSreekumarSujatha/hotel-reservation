<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_login();

$user_id = current_user_id();
$rooms = $pdo->query("SELECT id, room_number, type, price FROM rooms")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    $days = (strtotime($check_out) - strtotime($check_in)) / (60*60*24);
    if ($days <= 0) {
        flash_set('error', 'Check-out must be after check-in.');
        header("Location: create.php");
        exit;
    }

    $room_stmt = $pdo->prepare("SELECT price FROM rooms WHERE id = ?");
    $room_stmt->execute([$room_id]);
    $room = $room_stmt->fetch();
    $total_amount = $room['price'] * $days;

    $stmt = $pdo->prepare("
        INSERT INTO reservations (user_id, room_id, check_in, check_out, total_amount, status, created_at)
        VALUES (?, ?, ?, ?, ?, 'confirmed', NOW())
    ");
    $stmt->execute([$user_id, $room_id, $check_in, $check_out, $total_amount]);

    flash_set('success', 'Reservation created successfully.');
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Create Reservation</title></head>
<body>
<h1>Book a Room</h1>
<?php if ($msg = flash_get('error')) echo "<p style='color:red;'>$msg</p>"; ?>
<form method="POST">
    <label>Room:</label><br>
    <select name="room_id" required>
        <?php foreach ($rooms as $r): ?>
        <option value="<?= $r['id'] ?>">
            <?= e($r['room_number']) ?> - <?= e($r['type']) ?> ($<?= $r['price'] ?>/night)
        </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Check-in:</label><br>
    <input type="date" name="check_in" required><br><br>

    <label>Check-out:</label><br>
    <input type="date" name="check_out" required><br><br>

    <button type="submit">Book Now</button>
</form>

<p><a href="index.php">Back to My Reservations</a></p>
</body>
</html>
