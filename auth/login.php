<?php
require __DIR__ . '/../includes/db_connect.php';
require __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../includes/config.php';

// If already logged in, redirect to role-based dashboard
if (is_logged_in()) {
    if ($_SESSION['user']['role'] === 'admin') {
        header('Location: ' . BASE_URL . 'dashboard.php');
    } else {
        header('Location: ' . BASE_URL . 'customer/index.php');
    }
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        unset($user['password']);
        $_SESSION['user'] = $user;

        flash_set('success', 'Logged in successfully!');

        // 👇 Role-based redirection
        if ($user['role'] === 'admin') {
            header('Location: ' . BASE_URL . 'dashboard.php');
        } else {
            header('Location: ' . BASE_URL . 'customer/index.php');
        }
        exit;
    } else {
        $error = 'Invalid email or password.';
    }
}

include 'login_form.php';
?>
