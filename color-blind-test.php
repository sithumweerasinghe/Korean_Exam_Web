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
            background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #198754 100%);
            padding: 10px 0;
        }
        
        .test-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.15);
            padding: 20px;
            margin: 10px auto;
            max-width: 95%;
            border: 2px solid rgba(40, 167, 69, 0.1);
        }
        
        @media (min-width: 768px) {
            .test-card {
                max-width: 700px;
                padding: 25px;
                margin: 15px auto;
            }
        }
        
        .test-image-container {
            background: linear-gradient(135deg, #e8f5e8 0%, #f0f9f0 100%);
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin: 15px 0;
            border: 2px solid #28a745;
        }
        
        @media (min-width: 768px) {
            .test-image-container {
                padding: 25px;
                margin: 20px 0;
            }
        }
        
        .test-image {
            max-width: 250px;
            max-height: 250px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);
        }
        
        @media (min-width: 768px) {
            .test-image {
                max-width: 300px;
                max-height: 300px;
            }
        }
        
        .progress-bar {
            width: 100%;
            height: 6px;
            background: #e8f5e8;
            border-radius: 3px;
            overflow: hidden;
            margin: 15px 0;
            border: 1px solid #28a745;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745, #20c997);
            transition: width 0.3s ease;
            border-radius: 3px;
        }
        
        .test-controls {
            text-align: center;
            margin: 20px 0;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
            color: white;
        }
        
        @media (min-width: 768px) {
            .btn-primary {
                padding: 12px 30px;
            }
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
            background: linear-gradient(45deg, #198754, #20c997);
        }
        
        .results-container {
            background: linear-gradient(135deg, #e8f5e8 0%, #f0f9f0 100%);
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
            text-align: center;
            border: 2px solid #28a745;
        }
        
        @media (min-width: 768px) {
            .results-container {
                padding: 25px;
                margin: 20px 0;
            }
        }
        
        .score-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 15px auto;
            font-size: 18px;
            font-weight: bold;
            color: white;
        }
        
        @media (min-width: 768px) {
            .score-circle {
                width: 120px;
                height: 120px;
                margin: 20px auto;
                font-size: 20px;
            }
        }
        
        .score-excellent { background: linear-gradient(45deg, #28a745, #20c997); }
        .score-good { background: linear-gradient(45deg, #20c997, #17a2b8); }
        .score-fair { background: linear-gradient(45deg, #ffc107, #fd7e14); }
        .score-poor { background: linear-gradient(45deg, #dc3545, #c82333); }
        
        /* Compact Results Design Styles */
        .score-circle-container {
            display: flex;
            justify-content: center;
            margin: 15px 0;
        }
        
        .score-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
            position: relative;
        }
        
        @media (min-width: 768px) {
            .score-circle {
                width: 140px;
                height: 140px;
            }
        }
        
        .score-inner {
            text-align: center;
        }
        
        .score-number {
            font-size: 24px;
            font-weight: bold;
            display: block;
        }
        
        @media (min-width: 768px) {
            .score-number {
                font-size: 28px;
            }
        }
        
        .score-label {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 3px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 3px 12px rgba(40, 167, 69, 0.1);
            transition: transform 0.3s ease;
            border: 2px solid transparent;
            margin-bottom: 10px;
        }
        
        @media (min-width: 768px) {
            .stat-card {
                padding: 18px;
                margin-bottom: 0;
            }
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.2);
        }
        
        .correct-card {
            border-color: #28a745;
        }
        
        .correct-card .stat-icon {
            color: #28a745;
            font-size: 30px;
            margin-bottom: 8px;
        }
        
        @media (min-width: 768px) {
            .correct-card .stat-icon {
                font-size: 35px;
                margin-bottom: 10px;
            }
        }
        
        .wrong-card {
            border-color: #dc3545;
        }
        
        .wrong-card .stat-icon {
            color: #dc3545;
            font-size: 30px;
            margin-bottom: 8px;
        }
        
        @media (min-width: 768px) {
            .wrong-card .stat-icon {
                font-size: 35px;
                margin-bottom: 10px;
            }
        }
        
        .skipped-card {
            border-color: #ffc107;
        }
        
        .skipped-card .stat-icon {
            color: #ffc107;
            font-size: 30px;
            margin-bottom: 8px;
        }
        
        @media (min-width: 768px) {
            .skipped-card .stat-icon {
                font-size: 35px;
                margin-bottom: 10px;
            }
        }
        
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            margin: 8px 0;
        }
        
        @media (min-width: 768px) {
            .stat-number {
                font-size: 32px;
                margin: 10px 0;
            }
        }
        
        .stat-label {
            font-size: 14px;
            color: #6c757d;
            font-weight: 500;
        }
        
        @media (min-width: 768px) {
            .stat-label {
                font-size: 15px;
            }
        }
        
        .question-counter {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 8px 16px;
            border-radius: 15px;
            display: inline-block;
            margin-bottom: 15px;
            font-weight: bold;
            font-size: 14px;
            box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);
        }
        
        @media (min-width: 768px) {
            .question-counter {
                padding: 10px 20px;
                margin-bottom: 20px;
                font-size: 15px;
            }
        }
        
        .instruction-text {
            background: linear-gradient(135deg, #e8f5e8 0%, #f0f9f0 100%);
            border-left: 4px solid #28a745;
            padding: 12px;
            margin: 15px 0;
            border-radius: 5px;
            font-size: 14px;
        }
        
        @media (min-width: 768px) {
            .instruction-text {
                padding: 15px;
                margin: 20px 0;
                font-size: 15px;
            }
        }
        
        /* Compact Answer Section Styles */
        .answer-section {
            margin: 15px 0;
        }
        
        .answer-section .card {
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid #28a745;
        }
        
        /* Compact Number Display */
        .number-display-container {
            background: linear-gradient(135deg, #e8f5e8 0%, #f0f9f0 100%);
            border-radius: 8px;
            padding: 15px;
            border: 2px solid #28a745;
        }
        
        @media (min-width: 768px) {
            .number-display-container {
                padding: 18px;
            }
        }
        
        .number-display {
            background: white;
            border: 2px solid #28a745;
            border-radius: 6px;
            padding: 12px;
            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Courier New', monospace;
        }
        
        @media (min-width: 768px) {
            .number-display {
                padding: 15px;
                min-height: 60px;
            }
        }
        
        #displayValue {
            font-size: 28px;
            font-weight: bold;
            color: #28a745;
            min-width: 40px;
        }
        
        @media (min-width: 768px) {
            #displayValue {
                font-size: 32px;
                min-width: 50px;
            }
        }
        
        .input-feedback {
            font-size: 13px;
            color: #6c757d;
            margin-top: 8px;
        }
        
        @media (min-width: 768px) {
            .input-feedback {
                font-size: 14px;
                margin-top: 10px;
            }
        }
        
        .input-feedback.valid {
            color: #28a745;
            font-weight: bold;
        }
        
        .input-feedback.invalid {
            color: #dc3545;
            font-weight: bold;
        }
        
        /* Compact Calculator Panel */
        .calculator-panel {
            max-width: 350px;
            margin: 0 auto;
        }
        
        @media (min-width: 768px) {
            .calculator-panel {
                max-width: 400px;
            }
        }
        
        .calculator-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            padding: 15px;
            background: linear-gradient(135deg, #e8f5e8 0%, #f0f9f0 100%);
            border-radius: 10px;
            border: 2px solid #28a745;
        }
        
        @media (min-width: 768px) {
            .calculator-grid {
                gap: 10px;
                padding: 18px;
            }
        }
        
        .calc-btn {
            height: 55px;
            border-radius: 6px;
            border: 2px solid #28a745;
            background: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        @media (min-width: 768px) {
            .calc-btn {
                height: 65px;
                font-size: 17px;
            }
        }
        
        .calc-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }
        
        .calc-btn:active {
            transform: translateY(0);
        }
        
        .number-btn {
            background: white;
            border-color: #28a745;
            color: #28a745;
            font-size: 20px;
        }
        
        @media (min-width: 768px) {
            .number-btn {
                font-size: 22px;
            }
        }
        
        .number-btn:hover {
            background: #28a745;
            color: white;
        }
        
        .action-btn {
            background: linear-gradient(45deg, #6c757d, #5a6268);
            border-color: #6c757d;
            color: white;
            font-size: 12px;
        }
        
        @media (min-width: 768px) {
            .action-btn {
                font-size: 13px;
            }
        }
        
        .action-btn:hover {
            background: linear-gradient(45deg, #5a6268, #495057);
            border-color: #5a6268;
        }
        
        .submit-btn {
            background: linear-gradient(45deg, #28a745, #20c997);
            border-color: #28a745;
            color: white;
            grid-column: span 2;
            font-size: 14px;
        }
        
        @media (min-width: 768px) {
            .submit-btn {
                font-size: 15px;
            }
        }
        
        .submit-btn:hover:not(:disabled) {
            background: linear-gradient(45deg, #218838, #1e7e34);
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
            font-size: 9px;
            margin-top: 2px;
        }
        
        @media (min-width: 768px) {
            .calc-btn small {
                font-size: 10px;
            }
        }
        
        /* Mobile responsiveness */
        @media (max-width: 767px) {
            .color-blind-container {
                padding: 5px 0;
            }
            
            .test-card {
                margin: 8px auto;
                padding: 15px;
                border-radius: 10px;
            }
            
            .calculator-grid {
                gap: 6px;
                padding: 12px;
            }
            
            .calc-btn {
                height: 50px;
                font-size: 14px;
            }
            
            .number-btn {
                font-size: 18px;
            }
            
            #displayValue {
                font-size: 24px;
            }
            
            .number-display {
                min-height: 45px;
                padding: 10px;
            }
            
            .test-image {
                max-width: 200px;
                max-height: 200px;
            }
            
            .test-image-container {
                padding: 12px;
                margin: 12px 0;
            }
            
            .score-circle {
                width: 90px;
                height: 90px;
            }
            
            .score-number {
                font-size: 20px;
            }
            
            .stat-card {
                padding: 12px;
                margin-bottom: 8px;
            }
            
            .stat-number {
                font-size: 24px;
                margin: 6px 0;
            }
            
            .stat-icon {
                font-size: 25px !important;
                margin-bottom: 6px !important;
            }
            
            .btn-primary {
                padding: 8px 20px;
                font-size: 14px;
            }
            
            .instruction-text {
                padding: 10px;
                margin: 12px 0;
                font-size: 13px;
            }
            
            .question-counter {
                padding: 6px 12px;
                font-size: 13px;
                margin-bottom: 12px;
            }
            
            .progress-bar {
                height: 5px;
                margin: 12px 0;
            }
            
            .results-container {
                padding: 15px;
                margin: 12px 0;
            }
            
            .test-controls {
                margin: 15px 0;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 576px) {
            .test-card {
                margin: 5px;
                padding: 12px;
                max-width: 98%;
            }
            
            .calculator-panel {
                max-width: 300px;
            }
            
            .calc-btn {
                height: 45px;
                font-size: 13px;
            }
            
            .number-btn {
                font-size: 16px;
            }
            
            .calc-btn small {
                font-size: 8px;
            }
            
            .test-image {
                max-width: 180px;
                max-height: 180px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            .score-circle {
                width: 80px;
                height: 80px;
            }
            
            .score-number {
                font-size: 18px;
            }
            
            .score-label {
                font-size: 11px;
            }
        }
        
        /* Compact Exam Results Preview Styles */
        .stat-card-mini {
            transition: transform 0.2s ease;
        }
        
        .stat-card-mini:hover {
            transform: translateY(-1px);
        }
        
        .stat-card-mini .fw-bold {
            font-size: 16px;
            margin: 2px 0;
        }
        
        .stat-card-mini small {
            font-size: 11px;
            display: block;
        }
        
        @media (min-width: 768px) {
            .stat-card-mini .fw-bold {
                font-size: 18px;
                margin: 4px 0;
            }
            
            .stat-card-mini small {
                font-size: 12px;
            }
        }
        
        /* Exam Results Section Responsive */
        #examResultsFullPreview .score-circle {
            margin: 0 auto 10px;
        }
        
        @media (max-width: 767px) {
            #examResultsFullPreview .row.g-2 {
                margin-bottom: 15px;
            }
            
            .stat-card-mini {
                padding: 8px !important;
                margin-bottom: 8px;
            }
            
            .stat-card-mini .fw-bold {
                font-size: 14px;
            }
            
            .stat-card-mini small {
                font-size: 10px;
            }
            
            #examSectionPerformance .col-6 {
                margin-bottom: 8px;
            }
        }
        
        /* Quick score badge */
        #quickScore {
            font-size: 12px;
            padding: 4px 8px;
        }
        
        @media (min-width: 768px) {
            #quickScore {
                font-size: 13px;
                padding: 5px 10px;
            }
        }
        
        /* Compact Header Styles */
        .breadcrumb {
            --bs-breadcrumb-divider: '>';
            background: none;
            padding: 0;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            color: #28a745;
        }
        
        .btn-group-sm .btn {
            padding: 4px 8px;
            font-size: 12px;
        }
        
        @media (min-width: 768px) {
            .btn-group-sm .btn {
                padding: 6px 12px;
                font-size: 13px;
            }
        }
        
        /* Compact breadcrumb for mobile */
        @media (max-width: 576px) {
            .breadcrumb {
                font-size: 11px !important;
            }
            
            .btn-group-sm .btn {
                padding: 3px 6px;
                font-size: 11px;
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
            <!-- Compact Navigation Header -->
            <div class="test-card" style="padding: 10px 15px; margin-bottom: 10px;">
                <div class="row align-items-center">
                    <div class="col-6 col-md-8">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                                <li class="breadcrumb-item">
                                    <a href="index.php" class="text-success text-decoration-none">
                                        <i class="fa fa-home me-1"></i>Home
                                    </a>
                                </li>
                                <?php if (isset($_GET['exam_completed'])): ?>
                                <li class="breadcrumb-item text-success">Exam</li>
                                <?php endif; ?>
                                <li class="breadcrumb-item active text-muted">Color Vision Test</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-6 col-md-4 text-end">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="window.location.href='index.php'">
                                <i class="fa fa-home"></i>
                                <span class="d-none d-md-inline ms-1">Home</span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                                <i class="fa fa-print"></i>
                                <span class="d-none d-md-inline ms-1">Print</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Exam Results Banner (if coming from exam) -->
            <?php if (isset($_GET['exam_completed']) && $_GET['exam_completed'] === 'true'): ?>
            <div class="test-card" id="examResultsBanner" style="border: 2px solid #28a745;">
                <div class="card border-0">
                    <div class="card-header text-white text-center py-3" style="background: linear-gradient(135deg, #28a745, #20c997);">
                        <h4 class="mb-0">
                            <i class="fa fa-graduation-cap me-2"></i>
                            Exam Results Preview
                        </h4>
                    </div>
                    <div class="card-body p-3">
                        <!-- Compact Exam Results Display -->
                        <div id="examResultsFullPreview" style="display: none;">
                            <div class="row g-2 mb-3">
                                <!-- Score Circle -->
                                <div class="col-12 col-md-4">
                                    <div class="text-center">
                                        <div class="score-circle mx-auto" id="examScoreCircle" style="width: 80px; height: 80px; background: linear-gradient(45deg, #007bff, #0056b3);">
                                            <div class="score-inner">
                                                <span class="score-number" id="examScoreDisplay" style="font-size: 18px;">--</span>
                                                <div class="score-label" style="font-size: 10px;">Score</div>
                                            </div>
                                        </div>
                                        <small class="text-muted mt-1 d-block">Total Score</small>
                                    </div>
                                </div>
                                
                                <!-- Stats Cards -->
                                <div class="col-12 col-md-8">
                                    <div class="row g-2">
                                        <div class="col-6 col-sm-3">
                                            <div class="stat-card-mini text-center p-2" style="background: #e8f5e8; border: 1px solid #28a745; border-radius: 6px;">
                                                <div class="text-success" style="font-size: 20px;">
                                                    <i class="fa fa-percent"></i>
                                                </div>
                                                <div class="fw-bold text-success" id="examPercentageDisplay">--%</div>
                                                <small class="text-muted">Percentage</small>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-3">
                                            <div class="stat-card-mini text-center p-2" style="background: #d4edda; border: 1px solid #28a745; border-radius: 6px;">
                                                <div class="text-success" style="font-size: 20px;">
                                                    <i class="fa fa-check"></i>
                                                </div>
                                                <div class="fw-bold text-success" id="examCorrectDisplay">--</div>
                                                <small class="text-muted">Correct</small>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-3">
                                            <div class="stat-card-mini text-center p-2" style="background: #f8d7da; border: 1px solid #dc3545; border-radius: 6px;">
                                                <div class="text-danger" style="font-size: 20px;">
                                                    <i class="fa fa-times"></i>
                                                </div>
                                                <div class="fw-bold text-danger" id="examWrongDisplay">--</div>
                                                <small class="text-muted">Wrong</small>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-3">
                                            <div class="stat-card-mini text-center p-2" style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 6px;">
                                                <div class="text-warning" style="font-size: 20px;">
                                                    <i class="fa fa-question"></i>
                                                </div>
                                                <div class="fw-bold text-warning" id="examUnansweredDisplay">--</div>
                                                <small class="text-muted">Unanswered</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status Badge -->
                            <div class="text-center mb-3">
                                <span class="badge" id="examStatusBadge" style="font-size: 14px; padding: 8px 20px;">--</span>
                            </div>
                            
                            <!-- Section Performance (if available) -->
                            <div id="examSectionPerformance" style="display: none;">
                                <h6 class="text-center mb-2" style="color: #28a745;">
                                    <i class="fa fa-chart-bar me-1"></i>Section Performance
                                </h6>
                                <div class="row g-2">
                                    <div class="col-6" id="readingSection" style="display: none;">
                                        <div class="text-center p-2" style="background: #e3f2fd; border: 1px solid #2196f3; border-radius: 6px;">
                                            <i class="fa fa-book text-primary mb-1"></i>
                                            <div class="fw-bold">Reading</div>
                                            <div class="text-success" id="readingScore">--/--</div>
                                            <small class="text-muted" id="readingPercentage">--%</small>
                                        </div>
                                    </div>
                                    <div class="col-6" id="listeningSection" style="display: none;">
                                        <div class="text-center p-2" style="background: #e0f2f1; border: 1px solid #4caf50; border-radius: 6px;">
                                            <i class="fa fa-headphones text-success mb-1"></i>
                                            <div class="fw-bold">Listening</div>
                                            <div class="text-success" id="listeningScore">--/--</div>
                                            <small class="text-muted" id="listeningPercentage">--%</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Compact Summary (always visible) -->
                        <div class="alert alert-success mb-3 py-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <i class="fa fa-check-circle me-2"></i>
                                    <strong>Exam Completed!</strong>
                                    <div class="small">Ready for Color Vision Test</div>
                                </div>
                                <div class="col-4 text-end">
                                    <span class="badge bg-success" id="quickScore">-- pts</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Toggle Button -->
                        <div class="text-center">
                            <button class="btn btn-outline-success btn-sm" onclick="toggleExamPreview()">
                                <span id="previewToggleText">Show Details</span>
                                <i class="fa fa-chevron-down ms-1" id="previewToggleIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Header -->
            <div class="test-card">
                <div class="text-center">
                    <h2 class="mb-2" style="color: #28a745;">
                        <i class="fa fa-eye" style="color: #20c997;"></i>
                        Color Vision Test
                    </h2>
                    <p class="text-muted mb-2" style="font-size: 14px;">Test your color vision by identifying numbers in the images below</p>
                    <div class="instruction-text">
                        <strong style="color: #28a745;">Instructions:</strong> You will see 10 random images containing hidden numbers. 
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
                        <div class="card-header text-white text-center py-3" style="background: linear-gradient(135deg, #28a745, #20c997); font-weight: 600; font-size: 17px;">
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
                    <div class="card-header text-white text-center py-4" style="background: linear-gradient(135deg, #28a745, #20c997); font-weight: 600; font-size: 18px;">
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
                                <button class="btn btn-lg me-2 mb-2" onclick="restartTest()" style="background: linear-gradient(45deg, #28a745, #20c997); border: none; color: white;">
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
                                    <div class="card-header" style="background: linear-gradient(135deg, #28a745, #20c997); color: white;">
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