<?php
require_once '../config/dbconnection.php';
require_once './services/init.php';

session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit();
}

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Unauthorized access. Please sign in."]);
    exit();
}
$data = json_decode(file_get_contents('php://input'), true);
$csrfToken = $data['csrf_token'] ?? null;
if (!$csrfToken || $csrfToken !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "CSRF token validation failed."]);
    exit();
}

$news_id = $data['news_id'] ?? null;
if (!$news_id || !filter_var($news_id, FILTER_VALIDATE_INT)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid news ID."]);
    exit();
}

try {
    $newsService = new AdminNewsService();
    $image_path = $newsService->getNewsImagePath($news_id);
    if ($image_path && file_exists('../../' . $image_path)) {
        unlink('../../' . $image_path);
    }
    $deleteNews = $newsService->deleteNews($news_id);
    if ($deleteNews) {
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "News deleted successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Failed to delete the news."]);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Internal server error."]);
}
?>