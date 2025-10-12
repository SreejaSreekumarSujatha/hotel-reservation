<!doctype html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h2>Login</h2>

<!-- Show flash messages -->
<?php if($msg = flash_get('success')): ?>
    <p style="color:green"><?= e($msg) ?></p>
<?php endif; ?>

<!-- Show error messages -->
<?php if($error): ?>
    <p style="color:red"><?= e($error) ?></p>
<?php endif; ?>

<form method="post" action="login.php">
    <label>Email <input type="email" name="email" required></label><br>
    <label>Password <input type="password" name="password" required></label><br>
    <button type="submit">Login</button>
</form>
</body>
</html>
