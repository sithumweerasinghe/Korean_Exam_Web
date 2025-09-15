<?php
require_once '../config/dbconnection.php';

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

$first_name = $inputData["first_name"] ?? null;
$last_name = $inputData["last_name"] ?? null;
$email = $inputData["email"] ?? null;
$password = $inputData["password"] ?? null;

if (!$first_name || !$last_name || !$email || !$password) {
    echo json_encode([
        "success" => false,
        "message" => "සියලුම පිරවීම් අවශ්‍ය වේ."
    ]);
    exit();
}
if (strlen($first_name) > 50) {
    echo json_encode([
        "success" => false,
        "message" => "මුල් නම අකුරු 50 ඉක්මවා නොයා යුතුය."
    ]);
    exit();
}
if (strlen($last_name) > 50) {
    echo json_encode([
        "success" => false,
        "message" => "අවසන් නම අකුරු 50 ඉක්මවා නොයා යුතුය."
    ]);
    exit();
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "success" => false,
        "message" => "අවලංගු විද්‍යුත් තැපැල් ලිපිනයකි."
    ]);
    exit();
}
if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[@#$%^&+=]).{8,}$/", $password)) {
    echo json_encode([
        "success" => false,
        "message" => "මුරපදය අකුරින් 8ක් වත් සහ එක අකුරු ලොකුක්, කුඩාක්, එකක් අංකයක්, විශේෂ ලක්ෂණයක් (@#$%^&+=) අඩංගු විය යුතුය."
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

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "success" => false,
            "message" => "පරිශීලකයා දැනටමත් පවතී."
        ]);
        exit();
    }

    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $rememberToken = bin2hex(random_bytes(32));
    $hashedRememberToken = password_hash($rememberToken, PASSWORD_DEFAULT);

    $query = "INSERT INTO user (first_name,last_name,email,password_hash,auth_providers_provider_id,remember_token) VALUES (:first_name, :last_name, :email, :password_hash, 1, :remember_token)";
    $insertStmt = $conn->prepare($query);
    $insertStmt->bindParam(":first_name", $first_name);
    $insertStmt->bindParam(":last_name", $last_name);
    $insertStmt->bindParam(":email", $email);
    $insertStmt->bindParam(":password_hash", $password_hash);
    $insertStmt->bindParam(":remember_token", $hashedRememberToken);

    if ($insertStmt->execute()) {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $_SESSION["client_id"] = $conn->lastInsertId();
        $_SESSION["client_name"] = $first_name . " " . $last_name;
        $_SESSION["client_avatar"] = "";
        $_SESSION["client_type"] = 'email';
        $_SESSION["client_email"] = $email;
        $_SESSION["client_mobile"] = "";
        $_SESSION["client_address"] = "";
        $_SESSION["client_city"] = "";
        setcookie("remember_me", $rememberToken, time() + 86400 * 30, "/", "", false, true);
        echo json_encode([
            "success" => true,
            "message" => "පරිශීලකයා සාර්ථකව ලියාපදිංචි විය."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "පරිශීලකයා ලියාපදිංචි කිරීමට අසමත් විය."
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

