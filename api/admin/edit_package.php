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

    // Validate required fields
    if (!isset($input['id'], $input['name'], $input['description'], $input['price'], $input['months'], $input['options'], $input['papers'], $input['csrf_token'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input. All fields are required.']);
        exit();
    }

    $id = $input['id'];
    $name = $input['name'];
    $description = $input['description'];
    $price = $input['price'];
    $validMonths = $input['months'];
    $options = $input['options'];
    $papers = $input['papers'];
    $csrfToken = $input['csrf_token'];

    // CSRF token validation
    if (!isset($_SESSION['csrf_token']) || $csrfToken !== $_SESSION['csrf_token']) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid CSRF token.']);
        exit();
    }

    try {
        $packageService = new PackageService();
        $packageService->updatePackage($id, $name, $price, $description, $validMonths, $options, $papers);
        echo json_encode(['success' => 'Package updated successfully.']);
    } catch (Exception $e) {
        http_response_code(500);
        error_log($e->getMessage());
        echo json_encode(['error' => 'An error occurred while updating the package.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
}
