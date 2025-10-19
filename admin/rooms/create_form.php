<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_number = trim($_POST['room_number'] ?? '');
    $type = $_POST['type'] ?? '';
    $price = $_POST['price'] ?? '';

    if (empty($room_number) || empty($type) || empty($price)) {
        $error = 'All fields are required.';
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = 'Please enter a valid price.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO rooms (room_number, type, price, status) VALUES (?, ?, ?, 'available')");
        $stmt->execute([$room_number, $type, $price]);
        $success = 'Room added successfully!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Room</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex bg-gray-100">

<!-- Toast Notification -->
<?php if ($success): ?>
    <div id="toast" class="fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded shadow-lg transition-opacity duration-500">
        <?= e($success) ?>
    </div>
<?php endif; ?>

<!-- Sidebar -->
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Content -->
<div class="flex-1 flex flex-col h-screen">
    <!-- Header -->
    <header class="bg-white shadow p-4 flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-700">✨ Add Room</h1>
        <span class="text-gray-600 text-sm">Hello, <?= e($_SESSION['user']['name']) ?> | Dashboard / Add Room</span>
    </header>

    <!-- Form Section -->
<main class="flex-1 flex justify-center p-4 md:p-6 lg:p-12 overflow-auto">
    <form method="POST"
          class="w-full max-w-md md:max-w-3xl lg:max-w-4xl bg-white shadow-lg rounded-lg p-6 md:p-12 space-y-6">
        <?php if ($error): ?>
            <div class="p-3 bg-red-100 text-red-700 rounded"><?= e($error) ?></div>
        <?php endif; ?>

        <!-- Room Name -->
        <div class="flex flex-col space-y-2">
            <label class="text-gray-700 font-semibold text-base md:text-lg">🏷️ Room Name</label>
            <input type="text" name="room_number"
                   value="<?= e($_POST['room_number'] ?? '') ?>"
                   class="w-full border border-gray-300 rounded-lg p-3 md:p-4 text-base md:text-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                   placeholder="Enter room name" required>
        </div>

        <!-- Type -->
        <div class="flex flex-col space-y-2">
            <label class="text-gray-700 font-semibold text-base md:text-lg">🏨 Room Type</label>
            <select name="type" required
                    class="w-full border border-gray-300 rounded-lg p-3 md:p-4 text-base md:text-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">-- Select Type --</option>
                <option value="Single" <?= (($_POST['type'] ?? '') === 'Single') ? 'selected' : '' ?>>Single</option>
                <option value="Double" <?= (($_POST['type'] ?? '') === 'Double') ? 'selected' : '' ?>>Double</option>
                <option value="Family" <?= (($_POST['type'] ?? '') === 'Family') ? 'selected' : '' ?>>Family</option>
            </select>
        </div>

        <!-- Price -->
        <div class="flex flex-col space-y-2">
            <label class="text-gray-700 font-semibold text-base md:text-lg">💰 Price (per night)</label>
            <input type="number" name="price"
                   value="<?= e($_POST['price'] ?? '') ?>"
                   class="w-full border border-gray-300 rounded-lg p-3 md:p-4 text-base md:text-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                   placeholder="Enter price" required>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center">
            <button type="submit"
                    class="w-32 md:w-48 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg text-base md:text-lg font-semibold transition">
                Add Room ✨
            </button>
        </div>
    </form>
</main>



</div>

<script>
// Toast fade out
setTimeout(() => {
    const toast = document.getElementById('toast');
    if (toast) {
        toast.classList.add('opacity-0');
        setTimeout(() => toast.remove(), 500);
    }
}, 3000);
</script>

</body>
</html>
