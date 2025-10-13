<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
<h2>Edit User</h2>

<?php if($error): ?>
    <p style="color:red"><?= e($error) ?></p>
<?php endif; ?>
<?php if($success): ?>
    <p style="color:green"><?= e($success) ?></p>
<?php endif; ?>

<form method="post">
    <label>Name: <input type="text" name="name" value="<?= e($user['name']) ?>" required></label><br>
    <label>Email: <input type="email" name="email" value="<?= e($user['email']) ?>" required></label><br>
    <label>Password (leave blank to keep current):
        <input type="password" name="password" 
               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
               title="At least 8 chars, one uppercase, one lowercase, one number">
    </label><br>
    <label>Role:
        <select name="role">
            <option value="customer" <?= $user['role']=='customer' ? 'selected' : '' ?>>Customer</option>
            <option value="admin" <?= $user['role']=='admin' ? 'selected' : '' ?>>Admin</option>
        </select>
    </label><br>
    <button type="submit">Update</button>
</form>

<a href="index.php">Back to Users</a>
</body>
</html>
