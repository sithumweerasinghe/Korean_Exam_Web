<?php
require_once '../config/dbconnection.php';

header("Content-Type: application/json");
include "../request-filters/client_request_filter.php";

$inputData = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
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

if (!isset($inputData["current_password"]) || !isset($inputData["new_password"]) || empty($inputData["current_password"]) || empty($inputData["new_password"])) {
    echo json_encode([
        "success" => false,
        "message" => "Both current and new passwords are required."
    ]);
    exit();
}

$email = $inputData["email"];
$current_password = $inputData["current_password"];
$new_password = $inputData["new_password"];

if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[@#$%^&+=]).{8,}$/", $new_password)) {
    echo json_encode([
        "success" => false,
        "message" => "The password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a number, and a special character (@#$%^&+=)."
    ]);
    exit();
}

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
        echo json_encode([
            'success' => false,
            'message' => 'Users logged in with Google cannot change their password.'
        ]);
        exit();
    }

    if (!password_verify($current_password, $client["password_hash"])) {
        echo json_encode([
            "success" => false,
            "message" => "Current password is incorrect."
        ]);
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    $updateQuery = "UPDATE user SET password_hash = :password_hash WHERE email = :email";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(":password_hash", $hashed_password);
    $updateStmt->bindParam(":email", $email);
    $updateStmt->execute();

    echo json_encode([
        "success" => true,
        "message" => "Password updated successfully."
    ]);
    exit();

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