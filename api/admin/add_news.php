<?php
require_once '../config/dbconnection.php';
require_once './services/init.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['admin_id'])) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Unauthorized access. Please sign in."]);
        exit();
    }
    $errors = [];
    $newsTitle = $_POST['news_title'] ?? null;
    $newsDescription = $_POST['news_des'] ?? null;
    $newsTag = $_POST['news_tag'] ?? null;
    $validDays = $_POST['news_valid_days'] ?? null;
    $imagePath = null;

    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] == UPLOAD_ERR_OK) {
        $imageFileExtension = pathinfo($_FILES['imageFile']['name'], PATHINFO_EXTENSION);
        $imageFileName = 'news_image_' . time() . '.' . $imageFileExtension;
        $imageFilePath = '../../uploads/news/' . $imageFileName;
        if (move_uploaded_file($_FILES['imageFile']['tmp_name'], $imageFilePath)) {
            $imagePath = 'uploads/news/' . $imageFileName;
        } else {
            $errors[] = "Failed to upload image file.";
        }
    }
    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'message' => $errors]);
        exit;
    }
    try {
        $newsService = new AdminNewsService();
        if ($newsService->insertNews($newsTitle, $newsDescription,$newsTag,$validDays,$imagePath)) {
            http_response_code(200);
            echo json_encode(["success" => true, "message" => "News added successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to add the news."]);
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Internal server error."]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
