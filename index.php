<?php
require __DIR__ . '/includes/functions.php';
require_login();

if ($_SESSION['user']['role'] === 'admin') {
    header('Location: dashboard.php');
} else {
    header('Location: customer/index.php');
}
exit;
?>
<?php
require __DIR__ . '/includes/functions.php';
require_login();

$redirectUrl = $_SESSION['user']['role'] === 'admin' 
    ? 'dashboard.php' 
    : 'customer/index.php';

// Optional: Delay redirect slightly to show loading screen
header("Refresh: 1; URL=$redirectUrl");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Redirecting...</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="text-center">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500 mx-auto mb-4"></div>
        <h1 class="text-xl font-semibold text-gray-800">Redirecting to your dashboard...</h1>
        <p class="text-gray-600 mt-2">Please wait a moment.</p>
    </div>
</body>
</html>
