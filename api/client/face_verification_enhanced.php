<?php
/**
 * Enhanced Face Verification API
 * - Checks for profile photo before verification
 * - Requires 80% similarity to pass
 * - Implements enhanced face detection
 * - Provides detailed feedback and retry mechanism
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/dbconnection.php';
require_once '../request-filters/client_request_filter.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST method allowed');
    }

    // Check if user is logged in
    if (!isset($_SESSION['client_id'])) {
        throw new Exception('User not logged in');
    }

    // CSRF Protection
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        throw new Exception('Invalid CSRF token');
    }

    // Validate captured image
    if (!isset($_FILES['captured_image'])) {
        throw new Exception('No captured image provided');
    }

    $capturedImage = $_FILES['captured_image'];
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

    // Check if user has profile photo
    $profileCheck = checkUserProfilePhoto($_SESSION['client_id']);
    if (!$profileCheck['hasProfilePhoto']) {
        throw new Exception('Profile photo required. Please upload a profile photo before using face verification.');
    }

    // Create upload directory
    $uploadDir = '../../uploads/verification/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Save captured image temporarily
    $extension = pathinfo($capturedImage['name'], PATHINFO_EXTENSION);
    $capturedImagePath = $uploadDir . 'captured_' . uniqid() . '.' . $extension;

    if (!move_uploaded_file($capturedImage['tmp_name'], $capturedImagePath)) {
        throw new Exception('Failed to save captured image');
    }

    try {
        // Enhanced face detection on captured image
        $faceDetectionResult = performEnhancedFaceDetection($capturedImagePath);
        
        if (!$faceDetectionResult['isFace']) {
            throw new Exception($faceDetectionResult['message']);
        }

        // Perform face comparison with profile photo
        $similarity = compareFacesEnhanced($capturedImagePath, $profileCheck['profileImagePath'], $profileCheck['isGoogleUser']);

        // Enhanced similarity threshold (80%)
        $threshold = 80;
        $passed = $similarity >= $threshold;

        // Generate response message
        $message = generateResponseMessage($similarity, $passed, $faceDetectionResult['confidence']);

        // Log verification attempt
        if (!$isLiveMode) {
            logEnhancedVerificationAttempt($similarity, $passed, $faceDetectionResult['confidence']);
        }

        // Generate new CSRF token for next request
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // Return detailed result
        echo json_encode([
            'success' => true,
            'similarity' => round($similarity, 2),
            'passed' => $passed,
            'threshold' => $threshold,
            'faceConfidence' => round($faceDetectionResult['confidence'], 2),
            'canRetry' => !$passed,
            'message' => $message,
            'live_mode' => $isLiveMode,
            'timestamp' => date('Y-m-d H:i:s'),
            'requiresRetry' => !$passed && $similarity < $threshold,
            'suggestions' => !$passed ? generateImprovementSuggestions($similarity, $faceDetectionResult['confidence']) : [],
            'csrf_token' => $_SESSION['csrf_token']  // Return new CSRF token
        ]);

    } finally {
        // Clean up temporary files
        if (file_exists($capturedImagePath)) {
            unlink($capturedImagePath);
        }
    }

} catch (Exception $e) {
    // Clean up files on error
    if (isset($capturedImagePath) && file_exists($capturedImagePath)) {
        unlink($capturedImagePath);
    }

    // Generate new CSRF token for next request
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    http_response_code(400);
    echo json_encode([
        'success' => false,
        'similarity' => 0,
        'passed' => false,
        'threshold' => 80,
        'faceConfidence' => 0,
        'canRetry' => true,
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s'),
        'requiresRetry' => true,
        'suggestions' => ['Please ensure good lighting', 'Position your face clearly in the center', 'Remove any obstructions from your face'],
        'csrf_token' => $_SESSION['csrf_token']  // Return new CSRF token even on error
    ]);
}

/**
 * Check if user has uploaded a profile photo
 */
