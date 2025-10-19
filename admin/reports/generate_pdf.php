<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

// Include TCPDF
require_once __DIR__ . '/../../vendor/autoload.php';
$pdf = new TCPDF();

// Include JPGraph (make sure jpgraph is installed via Composer or manually)
// require_once __DIR__ . '/../../vendor/jpgraph/jpgraph/src/jpgraph.php';
// require_once __DIR__ . '/../../vendor/jpgraph/jpgraph_bar.php';
// require_once __DIR__ . '/../../vendor/jpgraph/jpgraph_pie.php';
// require_once __DIR__ . '/../../vendor/jpgraph/jpgraph_pie3d.php';

// Get filter parameters
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

// Fetch summary data
$totalReservationsStmt = $pdo->prepare("SELECT COUNT(*) FROM reservations r $whereSQL");
$totalReservationsStmt->execute($params);
$totalReservations = $totalReservationsStmt->fetchColumn();

$totalRevenueStmt = $pdo->prepare("SELECT SUM(total_amount) FROM reservations r WHERE status='confirmed'" . ($where ? " AND " . implode(' AND ', $where) : ""));
$totalRevenueStmt->execute($params);
$totalRevenue = $totalRevenueStmt->fetchColumn();

$cancelledStmt = $pdo->prepare("SELECT COUNT(*) FROM reservations r WHERE status='cancelled'" . ($where ? " AND " . implode(' AND ', $where) : ""));
$cancelledStmt->execute($params);
$cancelledCount = $cancelledStmt->fetchColumn();

// Fetch reservation data
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

// --- Prepare charts ---
// 1) Reservation status pie chart
$statusCounts = ['confirmed' => 0, 'cancelled' => 0, 'pending' => 0];
foreach ($reservations as $r) {
    $status = $r['status'] ?? 'pending';
    if (isset($statusCounts[$status])) $statusCounts[$status]++;
}

$dataPie = array_values($statusCounts);
$labelsPie = array_map(fn($k, $v) => "$k ($v)", array_keys($statusCounts), $dataPie);

// $pieGraph = new PieGraph(400, 300);
// $pieGraph->SetShadow();
// $pieGraph->title->Set("Reservation Status Distribution");
// $piePlot = new PiePlot3D($dataPie);
// $piePlot->SetLegends($labelsPie);
// $pieGraph->Add($piePlot);

// Save pie chart to temp file
// $pieFile = tempnam(sys_get_temp_dir(), 'pie') . '.png';
// $pieGraph->Stroke($pieFile);

// 2) Monthly revenue bar chart
$monthlyRevenue = [];
foreach ($reservations as $r) {
    $month = date('Y-m', strtotime($r['check_in']));
    if (!isset($monthlyRevenue[$month])) $monthlyRevenue[$month] = 0;
    $monthlyRevenue[$month] += $r['total_amount'];
}
$months = array_keys($monthlyRevenue);
$amounts = array_values($monthlyRevenue);

// $barGraph = new Graph(500, 300, 'auto');
// $barGraph->SetScale('textlin');
// $barGraph->xaxis->SetTickLabels($months);
// $barGraph->title->Set('Monthly Revenue');
// $barPlot = new BarPlot($amounts);
// $barPlot->SetFillColor('orange');
// $barGraph->Add($barPlot);

// // Save bar chart to temp file
// $barFile = tempnam(sys_get_temp_dir(), 'bar') . '.png';
// $barGraph->Stroke($barFile);

// --- Generate PDF ---
$pdf = new TCPDF();
$pdf->AddPage();

// Summary section
$html = '<h1>Reservation Report</h1>';
$html .= '<h3>Summary</h3>';
$html .= '<ul>';
$html .= '<li>Total Reservations: ' . $totalReservations . '</li>';
$html .= '<li>Total Revenue: $' . number_format($totalRevenue ?? 0, 2) . '</li>';
$html .= '<li>Cancelled Reservations: ' . $cancelledCount . '</li>';
$html .= '</ul>';

// Embed charts
// $html .= '<h3>Charts</h3>';
// $html .= '<p>Status Distribution:</p>';
// $html .= '<img src="' . $pieFile . '" width="400" height="300"><br><br>';
// $html .= '<p>Monthly Revenue:</p>';
// $html .= '<img src="' . $barFile . '" width="500" height="300"><br><br>';

// Reservation details
$html .= '<h3>Details</h3>';
$html .= '<table border="1" cellpadding="4">';
$html .= '<tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Room</th>
            <th>Type</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Total Amount</th>
            <th>Status</th>
          </tr>';
foreach ($reservations as $r) {
    $html .= '<tr>
                <td>' . htmlspecialchars($r['id']) . '</td>
                <td>' . htmlspecialchars($r['customer_name']) . '</td>
                <td>' . htmlspecialchars($r['customer_email']) . '</td>
                <td>' . htmlspecialchars($r['room_number']) . '</td>
                <td>' . htmlspecialchars($r['type']) . '</td>
                <td>' . htmlspecialchars($r['check_in']) . '</td>
                <td>' . htmlspecialchars($r['check_out']) . '</td>
                <td>$' . number_format($r['total_amount'], 2) . '</td>
                <td>' . htmlspecialchars($r['status']) . '</td>
              </tr>';
}
$html .= '</table>';

// Output HTML to PDF
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('reservation_report.pdf', 'I');

// Delete temp chart files
unlink($pieFile);
unlink($barFile);
