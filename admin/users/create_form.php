<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
</head>
<body>
<h2>Add User</h2>

<?php if($error): ?>
    <p style="color:red"><?= e($error) ?></p>
<?php endif; ?>

<?php if($success): ?>
    <p style="color:green"><?= e($success) ?></p>
<?php endif; ?>

<form method="post">
    <label>Name: <input type="text" name="name" value="<?= e($_POST['name'] ?? '') ?>" required></label><br>
    <label>Email: <input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <label>Role:
        <select name="role">
            <option value="customer" <?= (($_POST['role'] ?? '') === 'customer') ? 'selected' : '' ?>>Customer</option>
            <option value="admin" <?= (($_POST['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
        </select>
    </label><br>
    <button type="submit">Create</button>
</form>

<a href="index.php">Back to Users</a>
</body>
</html>
