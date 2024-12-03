<?php
include 'db_server.php';

header('Content-Type: application/json');

try {
    $thesesQuery = "
        SELECT 
            t.id, t.title, t.year_of_submission, t.type_of_text, t.pdf_file_path,
            t.university, t.doi, t.publish_date, 
            GROUP_CONCAT(DISTINCT a.author_name ORDER BY a.author_name SEPARATOR ', ') AS authors,
            GROUP_CONCAT(DISTINCT tg.tag_name ORDER BY tg.tag_name SEPARATOR ', ') AS tags
        FROM theses t
        LEFT JOIN thesis_authors a ON t.id = a.thesis_id
        LEFT JOIN thesis_tags tg ON t.id = tg.thesis_id
        GROUP BY t.id
        ORDER BY t.created_at DESC";

    $result = $conn->query($thesesQuery);

    if (!$result) {
        throw new Exception("Database query failed: " . $conn->error);
    }

    $theses = [];
    while ($row = $result->fetch_assoc()) {
        $theses[] = $row;
    }

    echo json_encode(["success" => true, "data" => $theses]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
