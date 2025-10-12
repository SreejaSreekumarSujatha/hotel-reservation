<?php
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/db_connect.php';




// Redirect already logged-in users to index
if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

// Initialize error message
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Prepared statement to prevent SQL injection
    $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        unset($user['password']); // remove password before storing in session
        $_SESSION['user'] = $user;

        flash_set('success', 'Logged in successfully!'); // optional flash message
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid credentials.";
    }
}

// Include the HTML form
include 'login_form.php';
?>
