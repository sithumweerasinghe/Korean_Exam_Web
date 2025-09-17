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
    <meta name="description" content="Topik Sir | Korean language proficiency test and secure your employment abroad." />
    <meta name="keywords" content="Topik Sir | Korean language proficiency test and secure your employment abroad." />
    <meta name="author" content="Virul Nirmala Wickramasinghe" />
    <title>Topik Sir | Korean language proficiency test and secure your employment abroad.</title>
    <meta property="og:url" content="https://topiksir.com/">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Topik Sir">
    <meta property="og:image" content="https://opengraph.b-cdn.net/production/images/7ccbbf92-ba39-44c6-bac5-49bc09364734.png?token=09ysYKBiRQ9EiSFQ7w_cEVKlD1IsnFPaG-vPW7sP10U&height=630&width=1200&expires=33270101925">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="topiksir.com">
    <meta property="twitter:url" content="https://topiksir.com/">
    <meta name="twitter:title" content="Topik Sir">
    <meta name="twitter:description" content="Prepare for the EPS-TOPIK exam with our expert training program. Access study materials, mock tests, and personalized guidance to excel in your Korean language proficiency test and secure your employment abroad.">
    <meta name="twitter:image" content="https://opengraph.b-cdn.net/production/images/7ccbbf92-ba39-44c6-bac5-49bc09364734.png?token=09ysYKBiRQ9EiSFQ7w_cEVKlD1IsnFPaG-vPW7sP10U&height=630&width=1200&expires=33270101925">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <link rel="stylesheet" href="assets/plugins/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/animate.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/owl.carousel.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/swiper-bundle.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/maginific-popup.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/nice-select.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/icofont.css" />
    <link rel="stylesheet" href="assets/plugins/css/uicons.css" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="assets/css/exam-styles.css" />
</head>

