<?php
include("includes/lang/lang-check.php");
include("api/client/services/NewsService.php");
include("api/config/dbconnection.php");

$newsService = new ClientNewsService();
$news_id = isset($_GET['news_id']) ? (int) $_GET['news_id'] : 0;

if ($news_id <= 0) {
    header("Location: news-feed");
    exit;
}
$newsDetails = $newsService->getNewsById($news_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description"
        content="Topik Sir | Korean language proficiency test and secure your employment abroad." />
    <meta name="keywords" content="Topik Sir | Korean language proficiency test and secure your employment abroad." />
    <meta name="author" content="Virul Nirmala Wickramasinghe" />
    <meta property="og:url" content="https://topiksir.com/news-details?news_id=<?php echo $news_id; ?>" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="<?php echo $newsDetails['news_title']; ?>" />
    <meta property="og:description" content="<?php echo substr(strip_tags($newsDetails['news']), 0, 150); ?>" />
    <meta property="og:image" content="<?php echo $newsDetails['news_image']; ?>" />
    <meta property="og:image:alt" content="<?php echo $newsDetails['news_title']; ?>" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo $newsDetails['news_title']; ?>" />
    <meta name="twitter:description" content="<?php echo substr(strip_tags($newsDetails['news']), 0, 150); ?>" />
    <meta name="twitter:image" content="<?php echo $newsDetails['news_image']; ?>" />
    <meta name="twitter:image:alt" content="<?php echo $newsDetails['news_title']; ?>" />
    <title><?php echo $newsDetails['news_title']; ?> | Topik Sir</title>
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <meta property="og:url" content="https://topiksir.com/" />
    <meta property="og:type" content="website">
    <meta property="og:title" content="Topik Sir">

    <link rel="stylesheet" href="assets/plugins/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/animate.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/owl.carousel.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/swiper-bundle.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/maginific-popup.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/nice-select.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/icofont.css" />
    <link rel="stylesheet" href="assets/plugins/css/uicons.css" />
    <link rel="stylesheet" href="style.css" />
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
    $home = "";
    $about = "";
    $contact = "";
    $papers = "";
    $leadboard = "";
    include("includes/header.php");
    ?>

    <div id="smooth-wrapper">
        <div id="smooth-content">
            <main>
                <section class="ed-blog__details section-gap position-relative mt--100">
                    <div class="container ed-container">
                        <div class="row">
                            <div class="col-lg-12 col-xl-8 col-12">
                                <div class="ed-blog__details-main">
                                    <div class="ed-blog__details-top">
                                        <div class="ed-blog__details-cover">
                                            <div class="ed-blog__details-cover-img">
                                                <?php if ($newsDetails): ?>
                                                    <img src="<?= $newsDetails['news_image'] ?>" alt="news-img" />
                                                <?php else: ?>
                                                    <img src="assets/images/404.png" alt="default-news-img" />
                                                <?php endif; ?>
                                            </div>
                                            <ul class="ed-blog__details-meta">
                                                <li><i class="fi fi-rr-calendar"></i>
                                                    <?= date('d M, Y', strtotime($newsDetails['created_at'])) ?></li>
                                                <li><a href="news-feed"><?= $newsDetails['tag'] ?></a></li>
                                                <li><a href="https://www.facebook.com/sharer/sharer.php?u=https://topiksir.com/news-details?news_id=<?php echo $news_id; ?>"
                                                        target="_blank"><i class="icofont-share me-2"></i>Share on
                                                        Facebook</a></li>
                                            </ul>
                                        </div>
                                        <h2 class="ed-blog__details-title">
                                            <?= $newsDetails ? $newsDetails['news_title'] : 'News not found' ?>
                                        </h2>
                                        <p class="ed-blog__details-text">
                                            <?= $newsDetails ? nl2br(htmlspecialchars($newsDetails['news'], ENT_QUOTES)) : 'Sorry, the news details could not be fetched.' ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-4 col-md-8 col-12">
                                <div class="ed-blog__sidebar">
                                    <div class="ed-blog__sidebar-widget">
                                        <h4 class="ed-blog__sidebar-title"><?= $translations['footer']['contact'] ?>
                                        </h4>
                                        <div class="ed-contact__info-item">
                                            <div class="ed-contact__info-icon">
                                                <img src="assets/images/icons/icon-phone-blue.svg"
                                                    alt="icon-phone-blue" />
                                            </div>
                                            <div class="ed-contact__info-content">
                                                <span><?= $translations['footer']['help'] ?></span>
                                                <a href="https://wa.me/94771444404">+94 77 144 4404</a>
                                            </div>
                                        </div>
                                        <div class="ed-contact__info-item">
                                            <div class="ed-contact__info-icon">
                                                <img src="assets/images/icons/icon-envelope-blue.svg"
                                                    alt="icon-envelope-blue" />
                                            </div>
                                            <div class="ed-contact__info-content">
                                                <span><?= $translations['footer']['mail'] ?></span>
                                                <a href="mailto:aouranetwork@gmail.com">aouranetwork@gmail.com</a>
                                            </div>
                                        </div>
                                        <div class="ed-contact__info-item">
                                            <div class="ed-contact__info-icon">
                                                <img src="assets/images/icons/icon-location-blue.svg"
                                                    alt="icon-location-blue" />
                                            </div>
                                            <div class="ed-contact__info-content">
                                                <span><?= $translations['footer']['place'] ?></span>
                                                <a href="#">경북 칠곡군 왜관읍 2산업단지3길 97 센템빌 102동</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
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
    <script src="assets/plugins/js/jquery.counterup.min.js"></script>
    <script src="assets/plugins/js/waypoints.min.js"></script>
    <script src="assets/plugins/js/nice-select.min.js"></script>
    <script src="assets/plugins/js/backToTop.js"></script>
    <script src="assets/plugins/js/active.js"></script>
    <script src="assets/js/language.js"></script>
</body>

</html>