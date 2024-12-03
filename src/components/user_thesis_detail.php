<?php
include '../../server/db_server.php';

$id = $_GET['id'] ?? '';
if ($id) {
    $query = $conn->prepare("
        SELECT t.*, 
               GROUP_CONCAT(DISTINCT ta.author_name SEPARATOR ', ') AS authors, 
               GROUP_CONCAT(DISTINCT tg.tag_name SEPARATOR ', ') AS tags 
        FROM theses t
        LEFT JOIN thesis_authors ta ON t.id = ta.thesis_id
        LEFT JOIN thesis_tags tg ON t.id = tg.thesis_id
        WHERE t.id = ?
        GROUP BY t.id
    ");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $thesis = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>ThesisLookup | Details</title>
    <link href="/ws1-jamer/public/tailwind.css" rel="stylesheet">
</head>

<body class="bg-gray-50 text-gray-900 flex flex-col min-h-screen">
    <!-- Header -->
    <header class="sticky top-0 z-10 flex items-center justify-between px-8 py-6 bg-white shadow-md">
        <div class="text-2xl font-bold">Thesis<span class="text-blue-500">Lookup</span></div>
        <nav>
            <ul class="flex space-x-6 text-lg">
                <li><a href="/ws1-jamer/src/index.html" class="hover:text-blue-500">Home</a></li>
                <li><a href="/ws1-jamer/server/logout.php" class="hover:text-blue-500">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto my-12 p-8 bg-white shadow-lg rounded-lg flex-grow">
        <?php if ($thesis): ?>
            <div class="space-y-8">
                <h1 class="text-4xl font-semibold text-blue-600"><?php echo htmlspecialchars($thesis['title']); ?></h1>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-lg text-gray-600"><strong>Authors:</strong> <?php echo htmlspecialchars($thesis['authors']); ?></p>
                        <p class="text-lg text-gray-600"><strong>Tags:</strong> <?php echo htmlspecialchars($thesis['tags']); ?></p>
                    </div>
                    <div>
                        <p class="text-lg text-gray-600"><strong>Published on:</strong> <?php echo htmlspecialchars($thesis['publish_date']); ?></p>
                        <p class="text-lg text-gray-600"><strong>University:</strong> <?php echo htmlspecialchars($thesis['university']); ?></p>
                        <?php if (!empty($thesis['doi'])): ?>
                            <p class="text-lg text-gray-600"><strong>DOI:</strong> <a href="https://doi.org/<?php echo htmlspecialchars($thesis['doi']); ?>" target="_blank" class="text-blue-500"><?php echo htmlspecialchars($thesis['doi']); ?></a></p>
                        <?php endif; ?>
                    </div>
                </div>
                <p class="text-lg text-gray-600"><strong>Year of Submission:</strong> <?php echo htmlspecialchars($thesis['year_of_submission']); ?></p>
                <div class="mt-8 bg-gray-100 p-6 rounded-lg shadow-sm">
                    <p class="text-xl font-semibold text-blue-600">Abstract</p>
                    <p class="text-base text-gray-700 mt-4"><?php echo nl2br(htmlspecialchars($thesis['description'])); ?></p>
                </div>
                <div class="mt-6 text-center">
                    <a href="/ws1-jamer/server/<?php echo htmlspecialchars($thesis['pdf_file_path']); ?>" class="inline-block">
                        <button class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            Download Thesis (PDF)
                        </button>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <p class="text-lg text-center text-gray-500">Thesis not found.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-blue-100 text-gray-600 text-sm py-6 text-center">
        <p>&copy; 2024 ThesisLookup | All Rights Reserved.</p>
    </footer>
</body>

</html>