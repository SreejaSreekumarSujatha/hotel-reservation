<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

$error = '';
$success = '';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

// Fetch room
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id=?");
$stmt->execute([$id]);
$room = $stmt->fetch();

if (!$room) {
    header('Location: index.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_number = trim($_POST['room_number']);
    $type = $_POST['type'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    if (!$room_number || !$type || !$price || !$status) {
        $error = 'All fields are required.';
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = 'Price must be a positive number.';
    } else {
        $stmt = $pdo->prepare("UPDATE rooms SET room_number=?, type=?, price=?, status=? WHERE id=?");
        $stmt->execute([$room_number, $type, $price, $status, $id]);
        $success = 'Room updated successfully!';
        // Refresh room data
        $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id=?");
        $stmt->execute([$id]);
        $room = $stmt->fetch();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Room</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex bg-gray-100">

<!-- Sidebar -->
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Content -->
<div class="flex-1 flex flex-col h-screen">
    <header class="bg-white shadow p-4 flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-700">Edit Room</h1>
        <span class="text-gray-600 text-sm">Dashboard / Edit Room</span>
    </header>

    <main class="flex-1 flex justify-center items-start p-6 overflow-auto">
        <form method="POST" class="w-full max-w-3xl bg-white p-8 md:p-12 rounded-lg shadow-lg space-y-6">
            <?php if ($error): ?>
                <div class="p-3 bg-red-100 text-red-700 rounded"><?= e($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="p-3 bg-green-100 text-green-700 rounded"><?= e($success) ?></div>
            <?php endif; ?>

            <!-- Room Name -->
            <div class="flex flex-col space-y-2">
                <label class="text-gray-700 font-semibold">🏷️ Room Name</label>
                <input type="text" name="room_number" value="<?= e($room['room_number']) ?>" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
            </div>

            <!-- Room Type -->
            <div class="flex flex-col space-y-2">
                <label class="text-gray-700 font-semibold">🏨 Room Type</label>
                <select name="type" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    <option value="Single" <?= $room['type']=='Single' ? 'selected' : '' ?>>Single</option>
                    <option value="Double" <?= $room['type']=='Double' ? 'selected' : '' ?>>Double</option>
                    <option value="Family" <?= $room['type']=='Family' ? 'selected' : '' ?>>Family</option>
                </select>
            </div>

            <!-- Price -->
            <div class="flex flex-col space-y-2">
                <label class="text-gray-700 font-semibold">💰 Price (per night)</label>
                <input type="number" name="price" value="<?= e($room['price']) ?>" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
            </div>

            <!-- Status -->
            <div class="flex flex-col space-y-2">
                <label class="text-gray-700 font-semibold">📌 Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    <option value="available" <?= $room['status']=='available' ? 'selected' : '' ?>>Available</option>
                    <option value="booked" <?= $room['status']=='booked' ? 'selected' : '' ?>>Booked</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center">
                <button type="submit" class="w-full sm:w-48 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold shadow-md transition">Update Room ✨</button>
            </div>

            <div class="text-center">
                <!-- <a href="index.php" class="text-blue-600 hover:underline">← Back to Room List</a> -->
            </div>
        </form>
    </main>
</div>
</body>
</html>
