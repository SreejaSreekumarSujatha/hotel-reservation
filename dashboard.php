<?php
require __DIR__ . '/includes/db_connect.php';
require __DIR__ . '/includes/functions.php';
require_admin();

// Count reservations, customers, rooms
$res_count = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
$cust_count = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
$room_count = $pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn();

// Current date & time
date_default_timezone_set('Europe/Berlin');
$current_date = date('l, d M Y');
$current_time = date('H:i');

// Active page highlight
$current_page = basename($_SERVER['PHP_SELF']);
function isActive($page, $current_page) {
    return $page === $current_page ? 'bg-blue-600 text-white' : 'hover:bg-gray-200 text-gray-800';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans flex min-h-screen">

<!-- Sidebar -->
<?php include 'includes/sidebar.php';?>

<!-- Main content -->
<div class="flex-1 ml-64 p-6">

   
  <!-- Welcome message -->
<div class="bg-white shadow-md rounded-lg p-6 mb-8 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Welcome, <?= e($_SESSION['user']['name']) ?> 👋</h2>
        <p class="text-gray-600">Today is <?= $current_date ?> — Current time: <?= $current_time ?> 🕒</p>
    </div>
    <div>
        <a href="dashboard.php" class="px-4 py-2 bg-gray-100 text-gray-800 rounded hover:bg-gray-200 hover:text-gray-900 transition">
            Dashboard
        </a>
    </div>
</div>


    <!-- Stats cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Reservations -->
        <div class="relative bg-gradient-to-r from-blue-500 to-blue-400 text-white shadow-lg rounded-xl overflow-hidden p-6">
            <div class="absolute inset-0 opacity-20 bg-[url('/php_projects_github/hotel-reservation/assets/pattern.png')] bg-cover"></div>
            <h2 class="relative text-lg font-semibold">Total Reservations</h2>
            <p class="relative text-3xl font-bold mt-2"><?= $res_count ?></p>
            <span class="absolute bottom-4 right-4 text-6xl opacity-20">📅</span>
        </div>

        <!-- Total Customers -->
        <div class="relative bg-gradient-to-r from-green-500 to-green-400 text-white shadow-lg rounded-xl overflow-hidden p-6">
            <div class="absolute inset-0 opacity-20 bg-[url('/php_projects_github/hotel-reservation/assets/pattern.png')] bg-cover"></div>
            <h2 class="relative text-lg font-semibold">Total Customers</h2>
            <p class="relative text-3xl font-bold mt-2"><?= $cust_count ?></p>
            <span class="absolute bottom-4 right-4 text-6xl opacity-20">👤</span>
        </div>

        <!-- Total Rooms -->
        <div class="relative bg-gradient-to-r from-yellow-500 to-yellow-400 text-white shadow-lg rounded-xl overflow-hidden p-6">
            <div class="absolute inset-0 opacity-20 bg-[url('/php_projects_github/hotel-reservation/assets/pattern.png')] bg-cover"></div>
            <h2 class="relative text-lg font-semibold">Total Rooms</h2>
            <p class="relative text-3xl font-bold mt-2"><?= $room_count ?></p>
            <span class="absolute bottom-4 right-4 text-6xl opacity-20">🏘</span>
        </div>
    </div>

</div>

</body>
</html>
