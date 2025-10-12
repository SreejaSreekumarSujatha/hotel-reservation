<!doctype html>
<html>
<head><title>Register</title></head>
<body>
<h2>Register</h2>

<?php if($msg = flash_get('success')): ?>
    <p style="color:green"><?= e($msg) ?></p>
<?php endif; ?>

<?php if (!empty($errors)): ?>
<ul style="color:red">
  <?php foreach ($errors as $err): ?>
    <li><?= e($err) ?></li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<form method="post" action="register.php">
    <label>Name <input type="text" name="name" value="<?= e($old['name']) ?>" required></label><br>
    <label>Email <input type="email" name="email" value="<?= e($old['email']) ?>" required></label><br>
    <label>Password <input type="password" name="password" required></label><br>
    <label>Confirm Password <input type="password" name="password_confirm" required></label><br>
    <button type="submit">Register</button>
</form>

<p><a href="login.php">Already have an account? Login</a></p>
</body>
</html>
