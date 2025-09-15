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
$input = json_decode(file_get_contents('php://input'), true);
$csrfToken = $input['csrf_token'] ?? null;

if (!$csrfToken || $csrfToken !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "CSRF token validation failed."]);
    exit();
}

$invoiceId = $input['invoice_id'] ?? null;
$paymentStatus = $input['payment_status'] ?? null;

try {
    $incomeService = new IncomeService();
    $result = $incomeService->updatePaymentStatus($invoiceId, $paymentStatus);

    if ($result) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Payment status updated successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to update payment status.']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
