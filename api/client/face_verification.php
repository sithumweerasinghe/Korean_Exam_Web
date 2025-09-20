<?php
/**
 * Face Verification API
 * Compares captured image with profile image using computer vision
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/dbconnection.php';
require_once '../request-filters/client_request_filter.php';

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST method allowed');
    }

    // CSRF Protection
    session_start();
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        throw new Exception('Invalid CSRF token');
    }

    // Validate required parameters
    if (!isset($_FILES['captured_image']) || !isset($_POST['profile_image_url'])) {
        throw new Exception('Missing required parameters: captured_image or profile_image_url');
    }

    $capturedImage = $_FILES['captured_image'];
    $profileImageUrl = $_POST['profile_image_url'];
    $isLiveMode = isset($_POST['live_mode']) && $_POST['live_mode'] === 'true';

    // Validate uploaded file
    if ($capturedImage['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload error: ' . $capturedImage['error']);
    }

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $capturedImage['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes)) {
        throw new Exception('Invalid file type. Only JPEG and PNG are allowed.');
    }

    // Validate file size (max 5MB)
    if ($capturedImage['size'] > 5 * 1024 * 1024) {
        throw new Exception('File too large. Maximum size is 5MB.');
    }

    // Create unique filename for captured image
    $uploadDir = '../../uploads/verification/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $extension = pathinfo($capturedImage['name'], PATHINFO_EXTENSION);
    $capturedImagePath = $uploadDir . 'captured_' . uniqid() . '.' . $extension;

    // Move uploaded file
    if (!move_uploaded_file($capturedImage['tmp_name'], $capturedImagePath)) {
        throw new Exception('Failed to save captured image');
    }

    // Download profile image if it's a URL
    $profileImagePath = null;
    if (filter_var($profileImageUrl, FILTER_VALIDATE_URL)) {
        $profileImagePath = downloadImageFromUrl($profileImageUrl, $uploadDir);
    } else {
        // If it's a local path, use it directly
        $profileImagePath = '../../' . ltrim($profileImageUrl, '/');
        if (!file_exists($profileImagePath)) {
            throw new Exception('Profile image not found: ' . $profileImagePath);
        }
    }

    // Perform face comparison
    $similarity = compareFaces($capturedImagePath, $profileImagePath);

    // Clean up temporary files
    if (file_exists($capturedImagePath)) {
        unlink($capturedImagePath);
    }
    if ($profileImagePath && strpos($profileImagePath, 'temp_profile_') !== false && file_exists($profileImagePath)) {
        unlink($profileImagePath);
    }

    // Log verification attempt (optional, skip for live mode to reduce logs)
    if (!$isLiveMode) {
        logVerificationAttempt($similarity);
    }

    // Generate new CSRF token for next request
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    // Return result
    echo json_encode([
        'success' => true,
        'similarity' => round($similarity, 2),
        'passed' => $similarity >= 80,
        'threshold' => 80,
        'live_mode' => $isLiveMode,
        'timestamp' => date('Y-m-d H:i:s'),
        'csrf_token' => $_SESSION['csrf_token']  // Return new CSRF token
    ]);

} catch (Exception $e) {
    // Clean up files on error
    if (isset($capturedImagePath) && file_exists($capturedImagePath)) {
        unlink($capturedImagePath);
    }
    if (isset($profileImagePath) && strpos($profileImagePath, 'temp_profile_') !== false && file_exists($profileImagePath)) {
        unlink($profileImagePath);
    }

    // Generate new CSRF token for next request
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s'),
        'csrf_token' => $_SESSION['csrf_token']  // Return new CSRF token even on error
    ]);
}

/**
 * Download image from URL
 */
function downloadImageFromUrl($url, $uploadDir) {
    $tempFileName = 'temp_profile_' . uniqid() . '.jpg';
    $tempFilePath = $uploadDir . $tempFileName;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $imageData = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$imageData) {
        throw new Exception('Failed to download profile image from URL');
    }

    file_put_contents($tempFilePath, $imageData);
    return $tempFilePath;
}

/**
 * Compare two face images and return similarity percentage
 * This is a simplified implementation. In production, you should use:
 * - OpenCV with PHP
 * - Azure Face API
 * - AWS Rekognition
 * - Google Cloud Vision API
 * - Face++ API
 */
function compareFaces($image1Path, $image2Path) {
    // Method 1: Using basic image comparison (fallback)
    if (function_exists('imagecreatefromjpeg')) {
        return compareImagesBasic($image1Path, $image2Path);
    }
    
    // Method 2: Try to use external face comparison service
    $cloudSimilarity = tryCloudFaceComparison($image1Path, $image2Path);
    if ($cloudSimilarity !== null) {
        return $cloudSimilarity;
    }
    
    // Method 3: Use OpenCV if available (requires opencv extension)
    if (extension_loaded('opencv')) {
        return compareWithOpenCV($image1Path, $image2Path);
    }
    
    // Fallback to basic comparison
    return compareImagesBasic($image1Path, $image2Path);
}

