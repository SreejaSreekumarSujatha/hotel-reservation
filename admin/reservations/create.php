<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require __DIR__ . '/../../vendor/autoload.php';
require_admin();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = '';
$success = '';

// Fetch customers & rooms
$users = $pdo->query("SELECT id, name, email FROM users WHERE role='customer' ORDER BY name")->fetchAll();
$rooms = $pdo->query("SELECT id, room_number, type, price FROM rooms WHERE status='available'")->fetchAll();

// Handle reservation submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id   = $_POST['user_id'] ?? null;
    $room_id   = $_POST['room_id'] ?? null;
    $check_in  = $_POST['check_in'] ?? null;
    $check_out = $_POST['check_out'] ?? null;

    if (!$user_id || !$room_id || !$check_in || !$check_out) {
        $error = 'All fields are required.';
    } elseif (new DateTime($check_out) <= new DateTime($check_in)) {
        $error = 'Check-out date must be after check-in date.';
    } else {
        $stmt = $pdo->prepare("SELECT room_number, type, price FROM rooms WHERE id = ?");
        $stmt->execute([$room_id]);
        $room = $stmt->fetch();

        if (!$room) {
            $error = 'Selected room not found.';
        } else {
            $days = (new DateTime($check_in))->diff(new DateTime($check_out))->days;
            $total_amount = $room['price'] * $days;

            // Insert reservation
            $pdo->prepare("INSERT INTO reservations (user_id, room_id, check_in, check_out, total_amount) VALUES (?, ?, ?, ?, ?)")
                ->execute([$user_id, $room_id, $check_in, $check_out, $total_amount]);

            // Update room status
            $pdo->prepare("UPDATE rooms SET status='booked' WHERE id=?")->execute([$room_id]);

            $success = "Reservation added successfully! Total: $" . number_format($total_amount, 2);

            // Send email notification
            $stmt = $pdo->prepare("SELECT name, email FROM users WHERE id=?");
            $stmt->execute([$user_id]);
            $customer = $stmt->fetch();

            if ($customer && filter_var($customer['email'], FILTER_VALIDATE_EMAIL)) {
                try {
                    $mail = new PHPMailer(true);
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
                    $mail->Subject = 'Reservation Confirmation';
                    $mail->Body = "
                        Hello {$customer['name']},<br>
                        Your reservation for Room {$room['room_number']} ({$room['type']})<br>
                        Check-in: {$check_in}<br>
                        Check-out: {$check_out}<br>
                        Total Amount: $" . number_format($total_amount, 2) . "<br><br>
                        Thank you for choosing our hotel.
                    ";
                    $mail->send();
                } catch (Exception $e) {
                    error_log("Mail could not be sent. Error: " . $mail->ErrorInfo);
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Reservation</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex bg-gray-100">

<!-- Sidebar -->
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Content -->
<div class="flex-1 flex flex-col h-screen">
    <!-- Header -->
    <header class="bg-white shadow p-4 flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-700">✨ Add Reservation</h1>
        <span class="text-gray-600 text-sm">Hello, <?= e($_SESSION['user']['name']) ?> | Dashboard / Add Reservation</span>
    </header>

    <!-- Main Form -->
    <main class="flex-1 flex justify-center items-start p-6 overflow-auto">
        <form method="POST" class="w-full max-w-3xl bg-white p-8 md:p-12 rounded-lg shadow-lg space-y-6">
            <?php if ($error): ?>
                <div class="p-3 bg-red-100 text-red-700 rounded"><?= e($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="p-3 bg-green-100 text-green-700 rounded"><?= e($success) ?></div>
            <?php endif; ?>

            <!-- Customer -->
            <div class="flex flex-col space-y-2">
                <label class="text-gray-700 font-semibold">👤 Customer</label>
                <div class="flex flex-col sm:flex-row sm:space-x-2">
                    <select name="user_id" id="user_id" class="flex-1 border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        <option value="">-- Select Customer --</option>
                        <?php foreach ($users as $u): ?>
                            <option value="<?= e($u['id']) ?>"><?= e($u['name']) ?> (<?= e($u['email']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" onclick="openModal()" class="mt-2 sm:mt-0 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 shadow-md">➕ Add Customer</button>
                </div>
            </div>

            <!-- Room -->
            <div class="flex flex-col space-y-2">
                <label class="text-gray-700 font-semibold">🏨 Room</label>
                <select name="room_id" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    <option value="">-- Select Room --</option>
                    <?php foreach ($rooms as $r): ?>
                        <option value="<?= e($r['id']) ?>"><?= e($r['room_number']) ?> - <?= e($r['type']) ?> ($<?= number_format($r['price'],2) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Check-in -->
            <div class="flex flex-col space-y-2">
                <label class="text-gray-700 font-semibold">📅 Check-in</label>
                <input type="date" name="check_in" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
            </div>

            <!-- Check-out -->
            <div class="flex flex-col space-y-2">
                <label class="text-gray-700 font-semibold">📅 Check-out</label>
                <input type="date" name="check_out" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center">
                <button type="submit" class="w-full sm:w-48 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold shadow-md transition">Add Reservation ✨</button>
            </div>
        </form>
    </main>
</div>

<!-- Add Customer Modal -->
<div id="customerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <h2 class="text-xl font-semibold mb-4">➕ Add Customer</h2>
        <div id="modalSuccess" class="text-green-600 text-sm mb-2 opacity-0 transition-opacity duration-500"></div>
        <form id="addCustomerForm" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Name</label>
                <input type="text" name="name" class="w-full border border-gray-300 rounded-lg p-2.5" required>
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Email</label>
                <input type="email" name="email" class="w-full border border-gray-300 rounded-lg p-2.5" required>
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Password</label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded-lg p-2.5" required>
            </div>
            <div id="modalError" class="text-red-600 text-sm"></div>
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add</button>
            </div>
        </form>
        <button onclick="closeModal()" class="absolute top-2 right-3 text-gray-500 text-xl">&times;</button>
    </div>
</div>

<script>
const modal = document.getElementById('customerModal');
const form = document.getElementById('addCustomerForm');
const dropdown = document.getElementById('user_id');
const errorDiv = document.getElementById('modalError');
const successDiv = document.getElementById('modalSuccess');

function openModal() {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    form.reset();
    errorDiv.textContent = '';
    successDiv.textContent = '';
    successDiv.classList.remove('opacity-100');
    successDiv.classList.add('opacity-0');
}

function closeModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    errorDiv.textContent = '';
    successDiv.textContent = '';
    successDiv.classList.remove('opacity-100');
    successDiv.classList.add('opacity-0');

    const formData = new FormData(form);

    try {
        const response = await fetch('../../admin/users/quick_create.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            const option = document.createElement('option');
            option.value = data.id;
            option.textContent = `${data.name} (${data.email})`;
            dropdown.appendChild(option);
            dropdown.value = data.id;

            successDiv.textContent = '✅ Customer added successfully!';
            successDiv.classList.add('opacity-100');

            form.reset();
            setTimeout(() => closeModal(), 1500);
        } else {
            errorDiv.textContent = data.message;
        }
    } catch {
        errorDiv.textContent = 'Something went wrong. Please try again.';
    }
});
</script>
</body>
</html>
