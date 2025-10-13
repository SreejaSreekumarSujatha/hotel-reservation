<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin(); // Only admins can access

$id = $_GET['id'] ?? null;
if (!$id) {
    die('Reservation ID is required.');
}

// Delete reservation
$stmt = $pdo->prepare("DELETE FROM reservations WHERE id=:id");
$stmt->execute([':id' => $id]);

// Redirect back with a simple message (optional: use flash)
header('Location: index.php');
exit;
