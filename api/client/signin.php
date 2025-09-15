<?php
require_once "../config/dbconnection.php";
header("Content-Type: application/json");
session_start();

$inputData = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "අවලංගු ඉල්ලීම් ක්‍රමයකි."
    ]);
    exit();
}

$email = $inputData["email"] ?? null;
$password = $inputData["password"] ?? null;
$rememberMe = isset($inputData["remember_me"]) ? true : false;
if (!$email || !$password) {
    echo json_encode([
        "success" => false,
        "message" => "විද්‍යුත් තැපෑල සහ මුරපදය අවශ්‍ය වේ."
    ]);
    exit();
}
try {
    $database = new Database();
    $conn = $database->getConnection();
    $query = "SELECT * FROM user WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $client = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($client && password_verify($password, $client['password_hash'])) {
        $_SESSION["client_id"] = $client['id'];
        if ($rememberMe) {
            $rememberToken = bin2hex(random_bytes(32));
            $hashedRememberToken = password_hash($rememberToken, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE user SET remember_token = :remember_token WHERE id = :client_id";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bindParam(":remember_token", $hashedRememberToken);
            $updateStmt->bindParam(":client_id", $client["id"]);
            $updateStmt->execute();
            setcookie("remember_me", $rememberToken, time() + 86400 * 30, "/", "", false, true);
        }
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $_SESSION["client_name"] = $client['first_name'] . " " . $client['last_name'];
        $_SESSION["client_avatar"] = $client['profile_picture_url'];
        $_SESSION["client_type"] = 'email';
        $_SESSION["client_email"] = $email;
        $_SESSION["client_mobile"] = $client["mobile"];
        $_SESSION["client_address"] = $client["address"];
        $_SESSION["client_city"] = $client["city"];
        echo json_encode([
            "success" => true,
            "message" => "ඇතුල් වීම සාර්ථකයි.",
            "client_id" => $client['id']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'වලංගු නොවන විද්‍යුත් තැපෑලක් හෝ මුරපදයකි '
        ]);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "අභ්‍යන්තර දෝෂයක් සිදු වී ඇත."
    ]);
}finally {
    $conn = null; 
}

