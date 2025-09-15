<?php
require_once '../config/dbconnection.php';

header("Content-Type: application/json");

session_start();

$inputData = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $inputData['email'] ?? null;
    $password = $inputData['password'] ?? null;
    $rememberMe = isset($inputData['remember_me']);

    if (!$email || !$password) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Email and password are required."]);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Invalid email format."]);
        exit();
    }

    try {
        $database = new Database();
        $conn = $database->getConnection();

        $query = "SELECT * FROM admin WHERE email = :email LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id'] = $admin['admin_id'];

            if (!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }

            if ($rememberMe) {
                $rememberToken = bin2hex(random_bytes(32));
                $hashedToken = password_hash($rememberToken, PASSWORD_DEFAULT);

                $insertQuery = "UPDATE admin SET remember_token = :remember_token WHERE admin_id = :admin_id";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bindParam(':remember_token', $hashedToken);
                $insertStmt->bindParam(':admin_id', $admin['admin_id']);
                $insertStmt->execute();

                setcookie("remember_me", $rememberToken, time() + (86400 * 30), "/", "", true, true);
            }

            echo json_encode(["success" => true, "message" => "Sign-in successful.", "admin_id" => $admin['admin_id']]);
        } else {
            http_response_code(401);
            echo json_encode(["success" => false, "message" => "Invalid email or password."]);
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Internal server error."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>
