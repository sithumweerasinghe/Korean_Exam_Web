<?php
require __DIR__ . "/../../../../vendor/autoload.php";
require_once '../../../config/dbconnection.php';
session_start();

$client = new Google\Client();
$client->setClientId("836318542204-q1007spij3smievkk77ar7aau37etiiv.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("https://topiksir.com/api/client/auth/google/callback.php");

if (!isset($_GET["code"])) {
    exit("Login Failed");
}

$token = $client->fetchAccessTokenWithAuthCode($_GET["code"]);
if (isset($token['error'])) {
    die('Error during authentication: ' . $token['error']);
}

$client->setAccessToken($token["access_token"]);

$oauth = new Google\Service\Oauth2($client);
$userInfo = $oauth->userinfo->get();

$email = $userInfo->email;
$firstName = $userInfo->givenName;
$lastName = $userInfo->familyName;
$profilePicture = $userInfo->picture;

try {
    $database = new Database();
    $conn = $database->getConnection();

    $stmt = $conn->prepare("SELECT * FROM user WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = :email AND auth_providers_provider_id=2");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $googleUser = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($googleUser) {
            $_SESSION["client_id"] = $googleUser['id'];
            $_SESSION["client_name"] = $googleUser["first_name"] . " " . $googleUser["last_name"];
            $_SESSION["client_avatar"] = $googleUser["profile_picture_url"];
            $_SESSION["client_type"] = 'google';
            $_SESSION["client_email"] = $email;
            $_SESSION["client_mobile"] = $googleUser["mobile"];
            $_SESSION["client_address"] = $googleUser["address"];
            $_SESSION["client_city"] = $googleUser["city"];

            if (!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            $rememberToken = bin2hex(random_bytes(32));
            $hashedRememberToken = password_hash($rememberToken, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE user SET remember_token = :remember_token WHERE id = :client_id";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bindParam(":remember_token", $hashedRememberToken);
            $updateStmt->bindParam(":client_id", $googleUser["id"]);
            $updateStmt->execute();
            setcookie("remember_me", $rememberToken, time() + 86400 * 30, "/", "", false, true);
        }
    } else {
        $query = "INSERT INTO user (first_name, last_name, email, profile_picture_url, auth_providers_provider_id) VALUES (:first_name, :last_name, :email, :profile_picture, 2)";
        $insertStmt = $conn->prepare($query);
        $insertStmt->bindParam(":first_name", $firstName);
        $insertStmt->bindParam(":last_name", $lastName);
        $insertStmt->bindParam(":email", $email);
        $insertStmt->bindParam(":profile_picture", $profilePicture);

        if ($insertStmt->execute()) {
            $_SESSION["client_id"] = $conn->lastInsertId();
            $_SESSION["client_name"] = $firstName . " " . $lastName;
            $_SESSION["client_avatar"] = $profilePicture;
            $_SESSION["client_type"] = 'google';
            $_SESSION["client_email"] = $email;
            $_SESSION["client_mobile"] = "";
            $_SESSION["client_address"] = "";
            $_SESSION["client_city"] = "";

            if (!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }

            $rememberToken = bin2hex(random_bytes(32));
            $hashedRememberToken = password_hash($rememberToken, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE user SET remember_token = :remember_token WHERE id = :client_id";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bindParam(":remember_token", $hashedRememberToken);
            $updateStmt->bindParam(":client_id", $_SESSION["client_id"]);
            $updateStmt->execute();
            setcookie("remember_me", $rememberToken, time() + 86400 * 30, "/", "", false, true);
        }
    }
    header("Location: ../../../../");
    exit();
} catch (Exception $e) {
    error_log($e->getMessage());
    exit("Internal Server Error");
}
