<?php include("includes/lang/lang-check.php"); ?>
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
    <title>Topik Sir | Korean language proficiency test and secure your employment abroad.</title>
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <meta property="og:url" content="https://topiksir.com/">
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
    include("includes/header.php"); ?>
    <div id="smooth-wrapper">
        <div id="smooth-content">
            <main>
                <div class="section-bg hero-bg">
                    <section class="ed-breadcrumbs background-image"
                        style="background-image: url('assets/images/breadcrumbs-bg.png');">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="ed-breadcrumbs__content">
                                        <h3 class="ed-breadcrumbs__title">කොරියාව හා සම්බන්ද නවතම ප්‍රවෘත්ති</h3>
                                        <ul class="ed-breadcrumbs__menu">
                                            <li class="active"><a href="./">මුල් පිටුවට</a></li>
                                            <li>/</li>
                                            <li>කොරියාව හා සම්බන්ද නවතම ප්‍රවෘත්ති</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <?php
                include("api/client/services/NewsService.php");
                $newsService = new ClientNewsService();
                $page = isset($_GET['page']) ? max((int) $_GET['page'], 1) : 1;
                $newsPerPage = 9;
                $newsData = $newsService->getValidAllNews($page, $newsPerPage);
                $news = $newsData['news'];
                $totalNews = $newsData['total'];
                $totalPages = ceil($totalNews / $newsPerPage);

                ?>
                <section class="ed-blog ed-blog-page section-gap">
                    <div class="container ed-container">
                        <div class="row">
                            <?php if (!empty($news)): ?>
                                <?php foreach ($news as $item): ?>
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <div class="ed-blog__card wow fadeInUp" data-wow-delay=".3s" data-wow-duration="1s">
                                            <div class="ed-blog__head">
                                                <div class="ed-blog__img">
                                                    <img src="<?= htmlspecialchars($item['news_image']) ?>" alt="blog-img" />
                                                </div>
                                                <a href="news-feed"
                                                    class="ed-blog__category"><?= htmlspecialchars($item['tag']) ?></a>
                                            </div>
                                            <div class="ed-blog__content">
                                                <ul class="ed-blog__meta">
                                                    <li><i
                                                            class="fi fi-rr-calendar"></i><?= date('d M, Y', strtotime($item['created_at'])) ?>
                                                    </li>
                                                </ul>
                                                <a href="news-details?news_id=<?= htmlspecialchars($item['news_id']) ?>"
                                                    class="ed-blog__title">
                                                    <h4><?= htmlspecialchars($item['news_title']) ?></h4>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="no-news-message">
                                        <i class="fi fi-rr-info-circle"></i>
                                        <h3>‍ප්‍රවෘත්ති නොමැත</h3>
                                        <p>මේ මොහොතේ අපට කිසිදු ප්‍රවෘත්තියක් සොයාගත නොහැකි විය. කරුණාකර යාවත්කාලීන සඳහා පසුව නැවත පරීක්ෂා කරන්න.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="ed-pagination">
                                    <ul class="ed-pagination__list">
                                        <?php if ($page > 1): ?>
                                            <li>
                                                <a href="?page=<?= $page - 1 ?>"><i class="fi-rr-arrow-small-left"></i></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="<?= $i == $page ? 'active' : '' ?>">
                                                <a href="?page=<?= $i ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <?php if ($page < $totalPages): ?>
                                            <li>
                                                <a href="?page=<?= $page + 1 ?>"><i class="fi-rr-arrow-small-right"></i></a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
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