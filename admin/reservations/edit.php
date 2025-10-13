<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin(); // Only admins can access

$error = '';
$success = '';

$id = $_GET['id'] ?? null;
if (!$id) {
    die('Reservation ID is required.');
}

// Fetch reservation
$stmt = $pdo->prepare("SELECT * FROM reservations WHERE id=:id");
$stmt->execute([':id' => $id]);
$reservation = $stmt->fetch();
if (!$reservation) {
    die('Reservation not found.');
}

// Fetch all users and rooms for dropdowns
$users = $pdo->query("SELECT id,name,email FROM users WHERE role='customer' ORDER BY name")->fetchAll();
$rooms = $pdo->query("SELECT id,room_number,type FROM rooms ORDER BY room_number")->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? '';
    $room_id = $_POST['room_id'] ?? '';
    $check_in = $_POST['check_in'] ?? '';
    $check_out = $_POST['check_out'] ?? '';

    // Basic validation
    if (!$user_id || !$room_id || !$check_in || !$check_out) {
        $error = 'All fields are required.';
    } elseif ($check_in > $check_out) {
        $error = 'Check-out must be after check-in.';
    } else {
        $stmt = $pdo->prepare("
            UPDATE reservations 
            SET user_id=:user_id, room_id=:room_id, check_in=:check_in, check_out=:check_out 
            WHERE id=:id
        ");
        $stmt->execute([
            ':user_id' => $user_id,
            ':room_id' => $room_id,
            ':check_in' => $check_in,
            ':check_out' => $check_out,
            ':id' => $id
        ]);
        $success = 'Reservation updated successfully.';

        // Refresh reservation data
        $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id=:id");
        $stmt->execute([':id' => $id]);
        $reservation = $stmt->fetch();
    }
}

// Include HTML form
include __DIR__ . '/edit_form.php';
