<?php
// includes/sidebar.php

// Get current path to highlight menu
$current_path = $_SERVER['REQUEST_URI'];
?>

<aside id="sidebar" class="w-64 bg-gray-800 text-white flex flex-col p-4 fixed h-full transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-50">
    <div class="mb-6 flex items-center justify-between">
        <a href="/php_projects_github/hotel-reservation/dashboard.php" class="text-2xl font-bold flex items-center gap-2">
            🏨 Admin
        </a>
        <button class="md:hidden text-white text-2xl" onclick="toggleSidebarMobile()">✖</button>
    </div>

    <p class="mb-4 text-sm">Hello, <strong><?= e($_SESSION['user']['name']) ?></strong></p>

    <nav class="flex-1">
        <ul class="space-y-2">
            <?php
            $menus = [
                '/php_projects_github/hotel-reservation/dashboard.php' => '🏠 Dashboard',
                '/php_projects_github/hotel-reservation/admin/reservations/index.php' => '📅 Manage Reservations',
                '/php_projects_github/hotel-reservation/admin/rooms/index.php' => '🏘 Manage Rooms',
                '/php_projects_github/hotel-reservation/admin/users/index.php' => '👤 Manage Users',
                '/php_projects_github/hotel-reservation/admin/reports/index.php' => '📊 Reports'
            ];

            foreach ($menus as $link => $label):
                $active = strpos($current_path, $link) !== false ? 'bg-blue-600 text-white' : 'text-white hover:bg-gray-700';
            ?>
                <li>
                    <a href="<?= $link ?>" class="block p-2 rounded transition <?= $active ?>">
                        <?= $label ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <div class="mt-auto border-t border-gray-600 pt-4">
        <a href="/php_projects_github/hotel-reservation/auth/logout.php"
           class="block p-2 rounded bg-red-500 hover:bg-red-600 text-center text-white transition">
            🚪 Logout
        </a>
    </div>
</aside>

<script>
function toggleSidebarMobile() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('-translate-x-full');
}
</script>
