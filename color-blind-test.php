<?php
include("includes/lang/lang-check.php");

if (!(isset($_SESSION["client_id"]) || isset($_COOKIE["remember_me"])) && (!isset($_GET["sample"]) || $_GET["sample"] === "false")) {
    header("Location: ./?showModal=1");
    exit();
}

// Set active page class variables for header navigation
$home = "";
$papers = "";
$leadboard = "";
$about = "";
$contact = "";
$colorblind = "active"; // This page is active
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
    <!-- Main site CSS for header -->
    <link rel="stylesheet" href="assets/plugins/css/icofont.css" />
    <link rel="stylesheet" href="assets/plugins/css/uicons.css" />
    <link rel="stylesheet" href="style.css" />
    <style>
        body {
            background-color: white !important;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }
        
        /* Orientation overlay styles */
        #orientation-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            color: white;
            text-align: center;
            padding: 20px;
        }
        
        #orientation-overlay h2 {
            color: white;
            font-size: 1.8rem;
            margin-bottom: 15px;
            font-weight: bold;
        }
        
        #orientation-overlay p {
            color: #e0e0e0;
            font-size: 1rem;
            margin: 10px 0;
            line-height: 1.5;
        }
        
        #orientation-overlay .rotate-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            animation: rotatePhone 2s infinite;
        }
        
        @keyframes rotatePhone {
            0% { transform: rotate(0deg); }
            50% { transform: rotate(90deg); }
            100% { transform: rotate(0deg); }
        }
        
        /* Mobile orientation specific styles */
        @media screen and (max-width: 768px) and (orientation: portrait) {
            #orientation-overlay {
                display: flex !important;
            }
        }
        
        @media screen and (max-width: 768px) and (orientation: landscape) {
            #orientation-overlay {
                display: none !important;
            }
        }
        
        .color-blind-container {
            min-height: 100vh;
            background-color: white;
            padding: 20px;
        }
        
        .test-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 10px auto;
            max-width: 95%;
            border: 1px solid #e0e0e0;
        }
        
        @media (min-width: 768px) {
            .test-card {
                max-width: 1000px;
                padding: 30px;
                margin: 20px auto;
            }
        }
        
        .test-layout {
            display: flex;
            flex-direction: column;
            gap: 30px;
            align-items: center;
        }
        
        @media (min-width: 768px) {
            .test-layout {
                flex-direction: row;
                gap: 50px;
                align-items: flex-start;
            }
        }
        
        @media (min-width: 768px) {
            .test-layout {
                flex-direction: row;
                gap: 50px;
                align-items: flex-start;
            }
            
            .left-side {
                flex: 2.5;
            }
            
            .right-side {
                flex: 1;
                max-width: 320px;
            }
            
            .test-image-container {
                max-width: 600px;
                padding: 40px;
            }
            
            .test-image {
                max-height: 450px;
            }
        }
        
        @media (min-width: 1200px) {
            .test-card {
                max-width: 1200px;
                padding: 40px;
            }
            
            .test-image-container {
                max-width: 700px;
            }
            
            .test-image {
                max-height: 500px;
            }
        }
        
        .left-side {
            flex: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .right-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-width: 350px;
        }
        
        .test-image-container {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            border: 2px solid #dee2e6;
            width: 100%;
            max-width: 500px;
        }
        
        .test-image {
            max-width: 100%;
            max-height: 400px;
            width: auto;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin: 20px 0;
            border: 1px solid #dee2e6;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #007bff, #0056b3);
            transition: width 0.3s ease;
            border-radius: 4px;
        }
        
        .test-controls {
            text-align: center;
            margin: 25px 0;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
            color: white;
            font-size: 16px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
            background: linear-gradient(45deg, #0056b3, #004085);
        }
        
        .number-display-container {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            border: 2px solid #dee2e6;
            margin-bottom: 15px;
        }
        
        .number-display {
            background: white;
            border: 2px solid #007bff;
            border-radius: 6px;
            padding: 12px;
            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Courier New', monospace;
            font-size: 20px;
            font-weight: bold;
        }
        
        .keypad {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-top: 15px;
        }
        
        .number-btn, .action-btn {
            padding: 12px;
            border: 2px solid #007bff;
            background: white;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #007bff;
        }
        
        .number-btn:hover, .action-btn:hover {
            background: #007bff;
            color: white;
            transform: translateY(-1px);
        }
        
        .action-btn {
            background: #f8f9fa;
            border-color: #6c757d;
            color: #6c757d;
        }
        
        .action-btn:hover {
            background: #6c757d;
            color: white;
        }
        
        .question-counter {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
        }
        
        .instruction-text {
            background: #e3f2fd;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-size: 15px;
            color: #333;
        }
        
        .results-container {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            border: 2px solid #dee2e6;
        }
        
        .score-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px auto;
            font-size: 20px;
            font-weight: bold;
            color: white;
        }
        
        .score-excellent { background: linear-gradient(45deg, #28a745, #20c997); }
        .score-good { background: linear-gradient(45deg, #20c997, #17a2b8); }
        .score-fair { background: linear-gradient(45deg, #ffc107, #fd7e14); }
        .score-poor { background: linear-gradient(45deg, #dc3545, #c82333); }
        
        .score-circle-container {
            display: flex;
            justify-content: center;
            margin: 15px 0;
        }
        
        .score-inner {
            text-align: center;
        }
        
        .score-number {
            font-size: 24px;
            font-weight: bold;
            display: block;
        }
        
        .score-label {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 3px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 18px;
            text-align: center;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            border: 2px solid transparent;
            margin-bottom: 0;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }
        
        .correct-card {
            border-color: #28a745;
        }
        
        
        .wrong-card {
            border-color: #dc3545;
        }
        
        .wrong-card .stat-icon {
            color: #dc3545;
            font-size: 35px;
            margin-bottom: 10px;
        }
        
        .skipped-card {
            border-color: #ffc107;
        }
        
        .skipped-card .stat-icon {
            color: #ffc107;
            font-size: 35px;
            margin-bottom: 10px;
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .stat-label {
            font-size: 15px;
            color: #6c757d;
            font-weight: 500;
        }
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
        
        /* Mobile responsiveness */
        @media (max-width: 767px) {
            .color-blind-container {
                padding: 10px;
            }
            
            .test-card {
                margin: 5px auto;
                padding: 15px;
                max-width: 98%;
            }
            
            .test-layout {
                gap: 20px;
                flex-direction: column;
            }
            
            .left-side, .right-side {
                flex: 1;
                max-width: 100%;
            }
            
            .test-image-container {
                padding: 20px;
                max-width: 100%;
            }
            
            .test-image {
                max-height: 280px;
            }
            
            .number-display {
                font-size: 18px;
                min-height: 45px;
                padding: 10px;
            }
            
            .number-btn, .action-btn {
                padding: 10px;
                font-size: 14px;
            }
            
            .btn-primary {
                padding: 10px 20px;
                font-size: 14px;
            }
            
            .question-counter {
                padding: 8px 16px;
                font-size: 14px;
            }
            
            .instruction-text {
                padding: 12px;
                font-size: 14px;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 576px) {
            .test-card {
                margin: 2px;
                padding: 10px;
                max-width: 99%;
            }
            
            .keypad {
                gap: 8px;
            }
            
            .number-btn, .action-btn {
                padding: 10px;
                font-size: 14px;
            }
            
            .test-image {
                max-height: 200px;
            }
            
            .d-flex.gap-2 .btn {
                font-size: 14px;
                padding: 8px 16px;
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
    <!-- Include Main Header -->
    <?php include("includes/header.php"); ?>
    
    <!-- Orientation Overlay for Mobile Portrait Mode -->
    <div id="orientation-overlay">
        <div class="rotate-icon">📱</div>
        <h2>Please Rotate Your Device</h2>
        <p>For the best color blind test experience, please rotate your device to landscape mode.</p>
        <p>회전하여 가로 모드로 사용해주세요</p>
    </div>
    
    <div id="preloader">
        <div id="ed-preloader" class="ed-preloader">
            <div class="animation-preloader">
                <div class="spinner"></div>
            </div>
        </div>
    </div>

    <?php
    // Database connection and user service are already included via header.php
    // All user variables ($userId, $firstName, $fullName, etc.) are available from header
    ?>

    <div class="color-blind-container">
        <div class="container">
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

                <!-- Left-Right Layout Container -->
                <div class="test-layout">
                    <!-- Left Side - Image Display -->
                    <div class="left-side">
                        <div class="test-image-container">
                            <img id="testImage" class="test-image" src="" alt="Color blind test image" style="display: none;">
                            <div id="loadingText">
                                <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                                <p class="mt-3">Loading test...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Answer Input Section -->
                    <div class="right-side">
                        <div class="answer-section" id="answerSection" style="display: none;">
                            <h5 class="text-center mb-3" style="color: #007bff;">
                                <i class="fa fa-keyboard-o me-2"></i>
                                What number do you see?
                            </h5>
                            
                            <!-- Number Display -->
                            <div class="number-display-container text-center mb-4">
                                <div class="number-display" id="numberDisplay">
                                    <span id="displayValue">--</span>
                                </div>
                                <div class="input-feedback mt-2" id="inputFeedback">Enter the number you see (0-100)</div>
                            </div>

                            <!-- Number Keypad -->
                            <div class="keypad">
                                <button class="number-btn" data-number="1">1</button>
                                <button class="number-btn" data-number="2">2</button>
                                <button class="number-btn" data-number="3">3</button>
                                <button class="number-btn" data-number="4">4</button>
                                <button class="number-btn" data-number="5">5</button>
                                <button class="number-btn" data-number="6">6</button>
                                <button class="number-btn" data-number="7">7</button>
                                <button class="number-btn" data-number="8">8</button>
                                <button class="number-btn" data-number="9">9</button>
                                <button class="action-btn" id="clearBtn">
                                    <i class="fa fa-eraser"></i> Clear
                                </button>
                                <button class="number-btn" data-number="0">0</button>
                                <button class="action-btn" id="backspaceBtn">
                                    <i class="fa fa-backspace"></i> Back
                                </button>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 mt-3">
                                <button class="btn btn-secondary flex-fill" id="skipBtn">
                                    <i class="fa fa-forward me-1"></i>Skip
                                </button>
                                <button class="btn btn-primary flex-fill" id="submitBtn" disabled>
                                    <i class="fa fa-check me-1"></i>Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Controls -->
                <div class="test-controls">
                    <button class="btn btn-primary btn-lg" id="startBtn">
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

    <!-- Include footer with login/register modals -->
    <?php include("includes/footer.php") ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="assets/plugins/js/jquery-3.6.0.min.js"></script>
    <script src="assets/plugins/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/color-blind-test.js"></script>
    
    <!-- Mobile Orientation Script -->
    <script>
        function isMobileDevice() {
            return /Mobi|Android|iPhone|iPad|iPod|BlackBerry|Opera Mini|IEMobile|Windows Phone|webOS/i.test(navigator.userAgent);
        }
        
        // Force landscape orientation on mobile devices
        function checkOrientation() {
            if (isMobileDevice()) {
                const orientationOverlay = document.getElementById('orientation-overlay');
                if (window.innerHeight > window.innerWidth) {
                    // Portrait mode - show overlay
                    orientationOverlay.style.display = 'flex';
                    exitFullscreen();
                } else {
                    // Landscape mode - hide overlay and enter fullscreen
                    orientationOverlay.style.display = 'none';
                    requestFullscreen();
                }
            }
        }
        
        // Fullscreen functions
        function requestFullscreen() {
            if (isMobileDevice()) {
                const elem = document.documentElement;
                if (elem.requestFullscreen) {
                    elem.requestFullscreen().catch(err => console.log('Fullscreen request failed:', err));
                } else if (elem.webkitRequestFullscreen) {
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) {
                    elem.msRequestFullscreen();
                } else if (elem.mozRequestFullScreen) {
                    elem.mozRequestFullScreen();
                }
            }
        }
        
        function exitFullscreen() {
            if (document.exitFullscreen) {
                document.exitFullscreen().catch(err => console.log('Exit fullscreen failed:', err));
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            }
        }
        
        // Handle fullscreen change events
        function handleFullscreenChange() {
            // This function can be used to handle fullscreen state changes if needed
        }
        
        // Check orientation on load and resize
        window.addEventListener('load', function() {
            checkOrientation();
            // Add fullscreen change listeners
            document.addEventListener('fullscreenchange', handleFullscreenChange);
            document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
            document.addEventListener('mozfullscreenchange', handleFullscreenChange);
            document.addEventListener('MSFullscreenChange', handleFullscreenChange);
        });
        
        window.addEventListener('resize', checkOrientation);
        window.addEventListener('orientationchange', function() {
            setTimeout(checkOrientation, 100);
        });
    </script>
</body>
</html>