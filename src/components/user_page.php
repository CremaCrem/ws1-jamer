<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WS1 | ThesisLookup</title>
    <link href="/ws1-jamer/public/tailwind.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-r from-blue-100 via-white to-blue-100 text-gray-800 font-sans">
    <!-- Header -->
    <header class="flex items-center justify-between px-8 py-6 bg-white shadow-md">
        <div class="text-2xl font-bold">Thesis<span class="text-blue-500">Lookup</span></div>
        <nav>
            <ul class="flex space-x-6 text-lg">
                <li><a href="/ws1-jamer/src/index.html" class="hover:text-blue-500">Home</a></li>
                <li><a href="/ws1-jamer/server/logout.php" class="hover:text-blue-500">Logout</a></li>
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

        <!-- Search Bar -->
        <div id="search-container" class="relative w-full max-w-2xl">
            <form action="/ws1-jamer/server/user_search_results.php" method="GET" class="flex">
                <input
                    type="text"
                    name="search"
                    id="search-input"
                    placeholder="Search for topics, authors, or keywords..."
                    class="w-full py-4 px-6 rounded-l-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    oninput="fetchSuggestions(this.value)"
                    autocomplete="off"
                    required>
                <button
                    type="submit"
                    class="bg-blue-500 hover:bg-blue-600 transition duration-300 px-6 rounded-r-lg text-white font-bold">
                    Search
                </button>
            </form>
            <div id="suggestions-box" class="absolute bg-white shadow-lg w-full rounded-b-lg z-10"></div>
        </div>

        <!-- Features -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div class="p-6 bg-white border border-blue-200 rounded-lg shadow-lg hover:scale-105 transform transition duration-300">
                <h2 class="text-2xl font-bold">Browse by Topic</h2>
                <p class="mt-2 text-gray-600">Find theses categorized by popular academic disciplines.</p>
            </div>
            <div class="p-6 bg-white border border-blue-200 rounded-lg shadow-lg hover:scale-105 transform transition duration-300">
                <h2 class="text-2xl font-bold">Discover Authors</h2>
                <p class="mt-2 text-gray-600">Explore the work of brilliant student researchers.</p>
            </div>
            <div class="p-6 bg-white border border-blue-200 rounded-lg shadow-lg hover:scale-105 transform transition duration-300">
                <h2 class="text-2xl font-bold">Submit Your Work</h2>
                <p class="mt-2 text-gray-600">Contribute your research to inspire the academic community.</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-blue-100 text-gray-600 text-sm py-4 text-center">
        <p>&copy; 2024 ThesisLookup | All Rights Reserved.</p>
    </footer>

    <!-- Scripts -->
    <script src="/ws1-jamer/src/js/index_scripts.js"></script>
</body>

</html>