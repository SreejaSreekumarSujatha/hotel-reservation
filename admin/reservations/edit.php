<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require __DIR__ . '/../../vendor/autoload.php';
require_admin();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = '';
$success = '';

// Fetch reservation ID
$id = $_GET['id'] ?? $_POST['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

// Fetch reservation
$stmt = $pdo->prepare("SELECT * FROM reservations WHERE id=?");
$stmt->execute([$id]);
$reservation = $stmt->fetch();
if (!$reservation) {
    header("Location: index.php");
    exit;
}

// Fetch customers and rooms
$users = $pdo->query("SELECT id, name, email FROM users WHERE role='customer' ORDER BY name")->fetchAll();
$rooms = $pdo->query("SELECT id, room_number, type, price FROM rooms")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    $ci = new DateTime($check_in);
    $co = new DateTime($check_out);

    if ($co <= $ci) {
        $error = "Check-out must be after check-in.";
    } else {
        // Fetch new room info
        $stmtRoom = $pdo->prepare("SELECT room_number, type, price FROM rooms WHERE id=?");
        $stmtRoom->execute([$room_id]);
        $room = $stmtRoom->fetch();

        if (!$room) {
            $error = "Invalid room selected.";
        } else {
            $days = $ci->diff($co)->days;
            $total_amount = $room['price'] * $days;

            // Update reservation
            $stmt = $pdo->prepare("
                UPDATE reservations 
                SET user_id=?, room_id=?, check_in=?, check_out=?, total_amount=?
                WHERE id=?
            ");
            $stmt->execute([$user_id, $room_id, $ci->format('Y-m-d'), $co->format('Y-m-d'), $total_amount, $id]);

            // Update room statuses if room changed
            if ($room_id != $reservation['room_id']) {
                $pdo->prepare("UPDATE rooms SET status='available' WHERE id=?")->execute([$reservation['room_id']]);
                $pdo->prepare("UPDATE rooms SET status='booked' WHERE id=?")->execute([$room_id]);
            }

            // Fetch customer info
            $stmtCust = $pdo->prepare("SELECT name, email FROM users WHERE id=?");
            $stmtCust->execute([$user_id]);
            $customer = $stmtCust->fetch();

            // Send email notification
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'sreejass24@gmail.com';
                $mail->Password = 'zygf zkpt ztjj dlcb';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('sreejass24@gmail.com', 'Hotel Reservation');
                $mail->addAddress($customer['email'], $customer['name']);

                $mail->isHTML(true);
                $mail->Subject = 'Reservation Updated';
                $mail->Body = "
                    Hello {$customer['name']},<br>
                    Your reservation for Room {$room['room_number']} ({$room['type']}) has been updated.<br>
                    Check-in: {$check_in}<br>
                    Check-out: {$check_out}<br>
                    Total Amount: $" . number_format($total_amount, 2) . "<br><br>
                    Thank you for choosing our hotel.
                ";

                $mail->send();
            } catch (Exception $e) {
                error_log("Mail could not be sent: " . $mail->ErrorInfo);
            }

            $success = "Reservation updated successfully!";
            // Refresh reservation data
            $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id=?");
            $stmt->execute([$id]);
            $reservation = $stmt->fetch();
        }
    }
}
include __DIR__ . '/edit_form.php';