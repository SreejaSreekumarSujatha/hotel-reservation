<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Hotel Reservation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">📝 Register</h2>

        <!-- Flash success -->
        <?php if($msg = flash_get('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-600 text-green-700 p-3 rounded mb-4">
                <?= e($msg) ?>
            </div>
        <?php endif; ?>

        <!-- Error -->
        <?php if(!empty($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-600 text-red-700 p-3 rounded mb-4">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="register.php" class="space-y-4">
            <div>
                <label class="block text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Email</label>
                <input type="email" name="email" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="confirm_password" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Register
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            Already have an account? 
            <a href="login.php" class="text-blue-600 hover:underline">Login here</a>
        </p>
    </div>

</body>
</html>
