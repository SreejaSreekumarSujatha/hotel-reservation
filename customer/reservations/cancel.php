<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_login();

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: index.php"); exit; }

$user_id = current_user_id();
$stmt = $pdo->prepare("UPDATE reservations SET status = 'cancelled' WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);

flash_set('success', 'Reservation cancelled.');
header("Location: index.php");
exit;
