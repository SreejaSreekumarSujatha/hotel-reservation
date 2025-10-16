<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

// Fetch customers only
$users = $pdo->query("SELECT id, name FROM users WHERE role = 'customer' ORDER BY name")->fetchAll();

// Fetch available rooms only
$rooms = $pdo->query("SELECT id, room_number, type, price FROM rooms WHERE status = 'available'")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Reservation</title>
</head>
<body>
<h1>Add New Reservation</h1>

<form method="POST" action="create.php">
    <label>Customer:</label><br>
    <select name="user_id" id="user_id" required>
        <option value="">-- Select Customer --</option>
        <?php foreach ($users as $u): ?>
            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <button type="button" onclick="openCustomerModal()">+ Add Customer</button>
    <br><br>

    <label>Room:</label><br>
    <select name="room_id" required>
        <option value="">-- Select Room --</option>
        <?php foreach ($rooms as $r): ?>
            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['room_number']) ?> - <?= htmlspecialchars($r['type']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Check-in:</label><br>
    <input type="date" name="check_in" required><br><br>

    <label>Check-out:</label><br>
    <input type="date" name="check_out" required><br><br>

    <button type="submit">Add Reservation</button>
</form>

<p><a href="index.php">Back</a></p>

<!-- Modal for Adding Customer -->
<div id="customerModal" style="display:none; position:fixed; top:20%; left:50%; transform:translateX(-50%);
    background:#fff; padding:20px; border:1px solid #ccc; box-shadow:0px 0px 10px #999;">
    <h3>Add New Customer</h3>
    <label>Name:</label><br>
    <input type="text" id="new_name"><br><br>
    <label>Email:</label><br>
    <input type="email" id="new_email"><br><br>
    <label>Password:</label><br>
    <input type="password" id="new_password"><br><br>
    <button type="button" onclick="saveCustomer()">Save</button>
    <button type="button" onclick="closeCustomerModal()">Cancel</button>
</div>

<script>
function openCustomerModal() {
    document.getElementById('customerModal').style.display = 'block';
}
function closeCustomerModal() {
    document.getElementById('customerModal').style.display = 'none';
}

function saveCustomer() {
    const name = document.getElementById('new_name').value.trim();
    const email = document.getElementById('new_email').value.trim();
    const password = document.getElementById('new_password').value.trim();

    if (!name || !email || !password) {
        alert('All fields are required');
        return;
    }

    fetch('../users/quick_create.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({name, email, password})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const select = document.getElementById('user_id');
            const option = document.createElement('option');
            option.value = data.id;
            option.text = data.name;
            option.selected = true;
            select.add(option);
            closeCustomerModal();
        } else {
            alert(data.message);
        }
    });
}
</script>
</body>
</html>
