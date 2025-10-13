<!DOCTYPE html>
<html>
<head>
    <title>Add Room</title>
</head>
<body>
<h1>Add New Room</h1>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <p style="color:green"><?= e($success) ?></p>
<?php endif; ?>

<form method="POST" action="create.php">
    <!-- Room Name Input -->
    <label>Room Name:</label><br>
    <input type="text" name="room_number" value="<?= htmlspecialchars($_POST['room_number'] ?? '') ?>" required><br><br>

    <!-- Type Dropdown -->
    <label>Type:</label><br>
    <select name="type" required>
        <option value="">--Select Type--</option>
        <option value="Single" <?= (($_POST['type'] ?? '') === 'Single') ? 'selected' : '' ?>>Single</option>
        <option value="Double" <?= (($_POST['type'] ?? '') === 'Double') ? 'selected' : '' ?>>Double</option>
        <option value="Family" <?= (($_POST['type'] ?? '') === 'Family') ? 'selected' : '' ?>>Family</option>
    </select><br><br>

    <!-- Price Input -->
    <label>Price (per night):</label><br>
    <input type="number" name="price" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" required><br><br>

    <button type="submit">Add Room</button>
</form>

<p><a href="index.php">Back to list</a></p>

</body>
</html>
