<?php

require_once '../config/dbconnection.php';
require_once './services/init.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit();
}
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Unauthorized access. Please sign in."]);
    exit();
}
$input = json_decode(file_get_contents('php://input'), true);

$mark = $input['mark'];
$csrf_token = $input['csrf_token'];

if (!$csrf_token || $csrf_token !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "CSRF token validation failed."]);
    exit();
}

try {
    $paperService = new PaperService();
    $result = $paperService->addCutoffMark($mark);
    if ($result) {
        http_response_code(200);
        echo json_encode(['success' => true , 'message'=>'cutoff marks added successfully'] );
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to schedule the exam']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