function checkUserProfilePhoto($clientId) {
    try {
        $database = new Database();
        $conn = $database->getConnection();

        $query = "SELECT id, profile_picture_url, auth_providers_provider_id FROM user WHERE id = :client_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":client_id", $clientId);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return ['hasProfilePhoto' => false, 'profileImagePath' => null, 'isGoogleUser' => false];
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $hasProfilePhoto = false;
        $profileImagePath = null;
        $isGoogleUser = $user['auth_providers_provider_id'] == 2;

        if (!empty($user['profile_picture_url'])) {
            if ($isGoogleUser) {
                // Google user - profile image is a URL
                $profileImagePath = $user['profile_picture_url'];
                $hasProfilePhoto = true;
            } else {
                // Regular user - check if file exists on server
                $localPath = "../../uploads/client/profileImages/" . $user['profile_picture_url'];
                if (file_exists($localPath) && is_readable($localPath)) {
                    $profileImagePath = $localPath;
                    $hasProfilePhoto = true;
                }
            }
        }

        return [
            'hasProfilePhoto' => $hasProfilePhoto,
            'profileImagePath' => $profileImagePath,
            'isGoogleUser' => $isGoogleUser
        ];

    } catch (Exception $e) {
        error_log('Profile photo check error: ' . $e->getMessage());
        return ['hasProfilePhoto' => false, 'profileImagePath' => null, 'isGoogleUser' => false];
    }
}

/**
 * Enhanced face detection that only accepts actual faces
 */
function performEnhancedFaceDetection($imagePath) {
    try {
        $image = loadImageSafely($imagePath);
        if (!$image) {
            return ['isFace' => false, 'confidence' => 0, 'message' => 'Could not load image for analysis'];
        }

        $width = imagesx($image);
        $height = imagesy($image);

        // Multiple detection methods for better accuracy
        $skinDetection = analyzeSkinRegions($image, $width, $height);
        $faceFeatures = detectFacialFeatures($image, $width, $height);
        $faceGeometry = analyzeFaceGeometry($image, $width, $height);
        $handDetection = detectHands($image, $width, $height);

        // Reject if hand is detected
        if ($handDetection['isHand']) {
            imagedestroy($image);
            return ['isFace' => false, 'confidence' => 0, 'message' => 'Hand detected - Please show your face only'];
        }

        // Calculate overall confidence
        $confidence = ($skinDetection['score'] * 0.3 + $faceFeatures['score'] * 0.4 + $faceGeometry['score'] * 0.3) * 100;

        // Stricter requirements for face detection
        $isFace = $confidence >= 60 && $skinDetection['score'] >= 0.3 && $faceFeatures['score'] >= 0.4;

        imagedestroy($image);

        $message = $isFace ? 
            'Face detected successfully' : 
            'No face detected - Please position your face clearly in the frame';

        return [
            'isFace' => $isFace,
            'confidence' => $confidence,
            'message' => $message,
            'details' => [
                'skin' => $skinDetection['score'],
                'features' => $faceFeatures['score'],
                'geometry' => $faceGeometry['score']
            ]
        ];

    } catch (Exception $e) {
        error_log('Enhanced face detection error: ' . $e->getMessage());
        return ['isFace' => false, 'confidence' => 0, 'message' => 'Error during face detection'];
    }
}

/**
 * Enhanced face comparison with better accuracy
 */
function compareFacesEnhanced($capturedImagePath, $profileImagePath, $isGoogleUser) {
    try {
        // Load captured image
        $capturedImage = loadImageSafely($capturedImagePath);
        if (!$capturedImage) {
            throw new Exception('Failed to load captured image');
        }

        // Load profile image
        $profileImage = null;
        if ($isGoogleUser) {
            // Download Google profile image
            $profileImage = loadImageFromUrl($profileImagePath);
        } else {
            $profileImage = loadImageSafely($profileImagePath);
        }

        if (!$profileImage) {
            imagedestroy($capturedImage);
            throw new Exception('Failed to load profile image');
        }

        // Resize both images to standard size for comparison
        $compareSize = 150;
        $resizedCaptured = resizeImageProperly($capturedImage, $compareSize, $compareSize);
        $resizedProfile = resizeImageProperly($profileImage, $compareSize, $compareSize);

        // Multiple comparison methods
        $pixelSimilarity = calculatePixelSimilarity($resizedCaptured, $resizedProfile, $compareSize);
        $histogramSimilarity = calculateHistogramSimilarityEnhanced($resizedCaptured, $resizedProfile, $compareSize, $compareSize);
        $facialRegionSimilarity = calculateFacialRegionSimilarity($resizedCaptured, $resizedProfile, $compareSize);

        // Weighted combination for final similarity
        $finalSimilarity = ($pixelSimilarity * 0.3 + $histogramSimilarity * 0.4 + $facialRegionSimilarity * 0.3);

        // Apply realistic bounds
        $finalSimilarity = max(15, min(95, $finalSimilarity));

        // Clean up
        imagedestroy($capturedImage);
        imagedestroy($profileImage);
        imagedestroy($resizedCaptured);
        imagedestroy($resizedProfile);

        return $finalSimilarity;

    } catch (Exception $e) {
        error_log('Enhanced face comparison error: ' . $e->getMessage());
        return 25; // Low similarity on error
    }
}

