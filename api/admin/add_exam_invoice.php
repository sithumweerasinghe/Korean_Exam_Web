<?php
require_once '../config/dbconnection.php';
require_once './services/init.php';

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_SESSION['admin_id'])) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Unauthorized access. Please sign in."]);
        exit();
    }

    $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
    if ($csrfToken !== $_SESSION['csrf_token']) {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "Invalid CSRF token"]);
        exit();
    }

    $userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;
    $examId = isset($_GET['exam_id']) ? $_GET['exam_id'] : null;

    if (empty($userId) || !is_numeric($userId)) {
        echo json_encode(['success' => false, 'message' => 'Invalid or missing user ID']);
        exit;
    }

    if (empty($examId) || !is_numeric($examId)) {
        echo json_encode(['success' => false, 'message' => 'Invalid or missing exam ID']);
        exit;
    }

    $invoiceService = new InvoiceService();
    $invoiceNo = time();

    $result = $invoiceService->createExamInvoice($examId, $userId, $invoiceNo);
    if ($result) {
        http_response_code(201);
        echo json_encode(['success' => true, 'message' => 'Exam invoice created successfully', 'invoice_no' => $invoiceNo]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create exam invoice']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
