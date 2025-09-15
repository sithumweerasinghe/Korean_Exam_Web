<?php
/**
 * Simple Face Verification API for Testing
 * Returns simulated similarity scores based on basic image analysis
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors in production

/**
 * Simple skin color detection function
 */
function isSkinColor($r, $g, $b) {
    // Simple skin color detection algorithm
    return ($r > 95 && $g > 40 && $b > 20 && 
            max($r, $g, $b) - min($r, $g, $b) > 15 && 
            abs($r - $g) > 15 && $r > $g && $r > $b);
}

try {
    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST method allowed');
    }

    // Check if this is a live verification request
    $isLiveMode = isset($_POST['live_mode']) && $_POST['live_mode'] === 'true';
    
    // Validate required parameters
    if (!isset($_FILES['captured_image'])) {
        throw new Exception('Missing required parameter: captured_image');
    }

    $capturedImage = $_FILES['captured_image'];
    $profileImageUrl = $_POST['profile_image_url'] ?? '';

    // Validate uploaded file
    if ($capturedImage['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload error: ' + $capturedImage['error']);
    }

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $capturedImage['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes)) {
        throw new Exception('Invalid file type. Only JPEG and PNG allowed.');
    }

    // Validate file size (max 5MB)
    if ($capturedImage['size'] > 5 * 1024 * 1024) {
        throw new Exception('File too large. Maximum size is 5MB.');
    }

    // Load the captured image
    $capturedImagePath = $capturedImage['tmp_name'];
    $capturedImageData = file_get_contents($capturedImagePath);
    
    if (!$capturedImageData) {
        throw new Exception('Failed to read captured image');
    }

    // Simple face verification simulation
    $similarity = performSimpleFaceVerification($capturedImageData, $profileImageUrl, $isLiveMode);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'similarity' => round($similarity, 2),
        'message' => 'Face verification completed',
        'timestamp' => date('Y-m-d H:i:s'),
        'live_mode' => $isLiveMode
    ]);

} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'similarity' => 0,
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}

/**
 * Simple face verification simulation
 * Returns a similarity score based on basic image analysis
 */
function performSimpleFaceVerification($capturedImageData, $profileImageUrl, $isLiveMode) {
    try {
        // Create image resource from captured data
        $capturedImage = imagecreatefromstring($capturedImageData);
        if (!$capturedImage) {
            throw new Exception('Invalid captured image data');
        }

        // Get image dimensions
        $width = imagesx($capturedImage);
        $height = imagesy($capturedImage);
        
        if ($width < 50 || $height < 50) {
            throw new Exception('Image too small for face detection');
        }

        // Download and process profile image for comparison
        $profileSimilarity = 0;
        if (!empty($profileImageUrl)) {
            $profileSimilarity = compareWithProfileImage($capturedImage, $profileImageUrl, $width, $height);
        }

        // Enhanced face detection and quality analysis
        $faceQualityScore = analyzeImageQuality($capturedImage, $width, $height);
        $faceFeatureScore = detectAdvancedFaceFeatures($capturedImage, $width, $height);
        
        // Initialize similarity score
        $similarity = 0;
        
        // Require minimum face detection before proceeding
        if ($faceFeatureScore < 0.2) {
            // Very low face detection - probably not a face
            $similarity = mt_rand(5, 25);
        } else if ($profileSimilarity > 0) {
            // If we have profile comparison, weight it heavily (70%)
            $similarity += $profileSimilarity * 0.7;
            // Add face quality (20%) and feature detection (10%)
            $similarity += $faceQualityScore * 20;
            $similarity += $faceFeatureScore * 10;
        } else {
            // If no profile image, rely more on face detection but be stricter
            $baseSimilarity = 15; // Lower base score
            $qualityBonus = $faceQualityScore * 35;
            $featureBonus = $faceFeatureScore * 40;
            
            $similarity = $baseSimilarity + $qualityBonus + $featureBonus;
            
            // Add controlled randomness
            $randomVariation = mt_rand(-8, 12);
            $similarity += $randomVariation;
        }
        
        // Apply stricter bounds for realistic scores
        $similarity = max(5, min(88, $similarity));
        
        // Clean up
        imagedestroy($capturedImage);
        
        return $similarity;
        
    } catch (Exception $e) {
        error_log('Face verification error: ' . $e->getMessage());
        // Return a random score between 30-70 as fallback
        return mt_rand(30, 70);
    }
}

/**
 * Simple face feature detection
 * Returns a score between 0 and 1 based on detected face-like features
 */