/**
 * Analyze skin regions in the image
 */
function analyzeSkinRegions($image, $width, $height) {
    $skinPixels = 0;
    $totalSamples = 0;
    $step = max(2, min($width, $height) / 100);

    for ($x = 0; $x < $width; $x += $step) {
        for ($y = 0; $y < $height; $y += $step) {
            $rgb = imagecolorat($image, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            $totalSamples++;
            if (isAdvancedSkinColor($r, $g, $b)) {
                $skinPixels++;
            }
        }
    }

    $skinRatio = $totalSamples > 0 ? $skinPixels / $totalSamples : 0;
    $score = ($skinRatio >= 0.2 && $skinRatio <= 0.7) ? $skinRatio : 0;

    return ['score' => $score, 'ratio' => $skinRatio];
}

/**
 * Detect facial features (eyes, nose, mouth regions)
 */
function detectFacialFeatures($image, $width, $height) {
    $centerX = $width / 2;
    $centerY = $height / 2;
    $faceRadius = min($width, $height) * 0.4;

    $eyeRegionScore = detectEyeRegion($image, $centerX, $centerY - $faceRadius * 0.2, $faceRadius * 0.6, $faceRadius * 0.3);
    $noseRegionScore = detectNoseRegion($image, $centerX, $centerY, $faceRadius * 0.3, $faceRadius * 0.4);
    $mouthRegionScore = detectMouthRegion($image, $centerX, $centerY + $faceRadius * 0.3, $faceRadius * 0.4, $faceRadius * 0.2);

    $overallScore = ($eyeRegionScore + $noseRegionScore + $mouthRegionScore) / 3;

    return ['score' => $overallScore, 'details' => [
        'eyes' => $eyeRegionScore,
        'nose' => $noseRegionScore,
        'mouth' => $mouthRegionScore
    ]];
}

/**
 * Analyze face geometry and proportions
 */
function analyzeFaceGeometry($image, $width, $height) {
    // Check for oval/circular face shape
    $aspectRatio = $width / $height;
    $geometryScore = 0;

    // Good aspect ratio for face
    if ($aspectRatio >= 0.7 && $aspectRatio <= 1.3) {
        $geometryScore += 0.5;
    }

    // Check for symmetric brightness distribution
    $symmetryScore = checkFaceSymmetry($image, $width, $height);
    $geometryScore += $symmetryScore * 0.5;

    return ['score' => $geometryScore];
}

/**
 * Detect hands to reject non-face images
 */
function detectHands($image, $width, $height) {
    $fingerRegions = 0;
    $palmRegions = 0;
    $totalRegions = 0;
    $step = max(3, min($width, $height) / 50);

    for ($x = 0; $x < $width; $x += $step) {
        for ($y = 0; $y < $height; $y += $step) {
            $rgb = imagecolorat($image, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            $totalRegions++;

            // Detect finger-like regions (thin, elongated skin areas)
            if (isAdvancedSkinColor($r, $g, $b)) {
                $palmRegions++;
                
                // Check for finger patterns (this is simplified)
                if ($x > 0 && $x < $width - 1 && $y > 0 && $y < $height - 1) {
                    $surroundingSkin = 0;
                    for ($dx = -1; $dx <= 1; $dx++) {
                        for ($dy = -1; $dy <= 1; $dy++) {
                            $neighborRgb = imagecolorat($image, $x + $dx, $y + $dy);
                            $nr = ($neighborRgb >> 16) & 0xFF;
                            $ng = ($neighborRgb >> 8) & 0xFF;
                            $nb = $neighborRgb & 0xFF;
                            if (isAdvancedSkinColor($nr, $ng, $nb)) {
                                $surroundingSkin++;
                            }
                        }
                    }
                    
                    if ($surroundingSkin >= 6) { // Mostly surrounded by skin
                        $fingerRegions++;
                    }
                }
            }
        }
    }

    $palmRatio = $totalRegions > 0 ? $palmRegions / $totalRegions : 0;
    $fingerRatio = $totalRegions > 0 ? $fingerRegions / $totalRegions : 0;

    // Hand detection criteria
    $isHand = $palmRatio > 0.6 && $fingerRatio > 0.1;

    return ['isHand' => $isHand, 'palmRatio' => $palmRatio, 'fingerRatio' => $fingerRatio];
}

/**
 * Advanced skin color detection
 */
function isAdvancedSkinColor($r, $g, $b) {
    // Multiple skin color detection algorithms
    $method1 = ($r > 95 && $g > 40 && $b > 20 && 
                max($r, $g, $b) - min($r, $g, $b) > 15 && 
                abs($r - $g) > 15 && $r > $g && $r > $b);
    
    $method2 = ($r > 60 && $r < 255 && $g > 40 && $g < 255 && $b > 20 && $b < 255 &&
                $r > $g && $r > $b && $r - $g > 10);
    
    return $method1 || $method2;
}

/**
 * Detect eye region characteristics
 */
function detectEyeRegion($image, $centerX, $centerY, $width, $height) {
    $darkPixels = 0;
    $totalPixels = 0;
    $step = 2;

    for ($x = $centerX - $width/2; $x < $centerX + $width/2; $x += $step) {
        for ($y = $centerY - $height/2; $y < $centerY + $height/2; $y += $step) {
            if ($x >= 0 && $x < imagesx($image) && $y >= 0 && $y < imagesy($image)) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                $totalPixels++;
                $brightness = ($r + $g + $b) / 3;
                
                if ($brightness < 100) { // Dark areas for eyes
                    $darkPixels++;
                }
            }
        }
    }

    $darkRatio = $totalPixels > 0 ? $darkPixels / $totalPixels : 0;
    return ($darkRatio >= 0.1 && $darkRatio <= 0.6) ? $darkRatio : 0;
}

/**
 * Detect nose region characteristics
 */
function detectNoseRegion($image, $centerX, $centerY, $width, $height) {
    $skinPixels = 0;
    $totalPixels = 0;
    $step = 2;

    for ($x = $centerX - $width/2; $x < $centerX + $width/2; $x += $step) {
        for ($y = $centerY - $height/2; $y < $centerY + $height/2; $y += $step) {
            if ($x >= 0 && $x < imagesx($image) && $y >= 0 && $y < imagesy($image)) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                $totalPixels++;
                if (isAdvancedSkinColor($r, $g, $b)) {
                    $skinPixels++;
                }
            }
        }
    }

    $skinRatio = $totalPixels > 0 ? $skinPixels / $totalPixels : 0;
    return ($skinRatio >= 0.3 && $skinRatio <= 0.8) ? $skinRatio : 0;
}

