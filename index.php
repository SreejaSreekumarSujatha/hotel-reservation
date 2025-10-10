<?php
require __DIR__ . '/includes/functions.php';
require_login();
?>
<!doctype html>
<html>
<head><title>Dashboard</title></head>
<body>
<h2>Welcome, <?php echo e($_SESSION['user']['name']); ?>!</h2>
<p>Role: <?php echo e($_SESSION['user']['role']); ?></p>
<a href="logout.php">Logout</a>
</body>
</html>
