<?php
include 'db_server.php';
$query = $_GET['query'] ?? '';

if ($query) {
    $stmt = $conn->prepare("
        SELECT 
            t.id,  -- Add thesis ID
            t.title, 
            GROUP_CONCAT(DISTINCT a.author_name) AS authors, 
            GROUP_CONCAT(DISTINCT tg.tag_name) AS tags, 
            t.publish_date
        FROM theses t
        LEFT JOIN thesis_authors a ON t.id = a.thesis_id
        LEFT JOIN thesis_tags tg ON t.id = tg.thesis_id
        WHERE 
            t.title LIKE ? 
            OR a.author_name LIKE ? 
            OR tg.tag_name LIKE ? 
            OR t.publish_date LIKE ?
        GROUP BY t.id
        LIMIT 10
    ");
    $searchParam = "%$query%";
    $stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    $suggestions = [];

    while ($row = $result->fetch_assoc()) {
        $suggestions[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'authors' => $row['authors'],
            'tags' => $row['tags'],
            'publish_date' => $row['publish_date']
        ];
    }

    echo json_encode(['success' => true, 'suggestions' => $suggestions]);
} else {
    echo json_encode(['success' => false, 'suggestions' => []]);
}
