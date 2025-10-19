<?php

require __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/config.php';


if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$errors = [];
$old = ['name'=>'','email'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['password_confirm'] ?? '';

    $old['name'] = $name;
    $old['email'] = $email;

    // Validation
    if ($name === '') $errors[] = 'Name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
    if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
    if ($password !== $confirm) $errors[] = 'Passwords do not match.';

    // Check if email already exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email'=>$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email already registered.';
        }
    }

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name,email,password,role,created_at) VALUES (:name,:email,:password,'customer',NOW())");
        $stmt->execute([
            ':name'=>$name,
            ':email'=>$email,
            ':password'=>$hashed
        ]);
        flash_set('success','Registration successful. Please log in.');
        header('Location: login.php');
        exit;
    }
}

include __DIR__ . '/register_form.php';
