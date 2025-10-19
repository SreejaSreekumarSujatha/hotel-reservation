<?php
// Assume $error, $success, and $user are already set
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen bg-gray-100">

<!-- Sidebar -->
<?php include __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Content -->
<div class="flex-1 flex flex-col md:ml-64 p-6">

    <!-- Header -->
    <h1 class="text-3xl font-bold mb-6 text-center">✏️ Edit User</h1>

    <!-- Flash messages -->
    <?php if (!empty($error)): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"><?= e($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded"><?= e($success) ?></div>
    <?php endif; ?>

    <!-- Form -->
    <form method="POST" action="edit.php" class="bg-white p-6 rounded-xl shadow-lg w-full max-w-4xl mx-auto space-y-6">

        <input type="hidden" name="id" value="<?= e($user['id']) ?>">

        <!-- Name -->
        <div>
            <label class="block font-semibold mb-2">Name</label>
            <input type="text" name="name" value="<?= e($user['name']) ?>" required 
                   class="w-full border border-gray-300 rounded px-4 py-3 focus:ring focus:ring-blue-200 focus:outline-none text-lg">
        </div>

        <!-- Email -->
        <div>
            <label class="block font-semibold mb-2">Email</label>
            <input type="email" name="email" value="<?= e($user['email']) ?>" required 
                   class="w-full border border-gray-300 rounded px-4 py-3 focus:ring focus:ring-blue-200 focus:outline-none text-lg">
        </div>

        <!-- Password -->
        <div>
            <label class="block font-semibold mb-2">Password <span class="text-sm text-gray-500">(leave blank to keep current)</span></label>
            <input type="password" name="password"
                   pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
                   title="At least 8 chars, one uppercase, one lowercase, one number"
                   class="w-full border border-gray-300 rounded px-4 py-3 focus:ring focus:ring-blue-200 focus:outline-none text-lg">
        </div>

        <!-- Role -->
        <div>
            <label class="block font-semibold mb-2">Role</label>
            <select name="role" class="w-full border border-gray-300 rounded px-4 py-3 focus:ring focus:ring-blue-200 focus:outline-none text-lg">
                <option value="customer" <?= $user['role']=='customer' ? 'selected' : '' ?>>Customer</option>
                <option value="admin" <?= $user['role']=='admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center pt-4">
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition w-1/3 text-lg">
                Update User
            </button>
        </div>

    </form>

    <!-- Back Link -->
    <p class="mt-6 text-center">
        <a href="index.php" class="text-blue-600 hover:underline font-semibold">← Back to Users</a>
    </p>

</div>
</body>
</html>
