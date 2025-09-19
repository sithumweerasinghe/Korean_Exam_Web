<?php include("includes/lang/lang-check.php"); ?>
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
    $papers = "active";
    $leadboard = "";

    include("includes/header.php");
    include("api/client/services/paperService.php");

    $paperService = new PaperService();
    $papers = $paperService->getAllPapers();

    $samplePapers = [];
    $nonSamplePapers = [];

    foreach ($papers as $paper) {
        if ($paper["isSample"] == 1 && $paper["paper_status"] == 1) {
            $samplePapers[] = $paper;
        } else {
            $nonSamplePapers[] = $paper;
        }
    }

    ?>
    <div id="smooth-wrapper">
        <div id="smooth-content">
            <main>
                <div class="section-bg hero-bg-2" style="padding-top: 100px !important;">
                    <section
                        class="ed-breadcrumbs background-image"
                        style=" padding: 40px 0;">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="ed-breadcrumbs__content">
                                        <h3 class="ed-breadcrumbs__title"><?= $translations['breadcrumbs']['title'] ?></h3>
                                        <ul class="ed-breadcrumbs__menu">
                                            <li class="active"><a href="./"><?= $translations['breadcrumbs']['menu']['home'] ?></a></li>
                                            <li>/</li>
                                            <li><?= $translations['breadcrumbs']['menu']['current'] ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <section
                    class="ed-contact ed-contact--style2  position-relative bg-gradient-free pt-0">
                    <div class="container ed-container pt-5">
                        <div class="row mb-4">
                            <div class="col-12 text-center">
                                <div class="section-header">
                                    <h5 class="section-title"><?= $translations['free_papers']['heading'] ?></h5>
                                    <p class="text-muted">Practice with our free sample papers</p>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5 d-flex justify-content-start">
                            <?php
                            if ($samplePapers) {
                            ?>
                                <?php foreach ($samplePapers as $paper): ?>
                                    <?php
                                    if ($paper["isSample"] == "1") {
                                    ?>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-3" onclick="window.location= 'exam?paper_id=<?= $paper['paper_id'] ?>&sample=true'">
                                            <div class="ed-paper__card-item">
                                                <div class="ed-paper__card-item__image object-fit-cover" style="height:100%; width: 100%;">
                                                    <img src="assets/images/papers/free.jpg" class="w-100 h-100 " alt="paper image" />
                                                </div>
                                                <div class="hover-text text-center"><?= $paper["paper_name"] ?></div>
                                                <div class="ed-paper__card-info text-center">
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex justify-content-center">
                                                <span class="text-center fw-bold"><br><?= $paper["paper_name"] ?></span>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                <?php endforeach; ?>
                            <?php
                            } else {
                            ?>
                                <div class="col-12 text-center p-4">
                                    <div class="alert alert-info">No Papers</div>
                                </div>
                            <?php
                            }
                            ?>

                        </div>
                    </div>
                </section>

                <!-- Section Divider -->
                <div class="section-divider py-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <hr class="divider-line">
                                <div class="divider-content text-center">
                                    <span class="divider-text bg-white px-4">
                                        <i class="icofont-arrow-down"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <section
                    class="ed-contact mb-5 ed-contact--style2 pt-0 position-relative bg-light">
                    <div class="container ed-container pt-5">
                        <div class="row mb-4">
                            <div class="col-12 text-center">
                                <div class="section-header">
                                    <h5 class="section-title"><?= $translations['paid_papers']['heading'] ?></h5>
                                    <p class="text-muted">Access our complete collection of exam papers</p>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5" id="modal-paper-section">
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="ed-pagination">
                                    <ul class="ed-pagination__list"> </ul>
                                </div>
                            </div>
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
    <script src="assets/js/language.js"></script>
    <script>
        // Mobile device function for future use (redirect removed)
        function isMobileDevice() {
            return /Mobi|Android|iPhone|iPad|iPod|BlackBerry|Opera Mini|IEMobile|Windows Phone|webOS/i.test(navigator.userAgent);
        }
        
        // Removed mobile redirect as the site is now mobile responsive
        // if (isMobileDevice()) {
        //     window.location.href = "./mobile";
        // }

        const allPapers = <?= json_encode($nonSamplePapers); ?>;
        let papersPerPage = 8;
        let currentPage = 1;

        // Adjust papers per page based on screen size
        function updatePapersPerPage() {
            if (window.innerWidth <= 575) {
                papersPerPage = 4; // Mobile phones
            } else if (window.innerWidth <= 768) {
                papersPerPage = 6; // Tablets
            } else {
                papersPerPage = 8; // Desktop
            }
        }

        function renderPapers(page) {
            const startIndex = (page - 1) * papersPerPage;
            const endIndex = startIndex + papersPerPage;
            const papersToDisplay = allPapers.slice(startIndex, endIndex);

            const papersContainer = document.querySelector('#modal-paper-section');
            papersContainer.classList.add('d-flex', 'justify-content-start');
            papersContainer.innerHTML = '';

            const filteredPapers = papersToDisplay.filter(paper => paper.isSample === 0).filter(paper => paper.paper_status === 1);

            if (filteredPapers.length === 0) {
                papersContainer.innerHTML = `<div class="col-12 text-center p-4"><div class="alert alert-info">No papers to display</div></div>`;
                return;
            }

            filteredPapers.forEach((paper) => {
                const paperCard = `
                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-3" ${paper.status === 'locked' ? `onclick="window.location = 'pricing'"` : `onclick="window.location = 'exam?paper_id=${paper.paper_id}&sample=false'"`}>
                    <div class="ed-paper__card-item">
                           <div class="ed-paper__card-item__image object-fit-cover" style="height:100%; width: 100%;">
                                <img src="assets/images/papers/paid.jpg" class="w-100 h-100 " alt="paper image" />
                            </div>
                        <div class="hover-text text-center">ප්‍රශ්න පත්‍ර ${paper.paper_name}</div>
                        <div class="ed-paper__card-info text-center position-absolute">
                            ${paper.status === 'locked' ? `
                                <a target="_blank">
                                    <img class="bg-black p-2 rounded-circle" src="./assets/images/icons/icon-white-lock.svg" alt="">
                                </a>
                            ` : ''}
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-center">
                        <span class="text-center fw-bold"><br> ${paper.paper_name}</span>
                    </div>
                </div>`;
                papersContainer.insertAdjacentHTML('beforeend', paperCard);
            });
        }

        function renderPagination() {
            const filteredPapers = allPapers.filter(paper => paper.isSample === 0 && paper.paper_status === 1);

            const paginationContainer = document.querySelector('.ed-pagination__list');
            paginationContainer.innerHTML = '';

            if (filteredPapers.length === 0) {
                return;
            }

            const totalPages = Math.ceil(allPapers.length / papersPerPage);

            paginationContainer.innerHTML += `
        <li ${currentPage === 1 ? 'class="disabled"' : ''}>
            <a onclick="changePage(currentPage - 1)"><i class="fi-rr-arrow-small-left"></i></a>
        </li>`;
            for (let i = 1; i <= totalPages; i++) {
                paginationContainer.innerHTML += `
            <li ${i === currentPage ? 'class="active"' : ''}>
                <a onclick="changePage(${i})">${String(i).padStart(2, '0')}</a>
            </li>`;
            }
            paginationContainer.innerHTML += `
        <li ${currentPage === totalPages ? 'class="disabled"' : ''}>
            <a onclick="changePage(currentPage + 1)"><i class="fi-rr-arrow-small-right"></i></a>
        </li>`;
        }

        function changePage(page) {
            const totalPages = Math.ceil(allPapers.length / papersPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderPapers(currentPage);
            renderPagination();
        }

        document.addEventListener('DOMContentLoaded', () => {
            updatePapersPerPage();
            renderPapers(currentPage);
            renderPagination();
        });

        // Update on window resize
        window.addEventListener('resize', () => {
            updatePapersPerPage();
            currentPage = 1; // Reset to first page
            renderPapers(currentPage);
            renderPagination();
        });
    </script>

</body>

</html>