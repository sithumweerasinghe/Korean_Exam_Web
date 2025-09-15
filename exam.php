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
    <style>
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #555;
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
        
        /* Fullscreen mobile styles */
        @media (max-width: 767px) and (orientation: landscape) {
            body:-webkit-full-screen {
                width: 100vw;
                height: 100vh;
            }
            body:-moz-full-screen {
                width: 100vw;
                height: 100vh;
            }
            body:fullscreen {
                width: 100vw;
                height: 100vh;
            }
            #entire-section {
                height: 100vh !important;
            }
            .row.shadow-sm {
                height: 60px !important;
            }
            #exam-content {
                height: calc(100vh - 60px) !important;
            }
            #main-content {
                height: calc(100vh - 60px) !important;
            }
        }
    </style>
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
                    <div class="row shadow-sm" style="height: 120px;">
                        <div class="col-12 py-1 border-bottom px-4 d-flex align-items-center position-relative" style="background:  #e8ffed; ">
                            <h5 class="mb-0">EPS-TOPIK</h5>
                            <span class="fs-5 text-black fw-medium position-absolute start-50 translate-middle-x">Test of proficiency in Korean</span>
                            <div class="border rounded-2 px-2 py-2 d-flex align-items-center ms-auto" style="box-shadow: inset 0px 2px 4px rgba(0, 0, 0, 0.2);">
                                <div class="d-flex align-items-center me-3">
                                    <span class="bg-dark px-2 py-1 text-white rounded-2 me-2" style="font-size: 11px;">Application No</span>
                                    <span class="fw-semibold" style="font-size: 12px;"><?= $application_no ?></span>
                                </div>
                                <!-- <div class="d-flex align-items-center pe-2">
                                    <span class="bg-dark px-2 py-1 text-white rounded-2 me-2" style="font-size: 11px;">Seat No</span>
                                    <span class="fw-semibold" style="font-size: 12px;">17</span>
                                </div> -->
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end align-items-center" style="background:  #e8ffed">
                            <div class="me-3 py-2 d-flex align-items-center">
                                <button class="exam-button py-1 px-3 rounded-4 me-2">A+</button>
                                <button class="exam-button rounded-4 py-1 px-3 me-3">A-</button>
                                <span class="me-2">음량조절:</span>
                                <div class="d-flex align-items-center">
                                    <button class="bg-dark text-white rounded-circle" style="width: 25px; height: 25px" onclick="adjustVolume(-1)">-</button>
                                    <input type="range" class="form-range " id="customRange1" style="width: 150px;">
                                    <button class="rounded-circle text-white bg-dark" style="width: 25px; height: 25px" onclick="adjustVolume(1)">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Header End -->

                    <div class="col-12 d-flex align-items-center justify-content-center" id="main-content" style="height: calc(100vh - 155px);">
                        <div class="row">
                            <?php
                            if (!isset($_GET['start_exam'])) {
                            ?>
                                <div class="">
                                    <div class="">
                                        <!-- Enter Ticket Start-->
                                        <?php
                                        if ((isset($_GET["paper_id"]) && isset($_GET["sample"]) && count($_GET) === 2)) {
                                        ?>
                                            <div class="card shadow mt-4" style="border-radius: 12px;  width: 100%; max-height:  calc(100vh - 300px); overflow-y: auto;">
                                                <div class="p-3 text-white text-center" style="background-color: #2ca347; font-weight: 600; font-size: 19px;">
                                                    Information Check of Applicant
                                                </div>
                                                <div class="p-3 text-white bg-dark text-center d-flex justify-content-center align-items-center">
                                                    <i class="fa fa-volume-up me-3 fs-2 text-white "></i>Check your application and if there is no problem, click the confirm button.
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

                                            <div class="ed-hero__btn text-center mb-4">
                                                <a href="exam?<?= $_SERVER['QUERY_STRING'] ?>&notice" class="ed-btn">Confirm<i
                                                        class="fi fi-rr-arrow-small-right"></i></a>
                                            </div>
                                        <?php
                                        }
                                        ?>

                                        <!-- Enter Ticket End -->

                                        <!-- Notice Start -->
                                        <?php
                                        if (isset($_GET["notice"])) {
                                        ?>
                                            <div class="card shadow offset-2 col-8 mt-4" style="border-radius: 12px;  max-height:  calc(100vh - 300px); overflow-y: auto;">
                                                <div class="p-3 text-white text-center" style="background-color: #2ca347; font-weight: 600; font-size: 19px;">
                                                    Notice of Applicant
                                                </div>
                                                <div class="p-3 text-white bg-dark text-center d-flex justify-content-center align-items-center">
                                                    <i class="fa fa-volume-up me-3 fs-2 text-white "></i>After being fully aware of applicant notice below, click the [Confirm] button
                                                </div>
                                                <div class="bg-white p-5" style="max-height: 300px; overflow-y: auto;">
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
                                            <div class="ed-hero__btn text-center mb-4">
                                                <a href="exam?<?= str_replace('notice', 'instructions', $_SERVER['QUERY_STRING']) ?>" class="ed-btn">Confirm<i
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
                                            <div class="card shadow col-8 offset-2 mt-4" style="border-radius: 12px;  max-height: calc(100vh - 300px); overflow-y: auto">
                                                <div class="p-3 text-white text-center" style="background-color: #2ca347; font-weight: 600; font-size: 19px;">
                                                    Practice Test of Proficiency in Korea(CBT)
                                                </div>
                                                <div class="p-3 text-white bg-dark text-center d-flex justify-content-center align-items-center">
                                                    <i class="fa fa-volume-up  fs-2 text-white "></i>After clicking [Practice Test] button, proceed practice test and if there is nothing wrong, click [Ready] button.
                                                </div>
                                                <div class="bg-white p-5" style="max-height: 300px; overflow-y: auto;">
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
                                            <div class="ed-hero__btn text-center mb-4">
                                                <a onclick="prepareForPaper('<?= htmlspecialchars($_GET['paper_id'], ENT_QUOTES) ?>', 
                             '<?= htmlspecialchars($application_no, ENT_QUOTES) ?>', 
                             '<?= htmlspecialchars($_GET['exam_id'] ?? '', ENT_QUOTES) ?>',
                             '<?= htmlspecialchars($_GET['sample'] ?? '', ENT_QUOTES) ?>');" class="ed-btn">Ready<i
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
                            <div class="d-flex justify-content-center " style="width: 78%">
                                <!-- Questions Container Start -->
                                <div class="row align-items-center">
                                    <div id="quiz-container" class="col-lg-12 position-relative">
                                        <div id="question-container" class="bg-white card" style="width:700px; max-height: calc(100vh - 200px); overflow-y: auto; border: 1px solid #ddd;"></div>
                                    </div>
                                </div>
                                <!-- Questions Container End -->
                            </div>
                            <div class="px-4 py-3 text-center overflow-auto" style="background: #e8ffed; width: 22%; height: calc(100vh - 155px); overflow-y: auto;">
                                <button onclick="submitAnswers();" class="exam-button py-3 mb-3 px-3">Submit Answers ></button>
                                <div class="row" id="answer-sheet">
                                    <!-- Reading Start -->
                                    <div class="col-6">
                                        <div class="text-center text-white mb-2 p-2" style="background-color: #2ca347; border-radius:8px;">Reading</div>
                                        <div class="d-flex align-items-center border rounded-2 border-dark mb-3">
                                            <span class="bg-dark px-1 py-1 text-white rounded-2 me-2" style="font-size: 11px;">Remaining Time</span>
                                            <span id="reading-remaining" class="fw-semibold me-1" style="font-size: 12px;">04:59</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-3" style="height: 20px;">
                                            <span class=" px-1 py-1 fw-semibold rounded-2 me-1" style="font-size: 12px;"></span>
                                            <span class="fw-semibold" style="color:#2ca347; font-size: 15px;"></span>
                                        </div>
                                        <div class="row" id="reading-container"></div>
                                    </div>
                                    <!-- Reading End -->

                                    <!-- Listening Start -->
                                    <div class="col-6">
                                        <div class="text-center text-white mb-2 bg-dark p-2" style="border-radius: 8px;">Listening</div>
                                        <div class="d-flex align-items-center mb-3 border  rounded-2 border-success">
                                            <span class=" px-1 py-1 text-white rounded-2 me-1" style="font-size: 11px; background-color: #2ca347">Remaining Time</span>
                                            <span id="listening-remaining" class="fw-semibold me-2" style="font-size: 12px;">04:59</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-3" style="height: 20px;">
                                            <span class=" px-1 py-1 fw-semibold rounded-2 me-1" style="font-size: 12px;">Question Time: </span>
                                            <span id="listening-timer-single" class="fw-semibold" style="color:#2ca347; font-size: 15px;">00:00</span>
                                        </div>

                                        <div class="row" id="listening-container"></div>
                                    </div>
                                    <!-- Listening End -->
                                </div>
                                <div class="row mt-3 px-1 text-center">
                                    <button id="prev-btn" class="exam-button py-3 px-2 mx-2 col-5 rounded-2">
                                        < Previous</button>
                                            <button id="next-btn" class="exam-button py-2 px-2 mx-2 col-5 rounded-2">Next ></button>
                                </div>
                            </div>
                        </div>
                        <!-- Paper End -->

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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
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

                            const questions = JSON.parse(localStorage.getItem('examQuestions'));
                            const isSample = localStorage.getItem('isSample') === 'true';
                            const isExam = localStorage.getItem('isExam') === 'true';
                            const paperId = localStorage.getItem('paperId');
                            const examId = localStorage.getItem('examId');

                            var examContent = $('#exam-content');
                            $('#main-content').addClass('d-none');
                            examContent.removeClass('d-none')

                            loadQuestions(questions, isSample, isExam, paperId, examId);

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