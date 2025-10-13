<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_number = trim($_POST['room_number'] ?? '');
    $type = $_POST['type'] ?? '';
    $price = $_POST['price'] ?? '';

    if (!$room_number || !$type || !$price) {
        $error = 'All fields are required.';
    } else {
        // Check if room_number already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM rooms WHERE room_number = :room_number");
        $stmt->execute([':room_number' => $room_number]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Room with this name already exists.';
        } else {
            // Insert room
            $stmt = $pdo->prepare("INSERT INTO rooms (room_number, type, price, status, created_at)
                                   VALUES (:room_number, :type, :price, 'available', NOW())");
            $stmt->execute([
                ':room_number' => $room_number,
                ':type' => $type,
                ':price' => $price
            ]);
            $success = 'Room added successfully!';
            $_POST = []; // clear form
        }
    }
}

include __DIR__ . '/create_form.php';
