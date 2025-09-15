<?php
header('Content-Type: application/json');
require_once '../config/dbconnection.php';
require_once './services/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['answer_id']) || empty($input['answer_id'])) {
        echo json_encode(['success' => false, 'message' => 'Answer ID is required']);
        exit;
    }

    $answer_id = intval($input['answer_id']);

    $db = new Database();
    $conn = $db->getConnection();

    try {
        $checkQuery = $conn->prepare("SELECT id FROM answers WHERE id = ?");
        $checkQuery->execute([$answer_id]);

        if ($checkQuery->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'Answer not found']);
            exit;
        }
        $deleteQuery = $conn->prepare("DELETE FROM answers WHERE id = ?");
        $deleteQuery->execute([$answer_id]);

        echo json_encode(['success' => true, 'message' => 'Answer deleted successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
