<?php
require_once '../config/dbconnection.php';

header("Content-Type: application/json");

session_start();

$inputData = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($inputData['csrf_token']) || $inputData['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode([
            "success" => false,
            "message" => "Invalid CSRF token."
        ]);
        exit();
    }

    $packageName = $inputData['package_name'] ?? null;
    $packagePrice = $inputData['package_price'] ?? null;
    $packageDescription = $inputData['package_description'] ?? null;
    $validMonths = $inputData['valid_months'] ?? null;
    $packageOptions = $inputData['package_options'] ?? [];
    $packagePapers = $inputData['package_papers'] ?? [];

    if (!$packageName || !$packagePrice || !$packageDescription || !$validMonths) {
        echo json_encode([
            "success" => false,
            "message" => "All fields are required: package name, price, description, and valid months."
        ]);
        exit();
    }

    try {
        $database = new Database();
        $conn = $database->getConnection();

        $conn->beginTransaction();

        $query = "INSERT INTO `packages` (`package_name`, `package_price`, `package_description`, `valid_months`) 
                VALUES (:package_name, :package_price, :package_description, :valid_months)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':package_name', $packageName);
        $stmt->bindParam(':package_price', $packagePrice);
        $stmt->bindParam(':package_description', $packageDescription);
        $stmt->bindParam(':valid_months', $validMonths);

        if (!$stmt->execute()) {
            throw new Exception("Failed to add package.");
        }

        $packageId = $conn->lastInsertId();

        if (!empty($packageOptions)) {
            $optionQuery = "INSERT INTO `packages_has_options` (`packages_id`, `package_options_id`) VALUES (:package_id, :option_id)";
            $optionStmt = $conn->prepare($optionQuery);

            foreach ($packageOptions as $optionId) {
                $optionStmt->bindParam(':package_id', $packageId);
                $optionStmt->bindParam(':option_id', $optionId);
                if (!$optionStmt->execute()) {
                    throw new Exception("Failed to add package options.");
                }
            }
        }

        if (!empty($packagePapers)) {
            $paperQuery = "INSERT INTO `packages_has_papers` (`packages_id`, `papers_paper_id`) VALUES (:package_id, :paper_id)";
            $paperStmt = $conn->prepare($paperQuery);

            foreach ($packagePapers as $paperId) {
                $paperStmt->bindParam(':package_id', $packageId);
                $paperStmt->bindParam(':paper_id', $paperId);
                if (!$paperStmt->execute()) {
                    throw new Exception("Failed to add package papers.");
                }
            }
        }

        $conn->commit();

        echo json_encode([
            "success" => true,
            "message" => "Package added successfully."
        ]);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
}
