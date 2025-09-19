<?php
/**
 * Check Profile Photo API
 * Validates if user has uploaded a profile photo before face verification
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/dbconnection.php';
require_once '../request-filters/client_request_filter.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    // Validate request method
    if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
        throw new Exception('Only GET and POST methods allowed');
    }

    // Check if user is logged in
    if (!isset($_SESSION['client_id'])) {
        throw new Exception('User not logged in');
    }

    $database = new Database();
    $conn = $database->getConnection();

    // Get user profile information
    $query = "SELECT id, profile_picture_url, auth_providers_provider_id FROM user WHERE id = :client_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":client_id", $_SESSION['client_id']);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        throw new Exception('User not found');
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $hasProfilePhoto = false;
    $profileImageUrl = null;
    $profileImagePath = null;

    // Check if user has profile photo
    if (!empty($user['profile_picture_url'])) {
        if ($user['auth_providers_provider_id'] == 2) {
            // Google user - profile image is a URL
            $profileImageUrl = $user['profile_picture_url'];
            $hasProfilePhoto = true;
        } else {
            // Regular user - check if file exists on server
            $profileImagePath = "../../uploads/client/profileImages/" . $user['profile_picture_url'];
            if (file_exists($profileImagePath)) {
                $profileImageUrl = "uploads/client/profileImages/" . $user['profile_picture_url'];
                $hasProfilePhoto = true;
            }
        }
    }

    // Additional validation for image accessibility
    if ($hasProfilePhoto && $profileImageUrl) {
        // For local files, verify they're readable
        if ($user['auth_providers_provider_id'] != 2) {
            if (!is_readable($profileImagePath)) {
                $hasProfilePhoto = false;
                $profileImageUrl = null;
            }
        }
        // For Google URLs, we assume they're accessible (can add URL validation here if needed)
    }

    // Return result
    echo json_encode([
        'success' => true,
        'hasProfilePhoto' => $hasProfilePhoto,
        'profileImageUrl' => $profileImageUrl,
        'isGoogleUser' => $user['auth_providers_provider_id'] == 2,
        'userId' => $user['id'],
        'message' => $hasProfilePhoto ? 
            'Profile photo found - Face verification can proceed' : 
            'No profile photo found - Please upload a profile photo first',
        'timestamp' => date('Y-m-d H:i:s')
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'hasProfilePhoto' => false,
        'profileImageUrl' => null,
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} finally {
    if (isset($conn)) {
        $conn = null;
    }
}
?>