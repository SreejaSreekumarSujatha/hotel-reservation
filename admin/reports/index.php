<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

// Initialize filter variables
$start_date = $_POST['start_date'] ?? null;
$end_date   = $_POST['end_date'] ?? null;

// Build WHERE clause
$where = [];
$params = [];
if ($start_date) {
    $where[] = "r.check_in >= :start_date";
    $params[':start_date'] = $start_date;
}
if ($end_date) {
    $where[] = "r.check_out <= :end_date";
    $params[':end_date'] = $end_date;
}
$whereSQL = $where ? "WHERE " . implode(' AND ', $where) : "";

// Summary queries
$totalReservationsStmt = $pdo->prepare("SELECT COUNT(*) FROM reservations r $whereSQL");
$totalReservationsStmt->execute($params);
$totalReservations = $totalReservationsStmt->fetchColumn();

$totalRevenueStmt = $pdo->prepare("SELECT SUM(total_amount) FROM reservations r WHERE status='confirmed'" . ($where ? " AND " . implode(' AND ', $where) : ""));
$totalRevenueStmt->execute($params);
$totalRevenue = $totalRevenueStmt->fetchColumn();

$cancelledStmt = $pdo->prepare("SELECT COUNT(*) FROM reservations r WHERE status='cancelled'" . ($where ? " AND " . implode(' AND ', $where) : ""));
$cancelledStmt->execute($params);
$cancelledCount = $cancelledStmt->fetchColumn();

// Fetch reservations
$resStmt = $pdo->prepare("
    SELECT r.id, u.name AS customer_name, u.email AS customer_email,
           rm.room_number, rm.type, r.check_in, r.check_out, r.total_amount, r.status
    FROM reservations r
    JOIN users u ON r.user_id = u.id
    JOIN rooms rm ON r.room_id = rm.id
    $whereSQL
    ORDER BY r.check_in ASC
");
$resStmt->execute($params);
$reservations = $resStmt->fetchAll();

// Escape function
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reservation Reports</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">

<!-- Sidebar -->
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Content -->
<div class="flex-1 p-6 md:ml-64 overflow-auto">

    <h1 class="text-3xl font-bold text-center mb-6">📊 Reservation Reports</h1>

    <!-- Filter Form -->
    <form method="POST" class="bg-white shadow rounded-lg p-6 mb-6 flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block font-semibold mb-1">Start Date</label>
            <input type="date" name="start_date" value="<?= e($start_date) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
        </div>
        <div class="flex-1">
            <label class="block font-semibold mb-1">End Date</label>
            <input type="date" name="end_date" value="<?= e($end_date) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
        </div>
        <div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition w-full md:w-auto">Filter</button>
        </div>
    </form>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white shadow rounded-lg p-4 text-center">
            <p class="text-gray-500 font-medium">Total Reservations</p>
            <p class="text-2xl font-bold"><?= $totalReservations ?></p>
        </div>
        <div class="bg-white shadow rounded-lg p-4 text-center">
            <p class="text-gray-500 font-medium">Total Revenue</p>
            <p class="text-2xl font-bold">$<?= number_format($totalRevenue ?? 0, 2) ?></p>
        </div>
        <div class="bg-white shadow rounded-lg p-4 text-center">
            <p class="text-gray-500 font-medium">Cancelled Reservations</p>
            <p class="text-2xl font-bold"><?= $cancelledCount ?></p>
        </div>
    </div>

    <!-- Reservations Table -->
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ID</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Customer</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Email</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Room</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Check-in</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Check-out</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Total</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if($reservations): ?>
                    <?php foreach ($reservations as $r): ?>
                    <?php
                        $status_color = match($r['status']) {
                            'confirmed' => 'bg-green-500',
                            'cancelled' => 'bg-red-500',
                            'pending' => 'bg-yellow-400',
                            default => 'bg-gray-400',
                        };
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm"><?= e($r['id']) ?></td>
                        <td class="px-4 py-2 text-sm"><?= e($r['customer_name']) ?></td>
                        <td class="px-4 py-2 text-sm"><?= e($r['customer_email']) ?></td>
                        <td class="px-4 py-2 text-sm"><?= e($r['room_number']) ?></td>
                        <td class="px-4 py-2 text-sm"><?= e($r['type']) ?></td>
                        <td class="px-4 py-2 text-sm"><?= e($r['check_in']) ?></td>
                        <td class="px-4 py-2 text-sm"><?= e($r['check_out']) ?></td>
                        <td class="px-4 py-2 text-sm">$<?= number_format($r['total_amount'],2) ?></td>
                        <td class="px-4 py-2">
                            <span class="text-white px-2 py-1 rounded <?= $status_color ?>"><?= ucfirst($r['status']) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="px-4 py-4 text-center text-gray-500">No reservations found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Export Buttons -->
    <div class="flex flex-col md:flex-row gap-4 justify-center mt-6">
        <form method="POST" action="generate_pdf.php" target="_blank">
            <input type="hidden" name="start_date" value="<?= e($start_date) ?>">
            <input type="hidden" name="end_date" value="<?= e($end_date) ?>">
            <button type="submit" class="px-6 py-3 bg-red-600 text-white font-semibold rounded hover:bg-red-700 transition w-full md:w-auto">Download PDF</button>
        </form>
        <form method="POST" action="export_csv.php" target="_blank">
            <input type="hidden" name="start_date" value="<?= e($start_date) ?>">
            <input type="hidden" name="end_date" value="<?= e($end_date) ?>">
            <button type="submit" class="px-6 py-3 bg-green-600 text-white font-semibold rounded hover:bg-green-700 transition w-full md:w-auto">Download CSV</button>
        </form>
    </div>

</div>
</body>
</html>
