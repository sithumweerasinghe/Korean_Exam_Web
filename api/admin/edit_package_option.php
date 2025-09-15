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
    if (!isset($input['id']) || !isset($input['name'])  || !isset($input['csrf_token'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input. ID,option name, and CSRF token are required.']);
        exit();
    }
    $id = $input['id'];
    $name = $input['name'];
    $csrfToken = $input['csrf_token'];
    if (!isset($_SESSION['csrf_token']) || $csrfToken !== $_SESSION['csrf_token']) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid CSRF token.']);
        exit();
    }
    try {
        $packageService = new PackageService();
        $packageService->updatePackageOption($id, $name);
        echo json_encode(value: ['success' => 'Package option updated successfully.']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'An error occurred while updating the package option.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
}
