<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

$user_id = $_POST['user_id'] ?? null;
$room_id = $_POST['room_id'] ?? null;
$check_in = $_POST['check_in'] ?? null;
$check_out = $_POST['check_out'] ?? null;

if (!$user_id || !$room_id || !$check_in || !$check_out) {
    die("All fields are required.");
}

$checkInDate = new DateTime($check_in);
$checkOutDate = new DateTime($check_out);
$days = $checkInDate->diff($checkOutDate)->days;

if ($days <= 0) {
    die("Check-out date must be after check-in date.");
}

// Fetch room price
$stmt = $pdo->prepare("SELECT price FROM rooms WHERE id = ?");
$stmt->execute([$room_id]);
$room = $stmt->fetch();

if (!$room) die("Room not found.");

$total_amount = $room['price'] * $days;

// Insert reservation
$stmt = $pdo->prepare("INSERT INTO reservations (user_id, room_id, check_in, check_out, total_amount) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$user_id, $room_id, $check_in, $check_out, $total_amount]);

// Update room status to booked
$pdo->prepare("UPDATE rooms SET status = 'booked' WHERE id = ?")->execute([$room_id]);

header("Location: index.php");
exit;