/**
 * Basic image comparison using histogram analysis
 * This is a simplified approach - not suitable for production face verification
 */
function compareImagesBasic($image1Path, $image2Path) {
    try {
        // Load images
        $img1 = loadImage($image1Path);
        $img2 = loadImage($image2Path);
        
        if (!$img1 || !$img2) {
            throw new Exception('Failed to load images for comparison');
        }
        
        // Resize images to same size for comparison
        $width = 100;
        $height = 100;
        
        $resized1 = imagecreatetruecolor($width, $height);
        $resized2 = imagecreatetruecolor($width, $height);
        
        imagecopyresampled($resized1, $img1, 0, 0, 0, 0, $width, $height, imagesx($img1), imagesy($img1));
        imagecopyresampled($resized2, $img2, 0, 0, 0, 0, $width, $height, imagesx($img2), imagesy($img2));
        
        // Calculate similarity using histogram comparison
        $similarity = calculateHistogramSimilarity($resized1, $resized2, $width, $height);
        
        // Clean up
        imagedestroy($img1);
        imagedestroy($img2);
        imagedestroy($resized1);
        imagedestroy($resized2);
        
        // Add some randomness to simulate face detection (remove in production)
        $randomFactor = mt_rand(85, 95) / 100;
        return $similarity * $randomFactor;
        
    } catch (Exception $e) {
        error_log('Face comparison error: ' . $e->getMessage());
        return 0;
    }
}

/**
 * Load image from file
 */
function loadImage($imagePath) {
    $imageInfo = getimagesize($imagePath);
    
    if (!$imageInfo) {
        return false;
    }
    
    switch ($imageInfo[2]) {
        case IMAGETYPE_JPEG:
            return imagecreatefromjpeg($imagePath);
        case IMAGETYPE_PNG:
            return imagecreatefrompng($imagePath);
        case IMAGETYPE_GIF:
            return imagecreatefromgif($imagePath);
        default:
            return false;
    }
}

/**
 * Calculate similarity using histogram comparison
 */
function calculateHistogramSimilarity($img1, $img2, $width, $height) {
    $histogram1 = array_fill(0, 256, 0);
    $histogram2 = array_fill(0, 256, 0);
    
    // Build histograms
    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {
            $rgb1 = imagecolorat($img1, $x, $y);
            $rgb2 = imagecolorat($img2, $x, $y);
            
            $gray1 = ($rgb1 >> 16) + ($rgb1 >> 8 & 255) + ($rgb1 & 255);
            $gray2 = ($rgb2 >> 16) + ($rgb2 >> 8 & 255) + ($rgb2 & 255);
            
            $gray1 = intval($gray1 / 3);
            $gray2 = intval($gray2 / 3);
            
            $histogram1[$gray1]++;
            $histogram2[$gray2]++;
        }
    }
    
    // Calculate correlation coefficient
    $correlation = calculateCorrelation($histogram1, $histogram2);
    
    // Convert to percentage
    return max(0, min(100, $correlation * 100));
}

/**
 * Calculate correlation coefficient between two arrays
 */
function calculateCorrelation($array1, $array2) {
    $n = count($array1);
    $sum1 = array_sum($array1);
    $sum2 = array_sum($array2);
    
    $sum1Sq = 0;
    $sum2Sq = 0;
    $sumCo = 0;
    
    for ($i = 0; $i < $n; $i++) {
        $sum1Sq += $array1[$i] * $array1[$i];
        $sum2Sq += $array2[$i] * $array2[$i];
        $sumCo += $array1[$i] * $array2[$i];
    }
    
    $num = $sumCo - (($sum1 * $sum2) / $n);
    $den = sqrt(($sum1Sq - ($sum1 * $sum1) / $n) * ($sum2Sq - ($sum2 * $sum2) / $n));
    
    if ($den == 0) {
        return 0;
    }
    
    return $num / $den;
}

/**
 * Try cloud-based face comparison (placeholder)
 * Replace with actual cloud service integration
 */
function tryCloudFaceComparison($image1Path, $image2Path) {
    // This is where you would integrate with services like:
    // - Azure Face API
    // - AWS Rekognition
    // - Google Cloud Vision API
    // - Face++ API
    
    // For now, return null to indicate cloud service not available
    return null;
}

/**
 * OpenCV face comparison (placeholder)
 */
function compareWithOpenCV($image1Path, $image2Path) {
    // This would use OpenCV extension for PHP
    // More accurate but requires additional setup
    return null;
}

/**
 * Log verification attempts for audit purposes
 */
function logVerificationAttempt($similarity) {
    try {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $userId = $_SESSION['client_id'] ?? 'unknown';
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'user_id' => $userId,
            'similarity' => $similarity,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];
        
        $logFile = '../../logs/face_verification.log';
        $logDir = dirname($logFile);
        
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
        
    } catch (Exception $e) {
        // Log error but don't fail the main process
        error_log('Failed to log verification attempt: ' . $e->getMessage());
    }
}
?>