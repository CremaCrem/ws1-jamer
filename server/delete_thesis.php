<?php
include 'db_server.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);
    $thesis_id = $data['id'];

    if (!$thesis_id) {
        throw new Exception("Thesis ID is required.");
    }

    $getThesisQuery = "SELECT pdf_file_path FROM theses WHERE id = ?";
    $stmt = $conn->prepare($getThesisQuery);
    $stmt->bind_param("i", $thesis_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("Thesis not found.");
    }

    $thesis = $result->fetch_assoc();
    $filePath = $thesis['pdf_file_path'];

    $fullFilePath = __DIR__ . '/uploads/' . $filePath;

    $deleteQuery = "DELETE FROM theses WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $thesis_id);

    if ($stmt->execute()) {
        if (file_exists($fullFilePath)) {
            if (!unlink($fullFilePath)) {
                throw new Exception("Failed to delete the associated file.");
            }
        }

        echo json_encode(["success" => true]);
    } else {
        throw new Exception("Failed to delete the thesis.");
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