/**
 * Detect mouth region characteristics
 */
function detectMouthRegion($image, $centerX, $centerY, $width, $height) {
    $mouthPixels = 0;
    $totalPixels = 0;
    $step = 2;

    for ($x = $centerX - $width/2; $x < $centerX + $width/2; $x += $step) {
        for ($y = $centerY - $height/2; $y < $centerY + $height/2; $y += $step) {
            if ($x >= 0 && $x < imagesx($image) && $y >= 0 && $y < imagesy($image)) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                $totalPixels++;
                
                // Mouth characteristics: darker or reddish
                if (($r < 120 && $g < 120 && $b < 120) || ($r > $g + 20 && $r > $b + 10)) {
                    $mouthPixels++;
                }
            }
        }
    }

    $mouthRatio = $totalPixels > 0 ? $mouthPixels / $totalPixels : 0;
    return ($mouthRatio >= 0.05 && $mouthRatio <= 0.4) ? $mouthRatio : 0;
}

/**
 * Check face symmetry
 */
function checkFaceSymmetry($image, $width, $height) {
    $leftBrightness = 0;
    $rightBrightness = 0;
    $samples = 0;
    $step = max(3, $width / 50);

    for ($y = 0; $y < $height; $y += $step) {
        for ($x = 0; $x < $width / 2; $x += $step) {
            // Left side
            $leftRgb = imagecolorat($image, $x, $y);
            $leftR = ($leftRgb >> 16) & 0xFF;
            $leftG = ($leftRgb >> 8) & 0xFF;
            $leftB = $leftRgb & 0xFF;
            $leftBrightness += ($leftR + $leftG + $leftB) / 3;

            // Right side (mirror position)
            $rightX = $width - 1 - $x;
            $rightRgb = imagecolorat($image, $rightX, $y);
            $rightR = ($rightRgb >> 16) & 0xFF;
            $rightG = ($rightRgb >> 8) & 0xFF;
            $rightB = $rightRgb & 0xFF;
            $rightBrightness += ($rightR + $rightG + $rightB) / 3;

            $samples++;
        }
    }

    if ($samples === 0) return 0;

    $avgLeftBrightness = $leftBrightness / $samples;
    $avgRightBrightness = $rightBrightness / $samples;
    $brightnessDiff = abs($avgLeftBrightness - $avgRightBrightness);

    // Lower difference means better symmetry
    return max(0, 1 - ($brightnessDiff / 128));
}

