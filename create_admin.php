<?php
require __DIR__ . '/includes/db_connect.php';
require __DIR__ . '/includes/functions.php';

$email = 'admin@example.com';
$name  = 'Admin';
$password_plain = 'Admin@123';

// Prevent duplicate creation
$stmt = $pdo->query("SELECT COUNT(*) AS c FROM users");
$row = $stmt->fetch();
if ($row['c'] > 0) {
    die("Users already exist. Delete users if you want to run this again.");
}

// Create admin
$hashedPassword = password_hash($password_plain, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("
    INSERT INTO users (name,email,password,role,created_at)
    VALUES (:name, :email, :password, :role, NOW())
");
$stmt->execute([
    ':name' => $name,
    ':email' => $email,
    ':password' => $hashedPassword,
    ':role' => 'admin'
]);

echo "Admin created. Email: $email / Pass: $password_plain. Delete this file for security.";
