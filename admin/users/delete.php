<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

$id = $_GET['id'] ?? null;
if (!$id) {
    die('User ID is required.');
}

// Optional: prevent deleting currently logged-in admin
if ($id == ($_SESSION['user']['id'] ?? 0)) {
    die('You cannot delete your own account.');
}

// Delete user
$stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
$stmt->execute([':id' => $id]);

flash_set('success', 'User deleted successfully.');
header('Location: index.php');
exit;