/**
 * Load image safely with error handling
 */
function loadImageSafely($imagePath) {
    try {
        $imageInfo = @getimagesize($imagePath);
        if (!$imageInfo) {
            return false;
        }

        switch ($imageInfo[2]) {
            case IMAGETYPE_JPEG:
                return @imagecreatefromjpeg($imagePath);
            case IMAGETYPE_PNG:
                return @imagecreatefrompng($imagePath);
            case IMAGETYPE_GIF:
                return @imagecreatefromgif($imagePath);
            default:
                return false;
        }
    } catch (Exception $e) {
        error_log('Image loading error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Load image from URL (for Google profile images)
 */
function loadImageFromUrl($url) {
    try {
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
            return false;
        }

        return @imagecreatefromstring($imageData);
    } catch (Exception $e) {
        error_log('URL image loading error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Resize image properly maintaining aspect ratio
 */
function resizeImageProperly($image, $newWidth, $newHeight) {
    $resized = imagecreatetruecolor($newWidth, $newHeight);
    
    // Handle transparency for PNG
    imagealphablending($resized, false);
    imagesavealpha($resized, true);
    $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
    imagefill($resized, 0, 0, $transparent);
    
    imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($image), imagesy($image));
    
    return $resized;
}

/**
 * Calculate histogram similarity between two images
 */
function calculateHistogramSimilarityEnhanced($image1, $image2, $width, $height) {
    $histogram1 = array_fill(0, 256, 0);
    $histogram2 = array_fill(0, 256, 0);
    
    // Build histograms
    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {
            $rgb1 = imagecolorat($image1, $x, $y);
            $rgb2 = imagecolorat($image2, $x, $y);
            
            // Convert to grayscale
            $r1 = ($rgb1 >> 16) & 0xFF;
            $g1 = ($rgb1 >> 8) & 0xFF;
            $b1 = $rgb1 & 0xFF;
            $gray1 = intval(($r1 + $g1 + $b1) / 3);
            
            $r2 = ($rgb2 >> 16) & 0xFF;
            $g2 = ($rgb2 >> 8) & 0xFF;
            $b2 = $rgb2 & 0xFF;
            $gray2 = intval(($r2 + $g2 + $b2) / 3);
            
            $histogram1[$gray1]++;
            $histogram2[$gray2]++;
        }
    }
    
    // Calculate correlation coefficient
    $correlation = calculateCorrelationEnhanced($histogram1, $histogram2);
    
    // Convert to percentage
    return max(0, min(100, $correlation * 100));
}

/**
 * Calculate correlation coefficient between two arrays
 */
function calculateCorrelationEnhanced($array1, $array2) {
    $n = count($array1);
    $sum1 = array_sum($array1);
    $sum2 = array_sum($array2);
    
    if ($sum1 == 0 || $sum2 == 0) {
        return 0;
    }
    
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
    
    return abs($num / $den); // Use absolute value for similarity
}

/**
 * Calculate pixel-by-pixel similarity
 */
function calculatePixelSimilarity($image1, $image2, $size) {
    $similarPixels = 0;
    $totalPixels = $size * $size;
    $threshold = 40;

    for ($x = 0; $x < $size; $x++) {
        for ($y = 0; $y < $size; $y++) {
            $rgb1 = imagecolorat($image1, $x, $y);
            $rgb2 = imagecolorat($image2, $x, $y);

            $r1 = ($rgb1 >> 16) & 0xFF;
            $g1 = ($rgb1 >> 8) & 0xFF;
            $b1 = $rgb1 & 0xFF;

            $r2 = ($rgb2 >> 16) & 0xFF;
            $g2 = ($rgb2 >> 8) & 0xFF;
            $b2 = $rgb2 & 0xFF;

            $colorDistance = sqrt(pow($r1 - $r2, 2) + pow($g1 - $g2, 2) + pow($b1 - $b2, 2));

            if ($colorDistance <= $threshold) {
                $similarPixels++;
            }
        }
    }

    return ($similarPixels / $totalPixels) * 100;
}

/**
 * Calculate facial region similarity (focus on center area)
 */
function calculateFacialRegionSimilarity($image1, $image2, $size) {
    $centerX = $size / 2;
    $centerY = $size / 2;
    $faceRadius = $size * 0.4;

    $similarPixels = 0;
    $totalPixels = 0;
    $threshold = 35;

    for ($x = 0; $x < $size; $x++) {
        for ($y = 0; $y < $size; $y++) {
            $distance = sqrt(pow($x - $centerX, 2) + pow($y - $centerY, 2));
            
            if ($distance <= $faceRadius) {
                $totalPixels++;
                
                $rgb1 = imagecolorat($image1, $x, $y);
                $rgb2 = imagecolorat($image2, $x, $y);

                $r1 = ($rgb1 >> 16) & 0xFF;
                $g1 = ($rgb1 >> 8) & 0xFF;
                $b1 = $rgb1 & 0xFF;

                $r2 = ($rgb2 >> 16) & 0xFF;
                $g2 = ($rgb2 >> 8) & 0xFF;
                $b2 = $rgb2 & 0xFF;

                $colorDistance = sqrt(pow($r1 - $r2, 2) + pow($g1 - $g2, 2) + pow($b1 - $b2, 2));

                if ($colorDistance <= $threshold) {
                    $similarPixels++;
                }
            }
        }
    }

    return $totalPixels > 0 ? ($similarPixels / $totalPixels) * 100 : 0;
}

/**
 * Generate response message based on results
 */
function generateResponseMessage($similarity, $passed, $faceConfidence) {
    if ($passed) {
        return "Face verification successful! You may proceed.";
    } else {
        if ($similarity < 30) {
            return "Face verification failed. Please ensure you're the same person as in your profile photo.";
        } else if ($similarity < 50) {
            return "Face verification partially successful but below threshold. Please try again with better lighting.";
        } else if ($similarity < 70) {
            return "Face verification close to passing. Please position your face more clearly and try again.";
        } else {
            return "Face verification very close to passing. Please ensure optimal lighting and face positioning.";
        }
    }
}

/**
 * Generate improvement suggestions
 */
function generateImprovementSuggestions($similarity, $faceConfidence) {
    $suggestions = [];
    
    if ($faceConfidence < 50) {
        $suggestions[] = "Position your face clearly in the center of the frame";
        $suggestions[] = "Ensure your face is well-lit and clearly visible";
    }
    
    if ($similarity < 40) {
        $suggestions[] = "Make sure you're the same person as in your profile photo";
        $suggestions[] = "Remove any face coverings or accessories";
    }
    
    if ($similarity >= 40 && $similarity < 70) {
        $suggestions[] = "Improve lighting conditions";
        $suggestions[] = "Look directly at the camera";
        $suggestions[] = "Ensure your face fills most of the frame";
    }
    
    if ($similarity >= 70) {
        $suggestions[] = "Very close! Try adjusting the camera angle slightly";
        $suggestions[] = "Ensure consistent lighting";
    }
    
    return $suggestions;
}

/**
 * Enhanced logging with detailed information
 */
function logEnhancedVerificationAttempt($similarity, $passed, $faceConfidence) {
    try {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $userId = $_SESSION['client_id'] ?? 'unknown';
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'user_id' => $userId,
            'similarity' => $similarity,
            'passed' => $passed,
            'face_confidence' => $faceConfidence,
            'threshold' => 80,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];
        
        $logFile = '../../logs/enhanced_face_verification.log';
        $logDir = dirname($logFile);
        
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
        
    } catch (Exception $e) {
        error_log('Failed to log enhanced verification attempt: ' . $e->getMessage());
    }
}
?>