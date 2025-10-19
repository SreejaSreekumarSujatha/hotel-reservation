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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Reservation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

<!-- Toast Notification -->
<div id="toast" class="fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded shadow-lg hidden transition-opacity duration-500">
    Customer added successfully ✅
</div>

<!-- Top Bar -->
<header class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold text-gray-800">Add Reservation</h1>
    <a href="index.php" class="text-blue-600 hover:underline text-sm">← Back to Reservations</a>
</header>

<!-- Main Container -->
<main class="flex-1 p-6 max-w-2xl mx-auto bg-white shadow-lg rounded-lg mt-6">
    <form method="POST" action="create.php" class="space-y-4">
        <!-- Customer -->
        <div>
            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
            <div class="flex gap-2">
                <select name="user_id" id="user_id" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                    <option value="">-- Select Customer --</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" onclick="openCustomerModal()"
                        class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    + Add
                </button>
            </div>
            
        </div>

        <!-- Room -->
        <div>
            <label for="room_id" class="block text-sm font-medium text-gray-700 mb-1">Room</label>
            <select name="room_id" id="room_id" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                <option value="">-- Select Room --</option>
                <?php foreach ($rooms as $r): ?>
                    <option value="<?= $r['id'] ?>">
                        <?= htmlspecialchars($r['room_number']) ?> - <?= htmlspecialchars($r['type']) ?> ($<?= number_format($r['price'], 2) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Check-in -->
        <div>
            <label for="check_in" class="block text-sm font-medium text-gray-700 mb-1">Check-in</label>
            <input type="date" name="check_in" id="check_in" required
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
        </div>

        <!-- Check-out -->
        <div>
            <label for="check_out" class="block text-sm font-medium text-gray-700 mb-1">Check-out</label>
            <input type="date" name="check_out" id="check_out" required
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit"
                    class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700 transition">
                Add Reservation
            </button>
        </div>
    </form>
</main>

<!-- Modal Backdrop -->
<div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-50 hidden"></div>

<!-- Modal for Adding Customer -->
<div id="customerModal"
     class="fixed top-1/2 left-1/2 w-96 max-w-[90%] bg-white p-6 rounded shadow-lg transform -translate-x-1/2 -translate-y-1/2 hidden z-50">
    <h3 class="text-lg font-bold mb-4 text-gray-800">Add New Customer</h3>

    <div class="space-y-3">
        <div>
            <label class="block text-sm text-gray-600 mb-1">Name</label>
            <input type="text" id="new_name"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Email</label>
            <input type="email" id="new_email"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Password</label>
            <input type="password" id="new_password"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
        </div>
    </div>

    <div class="mt-5 flex justify-end gap-3">
        <button onclick="closeCustomerModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
        <button onclick="saveCustomer()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
    </div>
</div>

<script>
function openCustomerModal() {
    document.getElementById('customerModal').classList.remove('hidden');
    document.getElementById('modalBackdrop').classList.remove('hidden');
}

function closeCustomerModal() {
    document.getElementById('customerModal').classList.add('hidden');
    document.getElementById('modalBackdrop').classList.add('hidden');
}

// Toast
function showToast(message) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.classList.remove('hidden', 'opacity-0');
    toast.classList.add('opacity-100');
    setTimeout(() => {
        toast.classList.add('opacity-0');
        setTimeout(() => toast.classList.add('hidden'), 500);
    }, 3000);
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
            showToast('Customer added successfully ✅');
        } else {
            alert(data.message);
        }
    });
}
</script>

</body>
</html>
