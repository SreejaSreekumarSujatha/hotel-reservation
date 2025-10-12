<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin(); // Only admins can access

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'customer';
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (!$name || !$email || !$password) {
        $error = 'All fields are required.';
    }
    // Password validations
    elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } 
    elseif (!preg_match('/[A-Z]/', $password)) {
        $error = 'Password must contain at least one uppercase letter.';
    } 
    elseif (!preg_match('/[a-z]/', $password)) {
        $error = 'Password must contain at least one lowercase letter.';
    } 
    elseif (!preg_match('/[0-9]/', $password)) {
        $error = 'Password must contain at least one number.';
    } 
    else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Email already exists.';
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $pdo->prepare("
                INSERT INTO users (name,email,password,role,created_at)
                VALUES (:name,:email,:password,:role,NOW())
            ");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':role' => $role
            ]);

            $success = 'User created successfully!';
            // Optionally clear form fields
            $_POST = [];
        }
    }
}

// Include the HTML form
include __DIR__ . '/create_form.php';
