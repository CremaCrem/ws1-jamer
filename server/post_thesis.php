<?php
include 'db_server.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $university = $_POST['university'];
    $year_of_submission = $_POST['year_of_submission'];
    $type_of_text = $_POST['type_of_text'];
    $description = $_POST['description'];
    $doi = $_POST['doi'] ?? null;
    $published_date = $_POST['published_date'] ?? null;

    // Handle PDF upload
    $pdf_file_path = null;
    if (isset($_FILES['pdf_file'])) {
        $pdf_file = $_FILES['pdf_file'];
        $upload_dir = 'uploads/';
        $pdf_file_path = $upload_dir . basename($pdf_file['name']);
        move_uploaded_file($pdf_file['tmp_name'], $pdf_file_path);
    }

    // Insert thesis data
    $thesisQuery = "
        INSERT INTO theses (title, university, year_of_submission, type_of_text, description, doi, pdf_file_path, publish_date)
        VALUES ('$title', '$university', '$year_of_submission', '$type_of_text', '$description', '$doi', '$pdf_file_path', '$published_date')";

    if ($conn->query($thesisQuery) === TRUE) {
        // Insert authors
        $thesis_id = $conn->insert_id;
        foreach ($_POST['authors'] as $author) {
            $authorQuery = "INSERT INTO thesis_authors (thesis_id, author_name) VALUES ('$thesis_id', '$author')";
            $conn->query($authorQuery);
        }

        // Insert tags
        foreach ($_POST['tags'] as $tag) {
            $tagQuery = "INSERT INTO thesis_tags (thesis_id, tag_name) VALUES ('$thesis_id', '$tag')";
            $conn->query($tagQuery);
        }

        echo json_encode(['success' => true, 'message' => 'Thesis added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add thesis']);
    }
}
