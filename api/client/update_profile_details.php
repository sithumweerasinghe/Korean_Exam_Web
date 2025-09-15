<?php
require_once '../config/dbconnection.php';

header("Content-Type: application/json");
include "../request-filters/client_request_filter.php";

$inputData = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "අවලංගු ඉල්ලීම් ක්‍රමයකි."
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

if (!isset($inputData["email"]) || empty($inputData["email"])) {
    echo json_encode([
        "success" => false,
        "message" => "Email is required"
    ]);
    exit();
}

$email = $inputData["email"];

try {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT * FROM user WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        echo json_encode([
            "success" => false,
            "message" => "User not found"
        ]);
        exit();
    }

    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($client["auth_providers_provider_id"] == 2) {
        if (isset($inputData["mobile"]) && !empty($inputData["mobile"])) {

            if (!preg_match("/^[0]{1}[7]{1}[01245678]{1}[0-9]{7}$/", $inputData["mobile"])) {
                echo json_encode([
                    "success" => false,
                    "message" => "Invalid mobile number"
                ]);
                exit();
            }

            $updateQuery = "UPDATE user SET mobile = :mobile WHERE email = :email";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bindParam(":mobile", $inputData["mobile"]);
            $updateStmt->bindParam(":email", $email);
            $updateStmt->execute();

            $_SESSION["client_mobile"] = $inputData["mobile"];

            echo json_encode([
                "success" => true,
                "message" => "Mobile number updated successfully"
            ]);
            exit();
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Only mobile number can be updated for Google users"
            ]);
            exit();
        }
    } else {
        if (!(isset($inputData["first_name"]) && isset($inputData["last_name"]))) {
            echo json_encode([
                "success" => false,
                "message" => "First name and last name are required"
            ]);
            exit();
        }

        $first_name = $inputData["first_name"];
        $last_name = $inputData["last_name"];

        if (strlen($first_name) > 50 || strlen($last_name) > 50) {
            echo json_encode([
                "success" => false,
                "message" => "First name and last name must not exceed 50 characters"
            ]);
            exit();
        }

        if (isset($inputData["mobile"]) && !empty($inputData["mobile"])) {
            if (!preg_match("/^[0]{1}[7]{1}[01245678]{1}[0-9]{7}$/", $inputData["mobile"])) {
                echo json_encode([
                    "success" => false,
                    "message" => "Invalid mobile number"
                ]);
                exit();
            }
        }

        $updateQuery = "UPDATE user SET first_name = :first_name, last_name = :last_name";
        if (isset($inputData["mobile"]) && !empty($inputData["mobile"])) {
            $updateQuery .= ", mobile = :mobile";
        }
        $updateQuery .= " WHERE email = :email";

        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(":first_name", $first_name);
        $updateStmt->bindParam(":last_name", $last_name);
        $updateStmt->bindParam(":email", $email);

        if (isset($inputData["mobile"]) && !empty($inputData["mobile"])) {
            $updateStmt->bindParam(":mobile", $inputData["mobile"]);
            $_SESSION["client_mobile"] = $inputData["mobile"];
        }

        $updateStmt->execute();
        $_SESSION["client_name"] = $inputData['first_name'] . " " . $inputData['last_name'];
        echo json_encode([
            "success" => true,
            "message" => "User details updated successfully"
        ]);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Internal server error"
    ]);
    exit();
}finally {
    $conn = null; 
}

