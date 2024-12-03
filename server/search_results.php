<?php
include 'db_server.php';

$search = $_GET['search'] ?? '';
if ($search) {
    $query = $conn->prepare("
        SELECT t.*, 
               GROUP_CONCAT(DISTINCT ta.author_name SEPARATOR ', ') AS authors, 
               GROUP_CONCAT(DISTINCT tg.tag_name SEPARATOR ', ') AS tags 
        FROM theses t
        LEFT JOIN thesis_authors ta ON t.id = ta.thesis_id
        LEFT JOIN thesis_tags tg ON t.id = tg.thesis_id
        WHERE 
            t.title LIKE ? 
            OR ta.author_name LIKE ? 
            OR tg.tag_name LIKE ? 
            OR t.publish_date LIKE ?
        GROUP BY t.id
    ");
    $searchParam = "%$search%";
    $query->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
    $query->execute();
    $result = $query->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>ThesisLookup | Search Results</title>
    <link href="/ws1-jamer/public/tailwind.css" rel="stylesheet">
</head>

<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col">

    <!-- Header -->
    <header class="flex items-center justify-between px-8 py-6 bg-white shadow-md">
        <div class="text-2xl font-bold">Thesis<span class="text-blue-500">Lookup</span></div>
        <nav>
            <ul class="flex space-x-6 text-lg">
                <li><a href="/ws1-jamer/src/index.html" class="hover:text-blue-500">Home</a></li>
                <li><a href="/ws1-jamer/src/components/login.php" class="hover:text-blue-500">Login</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content Wrapper -->
    <div class="flex-grow max-w-4xl mx-auto my-10 p-6 bg-white shadow-lg rounded-lg">

        <h1 class="text-3xl font-semibold text-blue-600 mb-6">Search Results for "<?php echo htmlspecialchars($search); ?>"</h1>

        <!-- Results Section -->
        <?php if (!empty($result) && $result->num_rows > 0): ?>
            <div class="space-y-6">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="p-4 bg-white shadow-sm rounded-lg hover:shadow-md transition-all duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-3xl font-semibold text-blue-700 hover:text-blue-800 cursor-pointer transition-all">
                                <a href="/ws1-jamer/src/components/guest_thesis_detail.php?id=<?php echo $row['id']; ?>">
                                    <?php echo htmlspecialchars($row['title']); ?>
                                </a>
                            </h2>
                            <p class="text-sm text-gray-500"><?php echo htmlspecialchars($row['publish_date']); ?></p>
                        </div>
                        <div class="flex space-x-4 mt-2">
                            <p class="text-sm text-gray-500"><strong>Authors:</strong> <?php echo htmlspecialchars($row['authors']); ?></p>
                            <p class="text-sm text-gray-500"><strong>Tags:</strong> <?php echo htmlspecialchars($row['tags']); ?></p>
                        </div>
                        <div class="mt-4 text-sm text-gray-600">
                            <p><strong>University:</strong> <?php echo htmlspecialchars($row['university']); ?></p>
                            <p><strong>Year of Submission:</strong> <?php echo htmlspecialchars($row['year_of_submission']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-lg text-center text-gray-500">No results found.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-blue-100 text-gray-600 text-sm py-4 text-center">
        <p>&copy; 2024 ThesisLookup | All Rights Reserved.</p>
    </footer>
</body>

</html>