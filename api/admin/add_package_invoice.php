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
    $package_id = isset($_GET['package_id']) ? $_GET['package_id'] : null;

    if (empty($userId) || !is_numeric($userId)) {
        echo json_encode(['success' => false, 'message' => 'Invalid or missing user ID']);
        exit;
    }

    if (empty($package_id) || !is_numeric($package_id)) {
        echo json_encode(['success' => false, 'message' => 'Invalid or missing package ID']);
        exit;
    }

    $invoiceService = new InvoiceService();
    $invoiceNo = time();

    $result = $invoiceService->createPackageInvoice($package_id, $userId, $invoiceNo);

    echo json_encode(['success' => true, 'message' => 'Invoice added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
