<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ThesisLookup</title>
    <link href="/ws1-jamer/public/tailwind.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-r from-blue-100 via-white to-blue-100 text-gray-800 font-sans">

    <!-- Header -->
    <header class="flex items-center justify-between px-8 py-6 bg-white shadow-md">
        <div class="text-2xl font-bold">Thesis<span class="text-blue-500">Lookup</span></div>
        <nav>
            <ul class="flex space-x-6 text-lg">
                <li><a href="/ws1-jamer/src/index.html" class="hover:text-blue-500">Home</a></li>
                <li><a href="#" class="hover:text-blue-500">Login</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex flex-col items-center justify-center h-screen space-y-8">
        <!-- Hero Section -->
        <div class="text-center">
            <h1 class="text-5xl md:text-6xl font-extrabold tracking-wide">
                Empower Your <span class="text-blue-500">Research</span>
            </h1>
            <p class="mt-4 text-lg md:text-xl text-gray-600">
                Discover and explore groundbreaking theses from students around the world.
            </p>
        </div>
        <!-- Login Form -->
        <div class="bg-white p-8 rounded-lg shadow-lg w-96">
            <h2 class="text-2xl font-bold text-center text-blue-500 mb-6">Login to ThesisLookup</h2>

            <?php if (isset($error)): ?>
                <div class="bg-red-500 text-white p-4 mb-4 rounded-lg text-center">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="/ws1-jamer/server/login.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="w-full p-3 mt-1 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="w-full p-3 mt-1 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition duration-300">Login</button>
            </form>

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">Don't have an account? <a href="register.php" class="text-blue-500 hover:underline">Register here</a></p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-blue-100 text-gray-600 text-sm py-4 text-center">
        <p>&copy; 2024 ThesisLookup | All Rights Reserved.</p>
    </footer>

</body>

</html>