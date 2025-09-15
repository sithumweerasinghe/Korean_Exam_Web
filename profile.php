<?php
include("includes/lang/lang-check.php");
if (!(isset($_SESSION["client_id"]) || isset($_COOKIE["remember_me"]))) {
    header("Location: ./?showModal=1");
    exit();
}
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
    $papers = "";
    $leadboard = "";
    $contact = "";
    include("includes/header.php"); ?>

    <div id="smooth-wrapper">
        <div id="smooth-content">
            <main>
                <section
                    class="ed-contact ed-contact--style2  pt-5 position-relative">
                    <div class="container ed-container mt-3 pt-5">
                        <div class="row mt-5 shadow rounded mb-5" style="height: 670px; ">
                            <div class="col-3 bg-white py-5 text-center ">
                                <div class="d-inline-block position-relative overflow-hidden rounded-circle" style="width: 100px; height: 100px;" onclick="window.location = 'profile'">
                                    <img src="<?= $profileImage ?>" alt="profile_img" class="w-100 h-100 object-fit-cover">
                                </div>
                                <ul class="py-2">
                                    <li class=" fs-5 fw-bold py-2"><?= $firstName ?><br /><?= $lastName ?></li>
                                    <li class="col-12 overflow-scroll"> <a id="client_email" href="#"><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?></a></li>
                                    <button onclick="Logout();" class="mt-2 btn btn-outline-danger border border-danger">Logout</button>
                                    <!-- <label class=" px-3 py-1 mt-2 text-white  label-info" style="background-color:#2ca347; border-radius:8px">Student</label> -->
                                </ul>
                                <hr>
                                <ul class="nav flex-column mb-4">
                                    <li class="nav-item border-3 rounded border-end custom-active">
                                        <a href="#profile" class="nav-link px-4 py-2 rounded  d-flex align-items-center" style="height: 45px;" data-section="profile">
                                            <i class="fa fa-user me-2"></i> Profile
                                        </a>
                                    </li>
                                    <?php
                                    if (!$isGoogleUser) {
                                    ?>
                                        <li class="nav-item border-3 rounded">
                                            <a href="#change-password" class="nav-link mt-1 px-4 py-2 text-dark d-flex align-items-center" style="height: 45px;" data-section="change-password">
                                                <i class="fa fa-key me-2 "></i> Change Password
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                    <li class="nav-item border-3 rounded">
                                        <a href="#billing" class="nav-link mt-1 px-4 py-2 text-dark d-flex align-items-center" style="height: 45px;" data-section="billing">
                                            <i class="fa fa-credit-card me-2 "></i> Billing
                                        </a>
                                    </li>
                                    <li class="nav-item border-3 rounded">
                                        <a href="#billing" class="nav-link mt-1 px-4 py-2 text-dark d-flex align-items-center" style="height: 45px;" data-section="exams">
                                            <i class="fa fa-graduation-cap me-2 "></i> Exams
                                        </a>
                                    </li>
                                    <li class="nav-item border-3 rounded">
                                        <a href="#billing" class="nav-link mt-1 px-4 py-2 text-dark d-flex align-items-center" style="height: 45px;" data-section="papers">
                                            <i class="fa fa-book me-2 "></i> Papers
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Profile Section -->
                            <div class="col-9 py-4 section" id="profile">
                                <fieldset class="fieldset px-4">
                                    <label class=" fs-5 mt-1 fw-semibold">Personal Info</label>
                                    <hr class=" bg-secondary-subtle" />
                                    <div class="row d-flex justify-content-center align-items-center">
                                        <div class="figure col-md-2 col-sm-3 col-xs-12 p-0">
                                            <img width="100px" height="100px" id="profile_img" class="rounded-3 object-fit-cover" src="<?= $profileImage ?>" alt="">
                                        </div>


                                        <div class=" col-md-10 col-sm-9 col-xs-12">
                                            <?php
                                            if (!$isGoogleUser) {
                                            ?>
                                                <input type="file" id="profile_img_uploader" class="file-uploader pull-left" accept=".jpg, .png, .svg">
                                                <div class="ed-topbar__info-buttons">
                                                    <button type="button" onclick="UpdateProfileImage();" class="register-btn w-auto">
                                                        Update Image
                                                    </button>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <input type="hidden" id="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                                    <div class="col-12">
                                        <div class="ed-checkout__form-group">
                                            <label class="ed-checkout__label">First Name</label>
                                            <input
                                                class="ed-checkout__input"
                                                type="text"
                                                id="firstName"
                                                value="<?= $firstName ?>"
                                                placeholder="First Name"
                                                <?php if ($isGoogleUser) echo 'disabled'; ?> />
                                        </div>
                                    </div>
                                    <div class=col-12">
                                        <div class="ed-checkout__form-group">
                                            <label class="ed-checkout__label">Last Name</label>
                                            <input
                                                class="ed-checkout__input"
                                                type="text"
                                                id="lastName"
                                                value="<?= $lastName ?>"
                                                placeholder="Last Name"
                                                <?php if ($isGoogleUser) echo 'disabled'; ?> />
                                        </div>
                                    </div>
                                    <div class=col-12">
                                        <div class="ed-checkout__form-group">
                                            <label class="ed-checkout__label">Mobile</label>
                                            <input
                                                class="ed-checkout__input"
                                                type="mobile"
                                                id="mobile"
                                                placeholder="Mobile"
                                                required
                                                value="<?= $mobile ?>" />
                                        </div>
                                    </div>
                                    <div class="ed-hero__btn text-center">
                                        <a onclick="UpdateProfileDetails()" class="ed-btn">Update profile Details<i
                                                class="fi fi-rr-arrow-small-right"></i></a>
                                    </div>
                                </fieldset>
                            </div>

                            <?php
                            if (!$isGoogleUser) {
                            ?>
                                <!-- change password section -->
                                <div class="col-9 py-4 section d-none" id="change-password">
                                    <fieldset class="fieldset px-4">
                                        <label class=" fs-5 mt-1 fw-semibold">Change Password</label>
                                        <hr class=" bg-secondary-subtle" />
                                        <div class="col-12">
                                            <div class="ed-checkout__form-group">
                                                <label class="ed-checkout__label">Current Password*</label>
                                                <input
                                                    class="ed-checkout__input"
                                                    type="password"
                                                    id="current_password"
                                                    placeholder="Current Password"
                                                    required />
                                            </div>
                                        </div>
                                        <div class=col-12">
                                            <div class="ed-checkout__form-group">
                                                <label class="ed-checkout__label">New Password*</label>
                                                <input
                                                    class="ed-checkout__input"
                                                    type="password"
                                                    id="new_password"
                                                    placeholder="New Password"
                                                    required />
                                            </div>
                                        </div>
                                        <div class=col-12">
                                            <div class="ed-checkout__form-group">
                                                <label class="ed-checkout__label">Confirm Password*</label>
                                                <input
                                                    class="ed-checkout__input"
                                                    type="password"
                                                    id="c_password"
                                                    placeholder="Confirm Password"
                                                    required />
                                            </div>
                                        </div>
                                        <div class="ed-hero__btn text-center" onclick="UpdatePassword();">
                                            <a class="ed-btn">Update Password<i
                                                    class="fi fi-rr-arrow-small-right"></i></a>
                                        </div>
                                    </fieldset>
                                </div>
                            <?php
                            }
                            ?>

                            <?php
                            include("api/client/services/invoiceService.php");
                            $InvoiceService = new InvoiceService();
                            $invoices = $InvoiceService->getAllInvoices();

                            ?>
                            <!-- billing section -->
                            <div class="col-9 py-4 section d-none" id="billing">
                                <fieldset class="fieldset px-4">
                                    <label class=" fs-5 mt-1 fw-semibold">Purchase History</label>
                                    <hr class=" bg-secondary-subtle" />

                                    <?php
                                    if ($invoices) {
                                        $invoicesPerPage = 5;

                                        $totalInvoices = count($invoices);

                                        $totalPages = ceil($totalInvoices / $invoicesPerPage);
                                        $chunks = array_chunk($invoices, $invoicesPerPage);
                                    ?>

                                        <table class="table table-hover" id="invoiceTable">
                                            <thead class="table-light">
                                                <tr class="" style="height: 60px;">
                                                    <th>Package or Paper</th>
                                                    <th scope="col">Created On</th>
                                                    <th scope="col">Total</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($chunks[0] as $invoice) {
                                                ?>
                                                    <tr class="" style="height: 60px;">
                                                        <th style="font-size: 15px;" class="fw-medium"><?= $invoice["exam_id"] != null ? $invoice["start_time"] . " - " . $invoice["end_time"] : $invoice["package_name"]; ?></th>
                                                        <td style="font-size: 15px;"><?= $invoice["created_at"]; ?></td>
                                                        <td style="font-size: 15px;">LKR <?= $invoice["exam_id"] != null ? ($invoice["exam_price"] ?? '0') : ($invoice["package_price"] ?? '0'); ?></td>
                                                        <td style="font-size: 15px;"><?= $invoice["status"]; ?></td>
                                                        <td style="font-size: 15px;" class="">
                                                            <a class="me-2" href="invoice?invoice_no=<?= $invoice["invoice_no"]; ?>"><i class="fa fa-eye p-2 rounded-circle" style="background-color: #f0faf0; color:#2ca347;" aria-hidden="true"></i></a>
                                                            <?php if ($invoice["status"] == "Pending" && $invoice["payment_methods_payment_method_id"] == 1) {
                                                            ?>
                                                                <a href="" id="payhere-payment" onclick="completeOrder(<?= $invoice['invoice_no'] ?>');" class="py-1 px-3 rounded-pill cursor-pointer fw-medium" style="background-color: #f5f5f2; color:#000; font-size: 14px;">Pay Now</a>
                                                            <?php
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="ed-pagination">
                                                    <ul class="ed-pagination__list" id="paginationControls">
                                                        <!-- Pagination buttons will be added here dynamically -->
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    } else {
                                        echo "no invoics";
                                    }
                                    ?>

                                </fieldset>
                            </div>

                            <!-- exam result section -->
                            <div class="col-9 py-4 section d-none" id="exams">
                                <?php
                                include("api/client/services/resultsService.php");
                                $resultService = new ResultService();
                                $examResults = $resultService->getAllExamsResults();
                                ?>
                                <fieldset class="fieldset px-4">
                                    <label class="fs-5 mt-1 fw-semibold">Exam Results</label>
                                    <hr class="bg-secondary-subtle" />
                                    <?php
                                    if ($examResults) {
                                        $resultsPerPage = 5;

                                        $totalResults = count($examResults);

                                        $totalPages = ceil($totalResults / $resultsPerPage);
                                        $resultChunks = array_chunk($examResults, $resultsPerPage);
                                    ?>
                                        <table class="table table-hover" id="invoiceTable">
                                            <thead class="table-light">
                                                <tr class="" style="height: 60px;">
                                                    <th>Exam</th>
                                                    <th scope="col">Admission No</th>
                                                    <th scope="col">Mark</th>
                                                    <th scope="col">Cutoff Mark</th>
                                                    <th scope="col">Done by</th>
                                                    <th scope="col">Share</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($resultChunks[0] as $result) {
                                                ?>
                                                    <tr class="" style="height: 60px;">
                                                        <th style="font-size: 15px;" class="fw-medium"><?= $result["paper_name"]; ?></th>
                                                        <td style="font-size: 15px;"><?= $result["admission_no"]; ?></td>
                                                        <td style="font-size: 15px;"> <?= $result["marks"]; ?></td>
                                                        <td style="font-size: 15px;"><?= $result["cutoff_mark"] ?: 'Not Released'; ?></td>
                                                        <td style="font-size: 15px;" class=""><?= $result["created_at"]; ?></td>
                                                        <td>
                                                            <button
                                                                class="btn btn-primary btn-sm share-button"
                                                                onclick="shareResult('<?= $result["paper_name"]; ?>', '<?= $result["marks"]; ?>', '<?= $result["cutoff_mark"]; ?>')">
                                                                Share
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    <?php
                                    } else {
                                        echo "no results";
                                    }
                                    ?>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="ed-pagination">
                                                <ul class="ed-pagination__list" id="examResultPaginationControls">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <!-- paper result section -->
                            <div class="col-9 py-4 section d-none" id="papers">
                                <?php
                                $paperResults = $resultService->getAllPapersResults();
                                ?>
                                <fieldset class="fieldset px-4">
                                    <label class=" fs-5 mt-1 fw-semibold">Modal Paper Results</label>
                                    <hr class=" bg-secondary-subtle" />
                                    <?php
                                    if ($paperResults) {
                                        $resultsPerPage = 5;

                                        $totalPaperResults = count($paperResults);

                                        $totalPages = ceil($totalPaperResults / $resultsPerPage);
                                        $paperResultChunks = array_chunk($paperResults, $resultsPerPage);
                                    ?>
                                        <table class="table table-hover" id="invoiceTable">
                                            <thead class="table-light">
                                                <tr class="" style="height: 60px;">
                                                    <th>Paper</th>
                                                    <th scope="col">Admission No</th>
                                                    <th scope="col">Mark</th>
                                                    <th scope="col">Cutoff Mark</th>
                                                    <th scope="col">Done by</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($paperResultChunks[0] as $result) {
                                                ?>
                                                    <tr class="" style="height: 60px;">
                                                        <th style="font-size: 15px;" class="fw-medium"><?= $result["paper_name"]; ?></th>
                                                        <td style="font-size: 15px;"><?= $result["admission_no"]; ?></td>
                                                        <td style="font-size: 15px;"> <?= $result["result"]; ?></td>
                                                        <td style="font-size: 15px;"><?= $result["cutoff_mark"] ?: 'Not Released'; ?></td>
                                                        <td style="font-size: 15px;" class=""><?= $result["created_at"]; ?></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    <?php
                                    } else {
                                        echo "no results";
                                    }
                                    ?>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="ed-pagination">
                                                <ul class="ed-pagination__list" id="paperResultPaginationControls">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    </script>
                                </fieldset>
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
        <svg
            class="progress-circle svg-content"
            width="100%"
            height="100%"
            viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Account Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>පහත බැංකු විස්තර වෙත බැංකු ගෙවීම සිදු කර ඔබගේ ගෙවීම් පත්‍රිකාව <span class="fw-bold">+94 77 144 4404</span> whatsapp අංකයට එවන්න</p>
                    <br>
                    <p> Hatton National Bank</p>
                    <p>Account Name - Aoura Network</p>
                    <p>Account Number - 065020315233</p>
                    <p>Branch - Matale</p>
                    <br>
                    <p>Sampath Bank</p>
                    <p>Account Name - R A G DISSANAYAKE</p>
                    <p>Account Number - 102552527388</p>
                    <p>Branch - Matale</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Understood</button>
                </div>
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
    <script src='https://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
    <script src="assets/js/clientScript.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
    <script>
        function shareResult(paperName, marks, cutoffMark) {
            const customMessage = `I scored ${marks} marks in ${paperName}! Preparing with TopikSir.com helped me a lot. If you're preparing for the Korean TOPIK exam, check it out and make your fear disappear!`;
            const shareData = {
                title: `Exam Result: ${paperName}`,
                text: `${customMessage} Cutoff Mark: ${cutoffMark || 'Not Released'}.`,
                url: 'https://topiksir.com',
            };

            if (navigator.share) {
                navigator.share(shareData)
                    .then(() => console.log('Shared successfully'))
                    .catch(err => console.error('Error sharing:', err));
            } else {
                alert('Web Share API is not supported in this browser. Use a modern browser to share results.');
            }
        }
    </script>
    <script>
        let invoicesChunks = <?php echo json_encode($chunks); ?>;
        let currentPage = 0;
        let totalPages = invoicesChunks.length;

        function updateTable(page) {
            const tableBody = document.querySelector("#invoiceTable tbody");

            tableBody.innerHTML = '';

            invoicesChunks[page].forEach(invoice => {
                console.log(invoice);
                const row = document.createElement("tr");
                row.style.height = "60px";

                const packageOrTime = invoice.exam_id ?
                    `${invoice.exam_date} ${invoice.start_time} - ${invoice.end_time}  exam` :
                    invoice.package_name;

                const price = invoice.exam_id ?
                    (invoice.exam_price || '0') :
                    (invoice.package_price || '0');

                const isExam = invoice.exam_id ? "true" : "false"
                const showPayNow = invoice.status === "Pending" && invoice.payment_methods_payment_method_id === 1

                row.innerHTML = `
           <th style="font-size: 15px;" class="fw-medium">${packageOrTime}</th>
        <td style="font-size: 15px;">${formatDate(invoice.created_at)}</td>
        <td style="font-size: 15px;">LKR ${price}</td>
            <td style="font-size: 15px;">${invoice.status}</td>
            <td style="font-size: 15px;">
                <a class="me-2" href="invoice?invoice_no=${invoice.invoice_no}"><i class="fa fa-eye p-2 rounded-circle" style="background-color: #f0faf0; color:#2ca347;" aria-hidden="true"></i> <span style="color:#2ca347; font-size: 14px">View Invoice</span></a>
                ${showPayNow 
                    ? `<a id="payhere-payment" onclick="completeOrder(${invoice.invoice_no}, ${isExam});"  class="py-1 mt-2 px-3 rounded-pill cursor-pointer fw-medium" style="background-color: #f5f5f2; color:#000; font-size: 14px;">Pay Now</a>` 
                    : ''}
                    </td>`;

                tableBody.appendChild(row);
            });
            updatePagination();
        }

        function escapeHTML(text) {
            const div = document.createElement('div');
            div.innerText = text;
            return div.innerHTML;
        }

        function generatePaginationControls() {
            const paginationContainer = document.getElementById("paginationControls");
            paginationContainer.innerHTML = '';

            const prevButton = document.createElement("li");
            prevButton.innerHTML = `<a href="javascript:void(0);" id="prevBtn"><i class="fi-rr-arrow-small-left"></i></a>`;
            prevButton.addEventListener("click", () => {
                if (currentPage > 0) {
                    currentPage--;
                    updateTable(currentPage);
                }
            });
            paginationContainer.appendChild(prevButton);

            let startPage = Math.max(currentPage - 2, 0);
            let endPage = Math.min(currentPage + 2, totalPages - 1);

            if (startPage > 0) {
                const ellipsis = document.createElement("li");
                ellipsis.innerHTML = `<a href="javascript:void(0);">...</a>`;
                ellipsis.addEventListener("click", () => {
                    currentPage = 0;
                    updateTable(currentPage);
                });
                paginationContainer.appendChild(ellipsis);
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageItem = document.createElement("li");
                pageItem.innerHTML = `<a href="javascript:void(0);">${i + 1}</a>`;
                pageItem.addEventListener("click", () => {
                    currentPage = i;
                    updateTable(currentPage);
                });
                if (i === currentPage) {
                    pageItem.classList.add("active");
                }
                paginationContainer.appendChild(pageItem);
            }

            if (endPage < totalPages - 1) {
                const ellipsis = document.createElement("li");
                ellipsis.innerHTML = `<a href="javascript:void(0);">...</a>`;
                ellipsis.addEventListener("click", () => {
                    currentPage = totalPages - 1;
                    updateTable(currentPage);
                });
                paginationContainer.appendChild(ellipsis);
            }

            const nextButton = document.createElement("li");
            nextButton.innerHTML = `<a href="javascript:void(0);" id="nextBtn"><i class="fi-rr-arrow-small-right"></i></a>`;
            nextButton.addEventListener("click", () => {
                if (currentPage < totalPages - 1) {
                    currentPage++;
                    updateTable(currentPage);
                }
            });
            paginationContainer.appendChild(nextButton);
        }

        function updatePagination() {
            generatePaginationControls();
        }

        updateTable(currentPage);

        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            };
            const formattedDate = new Intl.DateTimeFormat('en-GB', options).format(date);
            const [datePart, timePart] = formattedDate.split(', ');
            const adjustedTime = timePart.replace(':', '.').toUpperCase();
            return `${datePart} at ${adjustedTime}`;
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const navLinks = document.querySelectorAll(".nav-item");

            navLinks.forEach((item) => {
                const link = item.querySelector(".nav-link");

                link.addEventListener("click", (e) => {
                    e.preventDefault();

                    navLinks.forEach((nav) => {
                        const navLink = nav.querySelector(".nav-link");
                        nav.classList.remove("custom-active", "border-end");
                        navLink.classList.add("text-dark");
                    });

                    item.classList.add("custom-active", "border-end");
                    link.classList.remove("text-dark");

                    const sections = document.querySelectorAll(".section");
                    sections.forEach((section) => section.classList.add("d-none"));

                    const sectionId = link.getAttribute("data-section");
                    document.getElementById(sectionId).classList.remove("d-none");
                });
            });
        });
    </script>
    <script>
        let examResultsChunks = <?php echo json_encode($resultChunks); ?>;
        let currentExamPage = 0;
        let totalExamPages = examResultsChunks.length;

        function updateExamTable(page) {
            const examTableBody = document.querySelector("#exams table tbody");

            // Clear the existing table rows
            examTableBody.innerHTML = '';

            // Add new rows for the selected page
            examResultsChunks[page].forEach(result => {
                const row = document.createElement("tr");
                row.style.height = "60px";

                row.innerHTML = `
                <th style="font-size: 15px;" class="fw-medium">${result.paper_name}</th>
                <td style="font-size: 15px;">${result.admission_no}</td>
                <td style="font-size: 15px;">${result.marks}</td>
                <td style="font-size: 15px;">${result.cutoff_mark || 'Not Released'}</td>
                <td style="font-size: 15px;">${formatExamDate(result.created_at)}</td>
                <td>
                    <button class="btn btn-success  btn-sm share-button" onclick="shareResult('${result.paper_name}', '${result.marks}', '${result.cutoff_mark || 'Not Released'}')">
                        Share
                    </button>
                </td>
            `;

                examTableBody.appendChild(row);
            });

            updateExamPagination();
        }

        function generateExamPaginationControls() {
            const paginationContainer = document.getElementById("examResultPaginationControls");
            paginationContainer.innerHTML = '';

            // Previous button
            const prevButton = document.createElement("li");
            prevButton.innerHTML = `<a href="javascript:void(0);" id="examPrevBtn"><i class="fi-rr-arrow-small-left"></i></a>`;
            prevButton.addEventListener("click", () => {
                if (currentExamPage > 0) {
                    currentExamPage--;
                    updateExamTable(currentExamPage);
                }
            });
            paginationContainer.appendChild(prevButton);

            // Pagination numbers
            let startPage = Math.max(currentExamPage - 2, 0);
            let endPage = Math.min(currentExamPage + 2, totalExamPages - 1);

            if (startPage > 0) {
                const ellipsis = document.createElement("li");
                ellipsis.innerHTML = `<a href="javascript:void(0);">...</a>`;
                paginationContainer.appendChild(ellipsis);
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageItem = document.createElement("li");
                pageItem.innerHTML = `<a href="javascript:void(0);">${i + 1}</a>`;
                pageItem.addEventListener("click", () => {
                    currentExamPage = i;
                    updateExamTable(currentExamPage);
                });
                if (i === currentExamPage) {
                    pageItem.classList.add("active");
                }
                paginationContainer.appendChild(pageItem);
            }

            if (endPage < totalExamPages - 1) {
                const ellipsis = document.createElement("li");
                ellipsis.innerHTML = `<a href="javascript:void(0);">...</a>`;
                paginationContainer.appendChild(ellipsis);
            }

            // Next button
            const nextButton = document.createElement("li");
            nextButton.innerHTML = `<a href="javascript:void(0);" id="examNextBtn"><i class="fi-rr-arrow-small-right"></i></a>`;
            nextButton.addEventListener("click", () => {
                if (currentExamPage < totalExamPages - 1) {
                    currentExamPage++;
                    updateExamTable(currentExamPage);
                }
            });
            paginationContainer.appendChild(nextButton);
        }

        function updateExamPagination() {
            generateExamPaginationControls();
        }

        function formatExamDate(dateString) {
            const date = new Date(dateString);
            const options = {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            };
            const formattedDate = new Intl.DateTimeFormat('en-GB', options).format(date);
            const [datePart, timePart] = formattedDate.split(', ');
            const adjustedTime = timePart.replace(':', '.').toUpperCase();
            return `${datePart} at ${adjustedTime}`;
        }

        // Initialize the exam results table
        updateExamTable(currentExamPage);
    </script>
    <script>
        let paperResultsChunks = <?php echo json_encode($paperResultChunks); ?>;
        let currentPaperPage = 0;
        let totalPaperPages = paperResultsChunks.length;

        function updatePaperTable(page) {
            const paperTableBody = document.querySelector("#papers table tbody");

            // Clear the existing table rows
            paperTableBody.innerHTML = '';

            // Add new rows for the selected page
            paperResultsChunks[page].forEach(result => {
                const row = document.createElement("tr");
                row.style.height = "60px";

                row.innerHTML = `
                <th style="font-size: 15px;" class="fw-medium">${result.paper_name}</th>
                <td style="font-size: 15px;">${result.admission_no}</td>
                <td style="font-size: 15px;">${result.result}</td>
                <td style="font-size: 15px;">${result.cutoff_mark || 'Not Released'}</td>
                <td style="font-size: 15px;">${formatExamDate(result.created_at)}</td>
            `;

                paperTableBody.appendChild(row);
            });

            updatePaperPagination();
        }

        function generatePaperPaginationControls() {
            const paginationContainer = document.getElementById("paperResultPaginationControls");
            paginationContainer.innerHTML = '';

            // Previous button
            const prevButton = document.createElement("li");
            prevButton.innerHTML = `<a href="javascript:void(0);" id="paperPrevBtn"><i class="fi-rr-arrow-small-left"></i></a>`;
            prevButton.addEventListener("click", () => {
                if (currentPaperPage > 0) {
                    currentPaperPage--;
                    updatePaperTable(currentPaperPage);
                }
            });
            paginationContainer.appendChild(prevButton);

            // Pagination numbers
            let startPage = Math.max(currentPaperPage - 2, 0);
            let endPage = Math.min(currentPaperPage + 2, totalPaperPages - 1);

            if (startPage > 0) {
                const ellipsis = document.createElement("li");
                ellipsis.innerHTML = `<a href="javascript:void(0);">...</a>`;
                paginationContainer.appendChild(ellipsis);
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageItem = document.createElement("li");
                pageItem.innerHTML = `<a href="javascript:void(0);">${i + 1}</a>`;
                pageItem.addEventListener("click", () => {
                    currentPaperPage = i;
                    updatePaperTable(currentPaperPage);
                });
                if (i === currentPaperPage) {
                    pageItem.classList.add("active");
                }
                paginationContainer.appendChild(pageItem);
            }

            if (endPage < totalPaperPages - 1) {
                const ellipsis = document.createElement("li");
                ellipsis.innerHTML = `<a href="javascript:void(0);">...</a>`;
                paginationContainer.appendChild(ellipsis);
            }

            // Next button
            const nextButton = document.createElement("li");
            nextButton.innerHTML = `<a href="javascript:void(0);" id="paperNextBtn"><i class="fi-rr-arrow-small-right"></i></a>`;
            nextButton.addEventListener("click", () => {
                if (currentPaperPage < totalPaperPages - 1) {
                    currentPaperPage++;
                    updatePaperTable(currentPaperPage);
                }
            });
            paginationContainer.appendChild(nextButton);
        }

        function updatePaperPagination() {
            generatePaperPaginationControls();
        }

        // Initialize the paper results table
        updatePaperTable(currentPaperPage);
    </script>
    <?php
    if (isset($_GET['showModal']) && $_GET['showModal'] == 1): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const loginModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
                loginModal.show();
            });
        </script>
    <?php endif; ?>


</body>

</html>