<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require __DIR__ . '/../../vendor/tcpdf/tcpdf.php';
require_admin();

$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$status = $_GET['status'] ?? '';

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

// TCPDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

$html = '<h2>Reservation Report</h2>';
$html .= '<table border="1" cellpadding="4">
<tr>
<th>ID</th><th>Customer</th><th>Room</th><th>Check-in</th><th>Check-out</th><th>Amount</th><th>Status</th>
</tr>';

while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $html .= '<tr>';
    $html .= '<td>'.htmlspecialchars($r['id']).'</td>';
    $html .= '<td>'.htmlspecialchars($r['customer_name']).'</td>';
    $html .= '<td>'.htmlspecialchars($r['room_number']).'</td>';
    $html .= '<td>'.htmlspecialchars($r['check_in']).'</td>';
    $html .= '<td>'.htmlspecialchars($r['check_out']).'</td>';
    $html .= '<td>$'.number_format($r['total_amount'],2).'</td>';
    $html .= '<td>'.htmlspecialchars($r['status']).'</td>';
    $html .= '</tr>';
}

$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('reservation_report_' . date('Y-m-d') . '.pdf', 'D');
exit;
