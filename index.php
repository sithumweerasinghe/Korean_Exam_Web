<?php include("includes/lang/lang-check.php"); ?>
<!DOCTYPE html>
<html class="no-js" lang="ZXX">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description"
        content="Topik Sir | Korean language proficiency test and secure your employment abroad." />
    <meta name="keywords" content="Topik Sir | Korean language proficiency test and secure your employment abroad." />
    <meta name="author" content="Virul Nirmala Wickramasinghe" />
    <title>Topik Sir | Korean language proficiency test and secure your employment abroad.</title>
    <meta property="og:url" content="https://topiksir.com/">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Topik Sir">
    <meta property="og:image"
        content="https://opengraph.b-cdn.net/production/images/7ccbbf92-ba39-44c6-bac5-49bc09364734.png?token=09ysYKBiRQ9EiSFQ7w_cEVKlD1IsnFPaG-vPW7sP10U&height=630&width=1200&expires=33270101925">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="topiksir.com">
    <meta property="twitter:url" content="https://topiksir.com/">
    <meta name="twitter:title" content="Topik Sir">
    <meta name="twitter:description"
        content="Prepare for the EPS-TOPIK exam with our expert training program. Access study materials, mock tests, and personalized guidance to excel in your Korean language proficiency test and secure your employment abroad.">
    <meta name="twitter:image"
        content="https://opengraph.b-cdn.net/production/images/7ccbbf92-ba39-44c6-bac5-49bc09364734.png?token=09ysYKBiRQ9EiSFQ7w_cEVKlD1IsnFPaG-vPW7sP10U&height=630&width=1200&expires=33270101925">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <link rel="stylesheet" href="assets/plugins/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/animate.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/owl.carousel.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/swiper-bundle.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/maginific-popup.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/nice-select.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/icofont.css" />
    <link rel="stylesheet" href="assets/plugins/css/uicons.css" />
    <link rel="stylesheet" href="assets/plugins/css/carousel-custom.css" />
    <link rel="stylesheet" href="style.css" />

    <style>
        .flip-container {
            perspective: 1000px;
            display: inline-block;
            position: relative;
            width: 15px;
            text-align: center;
            font-size: 1.2rem;
        }

        .flip-digit {
            display: inline-block;
            transform-style: preserve-3d;
            transform-origin: 50% 50%;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .flip-digit.flip {
            transform: rotateX(-180deg);
        }

        .unit {
            display: inline-block;
            font-size: 1.2rem;
            vertical-align: middle;
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
    <div id="ed-mouse">
        <div id="cursor-ball"></div>
    </div>
    <?php
    $home = "active";
    $about = "";
    $contact = "";
    $papers = "";
    $leadboard = "";
    include("includes/header.php"); ?>
    <div id="smooth-wrapper">
        <div id="smooth-content">
            <main>
                <section class="ed-hero ed-hero--style4"
                    style="background-image: url('assets/images/section-bg-11.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                    <div class="ed-hero__image--style-4 left-img">
                        <img src="assets/images/home/koreanman.png" alt="hero-img-1" />
                    </div>
                    <div class="ed-hero__elements--style-2">
                        <img class="shape-1 element-move" src="assets/images/hero/home-4/elements-move/shape-1.svg"
                            alt="shape-1" />
                        <img class="shape-2 element-move" src="assets/images/hero/home-4/elements-move/shape-2.svg"
                            alt="shape-2" />
                        <img class="shape-3 element-move" src="assets/images/hero/home-4/elements-move/shape-3.svg"
                            alt="shape-3" />
                        <img class="shape-4 element-move" src="assets/images/hero/home-4/elements-move/shape-4.svg"
                            alt="shape-4" />
                    </div>
                    <div class="ed-hero__fixed-shape">
                        <img class="shape-1" src="assets/images/hero/home-4/shape-1.svg" alt="shape-1" />
                        <img class="shape-2 d-md-none " src="assets/images/hero/home-4/vector-1.svg" alt="vector-1" />
                        <img class="shape-3" src="assets/images/hero/home-4/shape-3.svg" alt="shape-3" />
                    </div>
                    <div class="container ed-container-expand ">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-lg-6 col-12 ">
                                <div class="ed-hero__content text-center py-5 logo-bg"
                                    style="z-index: -2; margin-top: 120px; padding-left: 15px; padding-right: 15px;">
                                    <span
                                        class="ed-hero__content-sm-title"><?php echo $translations['welcome_message']; ?></span>
                                    <h3 class="ed-hero__content-title left">
                                        <?php echo $translations['hero_title']; ?>
                                    </h3>
                                    <p class="ed-hero__content-text p-0">
                                        <?php echo $translations['hero_text']; ?>
                                    </p>
                                    <div class="ed-hero__btn">
                                        <a href="papers" class="ed-btn"><?php echo $translations['button_text']; ?><i
                                                class="fi fi-rr-arrow-small-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ed-hero__image--style-4 right-img">
                        <div class="ed-hero__image-main d-none d-md-block">
                            <img src="assets/images/home/koreangirl.png" alt="hero-img-2" />
                        </div>
                    </div>
                </section>
                <section class="section-gap">
                    <div class="container ed-container">
                        <p> <i class="fi fi-ro-square-right"></i></p>
                        <div class="container ed-container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="ed-partner__section-head">
                                        <h1 class="ed-partner__section-head-title fw-semibold fs-3">
                                            Most visited countries to Topik Sir
                                        </h1>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-10 ">
                                    <div class="owl-carousel ed-partner__slider py-3">
                                        <a href="#" target="_blank" class="ed-country__brand-logo">
                                            <img src="./assets/images/country-flags/Cambodia Icon.png"
                                                alt="brand-logo" />
                                            <p class="fw-medium" style="font-size: 13px;">Cambodia</p>
                                            <p class="fw-semibold" style="font-size: 12px; color: #2ca347">13+</p>
                                        </a>
                                        <a href="#" target="_blank" class="ed-country__brand-logo">
                                            <img src="./assets/images/country-flags/Indonesia Flag.png"
                                                alt="brand-logo" />
                                            <p class="fw-medium" style="font-size: 13px;">Indonesia</p>
                                            <p class="fw-semibold" style="font-size: 12px; color: #2ca347">11+</p>
                                        </a>
                                        <a href="#" target="_blank" class="ed-country__brand-logo">
                                            <img src="./assets/images/country-flags/Kazakhstan Icon.png"
                                                alt="brand-logo" />
                                            <p class="fw-medium" style="font-size: 13px;">Kazakhstan</p>
                                            <p class="fw-semibold" style="font-size: 12px; color: #2ca347">14+</p>
                                        </a>
                                        <a href="#" target="_blank" class="ed-country__brand-logo">
                                            <img src="./assets/images/country-flags/Kyrgyzstan Icon.png"
                                                alt="brand-logo" />
                                            <p class="fw-medium" style="font-size: 13px;">Kyrgyzstan</p>
                                            <p class="fw-semibold" style="font-size: 12px; color: #2ca347">21+</p>
                                        </a>
                                        <a href="#" target="_blank" class="ed-country__brand-logo">
                                            <img src="./assets/images/country-flags/Laos Icon.png" alt="brand-logo" />
                                            <p class="fw-medium" style="font-size: 13px;">Laos</p>
                                            <p class="fw-semibold" style="font-size: 12px; color: #2ca347">17+</p>
                                        </a>
                                        <a href="#" target="_blank" class="ed-country__brand-logo">
                                            <img src="./assets/images/country-flags/Mongolia Icon.png"
                                                alt="brand-logo" />
                                            <p class="fw-medium" style="font-size: 13px;">Mongolia</p>
                                            <p class="fw-semibold" style="font-size: 12px; color: #2ca347">15+</p>
                                        </a>
                                        <a href="#" target="_blank" class="ed-country__brand-logo">
                                            <img src="./assets/images/country-flags/Myanmar Icon.png"
                                                alt="brand-logo" />
                                            <p class="fw-medium" style="font-size: 13px;">Myanmar</p>
                                            <p class="fw-semibold" style="font-size: 12px; color: #2ca347">20+</p>
                                        </a>
                                        <a href="#" target="_blank" class="ed-country__brand-logo">
                                            <img src="./assets/images/country-flags/Nepal Icon.png" alt="brand-logo" />
                                            <p class="fw-medium" style="font-size: 13px;">Nepal</p>
                                            <p class="fw-semibold" style="font-size: 12px; color: #2ca347">19+</p>
                                        </a>
                                        <a href="#" target="_blank" class="ed-country__brand-logo">
                                            <img src="./assets/images/country-flags/Pakistan Icon.png"
                                                alt="brand-logo" />
                                            <p class="fw-medium" style="font-size: 13px;">Pakistan</p>
                                            <p class="fw-semibold" style="font-size: 12px; color: #2ca347">10+</p>
                                        </a>
                                        <a href="#" target="_blank" class="ed-country__brand-logo">
                                            <img src="./assets/images/country-flags/Philippines Icon.png"
                                                alt="brand-logo" />
                                            <p class="fw-medium" style="font-size: 13px;">Philippines</p>
                                            <p class="fw-semibold" style="font-size: 12px; color: #2ca347">13+</p>
                                        </a>
                                        <a href="#" target="_blank" class="ed-country__brand-logo">
                                            <img src="./assets/images/country-flags/SriLanka Icon.png"
                                                alt="brand-logo" />
                                            <p class="fw-medium" style="font-size: 13px;">Sri Lanka</p>
                                            <p class="fw-semibold" style="font-size: 12px; color: #2ca347">31+</p>
                                        </a>
                                        <a href="#" target="_blank" class="ed-country__brand-logo">
                                            <img src="./assets/images/country-flags/Tajikistan Icon.png"
                                                alt="brand-logo" />
                                            <p class="fw-medium" style="font-size: 13px;">Tajikistan</p>
                                            <p class="fw-semibold" style="font-size: 12px; color: #2ca347">15+</p>
                                        </a>
                                        <a href="#" target="_blank" class="ed-country__brand-logo">
                                            <img src="./assets/images/country-flags/Thailand Icon.png"
                                                alt="brand-logo" />
                                            <p class="fw-medium" style="font-size: 13px;">Thailand</p>
                                            <p class="fw-semibold" style="font-size: 12px; color: #2ca347">12+</p>
                                        </a>
                                        <a href="#" target="_blank" class="ed-country__brand-logo">
                                            <img src="./assets/images/country-flags/Uzbekistan Icon.png"
                                                alt="brand-logo" />
                                            <p class="fw-medium" style="font-size: 13px;">Uzbekistan</p>
                                            <p class="fw-semibold" style="font-size: 12px; color: #2ca347">19+</p>
                                        </a>
                                        <a href="#" target="_blank" class="ed-country__brand-logo">
                                            <img src="./assets/images/country-flags/Vietnam Icon.png"
                                                alt="brand-logo" />
                                            <p class="fw-medium" style="font-size: 13px;">Vietnam</p>
                                            <p class="fw-semibold" style="font-size: 12px; color: #2ca347">20+</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>
                <section
                    class="ed-why-choose mg-top-70 ed-why-choose--style3 ed-why-choose--style4 section-gap position-relative pt-0"
                    style="margin-top: 100px">
                    <div class="container ed-container mg-top-70">
                        <div class="row ">
                            <div class="col-lg-6 col-12">
                                <div class="ed-w-choose__images ed-w-choose__images--style3 position-relative">
                                    <div class="ed-w-choose__main-img--style2 position-relative">
                                        <img class="why-choose-img-1"
                                            src="assets/images/why-choose/why-choose-4/img-1.jpg"
                                            alt="why-choose-img-1" />
                                        <img class="why-choose-img-2"
                                            src="assets/images/why-choose/why-choose-4/img-2.jpeg"
                                            alt="why-choose-img-2" />
                                    </div>
                                    <div class="counter-card updown-ani">
                                        <div class="counter-card__icon">
                                            <i class="fi fi-rr-graduation-cap"></i>
                                        </div>
                                        <div class="counter-card__info">
                                            <h4><span class="counter">3458</span>+</h4>
                                            <p><?php echo $translations['connected_people']; ?></p>
                                        </div>
                                    </div>
                                    <div class="ed-w-choose__shapes">
                                        <img class="ed-w-choose__shape-1 rotate-ani"
                                            src="assets/images/why-choose/why-choose-3/shape-1.svg" alt="shape-1" />
                                        <img class="ed-w-choose__shape-2"
                                            src="assets/images/why-choose/why-choose-3/shape-2.svg" alt="pattern-2" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="ed-w-choose__content">
                                    <div class="ed-section-head">
                                        <span
                                            class="ed-section-head__sm-title"><?php echo $translations['why_choose_subtitle']; ?></span>
                                        <h3 class="ed-section-head__title left">
                                            <?php echo $translations['why_choose_title']; ?>
                                        </h3>
                                        <p class="ed-section-head__text"><?php echo $translations['why_choose_desc']; ?>
                                        </p>
                                    </div>
                                    <div class="ed-w-choose__info">
                                        <div class="ed-w-choose__info-single">
                                            <div class="ed-w-choose__info-head">
                                                <div class="ed-w-choose__info-icon bg-1">
                                                    <img src="assets/images/why-choose/why-choose-1/icon-1.svg"
                                                        alt="icon" />
                                                </div>
                                                <h5><?php echo $translations['how_we_help_first']; ?></h5>
                                            </div>
                                            <div class="ed-w-choose__info-bottom">
                                                <p><?php echo $translations['how_we_help_first_desc']; ?></p>
                                            </div>
                                        </div>
                                        <div class="ed-w-choose__info-single">
                                            <div class="ed-w-choose__info-head">
                                                <div class="ed-w-choose__info-icon bg-2">
                                                    <img src="assets/images/why-choose/why-choose-1/icon-2.svg"
                                                        alt="icon" />
                                                </div>
                                                <h5><?php echo $translations['how_we_help_second']; ?></h5>
                                            </div>
                                            <div class="ed-w-choose__info-bottom">
                                                <p><?php echo $translations['how_we_help_second_desc']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <input type="hidden" id="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                <section class="ed-course section-gap section-bg background-image position-relative "
                    style="background-image: url('assets/images/section-bg-12.png'); height: auto">
                    <div class="container ed-container">
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-md-8 col-12">
                                <div class="ed-section-head text-center">
                                    <span
                                        class="ed-section-head__sm-title"><?= $translations['exam_schedule']; ?></span>
                                    <h3 class="ed-section-head__title  left">
                                        <?= $translations['choose_time']; ?>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <?php
                        include("api/client/services/examTimetable.php");
                        $examTimetableService = new ExamTimetableService();
                        $exams = $examTimetableService->getAllExams();
                        ?>
                        <div class="row">
                            <?php
                            if (!$exams) {
                                ?>
                                <p class="text-center fs-4">No exams</p>
                                <?php
                            } else {
                                $index = 0;
                                $exam_count = count($exams);
                                $images = ["image-1.webp", "image-2.webp", "image-3.webp"];
                                foreach ($exams as $exam) {
                                    $start = new DateTime($exam["start_time"]);
                                    $end = new DateTime($exam["end_time"]);
                                    $hour = (int) $start->format("H");
                                    $hasExam = $examTimetableService->getUserHasExam($exam["id"]);
                                    $isPaid = $examTimetableService->getUserPaidExam($exam["id"]);

                                    $start_formatted = $start->format("h:i A");
                                    $end_formatted = $end->format("h:i A");
                                    if ($hour >= 6 && $hour < 12) {
                                        $time_period = $translations['morning'];
                                        ;
                                    } elseif ($hour >= 12 && $hour < 18) {
                                        $time_period = $translations['afternoon'];
                                        ;
                                    } else {
                                        $time_period = $translations['evening'];
                                        ;
                                    }

                                    $offset_classes = '';
                                    if ($exam_count % 2 != 0 && $index != 1) {
                                        $offset_classes = 'offset-0 offset-md-3 offset-lg-3';
                                    }

                                    $startTime = $exam["start_time"];
                                    $now = new DateTime();
                                    list($startHours, $startMinutes, $startSeconds) = explode(":", $startTime);
                                    $startTimeDate = new DateTime();
                                    $startTimeDate->setTime($startHours, $startMinutes, $startSeconds);

                                    $image = $images[$index % count($images)];

                                    if ($exam["exam_date"] == date('Y-m-d') && $now > $startTimeDate) {
                                        continue;
                                    }

                                    ?>

                                    <div class="col-lg-6 col-xl-6 col-md-6 col-12">
                                        <div class="ed-course__card ed-course__card--style2 wow fadeInUp" data-wow-delay=".3s"
                                            data-wow-duration="1s">
                                            <div class="ed-course__head position-relative">
                                                <a href="#" class="ed-course__img">
                                                    <img src="./assets/images/timetable/<?= $image ?>" alt="course-img" />
                                                </a>
                                            </div>
                                            <div class="ed-course__body">
                                                <a href="#" class="ed-course__title">
                                                    <h5>
                                                        <?= $translations['exam_count']; ?>
                                                        <?= $index + 1 ?>
                                                    </h5>
                                                    <p class="fw-medium mb-1"><?= $exam["exam_date"] ?></p>
                                                </a>
                                                <div class="ed-course__rattings">
                                                    <span><?= $time_period ?>         <?= $translations['exam_time_period']; ?>
                                                        <br /><b><?= htmlspecialchars($start_formatted) ?> -
                                                            <?= htmlspecialchars($end_formatted) ?> </b> </span>
                                                </div>
                                                <div class="col-12">
                                                    <div class="ed-section-bottom-btn" style="margin-top: 15px;">
                                                        <?php

                                                        ?>
                                                        <input type="text" id="exam_date_<?= $exam['id'] ?>" value="<?= $exam["exam_date"] ?>"
                                                            hidden>
                                                        <input type="text" id="start_time_<?= $exam['id'] ?>" value="<?= $exam["start_time"] ?>"
                                                            hidden>

                                                        <div id="countdown-<?= $exam['id'] ?>" class="countdown mb-3"
                                                            data-start-time="<?= $exam["start_time"] ?>"
                                                            data-date="<?= $exam["exam_date"] ?>">
                                                            <div
                                                                class="col-12 d-flex justify-content-center align-items-center">
                                                                <div class="time-unit d-flex py-3 px-1 text-white me-1 rounded"
                                                                    style="background-color:#2ca347;">
                                                                    <div class="flip-container"><span
                                                                            class="flip-digit">0</span></div>
                                                                    <div class="flip-container"><span
                                                                            class="flip-digit">0</span></div>
                                                                    <span class="unit">d</span>
                                                                </div>
                                                                :
                                                                <div class="time-unit d-flex py-3 px-1 text-white mx-1 rounded"
                                                                    style="background-color:#2ca347;">
                                                                    <div class="flip-container"><span
                                                                            class="flip-digit">0</span></div>
                                                                    <div class="flip-container"><span
                                                                            class="flip-digit">0</span></div>
                                                                    <span class="unit">h</span>
                                                                </div>
                                                                :
                                                                <div class="time-unit d-flex py-3 px-1 text-white mx-1 rounded"
                                                                    style="background-color:#2ca347;">
                                                                    <div class="flip-container"><span
                                                                            class="flip-digit">0</span></div>
                                                                    <div class="flip-container"><span
                                                                            class="flip-digit">0</span></div>
                                                                    <span class="unit">m</span>
                                                                </div>
                                                                :
                                                                <div class="time-unit d-flex py-3 px-1 text-white ms-1 rounded"
                                                                    style="background-color:#2ca347;">
                                                                    <div class="flip-container"><span
                                                                            class="flip-digit">0</span></div>
                                                                    <div class="flip-container"><span
                                                                            class="flip-digit">0</span></div>
                                                                    <span class="unit">s</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <button
                                                            onclick="doExam('<?= htmlspecialchars($hasExam ? 'true' : 'false', ENT_QUOTES) ?>', '<?= htmlspecialchars($isPaid ? 'true' : 'false', ENT_QUOTES) ?>')"
                                                            class="exam-button ed-btn"
                                                            data-paper-id="<?= $exam["papers_paper_id"] ?>"
                                                            data-exam-id="<?= $exam["id"] ?>"><?= $translations['exam_button']; ?><i
                                                                class="fi fi-rr-arrow-small-right"></i></button>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $index++;
                                }
                            }

                            ?>
                        </div>
                    </div>
                </section>
                <section class="ed-features position-relative">
                    <div class="ed-category__shapes">
                        <img class="ed-category__shape-1 updown-ani" src="assets/images/features/features-1/shape-1.svg"
                            alt="shape-1" />
                        <img class="ed-category__shape-2 rotate-ani" src="assets/images/features/features-1/shape-2.svg"
                            alt="shape-2" />
                    </div>
                    <div class="container ed-container">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="ed-features__card wow fadeInUp" data-wow-delay=".3s" data-wow-duration="1s">
                                    <div class="ed-features__icon icon-bg bg-1">
                                        <img src="assets/images/features/features-1/1.svg" alt="icon" />
                                    </div>
                                    <div class="ed-features__info">
                                        <h4><?= $translations['features']['ranking']['title']; ?></h4>
                                        <p>
                                            <?= $translations['features']['ranking']['description']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="ed-features__card wow fadeInUp" data-wow-delay=".5s" data-wow-duration="1s">
                                    <div class="ed-features__icon icon-bg bg-2">
                                        <img src="assets/images/features/features-1/2.svg" alt="icon" />
                                    </div>
                                    <div class="ed-features__info">
                                        <h4><?= $translations['features']['practice_center']['title']; ?></h4>
                                        <p>
                                            <?= $translations['features']['practice_center']['description']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="ed-features__card wow fadeInUp" data-wow-delay=".7s" data-wow-duration="1s">
                                    <div class="ed-features__icon icon-bg bg-3">
                                        <img src="assets/images/features/features-1/3.svg" alt="icon" />
                                    </div>
                                    <div class="ed-features__info">
                                        <h4><?= $translations['features']['goal_oriented_education']['title']; ?></h4>
                                        <p>
                                            <?= $translations['features']['goal_oriented_education']['description']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="ed-video home-4 ed-video--style3 background-image section-gap"
                    style="background-image: url('assets/images/video/video-1/bg-img.jpg');">
                    <div class="container ed-container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7 col-md-8 col-12 wow fadeInUp" data-wow-delay=".3s"
                                data-wow-duration="1s">
                                <div class="ed-video__inner">
                                    <div class="ed-video__icon">
                                        <div class="ripple-wrapper">
                                            <div class="circles">
                                                <div class="circle1"></div>
                                                <div class="circle2"></div>
                                                <div class="circle3"></div>
                                            </div>
                                            <a href="https://www.youtube.com/watch?v=yAtu4iD0xo4"
                                                class="ed-video__btn popup-video ed-hover-layer-2">
                                                <img src="assets/images/icons/icon-play-blue.svg" alt="play-icon" />
                                            </a>
                                        </div>
                                    </div>
                                    <div class="ed-video__content">
                                        <span><?= $translations['video_tours']['title']; ?></span>
                                        <h3><?= $translations['video_tours']['description']; ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="ed-partner section-gap cover"
                    style="background-image: url('assets/images/section-bg-12.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                    <div class=" container ed-container">
                        <div class="row">
                            <div class="col-12">
                                <div class="ed-partner__section-head">
                                    <h4 class=""><?= $translations['other_service']; ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-between">
                            <div class=" partner-bg ">
                                <a target="_blank" class="ed-parnet__brand-logo d-block text-center">
                                    <img width="100px" src="./assets/images/partner/Kogo.png" alt="brand-logo" />
                                    <p class="fw-medium fs-4 pt-2 pb-3" style="color:#2ca347">
                                        <?= $translations['partners']['kogo']['title']; ?>
                                    </p>
                                    <p style="height: 150px"><?= $translations['partners']['kogo']['description']; ?>
                                    </p>
                                    <button onclick="window.location = 'https://kogo.lk/'"
                                        class="visit-btn">Visit</button>
                                </a>
                            </div>
                            <div class=" partner-bg ">
                                <a target="_blank" class="ed-parnet__brand-logo d-block text-center">
                                    <img width="150px" src="./assets/images/partner/koreansir.png" alt="brand-logo" />
                                    <p class="fw-medium fs-4 pt-2 pb-3" style="color:#2ca347">
                                        <?= $translations['partners']['korean_sir']['title']; ?>
                                    </p>
                                    <p style="height: 150px">
                                        <?= $translations['partners']['korean_sir']['description']; ?>
                                    </p>
                                    <button onclick="window.location = 'https://koreansir.lk/'"
                                        class="visit-btn">Visit</button>
                                </a>
                            </div>
                            <div class=" partner-bg ">
                                <a target="_blank" class="ed-parnet__brand-logo d-block text-center">
                                    <img width="100px" src="./assets/images/partner/wisby.png" alt="brand-logo" />
                                    <p class="fw-medium fs-4 pt-2 pb-3" style="color:#2ca347">
                                        <?= $translations['partners']['wisby']['title']; ?>
                                    </p>
                                    <p style="height: 150px"><?= $translations['partners']['wisby']['description']; ?>
                                    </p>
                                    <button onclick="window.location = 'https://wisby.lk/'"
                                        class="visit-btn">Visit</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="ed-faq section-gap position-relative">
                    <div class="container ed-container">
                        <div class="ed-faq__inner position-relative">
                            <div class="row align-items-center">
                                <div class="col-lg-12 col-xl-6 col-12">
                                    <div class="ed-faq__images position-relative">
                                        <div class="ed-faq__images-group">
                                            <div class="ed-faq__image-group-1">
                                                <img class="faq-img-1" src="./assets/images/faq/faq-1/faq-image-4.webp"
                                                    alt="faq-img-1" />
                                            </div>
                                            <div class="ed-faq__image-group-2">
                                                <img class="faq-img-2" src="./assets/images/faq/faq-1/faq-image-2.webp"
                                                    alt="faq-img-2" />
                                                <img class="faq-img-3" src="./assets/images/faq/faq-1/faq-image-1.webp"
                                                    alt="faq-img-2" />
                                            </div>
                                        </div>
                                        <div class="ed-faq__shapes">
                                            <img class="ed-faq__shape-1" src="assets/images/faq/faq-1/shape-1.svg"
                                                alt="shape-1" />
                                            <img class="ed-faq__shape-2" src="assets/images/faq/faq-1/shape-2.svg"
                                                alt="shape-2" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6 col-12">
                                    <div class="ed-faq__content">
                                        <div class="ed-section-head m-0">
                                            <span
                                                class="ed-section-head__sm-title"><?= $translations['faq']['section_title']; ?></span>
                                            <h3 class="ed-section-head__title right">
                                                <?= $translations['faq']['main_title']; ?>
                                            </h3>
                                        </div>
                                        <div class="ed-faq__accordion faq-inner accordion" id="accordionExample">
                                            <div class="ed-faq__accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                        aria-expanded="true" aria-controls="collapseOne">
                                                        <?= $translations['faq']['questions'][0]['question']; ?>
                                                    </button>
                                                </h2>
                                                <div id="collapseOne" class="accordion-collapse collapse show"
                                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="ed-faq__accordion-body">
                                                        <p class="ed-faq__accordion-text">
                                                            <?= $translations['faq']['questions'][0]['answer']; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ed-faq__accordion-item">
                                                <h2 class="accordion-header" id="headingTwo">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                        aria-expanded="false" aria-controls="collapseTwo">
                                                        <?= $translations['faq']['questions'][1]['question']; ?>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwo" class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                    <div class="ed-faq__accordion-body">
                                                        <p class="ed-faq__accordion-text">
                                                            <?= $translations['faq']['questions'][1]['answer']; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ed-faq__accordion-item">
                                                <h2 class="accordion-header" id="headingThree">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                        aria-expanded="false" aria-controls="collapseThree">
                                                        <?= $translations['faq']['questions'][2]['question']; ?>
                                                    </button>
                                                </h2>
                                                <div id="collapseThree" class="accordion-collapse collapse"
                                                    aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                                    <div class="ed-faq__accordion-body">
                                                        <p class="ed-faq__accordion-text">
                                                            <?= $translations['faq']['questions'][2]['answer']; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <?php
                if ($lang == "si") {
                    ?>
                    <div class="section-bg background-image"
                        style="background-image: url('assets/images/section-bg-3.png');">
                        <div class="section-bg background-image"
                            style="background-image: url('assets/images/section-bg-3.png');">
                            <section class="ed-blog section-gap">
                                <div class="container ed-container">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-6 col-md-8 col-12">
                                            <div class="ed-section-head text-center">
                                                <span class="ed-section-head__sm-title">කොරියාව ගැන පුවත්</span>
                                                <h3 class="ed-section-head__title left">
                                                    කොරියානු පුවත්
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex col-12 justify-content-end align-items-center">
                                        <ul class="ed-footer__widget-links">
                                            <li>
                                                <a href="news-feed" class="hover-text-success">පුවත් සංග්‍රහයට යන්න <i
                                                        class="fi fi-rr-arrow-small-right"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <?php
                                    include("api/client/services/NewsService.php");
                                    $clientNewsService = new ClientNewsService();
                                    $validNews = $clientNewsService->getValidNews();
                                    $newsCount = count($validNews);
                                    ?>
                                    <?php if (!empty($validNews)): ?>
                                        <div class="owl-carousel">
                                            <?php foreach ($validNews as $news): ?>
                                                <div class="ed-blog__card">
                                                    <div class="ed-blog__head">
                                                        <div class="ed-blog__img">
                                                            <img src="<?= htmlspecialchars($news['news_image']) ?>"
                                                                alt="news-img" />
                                                        </div>
                                                        <a href="news-feed"
                                                            class="ed-blog__category"><?= htmlspecialchars($news['tag']) ?></a>
                                                    </div>
                                                    <div class="ed-blog__content">
                                                        <ul class="ed-blog__meta">
                                                            <li><i
                                                                    class="fi fi-rr-calendar"></i><?= date('d M, Y', strtotime($news['created_at'])) ?>
                                                            </li>
                                                        </ul>
                                                        <a href="news-details?news_id=<?= htmlspecialchars($news['news_id']) ?>"
                                                            class="ed-blog__title">
                                                            <h4><?= htmlspecialchars($news['news_title']) ?></h4>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="no-news-message">
                                            <i class="fi fi-rr-info-circle"></i>
                                            <h3>ප්‍රවෘත්ති නොමැත</h3>
                                            <p>මේ මොහොතේ අපට කිසිදු ප්‍රවෘත්තියක් සොයාගත නොහැකි විය. කරුණාකර යාවත්කාලීන සඳහා
                                                පසුව නැවත පරීක්ෂා කරන්න.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </section>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <?php
                $currentTime = time();
                $application_no = $currentTime . $_SESSION["client_id"];
                ?>
                <input type="text" hidden id="application_no" value="<?= $application_no ?>">
            </main>
            <?php include("includes/footer.php") ?>
        </div>
    </div>
    <?php include("includes/models/login-model.php") ?>
    <?php include("includes/models/register-model.php") ?>
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
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
    <script src="assets/js/clientScript.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="assets/js/questions.js"></script>
    <script src="assets/js/language.js"></script>
    <script>
        $(document).ready(function () {
            var itemsToShow = <?= $newsCount ?> === 1 ? 1 : <?= $newsCount ?> === 2 ? 2 : 3;

            $('.owl-carousel').owlCarousel({
                loop: false,
                margin: 30,
                nav: true,
                dots: true,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 1,
                    },
                    768: {
                        items: 2,
                    },
                    1024: {
                        items: itemsToShow, 
                    },
                },
                navText: [
                    '<button class="custom-nav-btn prev-btn"><i class="fi fi-rr-angle-left"></i></button>',
                    '<button class="custom-nav-btn next-btn"><i class="fi fi-rr-angle-right"></i></button>',
                ],
            });
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const countdownElements = document.querySelectorAll(".countdown");

            countdownElements.forEach(element => {
                const examId = element.id.split('-')[1];
                const examDate = element.dataset.date;
                const startTime = element.dataset.startTime;
                const countdownDate = new Date(`${examDate}T${startTime}`).getTime();

                let previousDigits = {
                    days: "",
                    hours: "",
                    minutes: "",
                    seconds: ""
                };

                const flipDigit = (container, newValue) => {
                    const digit = container.querySelector(".flip-digit");
                    if (digit.innerText !== newValue) {
                        digit.classList.add("flip");
                        setTimeout(() => {
                            digit.innerText = newValue;
                            digit.classList.remove("flip");
                        }, 300);
                    }
                };

                const splitNumber = (number) => {
                    return number.toString().padStart(2, "0").split("");
                };

                const updateCountdown = () => {
                    const now = new Date().getTime();
                    const distance = countdownDate - now;

                    if (distance <= 0) {
                        element.innerHTML = `
                    <div class="col-12 d-flex align-items-center">
                        <div class="bg-danger w-25 py-3 text-white me-1 rounded">
                            <div class="flip-container"><span class="flip-digit">0</span></div>
                            <div class="flip-container"><span class="flip-digit">0</span></div> d
                        </div> :
                        <div class="bg-danger w-25 py-3 text-white mx-1 rounded">
                            <div class="flip-container"><span class="flip-digit">0</span></div>
                            <div class="flip-container"><span class="flip-digit">0</span></div> h
                        </div> :
                        <div class="bg-danger w-25 py-3 text-white mx-1 rounded">
                            <div class="flip-container"><span class="flip-digit">0</span></div>
                            <div class="flip-container"><span class="flip-digit">0</span></div> m
                        </div> :
                        <div class="bg-danger w-25 py-3 text-white ms-1 rounded">
                            <div class="flip-container"><span class="flip-digit">0</span></div>
                            <div class="flip-container"><span class="flip-digit">0</span></div> s
                        </div>
                    </div>`;
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    const currentDigits = {
                        days: splitNumber(days),
                        hours: splitNumber(hours),
                        minutes: splitNumber(minutes),
                        seconds: splitNumber(seconds),
                    };

                    ["days", "hours", "minutes", "seconds"].forEach((unit, index) => {
                        const containers = element.querySelectorAll(`.time-unit:nth-child(${index + 1}) .flip-container`);
                        currentDigits[unit].forEach((digit, i) => {
                            flipDigit(containers[i], digit);
                        });
                    });

                    previousDigits = currentDigits;
                };

                updateCountdown();
                setInterval(updateCountdown, 1000);
            });
        });
    </script>

    <script>
        function doExam(hasExam, isPaid) {
            event.preventDefault();

            const button = event.target;
            const paperId = button.getAttribute("data-paper-id");
            const examId = button.getAttribute("data-exam-id");
            const application_no = document.getElementById("application_no").value;
            const examDate = document.getElementById("exam_date_" + examId).value;
            const startTime = document.getElementById("start_time_" + examId).value;
            const csrf_token = document.getElementById("csrf_token").value.trim();

            let link;
            const hasSession = <?= json_encode(isset($_SESSION["client_id"]) || isset($_COOKIE["remember_me"])) ?>;

            if (!hasSession) {
                link = "?showModal=1";
            } else if (hasExam == 'true') {
                if (isPaid == 'true') {
                    link = `exam?paper_id=${paperId}&exam_id=${examId}&sample=false&start_time=${encodeURIComponent(startTime)}&exam_date=${examDate}`;
                } else {
                    link = "profile";
                }
            } else {
                const examPrice = <?= json_encode($exam["exam_price"]) ?>;
                const timePeriod = <?= json_encode($time_period) ?>;
                link = `checkout?id=${examId}&name=${examDate} දින ${timePeriod} විභාගය&price=${examPrice}&isExam=true`;
            }

            if (link.includes("paper_id")) {
                fetch(`api/client/prepareForPaper?paper_id=${paperId}&application_no=${application_no}&sample=false&exam_id=${examId}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success === true) {
                            if (data.questions.length == 0) {
                                window.location = "index";
                                showToast('error', 'No questions available');
                            } else {
                                localStorage.setItem('examQuestions', JSON.stringify(data.questions));
                                localStorage.setItem('isSample', data.isSample);
                                localStorage.setItem('isExam', data.isExam);
                                localStorage.setItem('paperId', paperId);
                                localStorage.setItem('examId', examId);
                                window.location.href = `exam?start_time=${startTime}&exam_date=${examDate}`;
                            }
                        } else {
                            window.location = "index";
                            showToast("error", data.message);
                        }
                    })
                    .catch(error => {
                        showToast("error", "An error occurred while preparing for the paper.");
                        console.error("Error:", error);
                    });
            } else {
                window.location.href = link;
            }
        }
    </script>


    <?php
    if (isset($_GET['showModal']) && $_GET['showModal'] == 1): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            });
        </script>
    <?php endif; ?>
</body>

</html>