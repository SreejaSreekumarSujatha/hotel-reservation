<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

$error = '';
$success = '';

$id = $_GET['id'] ?? null;
if (!$id) {
    die('Room ID is required.');
}

// Fetch room
$stmt = $pdo->prepare("SELECT id, room_number, type, price, status FROM rooms WHERE id = :id");
$stmt->execute([':id' => $id]);
$room = $stmt->fetch();
if (!$room) {
    die('Room not found.');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_number = trim($_POST['room_number'] ?? '');
    $type = $_POST['type'] ?? '';
    $price = $_POST['price'] ?? '';
    $status = $_POST['status'] ?? 'available';

    if (!$room_number || !$type || !$price) {
        $error = 'All fields are required.';
    } else {
        // Check for duplicate room_number (excluding current)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM rooms WHERE room_number = :room_number AND id != :id");
        $stmt->execute([':room_number' => $room_number, ':id' => $id]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Room name already exists.';
        } else {
            // Update room
            $stmt = $pdo->prepare("UPDATE rooms SET room_number=:room_number, type=:type, price=:price, status=:status WHERE id=:id");
            $stmt->execute([
                ':room_number' => $room_number,
                ':type' => $type,
                ':price' => $price,
                ':status' => $status,
                ':id' => $id
            ]);
            $success = 'Room updated successfully.';
            // Refresh data
            $stmt = $pdo->prepare("SELECT id, room_number, type, price, status FROM rooms WHERE id=:id");
            $stmt->execute([':id' => $id]);
            $room = $stmt->fetch();
        }
    }
}

// Include HTML form
include __DIR__ . '/edit_form.php';
