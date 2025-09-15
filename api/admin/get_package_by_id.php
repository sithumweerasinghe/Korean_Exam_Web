<?php
require_once '../config/dbconnection.php';

header('Content-Type: application/json');

if (!isset($_GET["id"])) {
    echo json_encode(['success' => false, 'message' => 'Package not found']);
    exit;
}

$packageId = $_GET["id"];

try {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT * FROM packages WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $packageId);
    $stmt->execute();


    $package = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($package) {
        $optionsQuery = "SELECT 
            po.id AS package_options_id, 
            po.package_options AS package_options
        FROM 
            packages_has_options phs
        JOIN 
            package_options po ON phs.package_options_id = po.id
        WHERE 
            phs.packages_id = :package_id";
        $optionsStmt = $conn->prepare($optionsQuery);  // Fixed to $conn
        $optionsStmt->bindParam(':package_id', $packageId, PDO::PARAM_INT);
        $optionsStmt->execute();
        $options = $optionsStmt->fetchAll(PDO::FETCH_ASSOC);

        $papersQuery = "SELECT 
            p.paper_id AS papers_paper_id, 
            p.paper_name AS papers_paper_title
        FROM 
            packages_has_papers php
        JOIN 
            papers p ON php.papers_paper_id = p.paper_id
        WHERE 
            php.packages_id = :package_id";
        $papersStmt = $conn->prepare($papersQuery);  // Fixed to $conn
        $papersStmt->bindParam(':package_id', $packageId, PDO::PARAM_INT);
        $papersStmt->execute();
        $papers = $papersStmt->fetchAll(PDO::FETCH_ASSOC);

        $package['options'] = $options;
        $package['papers'] = $papers;

        echo json_encode($package);
    } else {
        echo json_encode(['error' => 'Package not found']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred while fetching the package details.']);
}
