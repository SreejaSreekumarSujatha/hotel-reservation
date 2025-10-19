<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

$stmt = $pdo->query("SELECT id, room_number, type, price, status, created_at FROM rooms ORDER BY id DESC");
$rooms = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Rooms</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="flex min-h-screen bg-gray-100">

<!-- Sidebar -->
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main content -->
<div class="flex-1 flex flex-col ml-0 md:ml-64 transition-all">

    <!-- Top bar for mobile -->
    <header class="flex items-center justify-between bg-white shadow-md p-4 md:hidden">
        <button id="menu-btn" class="text-gray-700 focus:outline-none">&#9776;</button>
        <span class="font-bold text-lg">Rooms</span>
    </header>

    <!-- Top bar for desktop -->
    <div class="flex justify-end bg-white shadow px-4 py-3 hidden md:flex">
        <span>Hello, <?= e($_SESSION['user']['name']) ?> | Dashboard / Rooms</span>
    </div>

    <main class="p-4 md:p-6">
        <h1 class="text-2xl font-bold mb-4">Rooms</h1>

        <!-- Add Room Button -->
        <a href="create.php" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition">
            + Add Room
        </a>

        <!-- Responsive Table -->
        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full bg-white border border-gray-200 table-auto">
                <thead class="bg-gray-200 text-left">
                    <tr>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Room Number</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Type</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Price</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Status</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Created</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($rooms as $room): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border-b whitespace-nowrap"><?= e($room['room_number']) ?></td>
                            <td class="px-4 py-2 border-b whitespace-nowrap"><?= e($room['type']) ?></td>
                            <td class="px-4 py-2 border-b whitespace-nowrap">$<?= number_format($room['price'], 2) ?></td>
                            <td class="px-4 py-2 border-b whitespace-nowrap">
                                <?php
                                $color = match($room['status']) {
                                    'available' => 'bg-green-500',
                                    'booked' => 'bg-red-500',
                                    default => 'bg-gray-400',
                                };
                                ?>
                                <span class="text-white px-2 py-1 rounded <?= $color ?>"><?= ucfirst($room['status']) ?></span>
                            </td>
                            <td class="px-4 py-2 border-b whitespace-nowrap">
                                <?= date('Y-m-d', strtotime($room['created_at'])) ?>
                            </td>
                            <td class="px-4 py-2 border-b whitespace-nowrap flex gap-2">
                                <a href="edit.php?id=<?= e($room['id']) ?>"
                                   class="px-2 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition">
                                    Edit
                                </a>
                                <a href="delete.php?id=<?= e($room['id']) ?>"
                                   onclick="return confirm('Delete this room?')"
                                   class="px-2 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($rooms)): ?>
                <div class="p-4 text-center text-gray-500">No rooms found.</div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
// Mobile toggle sidebar
const btn = document.getElementById('menu-btn');
const sidebar = document.getElementById('sidebar');
btn?.addEventListener('click', () => {
    sidebar.classList.toggle('-translate-x-full');
});
</script>
</body>
</html>
