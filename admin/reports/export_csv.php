<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$status = $_GET['status'] ?? '';

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=reservations_' . date('Y-m-d') . '.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID','Customer','Room','Check-in','Check-out','Total Amount','Status']);

// Build query dynamically
$query = "
    SELECT r.id, u.name AS customer_name, rm.room_number, r.check_in, r.check_out, r.total_amount, r.status
    FROM reservations r
    JOIN users u ON r.user_id = u.id
    JOIN rooms rm ON r.room_id = rm.id
    WHERE 1=1
";
$params = [];
if ($from) { $query .= " AND r.check_in >= ?"; $params[] = $from; }
if ($to) { $query .= " AND r.check_out <= ?"; $params[] = $to; }
if ($status) { $query .= " AND r.status = ?"; $params[] = $status; }
$query .= " ORDER BY r.id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['id'],
        $row['customer_name'],
        $row['room_number'],
        $row['check_in'],
        $row['check_out'],
        $row['total_amount'],
        $row['status']
    ]);
}
fclose($output);
exit;
