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

$timeSlot = $input['timeSlot'];
$examDate = $input['examDate'];
$examPaperID = $input['examPaper'];
$csrf_token = $input['csrf_token'];
$examId = $input['examId'];
$examPrice = $input['examPrice'];


if (!$csrf_token || $csrf_token !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "CSRF token validation failed."]);
    exit();
}

try {
    $examService = new ExamService();
    $result = $examService->updateExamTime($examId, $examPaperID, $timeSlot, $examDate,$examPrice);
    if ($result) {
        http_response_code(200);
        echo json_encode(['success' => true , 'message'=>'exam updated successfully'] );
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to schedule the exam'.$examPaper]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
