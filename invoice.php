<?php
session_start();
if (!(isset($_SESSION["client_id"]) || isset($_COOKIE["remember_me"]))) {
    header("Location: /index?showModal=1");
    exit();
}

if (!isset($_GET["invoice_no"])) {
    header("Location: 404");
    exit();
}

include("api/client/services/invoiceService.php");
include("api/config/dbconnection.php");
$InvoiceService = new InvoiceService();
$invoice = $InvoiceService->getInvoiceById($_GET["invoice_no"]);
if (!$invoice) {
    header("Location: 404");
    exit();
}

if ($invoice["status"] == "Pending") {
    header("Location: profile");
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    $contact = "active";
    $papers = "";
    $leadboard = "";
    include("includes/header.php"); ?>

    <div id="smooth-wrapper">
        <div id="smooth-content">
            <main>
                <section
                    class="ed-contact ed-contact--style2 mt-5 pt-5 position-relative">
                    <div class="container ed-container mt-5">
                        <div class=" d-flex justify-content-end">
                            <div class="ed-topbar__info-buttons my-3 text-end">
                                <button onclick="downloadPdf();" type="button" class="bg-dark register-btn w-auto">
                                    <i class="fa fa-download me-2 "></i> Download Invoice
                                </button>
                            </div>
                        </div>
                        <div class="col-12 shadow-sm" id="pdf-content">
                            <div class=" invoice-2  ">
                                <div class="invoice-headar  ">
                                    <div class="row">
                                        <div class="col-6 ">
                                            <div class="invoice-logo ">
                                                <!-- logo started -->
                                                <div class="logo">
                                                    <img src="./assets/images/logo.png" alt="logo">
                                                </div>
                                                <!-- logo ended -->
                                            </div>
                                        </div>
                                        <div class="col-6 ">
                                            <div class="invoice-id">
                                                <div class="info text-end">
                                                    <h1 class="inv-header-1">Invoice</h1>
                                                    <p id="invoice_no" class="mb-1">Invoice Number: <span class="fw-medium"><?= $invoice["invoice_no"]; ?></span></p>
                                                    <p class="mb-0">Invoice Date: <span class="fw-medium"><?= (new DateTime($invoice["created_at"]))->format('d M Y \a\t h.i A'); ?> </span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="invoice-top bg-white p-5">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="invoice-number mb-30 ">
                                            <h6 class="inv-title-1" style="color: #2ca347;">Invoice To</h6>
                                            <h5 id="client_name" class="name"><?= $fullName ?></h5>
                                            <p class="invo-addr-1">
                                                <?= $address ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <div class="invoice-number mb-30">
                                            <div class="invoice-number-inner text-end">
                                                <h6 class="inv-title-1" style="color: #2ca347;">Invoice From</h4>
                                                    <h5 class="name">EPS TOPIK</h5>
                                                    <p class="invo-addr-1">
                                                        경북 칠곡군 왜관읍 2산업단지3길 97 센템빌 102동<br />
                                                        aouranetwork@gmail.com<br />
                                                    </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white p-5">
                                <div class="row">
                                    <table class=" table table-striped table-hover">
                                        <thead>
                                            <tr class="" style="height: 60px;">
                                                <th scope="col">Item</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="" style="height: 60px;">
                                                <td><?= $invoice["exam_id"] ? $invoice["exam_date"] . " " . $invoice["start_time"] . " - " . $invoice["end_time"] . " Exam" : $invoice["package_name"] ?></td>
                                                <td>LKR <?= $invoice["exam_id"] ? $invoice["exam_price"] : $invoice["package_price"] ?></td>
                                                <td>1</td>
                                                <td>LKR <?= $invoice["exam_id"] ? $invoice["exam_price"] : $invoice["package_price"] ?></td>
                                            </tr>
                                            <tr class="" style="height: 60px;">
                                                <td></td>
                                                <td></td>
                                                <td class=" fw-medium" style="color: #2ca347;">Grand Total</td>
                                                <td>LKR <?= $invoice["exam_id"] ? $invoice["exam_price"] : $invoice["package_price"] ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="invoice-bottom bg-white p-5">
                                <div class="row">
                                    <div class="col-lg-6 col-md-5 col-sm-5">
                                        <div class="payment-method mb-30">
                                            <h6 class="inv-title-1" style="color: #2ca347;">Payment Method</h6>
                                            <ul class="payment-method-list-1 text-14">
                                                <?php
                                                if ($invoice["payment_methods_payment_method_id"] == 1) {
                                                ?>
                                                    <li><span class=" fw-semibold">Paid by PayHere Payment Gateway</li>
                                                <?php
                                                } else {
                                                ?>
                                                    <li><span class=" fw-semibold">Account No:</span> 00 123 647 840</li>
                                                    <li><span class=" fw-semibold">Account Name:</span> Jhon Doe</li>
                                                    <li><span class=" fw-semibold">Branch Name:</span> xyz</li>
                                                <?php
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class=" col-lg-6 col-md-7 col-sm-7">
                                        <div class="terms-conditions mb-30">
                                            <h6 style="color: #2ca347;" class="inv-title-1">Terms & Conditions</h6>
                                            <p>This is a computer-generated invoice. For inquiries or disputes, please contact us.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="" style="background-color: #2ca347 ">
                                <div class="row g-0">
                                    <div class="row py-4 text-white">
                                        <div class="col-4 text-center d-flex justify-content-center align-items-center">
                                            <span class="bg-white me-2 p-2 rounded-circle d-inline-block" style="width: 40px; height: 40px;">
                                                <i class="fa fa-phone fs-4" style="color: #2ca347;" aria-hidden="true"></i>
                                            </span>
                                            <p class="text-white mb-0">+94 71 44 4404</p>
                                        </div>
                                        <div class="col-4 text-center d-flex justify-content-center align-items-center">
                                            <span class="bg-white me-2 p-2 rounded-circle d-inline-block" style="width: 40px; height: 40px;">
                                                <i class="fa fa-envelope fs-4" style="color: #2ca347;" aria-hidden="true"></i>
                                            </span>
                                            <p class="text-white mb-0">aouranetwork@gmail.com</p>
                                        </div>
                                        <div class="col-4 text-center d-flex justify-content-center align-items-center">
                                            <span class="bg-white me-2 p-2 rounded-circle d-inline-block" style="width: 40px; height: 40px;">
                                                <i class="fa fa-map-marker fs-4" style="color: #2ca347;" aria-hidden="true"></i>
                                            </span>
                                            <p class="text-white mb-0">경북 칠곡군 왜관읍 2산업단지3길 </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-5"></div>
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
    <script src="assets/plugins/js/jQuery.print.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.2/jspdf.umd.min.js"></script> -->
    <script src="assets/plugins/js/html2pdf bundle.min.js"></script>
    <script>
        function downloadPdf() {
            var invoice_no = $("#invoice_no").text();
            var client_name = $("#client_name").text();
            const element = document.getElementById('pdf-content');
            document.title = `${invoice_no} - ${client_name} Invoice`;
            const options = {
                margin: 0,
                html2canvas: {
                    scale: 2
                }
            };
            html2pdf().set(options).from(element).save();
        }
    </script>
</body>

</html>