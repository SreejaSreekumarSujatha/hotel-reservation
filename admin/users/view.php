<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

$stmt = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users</title>
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
        <span class="font-bold text-lg">Manage Users</span>
    </header>

    <!-- Hello Admin / Current Page -->
    <div class="flex justify-end bg-white shadow px-4 py-3 hidden md:flex">
        <span>Hello, <?= e($_SESSION['user']['name']) ?> | Dashboard / Users</span>
    </div>

    <main class="p-4 md:p-6">
        <h1 class="text-2xl font-bold mb-4">Manage Users</h1>

        <!-- Add User Button -->
        <a href="create.php" class="inline-block mb-4 px-4 py-2 bg-green-600 text-white font-semibold rounded hover:bg-green-700 transition">
            + Add User
        </a>

        <!-- Responsive Table Container -->
        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full bg-white border border-gray-200 table-auto">
                <thead class="bg-gray-200 text-left">
                    <tr>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Name</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Email</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Role</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Created</th>
                        <th class="px-4 py-2 border-b whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($users as $u): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border-b break-words"><?= e($u['name']) ?></td>
                            <td class="px-4 py-2 border-b break-words"><?= e($u['email']) ?></td>
                            <td class="px-4 py-2 border-b capitalize whitespace-nowrap"><?= e($u['role']) ?></td>
                            <td class="px-4 py-2 border-b whitespace-nowrap"><?= date('Y-m-d', strtotime($u['created_at'])) ?></td>
                            <td class="px-4 py-2 border-b whitespace-nowrap flex gap-2">
                                <a href="edit.php?id=<?= e($u['id']) ?>" class="px-2 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition">Edit</a>
                                <a href="delete.php?id=<?= e($u['id']) ?>" onclick="return confirm('Delete this user?')" class="px-2 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">No users found.</td>
                        </tr>
                    <?php endif; ?>
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
