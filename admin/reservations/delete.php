<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("SELECT room_id FROM reservations WHERE id = ?");
    $stmt->execute([$id]);
    $reservation = $stmt->fetch();

    if ($reservation) {
        $room_id = $reservation['room_id'];

        // Delete reservation
        $pdo->prepare("DELETE FROM reservations WHERE id = ?")->execute([$id]);

        // Update room status back to available
        $pdo->prepare("UPDATE rooms SET status = 'available' WHERE id = ?")->execute([$room_id]);
    }
}

header("Location: index.php");
exit;