function detectFaceFeatures($image, $width, $height) {
    try {
        $faceScore = 0;
        $totalPixels = $width * $height;
        $skinPixels = 0;
        $darkPixels = 0;
        
        // Sample pixels to analyze (every 4th pixel for performance)
        for ($x = 0; $x < $width; $x += 4) {
            for ($y = 0; $y < $height; $y += 4) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                
                // Simple skin color detection
                if (isSkinColor($r, $g, $b)) {
                    $skinPixels++;
                }
                
                // Detect dark areas (potential hair, eyebrows, eyes)
                if ($r < 100 && $g < 100 && $b < 100) {
                    $darkPixels++;
                }
            }
        }
        
        $sampledPixels = ($width / 4) * ($height / 4);
        $skinRatio = $skinPixels / $sampledPixels;
        $darkRatio = $darkPixels / $sampledPixels;
        
        // Calculate face score based on skin and dark area ratios
        if ($skinRatio > 0.1 && $skinRatio < 0.7) { // Reasonable amount of skin
            $faceScore += 0.5;
        }
        
        if ($darkRatio > 0.05 && $darkRatio < 0.3) { // Some dark areas for features
            $faceScore += 0.3;
        }
        
        // Bonus for good contrast
        if ($skinRatio > 0.2 && $darkRatio > 0.1) {
            $faceScore += 0.2;
        }
        
        return min(1.0, $faceScore);
        
    } catch (Exception $e) {
        error_log('Face feature detection error: ' . $e->getMessage());
        return 0.5; // Return neutral score on error
    }
}

/**
 * Compare captured image with profile image for similarity
 */
function compareWithProfileImage($capturedImage, $profileImageUrl, $capturedWidth, $capturedHeight) {
    try {
        // Download profile image
        $profileImageData = @file_get_contents($profileImageUrl);
        if (!$profileImageData) {
            return 0;
        }

        $profileImage = imagecreatefromstring($profileImageData);
        if (!$profileImage) {
            return 0;
        }

        $profileWidth = imagesx($profileImage);
        $profileHeight = imagesy($profileImage);

        // Resize both images to same size for comparison (100x100)
        $size = 100;
        $resizedCaptured = imagecreatetruecolor($size, $size);
        $resizedProfile = imagecreatetruecolor($size, $size);

        imagecopyresampled($resizedCaptured, $capturedImage, 0, 0, 0, 0, $size, $size, $capturedWidth, $capturedHeight);
        imagecopyresampled($resizedProfile, $profileImage, 0, 0, 0, 0, $size, $size, $profileWidth, $profileHeight);

        // Compare images pixel by pixel
        $totalPixels = $size * $size;
        $similarPixels = 0;
        $colorThreshold = 50; // Tolerance for color differences

        for ($x = 0; $x < $size; $x++) {
            for ($y = 0; $y < $size; $y++) {
                $capturedRgb = imagecolorat($resizedCaptured, $x, $y);
                $profileRgb = imagecolorat($resizedProfile, $x, $y);

                $capturedR = ($capturedRgb >> 16) & 0xFF;
                $capturedG = ($capturedRgb >> 8) & 0xFF;
                $capturedB = $capturedRgb & 0xFF;

                $profileR = ($profileRgb >> 16) & 0xFF;
                $profileG = ($profileRgb >> 8) & 0xFF;
                $profileB = $profileRgb & 0xFF;

                // Calculate color distance
                $colorDistance = sqrt(
                    pow($capturedR - $profileR, 2) +
                    pow($capturedG - $profileG, 2) +
                    pow($capturedB - $profileB, 2)
                );

                if ($colorDistance <= $colorThreshold) {
                    $similarPixels++;
                }
            }
        }

        $pixelSimilarity = ($similarPixels / $totalPixels) * 100;

        // Apply face region weighting (center region is more important)
        $faceRegionSimilarity = calculateFaceRegionSimilarity($resizedCaptured, $resizedProfile, $size);
        
        // Combine pixel similarity (60%) with face region similarity (40%)
        $finalSimilarity = ($pixelSimilarity * 0.6) + ($faceRegionSimilarity * 0.4);

        // Clean up
        imagedestroy($profileImage);
        imagedestroy($resizedCaptured);
        imagedestroy($resizedProfile);

        return $finalSimilarity;

    } catch (Exception $e) {
        error_log('Profile comparison error: ' . $e->getMessage());
        return 0;
    }
}

/**
 * Calculate similarity in face region (center area)
 */
function calculateFaceRegionSimilarity($capturedImage, $profileImage, $size) {
    $centerX = $size / 2;
    $centerY = $size / 2;
    $faceRadius = $size * 0.35; // Face region radius

    $facePixels = 0;
    $similarFacePixels = 0;
    $colorThreshold = 40;

    for ($x = 0; $x < $size; $x++) {
        for ($y = 0; $y < $size; $y++) {
            $distance = sqrt(pow($x - $centerX, 2) + pow($y - $centerY, 2));
            
            if ($distance <= $faceRadius) {
                $facePixels++;
                
                $capturedRgb = imagecolorat($capturedImage, $x, $y);
                $profileRgb = imagecolorat($profileImage, $x, $y);

                $capturedR = ($capturedRgb >> 16) & 0xFF;
                $capturedG = ($capturedRgb >> 8) & 0xFF;
                $capturedB = $capturedRgb & 0xFF;

                $profileR = ($profileRgb >> 16) & 0xFF;
                $profileG = ($profileRgb >> 8) & 0xFF;
                $profileB = $profileRgb & 0xFF;

                $colorDistance = sqrt(
                    pow($capturedR - $profileR, 2) +
                    pow($capturedG - $profileG, 2) +
                    pow($capturedB - $profileB, 2)
                );

                if ($colorDistance <= $colorThreshold) {
                    $similarFacePixels++;
                }
            }
        }
    }

    return $facePixels > 0 ? ($similarFacePixels / $facePixels) * 100 : 0;
}

