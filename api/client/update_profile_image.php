<?php
require_once '../config/dbconnection.php';

header("Content-Type: application/json");

define('MAX_FILE_SIZE', 2 * 1024 * 1024);
define('ALLOWED_MIME_TYPES', ["image/jpeg", "image/png", "image/svg+xml"]);

include "../request-filters/client_request_filter.php";

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

if (!isset($_POST["email"]) || empty($_POST["email"]) || !isset($_FILES["image"]) ) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid or missing parameters."
    ]);
    exit();
}

$email = $_POST["email"];
$profile_image = $_FILES["image"];

if ($profile_image['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        'success' => false,
        'message' => '"No file Uploaded or file Upload Error'
    ]);
    exit();
}

function validateFile($file)
{
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    return in_array($mimeType, ALLOWED_MIME_TYPES) && $file['size'] <= MAX_FILE_SIZE;
}

if (!validateFile($profile_image)) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid file. Ensure it's a JPG, PNG, or SVG and under 2MB."
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
            'message' => 'Users logged in google can not update profile images '
        ]);
        exit();
    }

    $upload_dir = "../../uploads/client/profileImages/";
    $existing_profile_img = $client['profile_picture_url'] ?? null;
    if ($existing_profile_img && file_exists($existing_profile_img) && is_writable($existing_profile_img)) {
        unlink($upload_dir . $existing_profile_img);
    }

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_extension = pathinfo($profile_image["name"], PATHINFO_EXTENSION);
    $file_name = $client["id"] . "_" . $client["first_name"] . "." . $file_extension;

    if (move_uploaded_file($profile_image["tmp_name"], $upload_dir . $file_name)) {
        $updateQuery = "UPDATE user SET profile_picture_url = :profile_pic WHERE id = :client_id";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(":profile_pic", $file_name);
        $updateStmt->bindParam(":client_id", $client["id"]);
        $updateStmt->execute();
        $_SESSION["client_avatar"] = $file_name;
        echo json_encode([
            "success" => true,
            "message" => "Updated profile image successfully."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to move the uploaded file."
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

