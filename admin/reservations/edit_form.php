<?php
require __DIR__ . '/../../includes/db_connect.php';
require __DIR__ . '/../../includes/functions.php';
require_admin();

// Fetch reservation, users, and rooms before rendering
// Assume $reservation, $users, $rooms are fetched properly
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Reservation</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen bg-gray-100">

<!-- Sidebar -->
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Content -->
<div class="flex-1 flex flex-col md:ml-64 p-6">

    <!-- Header -->
    <h1 class="text-3xl font-bold mb-6 text-center">✏️ Edit Reservation</h1>

    <!-- Flash messages -->
    <?php if (!empty($error)): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- Form -->
     
    <form method="POST" action="edit.php" class="bg-white p-6 rounded-xl shadow-lg w-full max-w-4xl mx-auto space-y-6">

        <input type="hidden" name="id" value="<?= $reservation['id'] ?>">

        <!-- Customer -->
        <div>
            <label class="block font-semibold mb-2">Customer</label>
            <select name="user_id" required class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                <?php foreach ($users as $u): ?>
                    <option value="<?= $u['id'] ?>" <?= $reservation['user_id'] == $u['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($u['name']) ?> (<?= htmlspecialchars($u['email']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Room -->
        <div>
            <label class="block font-semibold mb-2">Room</label>
            <select name="room_id" required  class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                <?php foreach ($rooms as $r): ?>
                    <option value="<?= $r['id'] ?>" <?= $reservation['room_id'] == $r['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($r['room_number']) ?> - <?= htmlspecialchars($r['type']) ?> ($<?= number_format($r['price'], 2) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Check-in -->
        <div>
            <label class="block font-semibold mb-2">Check-in</label>
            <input type="date" name="check_in" value="<?= $reservation['check_in'] ?>" required class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
        </div>

        <!-- Check-out -->
        <div>
            <label class="block font-semibold mb-2">Check-out</label>
            <input type="date" name="check_out" value="<?= $reservation['check_out'] ?>" required class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center pt-4">
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition w-1/3 text-lg">
                Update Reservation
            </button>
        </div>

    </form>

    <!-- Back Link -->
    <p class="mt-6 text-center"><a href="index.php" class="text-blue-600 hover:underline font-semibold">← Back to Reservations</a></p>

</div>
</body>
</html>
