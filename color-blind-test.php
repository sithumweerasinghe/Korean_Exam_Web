<?php
session_start();
if (!(isset($_SESSION["client_id"]) || isset($_COOKIE["remember_me"])) && (!isset($_GET["sample"]) || $_GET["sample"] === "false")) {
    header("Location: ./?showModal=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="Topik Sir | Color Blind Test - Check your color vision with our comprehensive test." />
    <meta name="keywords" content="color blind test, vision test, ishihara test, color blindness" />
    <meta name="author" content="Virul Nirmala Wickramasinghe" />
    <title>Color Blind Test | Topik Sir</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <link rel="stylesheet" href="assets/plugins/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/animate.min.css" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="assets/css/exam-styles.css" />
    <style>
        .color-blind-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px 0;
        }
        
        .test-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 30px;
            margin: 20px auto;
            max-width: 800px;
        }
        
        .test-image-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin: 20px 0;
            border: 3px solid #e9ecef;
        }
        
        .test-image {
            max-width: 300px;
            max-height: 300px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin: 20px 0;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745, #20c997);
            transition: width 0.3s ease;
            border-radius: 4px;
        }
        
        .test-controls {
            text-align: center;
            margin: 30px 0;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,123,255,0.3);
        }
        
        .results-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            margin: 20px 0;
            text-align: center;
        }
        
        .score-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px auto;
            font-size: 24px;
            font-weight: bold;
            color: white;
        }
        
        .score-excellent { background: linear-gradient(45deg, #28a745, #20c997); }
        .score-good { background: linear-gradient(45deg, #17a2b8, #138496); }
        .score-fair { background: linear-gradient(45deg, #ffc107, #e0a800); }
        .score-poor { background: linear-gradient(45deg, #dc3545, #c82333); }
        
        /* New Results Design Styles */
        .score-circle-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        
        .score-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(45deg, #2ca347, #28a745);
            color: white;
            box-shadow: 0 10px 30px rgba(44, 163, 71, 0.3);
            position: relative;
        }
        
        .score-inner {
            text-align: center;
        }
        
        .score-number {
            font-size: 32px;
            font-weight: bold;
            display: block;
        }
        
        .score-label {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            border: 2px solid transparent;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .correct-card {
            border-color: #2ca347;
        }
        
        .correct-card .stat-icon {
            color: #2ca347;
            font-size: 40px;
            margin-bottom: 10px;
        }
        
        .wrong-card {
            border-color: #dc3545;
        }
        
        .wrong-card .stat-icon {
            color: #dc3545;
            font-size: 40px;
            margin-bottom: 10px;
        }
        
        .skipped-card {
            border-color: #ffc107;
        }
        
        .skipped-card .stat-icon {
            color: #ffc107;
            font-size: 40px;
            margin-bottom: 10px;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .stat-label {
            font-size: 16px;
            color: #6c757d;
            font-weight: 500;
        }
        
        .question-counter {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 20px;
            font-weight: bold;
        }
        
        .instruction-text {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        
        /* Answer Section Styles - Matching Exam Design */
        .answer-section {
            margin: 20px 0;
        }
        
        .answer-section .card {
            border-radius: 12px;
            overflow: hidden;
        }
        
        /* Number Display */
        .number-display-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            border: 3px solid #2ca347;
        }
        
        .number-display {
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            min-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Courier New', monospace;
        }
        
        #displayValue {
            font-size: 36px;
            font-weight: bold;
            color: #2ca347;
            min-width: 50px;
        }
        
        .input-feedback {
            font-size: 14px;
            color: #6c757d;
            margin-top: 10px;
        }
        
        .input-feedback.valid {
            color: #2ca347;
            font-weight: bold;
        }
        
        .input-feedback.invalid {
            color: #dc3545;
            font-weight: bold;
        }
        
        /* Calculator Panel */
        .calculator-panel {
            max-width: 400px;
            margin: 0 auto;
        }
        
        .calculator-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            border: 2px solid #2ca347;
        }
        
        .calc-btn {
            height: 70px;
            border-radius: 8px;
            border: 2px solid #dee2e6;
            background: white;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .calc-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .calc-btn:active {
            transform: translateY(0);
        }
        
        .number-btn {
            background: white;
            border-color: #2ca347;
            color: #2ca347;
            font-size: 24px;
        }
        
        .number-btn:hover {
            background: #2ca347;
            color: white;
        }
        
        .action-btn {
            background: #6c757d;
            border-color: #6c757d;
            color: white;
            font-size: 14px;
        }
        
        .action-btn:hover {
            background: #5a6268;
            border-color: #5a6268;
        }
        
        .submit-btn {
            background: #28a745;
            border-color: #28a745;
            color: white;
            grid-column: span 2;
            font-size: 16px;
        }
        
        .submit-btn:hover:not(:disabled) {
            background: #218838;
            border-color: #218838;
        }
        
        .submit-btn:disabled {
            background: #6c757d;
            border-color: #6c757d;
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .zero-btn {
            grid-column: span 2;
        }
        
        .calc-btn small {
            font-size: 10px;
            margin-top: 2px;
        }
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .calculator-grid {
                gap: 8px;
                padding: 15px;
            }
            
            .calc-btn {
                height: 60px;
                font-size: 16px;
            }
            
            .number-btn {
                font-size: 20px;
            }
            
            #displayValue {
                font-size: 28px;
            }
            
            .number-display {
                min-height: 50px;
                padding: 10px;
            }
        }
    </style>
</head>

<body class="element-wrapper">
    <div id="preloader">
        <div id="ed-preloader" class="ed-preloader">
            <div class="animation-preloader">
                <div class="spinner"></div>
            </div>
        </div>
    </div>

    <?php
    require_once "api/config/dbconnection.php";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include("api/client/services/userService.php");
    $userService = new UserService();
    $userArray = $userService->validateUserLoggedIn();

    $userId = $userArray["userId"];
    $firstName = $userArray["fname"];
    $lastName = $userArray["lname"];
    $fullName = $userArray["fullname"];
    $profileImage = $userArray["profile"];
    ?>

    <div class="color-blind-container">
        <div class="container">
            <!-- Header -->
            <div class="test-card">
                <div class="text-center">
                    <h1 class="mb-3">
                        <i class="fa fa-eye text-primary"></i>
                        Color Blind Test
                    </h1>
                    <p class="lead text-muted">Test your color vision by identifying numbers in the images below</p>
                    <div class="instruction-text">
                        <strong>Instructions:</strong> You will see 10 random images containing hidden numbers. 
                        Look carefully at each image and select the number you can see. Click "Submit Answer" for each image to proceed.
                    </div>
                </div>
            </div>

            <!-- Test Area -->
            <div class="test-card" id="testArea">
                <!-- Progress Bar -->
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill" style="width: 0%"></div>
                </div>

                <div class="question-counter" id="questionCounter">
                    Question 1 of 10
                </div>

                <!-- Image Display -->
                <div class="test-image-container">
                    <img id="testImage" class="test-image" src="" alt="Color blind test image" style="display: none;">
                    <div id="loadingText">
                        <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                        <p class="mt-3">Loading test...</p>
                    </div>
                </div>

                <!-- Answer Input Section - Calculator Style -->
                <div class="answer-section" id="answerSection" style="display: none;">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header text-white text-center py-3" style="background-color: #2ca347; font-weight: 600; font-size: 19px;">
                            <i class="fa fa-keyboard-o me-2"></i>
                            What number do you see in the image?
                        </div>
                        <div class="card-body p-4">
                            <!-- Number Display -->
                            <div class="number-display-container text-center mb-4">
                                <div class="number-display" id="numberDisplay">
                                    <span id="displayValue">0</span>
                                </div>
                                <div class="input-feedback mt-2" id="inputFeedback">Enter the number you see (0-100)</div>
                            </div>

                            <!-- Calculator-style Number Panel -->
                            <div class="calculator-panel">
                                <div class="calculator-grid">
                                    <!-- Row 1: 7,8,9,Clear -->
                                    <button class="calc-btn number-btn" data-number="7">7</button>
                                    <button class="calc-btn number-btn" data-number="8">8</button>
                                    <button class="calc-btn number-btn" data-number="9">9</button>
                                    <button class="calc-btn action-btn" id="clearBtn">
                                        <i class="fa fa-eraser"></i><br><small>Clear</small>
                                    </button>
                                    
                                    <!-- Row 2: 4,5,6,Backspace -->
                                    <button class="calc-btn number-btn" data-number="4">4</button>
                                    <button class="calc-btn number-btn" data-number="5">5</button>
                                    <button class="calc-btn number-btn" data-number="6">6</button>
                                    <button class="calc-btn action-btn" id="backspaceBtn">
                                        <i class="fa fa-backspace"></i><br><small>Back</small>
                                    </button>
                                    
                                    <!-- Row 3: 1,2,3,Skip -->
                                    <button class="calc-btn number-btn" data-number="1">1</button>
                                    <button class="calc-btn number-btn" data-number="2">2</button>
                                    <button class="calc-btn number-btn" data-number="3">3</button>
                                    <button class="calc-btn action-btn" id="skipBtn">
                                        <i class="fa fa-forward"></i><br><small>Skip</small>
                                    </button>
                                    
                                    <!-- Row 4: 0 (span 2), Submit (span 2) -->
                                    <button class="calc-btn number-btn zero-btn" data-number="0">0</button>
                                    <button class="calc-btn submit-btn" id="submitBtn" disabled>
                                        <i class="fa fa-check"></i><br><small>Submit</small>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Controls -->
                <div class="test-controls">
                    <button class="btn btn-success btn-lg" id="startBtn">
                        <i class="fa fa-play me-2"></i>Start Test
                    </button>
                </div>
            </div>

            <!-- Results Area - Exam Style Green Design -->
            <div class="test-card" id="resultsArea" style="display: none;">
                <div class="card border-0 shadow-lg">
                    <div class="card-header text-white text-center py-4" style="background-color: #2ca347; font-weight: 600; font-size: 20px;">
                        <i class="fa fa-trophy me-2"></i>
                        Color Blind Test Results
                    </div>
                    <div class="card-body p-5">
                        <div class="results-container text-center">
                            <!-- Score Display -->
                            <div class="score-display mb-4">
                                <div class="score-circle-container">
                                    <div class="score-circle" id="scoreCircle">
                                        <div class="score-inner">
                                            <span id="scoreText" class="score-number">0/10</span>
                                            <div class="score-label">Score</div>
                                        </div>
                                    </div>
                                </div>
                                <h3 id="resultMessage" class="mt-3 mb-2" style="color: #2ca347;">Your Result</h3>
                                <p id="resultDescription" class="text-muted">Result description will appear here</p>
                            </div>
                            
                            <!-- Statistics Cards -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <div class="stat-card correct-card">
                                        <div class="stat-icon">
                                            <i class="fa fa-check-circle"></i>
                                        </div>
                                        <div class="stat-number" id="correctCount">0</div>
                                        <div class="stat-label">Correct</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-card wrong-card">
                                        <div class="stat-icon">
                                            <i class="fa fa-times-circle"></i>
                                        </div>
                                        <div class="stat-number" id="wrongCount">0</div>
                                        <div class="stat-label">Wrong</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-card skipped-card">
                                        <div class="stat-icon">
                                            <i class="fa fa-forward"></i>
                                        </div>
                                        <div class="stat-number" id="skippedCount">0</div>
                                        <div class="stat-label">Skipped</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                <button class="btn btn-lg me-2 mb-2" onclick="restartTest()" style="background-color: #2ca347; border-color: #2ca347; color: white;">
                                    <i class="fa fa-refresh me-1"></i> Take Test Again
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary btn-lg me-2 mb-2">
                                    <i class="fa fa-home me-1"></i> Back to Home
                                </a>
                                <button class="btn btn-outline-primary btn-lg mb-2" onclick="toggleDetailedResults()">
                                    <span id="toggleText">Show Details</span>
                                    <i class="fa fa-chevron-down ms-1"></i>
                                </button>
                            </div>
                            
                            <!-- Detailed Results -->
                            <div id="detailedResults" class="mt-4" style="display: none;">
                                <div class="card">
                                    <div class="card-header" style="background-color: #2ca347; color: white;">
                                        <h5 class="mb-0">
                                            <i class="fa fa-list me-2"></i>
                                            Detailed Results
                                        </h5>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="resultsList"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="assets/plugins/js/jquery-3.6.0.min.js"></script>
    <script src="assets/plugins/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/color-blind-test.js"></script>
</body>
</html>