/**
 * Analyze image quality for face verification
 */
function analyzeImageQuality($image, $width, $height) {
    try {
        $brightness = 0;
        $contrast = 0;
        $pixelCount = 0;

        // Sample pixels to calculate brightness and contrast
        $stepSize = max(1, min($width, $height) / 50); // Sample about 50x50 grid

        for ($x = 0; $x < $width; $x += $stepSize) {
            for ($y = 0; $y < $height; $y += $stepSize) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                
                $pixelBrightness = ($r + $g + $b) / 3;
                $brightness += $pixelBrightness;
                $pixelCount++;
            }
        }

        $avgBrightness = $pixelCount > 0 ? $brightness / $pixelCount : 0;

        // Calculate quality score
        $qualityScore = 0;

        // Good brightness range (not too dark or too bright)
        if ($avgBrightness >= 50 && $avgBrightness <= 200) {
            $qualityScore += 0.6;
        } else if ($avgBrightness >= 30 && $avgBrightness <= 230) {
            $qualityScore += 0.3;
        }

        // Check for sufficient image size
        if ($width >= 200 && $height >= 200) {
            $qualityScore += 0.4;
        } else if ($width >= 100 && $height >= 100) {
            $qualityScore += 0.2;
        }

        return min(1.0, $qualityScore);

    } catch (Exception $e) {
        error_log('Image quality analysis error: ' . $e->getMessage());
        return 0.5;
    }
}

/**
 * Advanced face feature detection
 */
function detectAdvancedFaceFeatures($image, $width, $height) {
    try {
        $faceScore = 0;
        $skinPixels = 0;
        $eyeRegionPixels = 0;
        $mouthRegionPixels = 0;
        $totalSamples = 0;

        // Define face regions (approximate)
        $centerX = $width / 2;
        $centerY = $height / 2;
        $faceWidth = $width * 0.6;
        $faceHeight = $height * 0.8;

        // Eye region (upper third of face)
        $eyeY = $centerY - ($faceHeight * 0.2);
        $eyeRegionHeight = $faceHeight * 0.3;

        // Mouth region (lower third of face)
        $mouthY = $centerY + ($faceHeight * 0.1);
        $mouthRegionHeight = $faceHeight * 0.2;

        $stepSize = max(2, min($width, $height) / 100);

        for ($x = 0; $x < $width; $x += $stepSize) {
            for ($y = 0; $y < $height; $y += $stepSize) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = ($rgb) & 0xFF;
                
                $totalSamples++;

                // Check if in face region
                if (abs($x - $centerX) <= $faceWidth/2 && abs($y - $centerY) <= $faceHeight/2) {
                    
                    // Skin detection
                    if (isSkinColor($r, $g, $b)) {
                        $skinPixels++;
                    }

                    // Eye region detection (look for darker pixels)
                    if ($y >= $eyeY && $y <= $eyeY + $eyeRegionHeight) {
                        if ($r < 100 && $g < 100 && $b < 100) {
                            $eyeRegionPixels++;
                        }
                    }

                    // Mouth region detection (look for different color characteristics)
                    if ($y >= $mouthY && $y <= $mouthY + $mouthRegionHeight) {
                        // Mouth can be darker (closed) or pinkish (open)
                        if (($r < 120 && $g < 120 && $b < 120) || 
                            ($r > $g && $r > $b && $r > 100)) {
                            $mouthRegionPixels++;
                        }
                    }
                }
            }
        }

        // Calculate scores
        $skinRatio = $totalSamples > 0 ? $skinPixels / $totalSamples : 0;
        $eyeRatio = $totalSamples > 0 ? $eyeRegionPixels / $totalSamples : 0;
        $mouthRatio = $totalSamples > 0 ? $mouthRegionPixels / $totalSamples : 0;

        // Score based on expected face characteristics
        if ($skinRatio > 0.15 && $skinRatio < 0.8) { // Reasonable amount of skin
            $faceScore += 0.5;
        }

        if ($eyeRatio > 0.02 && $eyeRatio < 0.15) { // Some dark areas for eyes
            $faceScore += 0.3;
        }

        if ($mouthRatio > 0.01 && $mouthRatio < 0.1) { // Mouth region
            $faceScore += 0.2;
        }

        return min(1.0, $faceScore);

    } catch (Exception $e) {
        error_log('Advanced face detection error: ' . $e->getMessage());
        return 0.3;
    }
}
?>