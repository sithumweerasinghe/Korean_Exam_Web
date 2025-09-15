<?php
require_once '../config/dbconnection.php';
require_once './services/init.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['admin_id'])) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Unauthorized access. Please sign in."]);
        exit();
    }
    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['id']) || !isset($input['start_time']) || !isset($input['end_time']) || !isset($input['csrf_token'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input. ID, Start time, End time, and CSRF token are required.']);
        exit();
    }
    $id = $input['id'];
    $startTime = $input['start_time'];
    $endTime = $input['end_time'];
    $csrfToken = $input['csrf_token'];
    if (!isset($_SESSION['csrf_token']) || $csrfToken !== $_SESSION['csrf_token']) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid CSRF token.']);
        exit();
    }
    try {
        $examService = new ExamService();
        $examService->updateTimeSlot($id, $startTime, $endTime);
        echo json_encode(['success' => 'Time slot updated successfully.']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'An error occurred while updating the time slot.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
}
