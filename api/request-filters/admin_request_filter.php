<?php
session_start();
require __DIR__ . '../../config/dbconnection.php'; 

if (!isset($_SESSION['admin_id'])) {
    if (isset($_COOKIE['remember_me'])) {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            $rememberToken = $_COOKIE['remember_me'];
            $query = "SELECT * FROM admin WHERE remember_token IS NOT NULL";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $validAdmin = null;
            foreach ($admins as $admin) {
                if (password_verify($rememberToken, $admin['remember_token'])) {
                    $validAdmin = $admin;
                    break;
                }
            }
            if ($validAdmin) {
                $_SESSION['admin_id'] = $validAdmin['admin_id'];
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            } else {
                throw new Exception("Invalid remember token.");
            }
        } catch (Exception $e) {
            error_log("Error verifying remember token: " . $e->getMessage());
            header("Location: login.php");
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

?>
