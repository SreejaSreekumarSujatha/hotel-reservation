<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

$error = '';
$success = '';

$id = $_GET['id'] ?? null;
if (!$id) {
    die('User ID is required.');
}

// Fetch user
$stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE id = :id");
$stmt->execute([':id' => $id]);
$user = $stmt->fetch();
if (!$user) {
    die('User not found.');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'customer';
    $password = $_POST['password'] ?? '';

    if (!$name || !$email) {
        $error = 'Name and email are required.';
    } else {
        // Check email uniqueness for other users
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email AND id != :id");
        $stmt->execute([':email' => $email, ':id' => $id]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Email already exists.';
        } else {
            // Update password only if filled
            $updateFields = "name=:name, email=:email, role=:role";
            $params = [':name'=>$name, ':email'=>$email, ':role'=>$role, ':id'=>$id];
            if ($password) {
                if (strlen($password) < 8) {
                    $error = 'Password must be at least 8 characters.';
                } else {
                    $updateFields .= ", password=:password";
                    $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
                }
            }

            if (!$error) {
                $stmt = $pdo->prepare("UPDATE users SET $updateFields WHERE id=:id");
                $stmt->execute($params);
                $success = 'User updated successfully.';
                // Refresh user data
                $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE id = :id");
                $stmt->execute([':id'=>$id]);
                $user = $stmt->fetch();
            }
        }
    }
}

// Include HTML form
include __DIR__ . '/edit_form.php';
