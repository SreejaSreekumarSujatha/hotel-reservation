<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin(); // Only admins can access

$error = '';
$success = '';

// Fetch all customers for the dropdown
$users = $pdo->query("
    SELECT id, name, email 
    FROM users 
    WHERE role='customer' 
    ORDER BY name ASC
")->fetchAll();

// Fetch all rooms
$rooms = $pdo->query("
    SELECT id, room_number, type 
    FROM rooms 
    ORDER BY room_number ASC
")->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id   = $_POST['user_id'] ?? '';
    $room_id   = $_POST['room_id'] ?? '';
    $check_in  = $_POST['check_in'] ?? '';
    $check_out = $_POST['check_out'] ?? '';

    // Basic validation
    if (!$user_id || !$room_id || !$check_in || !$check_out) {
        $error = 'All fields are required.';
    } elseif ($check_out <= $check_in) {
        $error = 'Check-out date must be after check-in date.';
    } else {
        // Insert reservation
        $stmt = $pdo->prepare("
            INSERT INTO reservations (user_id, room_id, check_in, check_out, created_at)
            VALUES (:user_id, :room_id, :check_in, :check_out, NOW())
        ");
        $stmt->execute([
            ':user_id'   => $user_id,
            ':room_id'   => $room_id,
            ':check_in'  => $check_in,
            ':check_out' => $check_out
        ]);

        $success = 'Reservation created successfully!';
        $_POST = []; // Clear form
    }
}

// Include the HTML form
include __DIR__ . '/create_form.php';
