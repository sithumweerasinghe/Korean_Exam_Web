<?php
session_start();

if (session_status() === PHP_SESSION_ACTIVE) {
    session_unset();
    session_destroy();
}

if (isset($_COOKIE['remember_me'])) {
    setcookie("remember_me", "", time() - 3600, "/", "", true, true);
}

header("Content-Type: application/json");
echo json_encode(["success" => true, "message" => "Successfully signed out."]);
