<?php
require_once "../config/dbconnection.php";
header("Content-Type: application/json");

include "../request-filters/client_request_filter.php";
$inputData = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid Request Method."
    ]);
    exit();
}

if (!isset($_SESSION["client_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Please login to continue"
    ]);
    exit();
}

if (!isset($_GET["invoice_no"]) && empty($_GET["invoice_no"]) && !isset($_GET["isExam"]) && empty($_GET["isExam"])) {
    echo json_encode([
        "success" => false,
        "message" => "Parameters are empty."
    ]);
    exit();
}

$invoiceNo = $_GET["invoice_no"];
$isExam = $_GET["isExam"];

try {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT * FROM invoice WHERE invoice_no = :invoice_no";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":invoice_no", $invoiceNo,);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        echo json_encode([
            "success" => false,
            "message" => "Invoice not found"
        ]);
        exit();
    }

    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    $updateQuery = "UPDATE invoice SET payment_status_status_id=2 WHERE invoice_no=:invoice_no";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(":invoice_no", $invoiceNo);

    if (!$updateStmt->execute()) {
        echo json_encode([
            "success" => false,
            "message" => "Error updating payment status."
        ]);
        exit();
    } else {
        if ($isExam == "true") {
            echo json_encode([
                "success" => true,
                "message" => "Payment Successful."
            ]);
        }
    }

    if ($isExam == "false") {

        $checkPackageQuery = "SELECT * FROM user_has_packages WHERE user_id = :userId AND packages_id = :packagesId";
        $checkPackageStmt = $conn->prepare($checkPackageQuery);
        $checkPackageStmt->bindParam(":userId", $client["user_id"]);
        $checkPackageStmt->bindParam(":packagesId", $client["packages_id"]);
        $checkPackageStmt->execute();

        if ($checkPackageStmt->rowCount() != 0) {
            echo json_encode([
                "success" => false,
                "message" => "User already bought this package"
            ]);
            exit();
        };

        $insertQuery = "INSERT INTO user_has_packages (user_id, packages_id) VALUES (:user_id, :packages_id)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bindParam(":user_id", $client["user_id"]);
        $insertStmt->bindParam(":packages_id", $client["packages_id"]);

        if (!$insertStmt->execute()) {
            echo json_encode([
                "success" => false,
                "message" => "Error inserting to user has packages."
            ]);
            exit();
        }

        echo json_encode([
            "success" => true,
            "message" => "Payment Successful."
        ]);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Internal server error."
    ]);
}finally {
    $conn = null; 
}

