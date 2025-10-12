<?php
require __DIR__ . '/includes/functions.php';
require_login();
//  Get any flash message set during login
$successMessage = flash_get('success');
?>
<!doctype html>
<html>
<head><title>Dashboard</title></head>
<body>
    <!--  Display flash success message -->
<?php if ($successMessage): ?>
    <p style="color: green;"><?= e($successMessage) ?></p>
<?php endif; ?>
<h2>Welcome, <?php echo e($_SESSION['user']['name']); ?>!</h2>
<p>Role: <?php echo e($_SESSION['user']['role']); ?></p>
<a href="logout.php">Logout</a>
</body>
</html>
