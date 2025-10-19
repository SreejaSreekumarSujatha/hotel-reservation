<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_login();

$user_id = current_user_id();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];

    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (password_verify($old, $user['password'])) {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->execute([$hashed, $user_id]);
        flash_set('success', 'Password changed.');
    } else {
        flash_set('error', 'Incorrect current password.');
    }

    header("Location: change_password.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Change Password</title></head>
<body>
<h1>Change Password</h1>
<?php if ($msg = flash_get('success')) echo "<p style='color:green;'>$msg</p>"; ?>
<?php if ($msg = flash_get('error')) echo "<p style='color:red;'>$msg</p>"; ?>
<form method="POST">
    <label>Current Password:</label><br>
    <input type="password" name="old_password" required><br><br>

    <label>New Password:</label><br>
    <input type="password" name="new_password" required><br><br>

    <button type="submit">Change Password</button>
</form>

<p><a href="../index.php">Back</a></p>
</body>
</html>
