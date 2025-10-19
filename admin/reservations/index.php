<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

$stmt = $pdo->query("
    SELECT r.id, u.name AS customer_name, u.email AS customer_email,
           rm.room_number, rm.type, rm.price AS room_price,
           r.check_in, r.check_out, r.total_amount, r.created_at, r.status
    FROM reservations r
    JOIN users u ON r.user_id = u.id
    JOIN rooms rm ON r.room_id = rm.id
    ORDER BY r.id DESC
");
$reservations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reservations</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="flex min-h-screen bg-gray-100">

<!-- Sidebar -->
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main content -->
<div class="flex-1 flex flex-col ml-0 md:ml-64 transition-all">

    <!-- Top bar -->
    <header class="flex items-center justify-between bg-white shadow-md p-4 md:hidden">
        <button id="menu-btn" class="text-gray-700 focus:outline-none">&#9776;</button>
        <span class="font-bold text-lg">Reservations</span>
    </header>

    <!-- Hello Admin / Current Page -->
    <div class="flex justify-end bg-white shadow px-4 py-3 hidden md:flex">
        <span>Hello, <?= e($_SESSION['user']['name']) ?> | Dashboard / Reservations</span>
    </div>

    <main class="p-4 md:p-6">
        <h1 class="text-2xl font-bold mb-4">Reservations</h1>

        <!-- Add Reservation Button -->
        <a href="create.php" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition">
            + Add Reservation
        </a>

        <!-- Responsive Table Container -->
        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full bg-white border border-gray-200 table-auto">
                <thead class="bg-gray-200 text-left">
                    <tr>
                        <!-- <th class="px-4 py-2 border-b whitespace-nowrap">ID</th> -->
                        <th class="px-4 py-2 border-b whitespace-nowrap">Customer</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Email</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Room</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Type</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Price/Night</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Check-in</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Check-out</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Total</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Status</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Created</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($reservations as $r): ?>
                        <tr class="hover:bg-gray-50">
                            <!-- <td class="px-4 py-2 border-b whitespace-nowrap"><?= e($r['id']) ?></td> -->
                            <td class="px-4 py-2 border-b break-words"><?= e($r['customer_name']) ?></td>
                            <td class="px-4 py-2 border-b break-words"><?= e($r['customer_email']) ?></td>
                            <td class="px-4 py-2 border-b whitespace-nowrap"><?= e($r['room_number']) ?></td>
                            <td class="px-4 py-2 border-b whitespace-nowrap"><?= e($r['type']) ?></td>
                            <td class="px-4 py-2 border-b whitespace-nowrap">$<?= number_format($r['room_price'], 2) ?></td>
                            <td class="px-4 py-2 border-b whitespace-nowrap"><?= e($r['check_in']) ?></td>
                            <td class="px-4 py-2 border-b whitespace-nowrap"><?= e($r['check_out']) ?></td>
                            <td class="px-4 py-2 border-b whitespace-nowrap">$<?= number_format($r['total_amount'], 2) ?></td>
                            <td class="px-4 py-2 border-b whitespace-nowrap">
                                <?php
                                $status_color = match($r['status']) {
                                    'confirmed' => 'bg-green-500',
                                    'cancelled' => 'bg-red-500',
                                    'pending' => 'bg-yellow-400',
                                    default => 'bg-gray-400',
                                };
                                ?>
                                <span class="text-white px-2 py-1 rounded <?= $status_color ?>"><?= ucfirst($r['status']) ?></span>
                            </td>
                            <td class="px-4 py-2 border-b whitespace-nowrap">
                                <?= date('Y-m-d', strtotime($r['created_at'])) ?>
                            </td>
                           <td class="px-4 py-2 border-b whitespace-nowrap flex gap-2">
    <a href="edit.php?id=<?= e($r['id']) ?>" class="px-2 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition">Edit</a>
    <a href="delete.php?id=<?= e($r['id']) ?>" onclick="return confirm('Delete this reservation?')" class="px-2 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition">Delete</a>
</td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
