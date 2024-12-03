<?php
include 'db_server.php';

if (isset($_GET['id'])) {
    $thesis_id = $_GET['id'];

    $thesisQuery = "SELECT * FROM theses WHERE id = '$thesis_id'";
    $result = $conn->query($thesisQuery);
    if ($result->num_rows > 0) {
        $thesis = $result->fetch_assoc();

        $authorsQuery = "SELECT * FROM thesis_authors WHERE thesis_id = '$thesis_id'";
        $authorsResult = $conn->query($authorsQuery);
        $authors = [];
        while ($author = $authorsResult->fetch_assoc()) {
            $authors[] = $author['author_name'];
        }

        $tagsQuery = "SELECT * FROM thesis_tags WHERE thesis_id = '$thesis_id'";
        $tagsResult = $conn->query($tagsQuery);
        $tags = [];
        while ($tag = $tagsResult->fetch_assoc()) {
            $tags[] = $tag['tag_name'];
        }
    } else {
        echo "Thesis not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid request. Thesis ID is required.']);
        exit;
    }

    $thesis_id = $_GET['id'];
    $title = $_POST['title'];
    $university = $_POST['university'];
    $year_of_submission = $_POST['year_of_submission'];
    $type_of_text = $_POST['type_of_text'];
    $description = $_POST['description'];
    $doi = $_POST['doi'] ?? null;
    $published_date = $_POST['published_date'] ?? null;

    // Handle PDF upload
    $pdf_file_path = $thesis['pdf_file_path']; // Default to the current file path
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
        $pdf_file = $_FILES['pdf_file'];
        $upload_dir = 'uploads/';
        $pdf_file_path = $upload_dir . basename($pdf_file['name']);
        move_uploaded_file($pdf_file['tmp_name'], $pdf_file_path);
    }

    // Update thesis data
    $thesisQuery = "
        UPDATE theses 
        SET title = '$title', university = '$university', year_of_submission = '$year_of_submission', 
            type_of_text = '$type_of_text', description = '$description', doi = '$doi', 
            pdf_file_path = '$pdf_file_path', publish_date = '$published_date'
        WHERE id = '$thesis_id'";

    if ($conn->query($thesisQuery) === TRUE) {
        // Update authors and tags
        $conn->query("DELETE FROM thesis_authors WHERE thesis_id = '$thesis_id'");
        $conn->query("DELETE FROM thesis_tags WHERE thesis_id = '$thesis_id'");

        foreach ($_POST['authors'] as $author) {
            $authorQuery = "INSERT INTO thesis_authors (thesis_id, author_name) VALUES ('$thesis_id', '$author')";
            $conn->query($authorQuery);
        }

        foreach ($_POST['tags'] as $tag) {
            $tagQuery = "INSERT INTO thesis_tags (thesis_id, tag_name) VALUES ('$thesis_id', '$tag')";
            $conn->query($tagQuery);
        }

        echo json_encode(['success' => true, 'message' => 'Thesis updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update thesis']);
    }
}
