<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '../../config/dbconnection.php'; 

if (!isset($_SESSION['client_id'])) {
    if (isset($_COOKIE['remember_me'])) {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            $rememberToken = $_COOKIE['remember_me'];
            $query = "SELECT * FROM user WHERE remember_token IS NOT NULL";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $validClient = null;
            foreach ($clients as $client) {
                if (password_verify($rememberToken, $client['remember_token'])) {
                    $validClient = $client;
                    break;
                }
            }
            if ($validClient) {
                $_SESSION['client_id'] = $validClient['client_id'];
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            } else {
                throw new Exception("Invalid remember token.");
            }
        } catch (Exception $e) {
            error_log("Error verifying remember token: " . $e->getMessage());
            header("Location: index");
            exit();
        }
    } else {
        header("Location: ./");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['csrf_token'])) {
    $csrfToken = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
    if ($csrfToken !== $_SESSION['csrf_token']) {
        http_response_code(403);
        echo "Invalid CSRF token.";
        exit();
    }
}

