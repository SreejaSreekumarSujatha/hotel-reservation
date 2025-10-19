<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require __DIR__ . '/../../vendor/autoload.php';
require_admin();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$id = $_GET['id'] ?? null;

if ($id) {
    // Fetch reservation with user and room details
    $stmt = $pdo->prepare("
        SELECT r.id, u.name AS user_name, u.email AS user_email, 
               rm.room_number, rm.type, rm.id AS room_id
        FROM reservations r
        JOIN users u ON r.user_id = u.id
        JOIN rooms rm ON r.room_id = rm.id
        WHERE r.id = ?
    ");
    $stmt->execute([$id]);
    $reservation = $stmt->fetch();

    if ($reservation) {
        $room_id = $reservation['room_id'];

        // Delete the reservation
        $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
        $stmt->execute([$id]);

        // Update the room status back to available
        $pdo->prepare("UPDATE rooms SET status = 'available' WHERE id = ?")->execute([$room_id]);

        // Send cancellation email
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
            $mail->addAddress($reservation['user_email'], $reservation['user_name']);

            $mail->isHTML(true);
            $mail->Subject = 'Reservation Cancelled';
            $mail->Body = "
                Hello {$reservation['user_name']},<br>
                Your reservation for Room {$reservation['room_number']} ({$reservation['type']}) has been <strong>cancelled</strong>.<br><br>
                Regards,<br>
                Hotel Reservation
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("Mail could not be sent. Error: " . $mail->ErrorInfo);
        }
    }
}

// Redirect back to reservations list
header("Location: index.php");
exit;
