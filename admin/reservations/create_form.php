<!DOCTYPE html>
<html>
<head>
    <title>Add Reservation</title>
</head>
<body>
<h1>Add New Reservation</h1>

<?php if($error): ?>
<p style="color:red"><?= e($error) ?></p>
<?php endif; ?>
<?php if($success): ?>
<p style="color:green"><?= e($success) ?></p>
<?php endif; ?>

<form method="POST" action="create.php">
    <label>User:</label><br>
    <select name="user_id" required>
        <option value="">--Select Customer--</option>
        <?php foreach($users as $u): ?>
        <option value="<?= e($u['id']) ?>" <?= (($_POST['user_id'] ?? '') == $u['id']) ? 'selected' : '' ?>>
            <?= e($u['name']) ?> (<?= e($u['email']) ?>)
        </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Room:</label><br>
    <select name="room_id" required>
        <option value="">--Select Room--</option>
        <?php foreach($rooms as $r): ?>
        <option value="<?= e($r['id']) ?>" <?= (($_POST['room_id'] ?? '') == $r['id']) ? 'selected' : '' ?>>
            <?= e($r['room_number']) ?> - <?= e($r['type']) ?>
        </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Check-in:</label><br>
    <input type="date" name="check_in" value="<?= e($_POST['check_in'] ?? '') ?>" required><br><br>

    <label>Check-out:</label><br>
    <input type="date" name="check_out" value="<?= e($_POST['check_out'] ?? '') ?>" required><br><br>

    <button type="submit">Add Reservation</button>
</form>

<p><a href="index.php">Back to Reservations</a></p>
</body>
</html>
