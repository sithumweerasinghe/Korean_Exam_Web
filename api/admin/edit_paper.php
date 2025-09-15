<?php
require_once '../config/dbconnection.php';
require_once './services/init.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
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

$paper_id = $data['paper_id'] ?? null;
$title = $data['title'] ?? null;
$isSample = isset($data['is_sample']) ? (int)$data['is_sample'] : null;
$paperStatus = isset($data['status']) ? (int)$data['status'] : null;

if (!$paper_id || !filter_var($paper_id, FILTER_VALIDATE_INT)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid paper ID."]);
    exit();
}

if (!$title || !isset($isSample)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
    exit();
}

try {
    $paperService = new PaperService();
    if ($paperService->updatePaper($paper_id, $title, $isSample, $paperStatus)) {
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Paper updated successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Failed to update the paper."]);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Internal server error."]);
}
?>