<body class="element-wrapper ">
    <!-- Orientation Overlay for Mobile Portrait Mode -->
    <div id="orientation-overlay">
        <div class="rotate-icon">📱</div>
        <h2>Please Rotate Your Device</h2>
        <p>For the best exam experience, please rotate your device to landscape mode.</p>
        <p>회전하여 가로 모드로 사용해주세요</p>
    </div>
    
    <div id="preloader">
        <div id="ed-preloader" class="ed-preloader">
            <div class="animation-preloader">
                <div class="spinner"></div>
            </div>
        </div>
    </div>
    <div id="ed-mouse">
        <div id="cursor-ball"></div>
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
    $mobile = $userArray["mobile"];
    $fullName = $userArray["fullname"];
    $profileImage = $userArray["profile"];
    $email = $userArray["email"];
    $address = $userArray["address"];
    $city = $userArray["city"];
    $isGoogleUser = $userArray["isGoogleUser"];

    $currentTime = time();
    $application_no = $currentTime . $userId;
    ?>
    <div id="smooth-wrapper ">
        <div id="smooth-content">
            <main>
                <section class="position-relative h-auto overflow-hidden" id="entire-section">
                    <input type="hidden" id="csrf_token" value="<?= isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>">

                    <!-- Header Start -->
                    <div class="exam-header shadow-sm">
                        <div class="header-top d-flex align-items-center justify-content-between px-3 py-2">
                            <div class="exam-brand d-flex align-items-center">
                                <div class="brand-icon">
                                    <i class="fa fa-graduation-cap"></i>
                                </div>
                                <div class="brand-text ms-2">
                                    <h6 class="mb-0 fw-bold">EPS-TOPIK UBT</h6>
                                    <small class="text-muted">Test of proficiency in Korean</small>
                                </div>
                            </div>
                            
                            <div class="exam-info d-flex align-items-center">
                                <div class="info-badge me-2">
                                    <span class="badge-label">App No</span>
                                    <span class="badge-value"><?= $application_no ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="header-controls d-flex align-items-center justify-content-end px-3 py-1">
                            <!-- Font Size Controls -->
                            <div class="control-group me-2">
                                <button class="control-btn compact" id="font-increase" title="Increase font size">A+</button>
                                <button class="control-btn compact" id="font-decrease" title="Decrease font size">A-</button>
                            </div>
                            
                            <!-- Screen Brightness Control -->
                            <div class="control-group me-2">
                                <label class="control-label">
                                    <i class="fa fa-sun"></i>
                                    <span style="font-size: 10px; margin-left: 3px;">밝기</span>
                                </label>
                                <button class="control-btn compact" onclick="adjustBrightness(-10)">-</button>
                                <input type="range" class="control-range" id="brightnessRange" min="20" max="100" value="100" onchange="setBrightness(this.value)">
                                <button class="control-btn compact" onclick="adjustBrightness(10)">+</button>
                            </div>
                            
                            <!-- Volume Control -->
                            <div class="control-group me-2">
                                <label class="control-label">
                                    <i id="volumeIcon" class="fa fa-volume-up"></i>
                                    <span style="font-size: 10px; margin-left: 3px;">음량</span>
                                </label>
                                <button class="control-btn compact" onclick="adjustVolume(-10)">-</button>
                                <input type="range" class="control-range" id="volumeRange" min="0" max="100" value="50" onchange="setVolume(this.value, true)">
                                <button class="control-btn compact" onclick="adjustVolume(10)">+</button>
                                <button id="testSoundBtn" class="control-btn compact" onclick="playTestSound()" style="margin-left: 5px; display: none;" title="테스트 소리">
                                    <i class="fa fa-play"></i>
                                </button>
                            </div>

                            <!-- Headphone Indicator -->
                            <div class="headphone-indicator" id="headphoneIndicator" title="오디오 출력 장치 상태">
                                <i class="fa fa-headphones" id="headphoneIcon"></i>
                                <span class="headphone-label" id="headphoneLabel">확인 중…</span>
                                <span class="status-dot status-unknown" id="headphoneDot"></span>
                                <i class="fa fa-caret-down ms-1" style="font-size: 12px; cursor: pointer;" onclick="toggleDeviceSelector()"></i>
                            </div>
                            
                            <!-- Audio Device Selector Dropdown -->
                            <div class="audio-device-selector d-none" id="deviceSelector">
                                <div class="device-selector-header">
                                    <i class="fa fa-volume-up me-2"></i>
                                    <span>오디오 출력 장치</span>
                                </div>
                                <div class="device-list" id="deviceList">
                                    <div class="device-item loading">
                                        <i class="fa fa-spinner fa-spin me-2"></i>
                                        장치를 검색 중...
                                    </div>
                                </div>
                                
                                <!-- Audio Test Section -->
                                <div class="audio-test-section">
                                    <div class="test-section-header">
                                        <i class="fa fa-headphones me-2"></i>
                                        <span>오디오 테스트</span>
                                    </div>
                                    
                                    <!-- Microphone Test -->
                                    <div class="test-item" onclick="testMicrophone()">
                                        <div class="test-content">
                                            <i class="fa fa-microphone me-2"></i>
                                            <span>마이크 확인</span>
                                            <div class="mic-status" id="micStatus">
                                                <span class="status-dot status-unknown" id="micDot"></span>
                                                <span id="micLabel">테스트하지 않음</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Sound Test -->
                                    <div class="test-item" onclick="testSound()">
                                        <div class="test-content">
                                            <i class="fa fa-play me-2"></i>
                                            <span>음향 테스트</span>
                                            <small class="text-muted d-block">한국어 안내 메시지 재생</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Header End -->

                    <div class="col-12 d-flex align-items-center justify-content-center" id="main-content" style="height: calc(100vh - 90px); overflow-y: auto; overflow-x: hidden; padding: 10px;">
                        <div class="container-fluid">
                            <?php
                            if (!isset($_GET['start_exam'])) {
                            ?>
                                <div class="">
                                    <div class="" style="justify-items: center; ">
                                        <!-- Enter Ticket Start-->
                                        <?php
                                        if ((isset($_GET["paper_id"]) && isset($_GET["sample"]) && count($_GET) === 2)) {
                                        ?>
                                            <div class="exam-card">
                                                <div class="card-header-compact">
                                                    <h5><i class="fa fa-user-check me-2"></i>Information Check of Applicant</h5>
                                                </div>
                                                <div class="instruction-bar">
                                                    <i class="fa fa-volume-up"></i>
                                                    <span>Check your application and if there is no problem, click the confirm button.</span>
                                                </div>
                                                <div class="card-body-compact">
                                                    <div class="profile-section">
                                                        <div class="d-flex align-items-center">
                                                            <img src="<?= $profileImage ?>" alt="Profile Image" class="profile-image-compact me-3">
                                                            <div class="profile-details">
                                                                <div class="detail-row">
                                                                    <span class="detail-label">Test Venus</span>
                                                                    <span class="detail-value">Online</span>
                                                                </div>
                                                                <div class="detail-row">
                                                                    <span class="detail-label">Test Room</span>
                                                                    <span class="detail-value">Not available</span>
                                                                </div>
                                                                <div class="detail-row">
                                                                    <span class="detail-label">Application No</span>
                                                                    <span class="detail-value"><?= $application_no ?></span>
                                                                </div>
                                                                <div class="detail-row">
                                                                    <span class="detail-label">Candidate Name</span>
                                                                    <span class="detail-value"><?= $fullName ?: 'Not Available' ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Face Verification Options removed as per requirements -->
                                                    
                                                    <div class="text-center">
                                                        <a href="javascript:void(0)" onclick="handleConfirmClick('<?= $profileImage ?>')" class="action-btn-compact">
                                                            Confirm<i class="fi fi-rr-arrow-small-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>

                                        <!-- Enter Ticket End -->

                                        <!-- AV Brightness Step Start -->
                                        <?php
                                        if (isset($_GET["av_brightness"])) {
                                        ?>
                                            <div class="exam-card">
                                                <div class="card-header-compact">
                                                    <h5><i class="fa fa-sun-o me-2"></i>Brightness Adjustment</h5>
                                                </div>
                                                <div class="instruction-bar">
                                                    <i class="fa fa-volume-up"></i>
                                                    <span>Adjust screen brightness until this sample looks comfortable, then click Confirm.</span>
                                                </div>
                                                <div class="card-body-compact">
                                                    <div class="d-flex flex-column align-items-center av-step">
                                                        <div class="rounded border mb-3" style="width: 280px; height: 140px; background: linear-gradient(90deg, #000, #222, #444, #666, #888, #aaa, #ccc, #eee, #fff);"></div>
                                                        <p id="avBrightnessText" class="text-muted mb-3" style="max-width:620px; text-align:center;">
                                                            Make sure you can distinguish the dark shades on the left and the bright shades on the right without eye strain. Use the slider to fine tune brightness.
                                                        </p>
                                                        <div class="d-flex align-items-center w-100" style="max-width: 520px; gap:10px;">
                                                            <i class="fa fa-moon-o text-success"></i>
                                                            <input id="avBrightnessRangeStep" type="range" min="20" max="100" step="1" class="form-range flex-grow-1" />
                                                            <i class="fa fa-sun-o text-success"></i>
                                                        </div>
                                                    </div>
                                                    <div class="text-center mt-3">
                                                        <?php
                                                            $currentQuery = $_SERVER['QUERY_STRING'];
                                                            // Next: remove av_brightness and set av_volume=true
                                                            $nextQuery = preg_replace('/(?:^|&)av_brightness=[^&]*/', '', $currentQuery);
                                                            $nextQuery = preg_replace('/&&+/', '&', $nextQuery);
                                                            $nextQuery = trim($nextQuery, '&');
                                                            if (strpos($nextQuery, 'av_volume=true') === false) {
                                                                $nextQuery .= ($nextQuery ? '&' : '') . 'av_volume=true';
                                                            }
                                                            // Previous: go back to sample (face id) page
                                                            $prevQueryB = preg_replace('/(?:^|&)(av_brightness|av_volume|notice|instructions)=[^&]*/', '', $currentQuery);
                                                            $prevQueryB = preg_replace('/&&+/', '&', $prevQueryB);
                                                            $prevQueryB = trim($prevQueryB, '&');
                                                            if (strpos($prevQueryB, 'sample=true') === false) {
                                                                $prevQueryB .= ($prevQueryB ? '&' : '') . 'sample=true';
                                                            }
                                                        ?>
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <a href="exam?<?= $prevQueryB ?>" class="btn btn-secondary">Previous</a>
                                                            <a href="exam?<?= $nextQuery ?>" class="action-btn-compact" id="avBrightnessConfirm">Confirm<i class="fi fi-rr-arrow-small-right"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <!-- AV Brightness Step End -->

                                        <!-- AV Volume Step Start -->
                                        <?php
                                        if (isset($_GET["av_volume"])) {
                                        ?>
                                            <div class="exam-card">
                                                <div class="card-header-compact">
                                                    <h5><i class="fa fa-volume-up me-2"></i>Volume Adjustment</h5>
                                                </div>
                                                <div class="instruction-bar">
                                                    <i class="fa fa-volume-up"></i>
                                                    <span id="avStepVolumeInstructions">Adjust the volume using the slider. Press Play to check and ensure you can hear clearly.</span>
                                                </div>
                                                <div class="card-body-compact">
                                                    <div class="d-flex flex-column align-items-center av-step">
                                                        <div class="d-flex align-items-center mb-3" style="gap:10px;">
                                                            <button id="avStepPlayBtn" type="button" class="btn btn-success rounded-circle" style="width: 48px; height: 48px; display:flex; align-items:center; justify-content:center; font-weight:700;">▶</button>
                                                            <audio id="avStepSampleAudio" src="assets/audio/question3.mp3"></audio>
                                                        </div>
                                                        <p id="avStepVolumeText" class="k-text mb-3 text-center" style="max-width: 720px;">
                                                            이어폰을 태블릿 PC에 연결해 주세요.<br><span>음량 조절을 위해서 상단에 보이는 플레이 버튼을 눌러주세요.</span><br>시험에 앞서 단말기의 화면 밝기, 음량을 조정하실 수 있습니다.<br>아래의 음량 조절 바를 움직여 <strong>음량을 적정하게 조절</strong>해 주시기 바랍니다.
                                                        </p>
                                                        <div class="d-flex align-items-center w-100" style="max-width: 520px; gap:10px;">
                                                            <i class="fa fa-volume-down text-success"></i>
                                                            <input id="avStepVolumeRange" type="range" min="0" max="100" step="1" class="form-range flex-grow-1" />
                                                            <i class="fa fa-volume-up text-success"></i>
                                                        </div>
                                                    </div>
                                                    <div class="text-center mt-3">
                                                        <?php
                                                            $currentQuery = $_SERVER['QUERY_STRING'];
                                                            // remove av_volume and set instructions=true
                                                            $newQuery = preg_replace('/(?:^|&)av_volume=[^&]*/', '', $currentQuery);
                                                            $newQuery = preg_replace('/&&+/', '&', $newQuery);
                                                            $newQuery = trim($newQuery, '&');
                                                            if (strpos($newQuery, 'instructions=true') === false) {
                                                                $newQuery .= ($newQuery ? '&' : '') . 'instructions=true';
                                                            }
                                                            // Build previous link to brightness step
                                                            $prevQuery = preg_replace('/(?:^|&)av_volume=[^&]*/', '', $currentQuery);
                                                            $prevQuery = preg_replace('/&&+/', '&', $prevQuery);
                                                            $prevQuery = trim($prevQuery, '&');
                                                            if (strpos($prevQuery, 'av_brightness=true') === false) {
                                                                $prevQuery .= ($prevQuery ? '&' : '') . 'av_brightness=true';
                                                            }
                                                        ?>
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <a href="exam?<?= $prevQuery ?>" class="btn btn-secondary">
                                                                Previous
                                                            </a>
                                                            <a href="exam?<?= $newQuery ?>" class="action-btn-compact" id="avVolumeConfirm">
                                                                Confirm<i class="fi fi-rr-arrow-small-right"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <!-- AV Volume Step End -->

                                        <!-- Notice Start -->
                                        <?php
                                        if (isset($_GET["notice"])) {
                                        ?>
                                            <div class="exam-card">
                                                <div class="card-header-compact">
                                                    <h5><i class="fa fa-bell me-2"></i>Notice of Applicant</h5>
                                                </div>
                                                <div class="instruction-bar">
                                                    <i class="fa fa-volume-up"></i>
                                                    <span>After being fully aware of applicant notice below, click the [Confirm] button</span>
                                                </div>
                                                <!-- Face Verification Status -->
                                                <div class="bg-light p-2 border-bottom">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa fa-shield-alt text-success me-2"></i>
                                                        <div class="flex-grow-1">
                                                            <strong class="text-success" style="font-size: 13px;">Face Verification Completed</strong>
                                                            <br><small class="text-muted" style="font-size: 11px;">Identity verified successfully with <span id="verificationScoreDisplay">--</span>% similarity</small>
                                                        </div>
                                                        <span class="badge bg-success">✓ Verified</span>
                                                    </div>
                                                </div>
                                                <div class="bg-white p-5" style="overflow-y: auto;">
                                                    <div class="row align-items-center">
                                                        <ul class="list-unstyled">
                                                            <li class="d-flex align-items-center mb-2">
                                                                <i class="bi bi-circle-fill text-success me-3" style="font-size: 12px;"></i>
                                                                <span style="font-size: 15px">You should organize all your belongings below your desk except test identification, ID card (passport).</span>
                                                            </li>
                                                            <li class="d-flex align-items-center mb-2">
                                                                <i class="bi bi-circle-fill text-success me-3" style="font-size: 12px;"></i>
                                                                <span style="font-size: 15px"><span class="fw-semibold">Electronic devices such as cell phone, camera, etc., are not allowed to possess and use.</span> Please hand in to supervisor.</span>
                                                            </li>
                                                            <li class="d-flex align-items-center mb-2">
                                                                <i class="bi bi-circle-fill text-success me-3" style="font-size: 12px;"></i>
                                                                <span style="font-size: 15px">If there is a <span class="fw-semibold">technical problem</span> with the computer during the test, please raise your hand quietly without making any noise. If needed, you can move to another PC and continue the test from the previous question.</span>
                                                            </li>
                                                            <li class="d-flex align-items-center mb-2">
                                                                <i class="bi bi-circle-fill text-success me-3" style="font-size: 12px;"></i>
                                                                <span style="font-size: 15px">This test will proceed for <span class="fw-semibold">50 minutes without a break.</span> It has all 40 questions. The reading test is from 1 to 20, and the listening test is from 21 to 40. The listening test will be played twice.</span>
                                                            </li>
                                                            <li class="d-flex align-items-center mb-2">
                                                                <i class="bi bi-circle-fill text-success me-3" style="font-size: 12px;"></i>
                                                                <span style="font-size: 15px"><span class="fw-semibold">Once you choose an answer, you can’t change the answer.</span> Please mark the answer carefully. </span>
                                                            </li>
                                                            <li class="d-flex align-items-center mb-2">
                                                                <i class="bi bi-circle-fill text-success me-3" style="font-size: 12px;"></i>
                                                                <span style="font-size: 15px"> <span class="fw-semibold">In case of cheating,</span> the test will be void, and <span class="fw-semibold">examinees will NOT be eligible for taking the EPS-TOPIK for 2 years.</span></span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ed-hero__btn text-center mb-1 mt-3">
                                                <a href="exam?<?= str_replace('notice', 'instructions', $_SERVER['QUERY_STRING']) ?>" class="ed-btn" onclick="return handleNoticeConfirm()">Confirm<i
                                                        class="fi fi-rr-arrow-small-right"></i></a>
                                            </div>
                                        <?php
                                        }
                                        ?>

                                        <!-- Notice End -->

                                        <!-- Instructions Start -->
                                        <?php
                                        if (isset($_GET["instructions"])) {
                                        ?>
                                            <div class="card shadow col-8 " style="border-radius: 12px;  overflow-y: auto; margin-top: 20px;">
                                                <div class="p-3 text-white text-center" style="background-color: #2ca347; font-weight: 600; font-size: 19px;">
                                                    Practice Test of Proficiency in Korea(CBT)
                                                </div>
                                                <div class="p-3 text-white bg-dark text-center d-flex justify-content-center align-items-center">
                                                    <i class="fa fa-volume-up  fs-2 text-white "></i>After clicking [Practice Test] button, proceed practice test and if there is nothing wrong, click [Ready] button.
                                                </div>
                                                <div class="bg-white p-4" style=" overflow-y: auto;">
                                                    <div class="row align-items-center">
                                                        <ul class="list-unstyled">
                                                            <li class="d-flex align-items-center mb-2">
                                                                <i class="bi bi-circle-fill text-success me-3" style="font-size: 12px;"></i>
                                                                <span style="font-size: 15px">Click (<span class="px-2 py-1 text-white" style="background-color: #2ca347;">Practice Test</span>) and proceed practice test.
                                                                    <span class="fw-semibold">(If you don’t proceed practice test, you can’t take the test.)</span></span>
                                                            </li>
                                                            <li class="d-flex align-items-center mb-2">
                                                                <i class="bi bi-circle-fill text-success me-3" style="font-size: 12px;"></i>
                                                                <span style="font-size: 15px">If there is any problem in practice test, ask supervisor about it.</span>
                                                            </li>
                                                            <li class="d-flex align-items-center mb-2">
                                                                <i class="bi bi-circle-fill text-success me-3" style="font-size: 12px;"></i>
                                                                <span style="font-size: 15px"><span class=" fw-semibold">After clicking (<span class="px-2 py-1 text-white" style="background-color: #2ca347;">Ready</span>) button,</span> please wait until supervisor gives direction.
                                                                    (You can take a test after <span class="fw-semibold">clicking [Ready].</span> )</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ed-hero__btn text-center mb-1 mt-3">
                                                <a onclick="handleInstructionsReady('<?= htmlspecialchars($_GET['paper_id'], ENT_QUOTES) ?>', 
                             '<?= htmlspecialchars($application_no, ENT_QUOTES) ?>', 
                             '<?= htmlspecialchars($_GET['exam_id'] ?? '', ENT_QUOTES) ?>',
                             '<?= htmlspecialchars($_GET['sample'] ?? 'true', ENT_QUOTES) ?>');" class="ed-btn">Ready<i
                                                        class="fi fi-rr-arrow-small-right"></i></a>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <!-- Instructions End -->

                                        <!--  Identification Check Start -->
                                        <div id="prepare-content" class="card shadow col-6 my-4 d-none" style="border-radius: 12px;  max-height:  calc(100vh - 300px); overflow-y: auto;">
                                            <div class="p-3 text-white text-center" style="background-color: #2ca347; font-weight: 600; font-size: 19px;">
                                                Identification Check
                                            </div>
                                            <div class="p-3 text-white bg-dark text-center d-flex justify-content-center align-items-center">
                                                <i class="fa fa-volume-up me-3 fs-2 text-white "></i>Prepare your identification and please wait for a minute.
                                            </div>
                                            <div class="bg-white p-5">
                                                <div class="row align-items-center">
                                                    <div class="col-4 text-center">
                                                        <img
                                                            src="<?= $profileImage ?>"
                                                            alt="Profile Image"
                                                            class="rounded-circle shadow"
                                                            style="width: 140px; height: 140px; object-fit: cover;">
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="row">
                                                            <div class="col-6 p-2 border text-end fw-bold">Test Venus</div>
                                                            <div class="col-6 p-2 border">Online</div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-6 p-2 border text-end fw-bold">Test Room</div>
                                                            <div class="col-6 p-2 border">Not available</div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-6 p-2 border text-end fw-bold">Application No</div>
                                                            <div class="col-6 p-2 border"><?= $application_no ?></div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-6 p-2 border text-end fw-bold">Candidate Name</div>
                                                            <div class="col-6 p-2 border"><?= $fullName ?: 'Not Available' ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  Identification Check End -->
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>

                    <div id="exam-content" class="col-12  d-none bg-secondary-subtle overflow-hidden" style="height: calc(100vh - 155px);">
                        <!-- Paper Start -->
                        <div class="row ">
                            <div class="d-flex flex-column align-items-center" style="width: 100%">
                                <!-- Top Status Bar -->
                                <div class="exam-topbar d-flex align-items-center justify-content-between w-100 mb-2">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <div class="time-chip"><span class="label">Reading</span><span id="reading-remaining" class="value">04:59</span></div>
                                        <div class="time-chip"><span class="label">Listening</span><span id="listening-remaining" class="value">04:59</span></div>
                                        <div class="time-chip"><span class="label">Question</span><span id="listening-timer-single" class="value">00:00</span></div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="qmap-btn" onclick="openQuestionMap()"><i class="fa fa-th me-1"></i> <span class="btn-label">Question Map</span></button>
                                        <button onclick="submitAnswers();" class="exam-button py-2 px-3">Submit Answers ></button>
                                    </div>
                                </div>

                                <!-- Questions Container Start -->
                                <div class="row align-items-center w-100 justify-content-center">
                                    <div id="quiz-container" class="col-lg-12 position-relative d-flex justify-content-center">
                                        <div class="question-wrapper position-relative">
                                            <button type="button" class="mob-nav-btn mob-prev" aria-label="Previous question" onclick="(function(){var b=document.getElementById('prev-btn'); if(b && b.style.display!=='none' && !b.disabled){ b.click(); }})()">
                                                <i class="fa fa-chevron-left"></i>
                                            </button>
                                            <div id="question-container" class="bg-white card" style="width:100%; max-width:700px; max-height: calc(100vh - 210px); overflow-y: auto; border: 1px solid #ddd;"></div>
                                            <button type="button" class="mob-nav-btn mob-next" aria-label="Next question" onclick="(function(){var b=document.getElementById('next-btn'); if(b && !b.disabled){ b.click(); }})()">
                                                <i class="fa fa-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Questions Container End -->

                                <!-- Bottom Navigation Bar -->
                                <div class="exam-bottombar d-flex align-items-center justify-content-center gap-2 w-100 mt-2">
                                    <button id="prev-btn" class="exam-button py-2 px-3 rounded-2"> < Previous</button>
                                    <div class="fw-semibold" id="question-counter" style="font-size:13px; color:#2c3e50;">-- / --</div>
                                    <button id="next-btn" class="exam-button py-2 px-3 rounded-2">Next ></button>
                                    <button type="button" class="qmap-btn" onclick="openQuestionMap()"><i class="fa fa-th me-1"></i> <span class="btn-label">Map</span></button>
                                    <button type="button" class="exam-button submit-mobile py-2 px-3" onclick="submitAnswers()"><i class="fa fa-paper-plane me-1"></i><span class="btn-label">Submit</span></button>
                                </div>

                                <!-- Hidden containers to support existing renderers -->
                                <div id="hidden-answer-sheet" class="d-none">
                                    <div class="row" id="reading-container"></div>
                                    <div class="row" id="listening-container"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Paper End -->

                    </div>
                    
                    <!-- Question Map Modal -->
                    <div id="questionMapModal" class="qm-backdrop d-none">
                        <div class="qm-card">
                            <div class="qm-header">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa fa-th"></i>
                                    <span class="fw-bold">Question Map</span>
                                </div>
                                <button class="qm-close" onclick="closeQuestionMap()" aria-label="Close">×</button>
                            </div>
                            <div class="qm-meta">
                                <span id="qm-total">Total: --</span>
                                <span id="qm-answered">Answered: --</span>
                                <span id="qm-remaining">Remaining: --</span>
                            </div>
                            <div class="qm-sections">
                                <div class="qm-section">
                                    <div class="qm-section-header">
                                        <i class="fa fa-book me-1 text-success"></i>
                                        <span>Reading (<span id="qm-reading-count">--</span> questions)</span>
                                    </div>
                                    <div class="qgrid" id="qm-grid-reading"></div>
                                </div>
                                <div class="qm-section">
                                    <div class="qm-section-header">
                                        <i class="fa fa-headphones me-1 text-success"></i>
                                        <span>Listening (<span id="qm-listening-count">--</span> questions)</span>
                                    </div>
                                    <div class="qgrid" id="qm-grid-listening"></div>
                                </div>
                            </div>
                            <div class="qm-legend">
                                <span><span class="dot answered"></span> Answered</span>
                                <span><span class="dot current"></span> Current</span>
                                <span><span class="dot locked"></span> Locked</span>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
            <!-- Footer -->
            <div class="py-3 " style="background-color: #e8ffed;  height: 35px; bottom: 0;">
            </div>
        </div>
    </div>
    <div id="examModal" class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50">
        <div class="modal-content py-5 px-3 text-center rounded shadow w-50 d-flex justify-content-center align-items-center" style="background-image: url('assets/images/section-bg-11.png'); background-size: cover; background-position: center; background-repeat: no-repeat; height: 50%; border: 1px solid rgba(255, 255, 255, 0.3);">
            <h2 class="fs-3 fw-bold">විභාගය ආරම්භ වීමට</h2>
            <p class="mt-2 text-danger">
                ඉතිරි කාලය:
            <h1 id="countdown" class="fw-semibold mt-1" style="color:#2ca347"></h1>
            </p>
            <div class="ed-hero__btn text-center mt-4">
                <a href="https://topiksir.com/" class="ed-btn">Go to Home<i
                        class="fi fi-rr-arrow-small-right"></i></a>
            </div>
        </div>
    </div>

    <script src="assets/plugins/js/jquery.min.js"></script>
    <script src="assets/plugins/js/jquery-migrate.js"></script>
    <script src="assets/plugins/js/bootstrap.min.js"></script>
    <script src="assets/plugins/js/gsap/gsap.js"></script>
    <script src="assets/plugins/js/gsap/gsap-scroll-to-plugin.js"></script>
    <script src="assets/plugins/js/gsap/gsap-scroll-smoother.js"></script>
    <script src="assets/plugins/js/gsap/gsap-scroll-trigger.js"></script>
    <script src="assets/plugins/js/gsap/gsap-split-text.js"></script>
    <script src="assets/plugins/js/wow.min.js"></script>
    <script src="assets/plugins/js/owl.carousel.min.js"></script>
    <script src="assets/plugins/js/swiper-bundle.min.js"></script>
    <script src="assets/plugins/js/magnific-popup.min.js"></script>
    <script src="assets/plugins/js/jquery.counterup.min.js"></script>
    <script src="assets/plugins/js/waypoints.min.js"></script>
    <script src="assets/plugins/js/nice-select.min.js"></script>
    <script src="assets/plugins/js/backToTop.js"></script>
    <script src="assets/plugins/js/active.js"></script>
    <script src="assets/js/questions.js"></script>
    <script src="assets/js/clientScript.js"></script>
    <script src="assets/js/face-verification.js?v=<?php echo time(); ?>"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        const IS_LOGGED_IN = <?php echo json_encode(!empty($userId)); ?>;
        // Global function to start face verification
        function startFaceVerification(profileImageUrl) {
            showFaceVerification(profileImageUrl);
        }

        // Headphone detection with Korean labels
        async function detectHeadphones() {
            const labelEl = document.getElementById('headphoneLabel');
            const dotEl = document.getElementById('headphoneDot');
            const iconEl = document.getElementById('headphoneIcon');
            const containerEl = document.getElementById('headphoneIndicator');
            if (!labelEl || !dotEl || !iconEl) return;

            try {
                if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) {
                    labelEl.textContent = '알 수 없음';
                    dotEl.className = 'status-dot status-unknown';
                    iconEl.className = 'fa fa-question-circle';
                    if (containerEl) containerEl.title = '오디오 장치 상태: 알 수 없음';
                    return;
                }

                let devices = await navigator.mediaDevices.enumerateDevices();
                const labelsAvailable = devices.some(d => !!d.label);
                const audioOutputs = devices.filter(d => d.kind === 'audiooutput');

                // Resolve the actual default output device using groupId when possible
                const defaultStub = audioOutputs.find(d => d.deviceId === 'default');
                let actualDefault = defaultStub;
                if (defaultStub && defaultStub.groupId) {
                    const matchByGroup = audioOutputs.find(d => d.groupId === defaultStub.groupId && d.deviceId !== 'default');
                    if (matchByGroup) actualDefault = matchByGroup;
                }
                // Fallback to a single non-default output if no default stub
                if (!actualDefault && audioOutputs.length > 0) {
                    actualDefault = audioOutputs[0];
                }

                // Device label to classify
                const name = actualDefault ? (actualDefault.label || actualDefault.deviceId || '').toString() : '';

                // Strict classification: only flag headphones with explicit terms on the DEFAULT output
                const headphoneRegex = /(\bheadphones?\b|\bheadset\b|\bearbuds?\b|\bear\s?phones?\b|\bairpods?\b|\bbluetooth\b)/i;
                const speakersRegex = /(\bspeakers?\b|\bmonitor\b|display\s*audio|\bhdmi\b|\btv\b)/i;
                const isHeadphone = !!name && headphoneRegex.test(name);
                const isSpeakers = !!name && speakersRegex.test(name);

                // Update UI based on device type
                if (audioOutputs.length === 0) {
                    labelEl.textContent = '출력 없음';
                    dotEl.className = 'status-dot status-disconnected';
                    iconEl.className = 'fa fa-times-circle';
                    if (containerEl) containerEl.title = '오디오 출력 장치가 감지되지 않음';
                } else if (isHeadphone) {
                    labelEl.textContent = '헤드폰 연결됨';
                    dotEl.className = 'status-dot status-connected';
                    iconEl.className = 'fa fa-headphones';
                    if (containerEl) containerEl.title = name ? `기본 출력: ${name}` : '헤드폰이 감지됨';
                } else {
                    // Not headphones: prefer classifying as Speakers if label suggests, else Unknown/Output
                    if (labelsAvailable && isSpeakers) {
                        labelEl.textContent = '스피커';
                        dotEl.className = 'status-dot status-disconnected';
                        iconEl.className = 'fa fa-volume-up';
                        if (containerEl) containerEl.title = name ? `기본 출력: ${name}` : '출력: 스피커';
                    } else if (labelsAvailable && name) {
                        labelEl.textContent = '출력 장치';
                        dotEl.className = 'status-dot status-disconnected';
                        iconEl.className = 'fa fa-volume-up';
                        if (containerEl) containerEl.title = `기본 출력: ${name}`;
                    } else {
                        labelEl.textContent = '알 수 없음';
                        dotEl.className = 'status-dot status-unknown';
                        iconEl.className = 'fa fa-question-circle';
                        if (containerEl) containerEl.title = '알 수 없는 출력. 클릭하여 다시 확인 (마이크 권한 요청 가능)';
                    }
                }
                
                // Update device list
                updateDeviceList(audioOutputs, actualDefault);
                
            } catch (err) {
                console.warn('Headphone detection error:', err);
                labelEl.textContent = '알 수 없음';
                dotEl.className = 'status-dot status-unknown';
                iconEl.className = 'fa fa-question-circle';
                if (containerEl) containerEl.title = '오디오 장치 상태: 알 수 없음';
            }
        }

        // Update device list in dropdown
        function updateDeviceList(audioOutputs, currentDefault) {
            const deviceList = document.getElementById('deviceList');
            if (!deviceList) return;

            if (audioOutputs.length === 0) {
                deviceList.innerHTML = `
                    <div class="device-item">
                        <i class="fa fa-exclamation-triangle me-2 text-warning"></i>
                        사용 가능한 출력 장치가 없습니다
                    </div>
                `;
                return;
            }

            let html = '';
            audioOutputs.forEach(device => {
                const isDefault = device.deviceId === currentDefault?.deviceId || 
                                  (device.deviceId === 'default' && !currentDefault);
                const deviceName = device.label || `장치 ${device.deviceId.substring(0, 8)}...`;
                const icon = getDeviceIcon(deviceName);
                
                html += `
                    <div class="device-item ${isDefault ? 'active' : ''}" 
                         onclick="selectAudioDevice('${device.deviceId}', '${deviceName.replace(/'/g, '\\\'')}')"
                         data-device-id="${device.deviceId}">
                        <i class="fa ${icon} me-2"></i>
                        <span>${deviceName}</span>
                        ${isDefault ? '<i class="fa fa-check ms-auto text-success"></i>' : ''}
                    </div>
                `;
            });

            deviceList.innerHTML = html;
        }

        // Get appropriate icon for device type
        function getDeviceIcon(deviceName) {
            const name = deviceName.toLowerCase();
            if (name.includes('headphone') || name.includes('headset') || 
                name.includes('earbud') || name.includes('airpod') || 
                name.includes('bluetooth')) {
                return 'fa-headphones';
            } else if (name.includes('speaker') || name.includes('monitor') || 
                       name.includes('hdmi') || name.includes('display')) {
                return 'fa-volume-up';
            }
            return 'fa-volume-up';
        }

        // Toggle device selector dropdown
        function toggleDeviceSelector() {
            const selector = document.getElementById('deviceSelector');
            if (selector.classList.contains('d-none')) {
                selector.classList.remove('d-none');
                // Refresh device list when opening
                detectHeadphones();
            } else {
                selector.classList.add('d-none');
            }
        }

        // Select audio device
        async function selectAudioDevice(deviceId, deviceName) {
            try {
                // Note: There's no direct way to set the default audio output device via JavaScript
                // This function would typically integrate with a browser extension or native app
                console.log('Selected device:', deviceId, deviceName);
                
                // Show notification about device selection
                if (typeof Toastify !== 'undefined') {
                    Toastify({
                        text: `선택된 오디오 장치: ${deviceName}`,
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#28a745"
                    }).showToast();
                }

                // Try to use the device if possible (limited browser support)
                if ('setSinkId' in HTMLAudioElement.prototype) {
                    // This would work for audio elements, but not for system-wide audio
                    const audioElements = document.querySelectorAll('audio');
                    audioElements.forEach(audio => {
                        if (audio.setSinkId) {
                            audio.setSinkId(deviceId).catch(err => 
                                console.warn('Failed to set sink ID:', err)
                            );
                        }
                    });
                }

                // Close the selector
                toggleDeviceSelector();
                
                // Refresh detection to update UI
                setTimeout(() => detectHeadphones(), 500);
                
            } catch (error) {
                console.error('Error selecting audio device:', error);
                if (typeof Toastify !== 'undefined') {
                    Toastify({
                        text: "오디오 장치 변경 중 오류가 발생했습니다",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#dc3545"
                    }).showToast();
                }
            }
        }

        // Test microphone functionality
        async function testMicrophone() {
            const micDot = document.getElementById('micDot');
            const micLabel = document.getElementById('micLabel');
            
            if (!micDot || !micLabel) return;
            
            try {
                // Update UI to show testing
                micDot.className = 'status-dot status-unknown';
                micLabel.textContent = '테스트 중...';
                
                // Request microphone access
                const stream = await navigator.mediaDevices.getUserMedia({ 
                    audio: true 
                });
                
                // Test if microphone is working by checking audio levels
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const source = audioContext.createMediaStreamSource(stream);
                const analyser = audioContext.createAnalyser();
                
                source.connect(analyser);
                analyser.fftSize = 256;
                
                const bufferLength = analyser.frequencyBinCount;
                const dataArray = new Uint8Array(bufferLength);
                
                let testDuration = 0;
                const maxTestTime = 3000; // 3 seconds
                let hasSound = false;
                
                const checkAudio = () => {
                    analyser.getByteFrequencyData(dataArray);
                    
                    // Check for audio input (simple volume detection)
                    const average = dataArray.reduce((a, b) => a + b) / bufferLength;
                    
                    if (average > 10) { // Threshold for detecting sound
                        hasSound = true;
                    }
                    
                    testDuration += 100;
                    
                    if (testDuration < maxTestTime && !hasSound) {
                        setTimeout(checkAudio, 100);
                    } else {
                        // Stop the stream
                        stream.getTracks().forEach(track => track.stop());
                        audioContext.close();
                        
                        // Update UI based on results
                        if (hasSound) {
                            micDot.className = 'status-dot status-connected';
                            micLabel.textContent = '작동 중';
                            showNotification('마이크가 정상적으로 작동합니다', 'success');
                        } else {
                            micDot.className = 'status-dot status-disconnected';
                            micLabel.textContent = '음성 감지 안됨';
                            showNotification('마이크에서 음성이 감지되지 않습니다. 말씀해 주세요.', 'warning');
                        }
                    }
                };
                
                // Start testing after a short delay
                setTimeout(checkAudio, 500);
                
                showNotification('마이크 테스트 중입니다. 말씀해 주세요.', 'info');
                
            } catch (error) {
                console.error('Microphone test error:', error);
                micDot.className = 'status-dot status-disconnected';
                micLabel.textContent = '접근 거부됨';
                
                if (error.name === 'NotAllowedError') {
                    showNotification('마이크 접근이 거부되었습니다. 브라우저 설정을 확인해 주세요.', 'error');
                } else {
                    showNotification('마이크 테스트 중 오류가 발생했습니다.', 'error');
                }
            }
        }

        // Test sound functionality with Korean message
        function testSound() {
            try {
                // Korean test message
                const koreanMessage = "안녕하세요. EPS-TOPIK 시험의 음향 테스트입니다. 이 메시지가 명확하게 들리면 음향이 정상적으로 작동하고 있습니다. 시험 중에는 헤드폰을 착용하시기 바랍니다.";
                
                // Check if speech synthesis is supported
                if ('speechSynthesis' in window) {
                    // Cancel any ongoing speech
                    speechSynthesis.cancel();
                    
                    const utterance = new SpeechSynthesisUtterance(koreanMessage);
                    
                    // Set Korean language
                    utterance.lang = 'ko-KR';
                    utterance.rate = 0.9; // Slightly slower for clarity
                    utterance.pitch = 1.0;
                    utterance.volume = 1.0;
                    
                    // Set up event handlers
                    utterance.onstart = () => {
                        showNotification('음향 테스트가 시작되었습니다.', 'info');
                    };
                    
                    utterance.onend = () => {
                        showNotification('음향 테스트가 완료되었습니다.', 'success');
                    };
                    
                    utterance.onerror = (event) => {
                        console.error('Speech synthesis error:', event);
                        showNotification('음성 재생 중 오류가 발생했습니다.', 'error');
                    };
                    
                    // Speak the message
                    speechSynthesis.speak(utterance);
                    
                } else {
                    // Fallback: show text message
                    showNotification('브라우저에서 음성 재생을 지원하지 않습니다.', 'warning');
                    
                    // Create a simple beep sound as fallback
                    createBeepSound();
                }
                
            } catch (error) {
                console.error('Sound test error:', error);
                showNotification('음향 테스트 중 오류가 발생했습니다.', 'error');
            }
        }

        // Create a simple beep sound as fallback
        function createBeepSound() {
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
                oscillator.type = 'sine';
                
                gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
                
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.5);
                
                showNotification('테스트 음성이 재생되었습니다.', 'info');
                
            } catch (error) {
                console.error('Beep sound error:', error);
                showNotification('오디오를 재생할 수 없습니다.', 'error');
            }
        }

        // Helper function for notifications
        function showNotification(message, type = 'info') {
            if (typeof Toastify !== 'undefined') {
                const colors = {
                    'success': '#28a745',
                    'error': '#dc3545',
                    'warning': '#ffc107',
                    'info': '#17a2b8'
                };
                
                Toastify({
                    text: message,
                    duration: 4000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: colors[type] || colors['info'],
                    stopOnFocus: true
                }).showToast();
            }
        }

        function initHeadphoneDetection() {
            detectHeadphones();
            if (navigator.mediaDevices && 'ondevicechange' in navigator.mediaDevices) {
                navigator.mediaDevices.addEventListener('devicechange', () => {
                    detectHeadphones();
                });
            }
            
            // Close device selector when clicking outside
            document.addEventListener('click', (e) => {
                const selector = document.getElementById('deviceSelector');
                const indicator = document.getElementById('headphoneIndicator');
                if (selector && !selector.classList.contains('d-none') && 
                    !indicator.contains(e.target) && !selector.contains(e.target)) {
                    selector.classList.add('d-none');
                }
            });
        }

        // Handle confirm click — if logged in run verification, else skip to brightness
        function handleConfirmClick(profileImageUrl) {
            try {
                sessionStorage.setItem('faceVerificationStep', 'now');
                sessionStorage.setItem('faceVerificationPending', 'true');
            } catch (e) {}
            if (IS_LOGGED_IN) {
                startFaceVerification(profileImageUrl);
            } else {
                const url = new URL(window.location.href);
                url.searchParams.delete('sample');
                url.searchParams.set('av_brightness', 'true');
                window.location.href = url.toString();
            }
        }

        // Enhanced proceedToNotice function with verification step checking
        function proceedToNotice() {
            const currentUrl = new URL(window.location.href);
            const params = new URLSearchParams(currentUrl.search);
            
            // Replace current parameter with notice
            params.delete('sample');
            params.set('notice', 'true');
            
            const newUrl = `${currentUrl.pathname}?${params.toString()}`;
            window.location.href = newUrl;
        }

        // Function to check if face verification should be triggered at current step
        function checkFaceVerificationAtStep(step) {
            if (!IS_LOGGED_IN) return false;
            const selectedStep = sessionStorage.getItem('faceVerificationStep');
            const verificationPassed = sessionStorage.getItem('faceVerificationPassed') === 'true';
            const verificationPending = sessionStorage.getItem('faceVerificationPending') === 'true';
            
            if (selectedStep === step && !verificationPassed && verificationPending) {
                const profileImage = '<?= $profileImage ?>';
                
                // Show modal asking user to complete verification
                if (typeof Toastify !== 'undefined') {
                    Toastify({
                        text: "Face verification is required at this step. Click OK to proceed.",
                        duration: 5000,
                        gravity: "top",
                        position: "center",
                        backgroundColor: "#ff9500",
                        stopOnFocus: true,
                        onClick: function() {
                            startFaceVerification(profileImage);
                        }
                    }).showToast();
                }
                
                // Auto-start face verification after a short delay
                setTimeout(() => {
                    startFaceVerification(profileImage);
                }, 2000);
                
                return true;
            }
            
            return false;
        }

        // AV Wizard helpers
        // AV Wizard helpers (deprecated popup flow) — no-op
        function showAVSetupWizard() {}
        function hideAVSetupWizard() {}
        function avGoToStep() {}
        function completeAvWizard() {}

        // Wire AV step sliders + play button after DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            const bHeader = document.getElementById('brightnessRange');
            const vHeader = document.getElementById('volumeRange');
            const bStep = document.getElementById('avBrightnessRangeStep');
            const vStep = document.getElementById('avStepVolumeRange');
            const playBtn = document.getElementById('avStepPlayBtn');
            const audio = document.getElementById('avStepSampleAudio');
            const volText = document.getElementById('avStepVolumeText');
            let highlightTimer = null;
            let currentWordIndex = 0;
            let speaking = false;
            let paused = false;
            let currentUtterance = null;
            let wordStarts = [];
            if (volText) {
                // Convert text content into span-wrapped words while preserving spaces and line breaks
                const fragments = [];
                volText.childNodes.forEach(node => {
                    if (node.nodeType === Node.TEXT_NODE) {
                        const parts = node.textContent.split(/(\s+)/);
                        parts.forEach(p => {
                            if (p === '') return;
                            if (/\s+/.test(p)) fragments.push(p.replace(/\n/g, '<br>'));
                            else fragments.push(`<span class=\"avw\">${p}</span>`);
                        });
                    } else if (node.nodeType === Node.ELEMENT_NODE && node.tagName === 'BR') {
                        fragments.push('<br>');
                    } else if (node.nodeType === Node.ELEMENT_NODE) {
                        // unwrap inner text of elements like <span>, <strong>
                        const inner = node.textContent;
                        const parts = inner.split(/(\s+)/);
                        parts.forEach(p => {
                            if (p === '') return;
                            if (/\s+/.test(p)) fragments.push(p.replace(/\n/g, '<br>'));
                            else fragments.push(`<span class=\"avw\">${p}</span>`);
                        });
                    }
                });
                volText.innerHTML = fragments.join('');

                // Build word start indices over plain text for TTS boundary mapping
                const plain = volText.textContent;
                wordStarts = [];
                let m; const re = /[^\s]+/g;
                while ((m = re.exec(plain)) !== null) {
                    wordStarts.push(m.index);
                }
            }

            if (bStep) {
                // initialize from header/localStorage
                if (bHeader) bStep.value = bHeader.value;
                else if (localStorage.getItem('examBrightness')) bStep.value = localStorage.getItem('examBrightness');
                bStep.addEventListener('input', (e) => setBrightness(e.target.value));
            }
            if (vStep) {
                if (vHeader) vStep.value = vHeader.value;
                else if (localStorage.getItem('examVolume')) vStep.value = localStorage.getItem('examVolume');
                vStep.addEventListener('input', (e) => setVolume(e.target.value));
            }
            function resetHighlight() {
                currentWordIndex = 0;
                document.querySelectorAll('#avStepVolumeText .avw').forEach(el => el.classList.remove('reading-active'));
            }
            function stopTimerHighlight() { if (highlightTimer) { clearInterval(highlightTimer); highlightTimer = null; } }
            function startTimerHighlight() {
                resetHighlight();
                const wordEls = Array.from(document.querySelectorAll('#avStepVolumeText .avw'));
                if (wordEls.length === 0) return;
                const total = wordEls.length;
                const duration = Math.max(4, Math.min(12, Math.ceil(total / 2))); // ~2 w/s, clamp 4–12s
                const stepMs = Math.floor((duration * 1000) / total);
                highlightTimer = setInterval(() => {
                    if (currentWordIndex >= total) { stopTimerHighlight(); return; }
                    if (currentWordIndex > 0) wordEls[currentWordIndex - 1].classList.remove('reading-active');
                    wordEls[currentWordIndex].classList.add('reading-active');
                    currentWordIndex++;
                }, stepMs);
            }

            function speakWithHighlight(text, lang = 'ko-KR') {
                if (!('speechSynthesis' in window)) return false;
                try {
                    // Cancel any ongoing speech
                    window.speechSynthesis.cancel();
                    resetHighlight();
                    currentUtterance = new SpeechSynthesisUtterance(text);
                    currentUtterance.lang = lang;
                    currentUtterance.rate = 1.0;
                    currentUtterance.pitch = 1.0;
                    currentUtterance.volume = (typeof currentVolume === 'number' ? currentVolume : 50) / 100;
                    // Prefer Korean voice if available
                    const voices = window.speechSynthesis.getVoices();
                    const ko = voices.find(v => v.lang && v.lang.toLowerCase().startsWith('ko'));
                    if (ko) currentUtterance.voice = ko;

                    const label = document.getElementById('avStepVolumeInstructions');
                    currentUtterance.onstart = () => { speaking = true; paused = false; playBtn.textContent = '⏸'; label?.classList.add('reading-active'); };
                    currentUtterance.onend = () => { speaking = false; paused = false; playBtn.textContent = '▶'; label?.classList.remove('reading-active'); resetHighlight(); };
                    currentUtterance.onpause = () => { paused = true; };
                    currentUtterance.onresume = () => { paused = false; };
                    currentUtterance.onerror = () => { speaking = false; paused = false; playBtn.textContent = '▶'; label?.classList.remove('reading-active'); resetHighlight(); };

                    // Map boundary charIndex -> word index and highlight
                    let lastIdx = -1;
                    currentUtterance.onboundary = (e) => {
                        if (e.name !== 'word' && e.charIndex == null) return;
                        const idx = e.charIndex;
                        if (!Array.isArray(wordStarts) || wordStarts.length === 0) return;
                        // Advance currentWordIndex to the word whose start <= idx
                        while (currentWordIndex + 1 < wordStarts.length && wordStarts[currentWordIndex + 1] <= idx) {
                            const prevEl = document.querySelectorAll('#avStepVolumeText .avw')[currentWordIndex];
                            prevEl?.classList.remove('reading-active');
                            currentWordIndex++;
                        }
                        if (currentWordIndex !== lastIdx) {
                            const el = document.querySelectorAll('#avStepVolumeText .avw')[currentWordIndex];
                            if (el) {
                                document.querySelectorAll('#avStepVolumeText .avw').forEach(s => s.classList.remove('reading-active'));
                                el.classList.add('reading-active');
                                lastIdx = currentWordIndex;
                            }
                        }
                    };

                    window.speechSynthesis.speak(currentUtterance);
                    return true;
                } catch (_) { return false; }
            }

            if (playBtn && audio) {
                let isAudioPlaying = false;
                // ensure audio respects current volume
                audio.volume = (typeof currentVolume === 'number' ? currentVolume : 50) / 100;
                playBtn.addEventListener('click', async () => {
                    const label = document.getElementById('avStepVolumeInstructions');
                    try {
                        if (audio.paused || audio.ended || !isAudioPlaying) {
                            if (audio.ended) audio.currentTime = 0;
                            audio.volume = (typeof currentVolume === 'number' ? currentVolume : 50) / 100;
                            await audio.play();
                            isAudioPlaying = true;
                            playBtn.textContent = '⏸';
                            label?.classList.add('reading-active');
                            startTimerHighlight();
                        } else {
                            audio.pause();
                            isAudioPlaying = false;
                            playBtn.textContent = '▶';
                            label?.classList.remove('reading-active');
                            stopTimerHighlight();
                        }
                    } catch (_) { /* ignore */ }
                });
                audio.addEventListener('ended', () => {
                    isAudioPlaying = false;
                    playBtn.textContent = '▶';
                    document.getElementById('avStepVolumeInstructions')?.classList.remove('reading-active');
                    resetHighlight();
                    stopTimerHighlight();
                });
                audio.addEventListener('pause', () => { isAudioPlaying = false; stopTimerHighlight(); });
            }
        });

        // Enhanced face verification completion handler – now routes to AV steps
        window.proceedToNextStep = function() {
            console.log('🚀 Proceeding to next step after face verification');
            // Mark verification as completed
            sessionStorage.setItem('faceVerificationPassed', 'true');
            sessionStorage.setItem('faceVerificationPending', 'false');
            // Navigate to AV brightness step (skip notice)
            const url = new URL(window.location.href);
            url.searchParams.delete('sample');
            url.searchParams.delete('notice');
            url.searchParams.set('av_brightness', 'true');
            window.location.href = url.toString();
        }

        // Handle notice confirmation with face verification check
        function handleNoticeConfirm() {
            if (checkFaceVerificationAtStep('notice')) {
                return false; // Prevent default navigation
            }
            return true; // Allow default navigation
        }

        // Handle instructions ready - no longer needs face verification check
        function handleInstructionsReady(paperId, applicationNo, examId, sample) {
            // Proceed with original ready logic since face verification is no longer done at this step
            handleReadyClick(paperId, applicationNo, examId, sample);
        }


        function proceedToInstructions() {
            const currentUrl = new URL(window.location.href);
            const params = new URLSearchParams(currentUrl.search);
            
            params.delete('notice');
            params.set('instructions', 'true');
            
            const newUrl = `${currentUrl.pathname}?${params.toString()}`;
            window.location.href = newUrl;
        }

        function proceedToIdentificationCheck() {
            // Show identification check modal
            document.getElementById('prepare-content').classList.remove('d-none');
            document.getElementById('main-content').style.display = 'none';
        }

        // Brightness control functions
        let currentBrightness = 100;
        
        function setBrightness(value) {
            currentBrightness = parseInt(value);
            const brightnessOverlay = getOrCreateBrightnessOverlay();
            const opacity = (100 - currentBrightness) / 100 * 0.8; // Max 80% opacity
            brightnessOverlay.style.background = `rgba(0, 0, 0, ${opacity})`;
            
            // Update slider
            document.getElementById('brightnessRange').value = currentBrightness;
            
            // Save to localStorage
            localStorage.setItem('examBrightness', currentBrightness);
        }
        
        function adjustBrightness(change) {
            const newBrightness = Math.max(20, Math.min(100, currentBrightness + change));
            setBrightness(newBrightness);
        }
        
        function getOrCreateBrightnessOverlay() {
            let overlay = document.getElementById('brightness-overlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'brightness-overlay';
                overlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0);
                    pointer-events: none;
                    z-index: 9998;
                    transition: background 0.3s ease;
                `;
                document.body.appendChild(overlay);
            }
            return overlay;
        }
        
        // Volume control functions
        let currentVolume = 50;
        
        function setVolume(value, showNotification = false) {
            currentVolume = parseInt(value);
            
            // Update all audio elements
            const audioElements = document.querySelectorAll('audio');
            audioElements.forEach(audio => {
                audio.volume = currentVolume / 100;
            });
            
            // Update current playing audio from questions.js
            if (typeof updateAllAudioVolume === 'function') {
                updateAllAudioVolume(currentVolume);
            }
            
            // Update sliders (header + step)
            const headerSlider = document.getElementById('volumeRange');
            if (headerSlider) headerSlider.value = currentVolume;
            const stepSlider = document.getElementById('avStepVolumeRange');
            if (stepSlider) stepSlider.value = currentVolume;
            
            // Update volume icon and test sound button
            updateVolumeIcon();
            
            // Save to localStorage
            localStorage.setItem('examVolume', currentVolume);
            
            // Show notification only for manual adjustments
            if (showNotification) {
                showVolumeToast(`음량: ${currentVolume}%`);
            }
        }
        
        function updateVolumeIcon() {
            const volumeIcon = document.getElementById('volumeIcon');
            const testSoundBtn = document.getElementById('testSoundBtn');
            
            if (currentVolume === 0) {
                volumeIcon.className = 'fa fa-volume-off';
                if (testSoundBtn) testSoundBtn.style.display = 'none';
            } else if (currentVolume < 30) {
                volumeIcon.className = 'fa fa-volume-down';
                if (testSoundBtn) testSoundBtn.style.display = 'inline-block';
            } else {
                volumeIcon.className = 'fa fa-volume-up';
                if (testSoundBtn) testSoundBtn.style.display = 'inline-block';
            }
        }
        
        function adjustVolume(change) {
            const newVolume = Math.max(0, Math.min(100, currentVolume + change));
            setVolume(newVolume, true); // Show notification for manual adjustments
        }
        
        function playTestSound() {
            // Create a test audio element with a short beep sound
            const testAudio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSyBzvLYiTYEKILL8dqDMwUzgM3z2IoOhZiUnpqeNAUkbLny1YgaGClftN3wkDcDO3G39N2BQAR0u/PYhlcMAOOp7vCjPgM5drrz1X8LHDa21vLZdysELXy89dWCAikPdNH+3YcTBl1k1eTPj1oXCj++y/Hib0EAJY/U8daFjAUzf9HyVJ11aA==');
            testAudio.volume = currentVolume / 100;
            testAudio.play().catch(e => {
                // Fallback for browsers that don't support data URI audio
                showVolumeToast('테스트 소리를 재생할 수 없습니다');
            });
            
            // Show feedback
            showVolumeToast(`테스트 소리 재생 (음량: ${currentVolume}%)`);
        }
        
        function showVolumeToast(message) {
            if (typeof Toastify !== 'undefined') {
                Toastify({
                    text: message,
                    duration: 2000,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#17a2b8",
                    stopOnFocus: true
                }).showToast();
            }
        }
        
        // Font size control functions
        let currentFontScale = 1;
        
        function adjustFontSize(increase = true) {
            const change = increase ? 0.1 : -0.1;
            currentFontScale = Math.max(0.8, Math.min(1.4, currentFontScale + change));
            
            // Apply font size to content areas
            const contentElements = document.querySelectorAll('#question-container, .card, .list-unstyled, .form-range');
            contentElements.forEach(element => {
                element.style.fontSize = `${currentFontScale}rem`;
            });
            
            // Save to localStorage
            localStorage.setItem('examFontScale', currentFontScale);
        }
        
        // Initialize controls on page load
        function initializeControls() {
            // Load saved brightness
            const savedBrightness = localStorage.getItem('examBrightness');
            if (savedBrightness) {
                setBrightness(parseInt(savedBrightness));
            }
            
            // Load saved volume (no notification on page load)
            const savedVolume = localStorage.getItem('examVolume');
            if (savedVolume) {
                setVolume(parseInt(savedVolume), false); // false = no notification
            } else {
                // Set default volume and update icon
                updateVolumeIcon();
            }
            
            // Load saved font scale
            const savedFontScale = localStorage.getItem('examFontScale');
            if (savedFontScale) {
                currentFontScale = parseFloat(savedFontScale);
                adjustFontSize(true); // Apply the saved scale
                adjustFontSize(false); // Trigger the adjustment
            }
            
            // Bind font size controls
            document.getElementById('font-increase')?.addEventListener('click', () => adjustFontSize(true));
            document.getElementById('font-decrease')?.addEventListener('click', () => adjustFontSize(false));
            
            // Live header volume updates while dragging
            document.getElementById('volumeRange')?.addEventListener('input', (e) => setVolume(e.target.value, false));
            
            // Bind keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey) {
                    switch(e.key) {
                        case '=': // Ctrl + = for brightness up
                        case '+':
                            e.preventDefault();
                            adjustBrightness(10);
                            break;
                        case '-': // Ctrl + - for brightness down
                            e.preventDefault();
                            adjustBrightness(-10);
                            break;
                        case 'ArrowUp': // Ctrl + Arrow Up for volume up
                            e.preventDefault();
                            adjustVolume(10);
                            break;
                        case 'ArrowDown': // Ctrl + Arrow Down for volume down
                            e.preventDefault();
                            adjustVolume(-10);
                            break;
                    }
                }
            });
        }

        // Check face verification status on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize controls
            initializeControls();
            // Initialize headphone detection
            initHeadphoneDetection();
            // Sidebar tab switching
            (function initExamTabs(){
                const tabs = document.querySelectorAll('.exam-tab');
                tabs.forEach(tab => tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    const target = tab.getAttribute('data-target');
                    document.querySelectorAll('.exam-pane').forEach(p => p.classList.remove('show'));
                    const pane = document.querySelector(target);
                    if (pane) pane.classList.add('show');
                }));
            })();
            
            // Check which step we're on and handle face verification
            const urlParams = new URLSearchParams(window.location.search);
            
            if (urlParams.has('notice')) {
                if (IS_LOGGED_IN) {
                    checkFaceVerificationStatus();
                }
                // Check if face verification should be triggered at notice step
                if (IS_LOGGED_IN) setTimeout(() => checkFaceVerificationAtStep('notice'), 1000);
            } else if (urlParams.has('instructions')) {
                // Check if face verification should be triggered at instructions step
                if (IS_LOGGED_IN) setTimeout(() => checkFaceVerificationAtStep('instructions'), 1000);
            }
        });

        function checkFaceVerificationStatus() {
            const verificationPassed = sessionStorage.getItem('faceVerificationPassed') === 'true';
            const verificationScore = sessionStorage.getItem('faceVerificationScore');
            
            if (!verificationPassed) {
                // Redirect back to initial page if verification not completed
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('notice');
                currentUrl.searchParams.set('sample', 'true');
                
                Toastify({
                    text: "Face verification must be completed before proceeding to notice.",
                    duration: 5000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#dc3545",
                    stopOnFocus: true
                }).showToast();
                
                setTimeout(() => {
                    window.location.href = currentUrl.toString();
                }, 2000);
                return;
            }
            
            // Update verification score display
            const scoreDisplay = document.getElementById('verificationScoreDisplay');
            if (scoreDisplay && verificationScore) {
                scoreDisplay.textContent = parseFloat(verificationScore).toFixed(1);
            }
        }

        // Function to proceed to next step after successful face verification (final override)
        window.proceedToNextStep = function() {
            console.log('🚀 Proceeding to next step after face verification');
            sessionStorage.setItem('faceVerificationPassed', 'true');
            sessionStorage.setItem('faceVerificationPending', 'false');
            const url = new URL(window.location.href);
            url.searchParams.delete('sample');
            url.searchParams.delete('notice');
            url.searchParams.set('av_brightness', 'true');
            window.location.href = url.toString();
        };
    </script>
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
        
        // Handle ready button click
        function handleReadyClick(paper_id, application_no, exam_id, sample) {
            const urlParams = new URLSearchParams(window.location.search);
            const startTime = urlParams.get("start_time");
            const examDate = urlParams.get("exam_date");
            
            // If there's a scheduled start time, don't allow starting early
            if (startTime && examDate) {
                const now = new Date();
                const [year, month, day] = examDate.split("-").map(Number);
                const [startHours, startMinutes, startSeconds] = startTime.split(":").map(Number);
                const startTimeDate = new Date(year, month - 1, day, startHours, startMinutes, startSeconds);
                
                if (now < startTimeDate) {
                    showToast('error', 'Please wait for the scheduled exam start time');
                    return;
                }
            }
            
            // If no scheduled time or time has passed, start the exam
            // Ensure sample parameter has a valid value
            if (!sample || sample === '') {
                sample = 'true'; // Default to sample exam if not specified
            }
            
            prepareForPaper(paper_id, application_no, exam_id, sample);
        }
        
        // Handle fullscreen changes
        function handleFullscreenChange() {
            if (isMobileDevice()) {
                const isFullscreen = document.fullscreenElement || 
                                   document.webkitFullscreenElement || 
                                   document.mozFullScreenElement || 
                                   document.msFullscreenElement;
                
                if (isFullscreen) {
                    console.log('Entered fullscreen mode');
                    // Hide browser UI elements if any
                    setTimeout(() => {
                        window.scrollTo(0, 1);
                    }, 500);
                } else {
                    console.log('Exited fullscreen mode');
                }
            }
        }
        document.addEventListener("DOMContentLoaded", () => {
            // Also refresh headphone status after second phase init
            setTimeout(() => { try { detectHeadphones(); } catch(_) {} }, 500);
            const urlParams = new URLSearchParams(window.location.search);
            const startTime = urlParams.get("start_time"); // Start time in format HH:MM:SS
            const examDate = urlParams.get("exam_date"); // Exam date in format YYYY-MM-DD

            console.log("startTime: " + startTime);
            console.log("examDate: " + examDate);

            if (startTime && examDate) {
                const now = new Date();

                // Parse the exam date and start time into a combined Date object
                const [year, month, day] = examDate.split("-").map(Number);
                const [startHours, startMinutes, startSeconds] = startTime.split(":").map(Number);

                const startTimeDate = new Date(year, month - 1, day, startHours, startMinutes, startSeconds); // Month is 0-based
                console.log("startTimeDate: ", startTimeDate);
                console.log("now: ", now);

                if (now <= startTimeDate) {
                    // Show modal
                    const modal = document.getElementById("examModal");
                    const countdownElement = document.getElementById("countdown");

                    modal.classList.remove("d-none");
                    modal.classList.add("d-block");
                    document.body.style.overflow = "hidden";

                    const interval = setInterval(() => {
                        const now = new Date();
                        const diff = startTimeDate - now;

                        if (diff <= 0) {
                            clearInterval(interval);
                            modal.classList.remove("d-block");
                            modal.classList.add("d-none");
                            document.body.style.overflow = "auto";
                            countdownElement.textContent = "Exam has started!";

                            // Get the parameters from the URL
                            const urlParams = new URLSearchParams(window.location.search);
                            const paperId = urlParams.get('paper_id');
                            const sample = urlParams.get('sample') || 'true'; // Default to 'true' if not specified
                            const examId = urlParams.get('exam_id') || '';
                            const applicationNo = '<?= $application_no ?>';

                            console.log('Starting exam with parameters:', {
                                paperId: paperId,
                                sample: sample,
                                examId: examId,
                                applicationNo: applicationNo,
                                currentURL: window.location.href
                            });

                            // Call prepareForPaper function to start the exam
                            if (paperId && applicationNo && sample) {
                                console.log('Calling prepareForPaper...');
                                prepareForPaper(paperId, applicationNo, examId, sample);
                            } else {
                                console.error('Missing required parameters to start exam', {
                                    paperId: paperId,
                                    applicationNo: applicationNo,
                                    sample: sample,
                                    missingPaperId: !paperId,
                                    missingApplicationNo: !applicationNo,
                                    missingSample: sample === null
                                });
                                showToast('error', 'Missing required parameters to start exam');
                                setTimeout(() => {
                                    window.location = "index";
                                }, 2000);
                            }

                        } else {
                            // Calculate days, hours, minutes, and seconds
                            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                            // Update countdown display
                            countdownElement.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                        }
                    }, 1000);
                } else {
                    console.log("The exam time and date have already passed.");
                }
            }

        });
    </script>
</body>

</html>