<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_login();

$user_id = current_user_id();
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $update = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $update->execute([$name, $email, $user_id]);
    $_SESSION['user']['name'] = $name;
    flash_set('success', 'Profile updated.');
    header("Location: edit.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Profile</title></head>
<body>
<h1>Edit Profile</h1>
<?php if ($msg = flash_get('success')) echo "<p style='color:green;'>$msg</p>"; ?>
<form method="POST">
    <label>Name:</label><br>
    <input type="text" name="name" value="<?= e($user['name']) ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= e($user['email']) ?>" required><br><br>

    <button type="submit">Save</button>
</form>

<p><a href="../index.php">Back to Dashboard</a></p>
</body>
</html>
