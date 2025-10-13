<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

$id = $_GET['id'] ?? null;
if (!$id) {
    die('Room ID is required.');
}

// Delete room
$stmt = $pdo->prepare("DELETE FROM rooms WHERE id=:id");
$stmt->execute([':id' => $id]);

// Redirect to room list
header('Location: index.php');
exit;
