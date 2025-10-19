<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

header('Content-Type: application/json');

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$name || !$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

// Check if email exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Email already exists.']);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, 'customer', NOW())");
    $stmt->execute([$name, $email, $hashedPassword]);
    $newUserId = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Customer added successfully.',
        'id' => $newUserId,
        'name' => $name,
        'email' => $email
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
