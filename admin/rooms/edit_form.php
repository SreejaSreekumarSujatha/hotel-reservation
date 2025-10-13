<!DOCTYPE html>
<html>
<head>
    <title>Edit Room</title>
</head>
<body>
<h1>Edit Room</h1>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= e($error) ?></p>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <p style="color:green;"><?= e($success) ?></p>
<?php endif; ?>

<form method="POST" action="edit.php?id=<?= e($room['id']) ?>">
    <label>Room Name:</label><br>
    <input type="text" name="room_number" value="<?= e($room['room_number']) ?>" required><br><br>

    <label>Type:</label><br>
    <select name="type" required>
        <option value="Single" <?= $room['type']=='Single' ? 'selected' : '' ?>>Single</option>
        <option value="Double" <?= $room['type']=='Double' ? 'selected' : '' ?>>Double</option>
        <option value="Family" <?= $room['type']=='Family' ? 'selected' : '' ?>>Family</option>
    </select><br><br>

    <label>Price (per night):</label><br>
    <input type="number" name="price" value="<?= e($room['price']) ?>" required><br><br>

    <label>Status:</label><br>
    <select name="status" required>
        <option value="available" <?= $room['status']=='available' ? 'selected' : '' ?>>Available</option>
        <option value="booked" <?= $room['status']=='booked' ? 'selected' : '' ?>>Booked</option>
    </select><br><br>

    <button type="submit">Update Room</button>
</form>

<p><a href="index.php">Back to Room List</a></p>
</body>
</html>
