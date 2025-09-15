<?php
include("includes/lang/lang-check.php");
include("api/client/services/packgeService.php");
?>
<!DOCTYPE html>
<html class="no-js" lang="ZXX">

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
    <link rel="stylesheet" href="https://cdn.lineicons.com/3.0/lineicons.css">
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
    $packageService = new PackageService();
    ?>

    <div id="smooth-wrapper">
        <div id="smooth-content">
            <main>
                <div class="section-bg hero-bg-2 ">
                    <section
                        class="ed-breadcrumbs background-image"
                        style="background-image: url('assets/images/breadcrumbs-bg.png');">
                        <div class="container ">
                            <div class="row justify-content-center">
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="ed-breadcrumbs__content">
                                        <h3 class="ed-breadcrumbs__title">අපගේ පැකේජ </h3>
                                        <ul class="ed-breadcrumbs__menu">
                                            <li class="active"><a href="./">මුල් පිටුව</a></li>
                                            <li>/</li>
                                            <li>අපගේ පැකේජ </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <section class="ed-contact ed-contact--style2 pt-5 position-relative background-image section-bg">
                    <div class="container ed-container">
                        <div class="row justify-content-center">
                            <?php
                            $packages = $packageService->getAllPackages();
                            ?>
                            <?php foreach ($packages as $plan): ?>
                                <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                                    <div class="single_price_plan wow shadow fadeInUp "
                                        data-wow-delay="0.2s"
                                        style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                                        <?php if ($plan['popular']): ?>
                                            <div class="side-shape">
                                                <img src="https://bootdey.com/img/popular-pricing.png" alt="">
                                            </div>
                                            <div class="title"><span>Popular</span>
                                            <?php else: ?>
                                                <div class="title">
                                                <?php endif; ?>
                                                <h3><?php echo $plan['name']; ?></h3>
                                                <p><?php echo $plan['description']; ?></p>
                                                <div class="line"></div>
                                                </div>
                                                <div class="price">
                                                    <h4>LKR <?php echo $plan['price']; ?></h4>
                                                </div>
                                                <div class="description">
                                                    <?php foreach ($plan['options'] as $option): ?>
                                                        <p>
                                                            <i class="lni lni-<?php echo strpos($option, 'No') === false ? 'checkmark-circle' : 'close'; ?>"></i>
                                                            <?php echo $option; ?>
                                                        </p>
                                                    <?php endforeach; ?>
                                                </div>
                                                <div class="button">
                                                    <a class="btn btn-success ?>" href="checkout?id=<?= $plan["id"]; ?>&name=<?= $plan["name"]; ?>&price=<?= $plan["price"]; ?>&isExam=false">Get Started</a>
                                                </div>
                                            </div>
                                    </div>
                                <?php endforeach; ?>
                                </div>
                        </div>
                </section>
            </main>
            <?php include("includes/footer.php"); ?>
        </div>
    </div>
    <?php include("includes/models/login-model.php") ?>
    <?php include("includes/models/register-model.php") ?>
    <div class="progress-wrap">
        <svg
            class="progress-circle svg-content"
            width="100%"
            height="100%"
            viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <script src="assets/plugins/js/jquery.min.js"></script>
    <script src="assets/js/lib/iconify-icon.min.js"></script>
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

</body>

</html>