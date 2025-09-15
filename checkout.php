<?php
session_start();
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
  <!-- Start Preloader  -->
  <div id="preloader">
    <div id="ed-preloader" class="ed-preloader">
      <div class="animation-preloader">
        <div class="spinner"></div>
      </div>
    </div>
  </div>
  <!-- End Preloader -->

  <!-- Custom Cursor Start -->
  <div id="ed-mouse">
    <div id="cursor-ball"></div>
  </div>
  <!-- Custom Cursor End -->

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
        <div class="section-bg hero-bg">
          <!-- Start Bredcrumbs Area -->
          <section
            class="ed-breadcrumbs background-image"
            style="background-image: url('assets/images/breadcrumbs-bg.png')">
            <div class="container">
              <div class="row justify-content-center">
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="ed-breadcrumbs__content">
                    <h3 class="ed-breadcrumbs__title">පිටවීමේ පිටුව</h3>
                    <ul class="ed-breadcrumbs__menu">
                      <li class="active"><a href="./">මුල් පිටුවට</a></li>
                      <li>/</li>
                      <li>පිටවීමේ පිටුව</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <!-- End Bredcrumbs Area -->
        </div>

        <!-- Checkout Page -->
        <section class="ed-checkout section-gap">
          <div class="container ed-container">
            <div class="row">
              <div class="col-12">
                <div class="ed-checkout__form-wrapper">
                  <div class="ed-checkout__form">
                    <div class="row">
                      <div class="col-lg-8 col-12 mg-top-40">
                        <div class="ed-checkout__section">
                          <h2 class="ed-checkout__section-title">
                            බිල්කරණ තොරතුරු
                          </h2>
                          <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                              <div class="ed-checkout__form-group">
                                <label class="ed-checkout__label">මුල් නම *</label>
                                <input
                                  class="ed-checkout__input"
                                  type="text"
                                  id="fname"
                                  value="<?= $firstName ?>"
                                  placeholder="මුල් නම"
                                  required />
                              </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                              <div class="ed-checkout__form-group">
                                <label class="ed-checkout__label">අවසන් නම *</label>
                                <input
                                  class="ed-checkout__input"
                                  type="text"
                                  id="lname"
                                  value="<?= $lastName ?>"
                                  placeholder="අවසන් නම"
                                  required />
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="ed-checkout__form-group">
                                <label class="ed-checkout__label">වීථි ලිපිනය *</label>
                                <input
                                  class="ed-checkout__input"
                                  type="text"
                                  id="address"
                                  value="<?= $address ?>"
                                  placeholder="නිවසේ අංකය සහ වීදි නම"
                                  required />
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="ed-checkout__form-group">
                                <label class="ed-checkout__label">City*</label>
                                <input
                                  class="ed-checkout__input"
                                  type="text"
                                  id="city"
                                  value="<?= $city ?>"
                                  placeholder="City"
                                  required />
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="ed-checkout__form-group">
                                <label class="ed-checkout__label">දුරකථන අංකය *</label>
                                <input
                                  class="ed-checkout__input"
                                  type="text"
                                  id="mobile"
                                  placeholder="දුරකථන අංකය "
                                  value="<?= $mobile ?>"
                                  required />
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="ed-checkout__form-group">
                                <label class="ed-checkout__label">විද්‍යුත් තැපැල් ලිපිනය *</label>
                                <input
                                  class="ed-checkout__input"
                                  type="text"
                                  id="email"
                                  value="<?= $email ?>"
                                  placeholder="විද්‍යුත් තැපැල් ලිපිනය"
                                  required />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4 col-12 mg-top-40">
                        <div
                          class="ed-checkout__section ed-checkout__section--order">
                          <h2 class="ed-checkout__section-title">මගේ ඇණවුම</h2>
                          <div class="ed-checkout__summary">
                            <div class="ed-checkout__summary-item">
                              <div class="ed-checkout__summary-item-name">
                                <a href="#"><?= $_GET["name"] ?> </a>
                              </div>
                              <span class="ed-checkout__summary-item-price">LKR <?= $_GET["price"] ?></span>
                            </div>
                          </div>
                          <div class="ed-cart__totals">
                            <div class="ed-cart__totals-row">
                              <span class="ed-cart__totals-label">මුළු මිල</span>
                              <span class="ed-cart__totals-value total-amount">LKR <?= $_GET["price"] ?></span>
                            </div>
                          </div>
                        </div>
                        <div class="ed-payment-infos">
                          <div class="ed-cart__summary-body">
                            <div
                              class="accordion ed-payment-accordion"
                              id="paymentAccordion">
                              <!-- Direct Bank Transfer -->
                              <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                  <button
                                    class="accordion-button d-flex justify-content-between"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne"
                                    aria-expanded="true"
                                    aria-controls="collapseOne">
                                    <input
                                      type="radio"
                                      class="form-check-input payment-checkbox"
                                      name="paymentMethod" id="directPayment" />
                                    <span>
                                      <span class="pm-check"></span>සෘජු බැංකු හුවමාරුව (Bank Transfer)
                                    </span>
                                  </button>
                                </h2>
                                <div
                                  id="collapseOne"
                                  class="accordion-collapse collapse "
                                  aria-labelledby="headingOne"
                                  data-bs-parent="#paymentAccordion">
                                  <div class="accordion-body">
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
                                </div>
                              </div>
                              <!-- Check Payments -->
                              <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                  <button
                                    class="accordion-button collapsed d-flex justify-content-between"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo"
                                    aria-expanded="false"
                                    aria-controls="collapseTwo">
                                    <input
                                      type="radio"
                                      class="form-check-input payment-checkbox"
                                      name="paymentMethod"
                                      id="paymentGateway" />
                                    <span>
                                      <span class="pm-check"></span>ගෙවීම් දොරටුව (Payment Gateway)
                                    </span>
                                  </button>
                                </h2>
                                <im
                                  id="collapseTwo"
                                  class="accordion-collapse collapse"
                                  aria-labelledby="headingTwo"
                                  data-bs-parent="#paymentAccordion">
                                  <div class="accordion-body">
                                    ගෙවීම් දොරටුවක් හරහා ඔබට මිනිත්තු කිහිපයකදීම විශේෂිත ආරක්ෂිත මාර්ගවලින් ගෙවීම් කිරීමේ හැකියාව ලැබේ. ක්‍රෙඩිට් කාඩ්, ඩෙබිට් කාඩ්, බැංකු මාරුම් හෝ රැකියාවෙන්ම ගෙවීම් පහසුකම් ලබා ගත හැක.
                                  </div>
                                  <img src="assets/images/payment-methods.png" alt="payment_methods_img">
                              </div>
                              <input type="hidden" id="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                            </div>
                          </div>
                        </div>
                        <button id="payhere-payment" onclick="placeOrder(<?= $_GET['id']; ?>, '<?= htmlspecialchars($_GET['name'], ENT_QUOTES); ?>', '<?= htmlspecialchars($_GET['price'], ENT_QUOTES); ?>', '<?= htmlspecialchars($_GET['isExam'], ENT_QUOTES); ?>');" class="ed-btn mt-3">
                          ඇණවුම් කරන්න<i
                            class="fi fi-rr-arrow-small-right"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div>
    </section>
    <!-- End Checkout Page -->

    </main>
    <?php include("includes/footer.php"); ?>
  </div>
  </div>

  <?php include("includes/models/login-model.php") ?>
  <?php include("includes/models/register-model.php") ?>

  <!-- Start Sidebar Cart -->
  <div
    class="offcanvas offcanvas-end ed-sidebar ed-sidebar-cart"
    tabindex="-1"
    id="edSidebarCart"
    aria-labelledby="offcanvasRightLabel">
    <div class="ed-sidebar-header">
      <h3 class="ed-sidebar-header-title">Add to cart</h3>
      <button
        type="button"
        class="text-reset"
        data-bs-dismiss="offcanvas"
        aria-label="Close">
        <i class="fi fi-rr-cross"></i>
      </button>
    </div>
    <div class="ed-sidebar-body">
      <!-- Single Cart Item  -->
      <div class="ed-sidebar-cart-item">
        <div class="ed-sidebar-cart-main">
          <div class="ed-sidebar-cart-img">
            <img src="assets/images/product/cart-1.png" alt="cart-1" />
          </div>
          <div class="ed-sidebar-cart-info">
            <span>1 x <strong>$64</strong></span>
            <a href="product-details.html">Digital marketing demo</a>
          </div>
        </div>
        <div class="ed-sidebar-cart-remove">
          <button type="button"><i class="fi-rr-cross"></i></button>
        </div>
      </div>

      <!-- Single Cart Item  -->
      <div class="ed-sidebar-cart-item">
        <div class="ed-sidebar-cart-main">
          <div class="ed-sidebar-cart-img">
            <img src="assets/images/product/cart-2.png" alt="cart-2" />
          </div>
          <div class="ed-sidebar-cart-info">
            <span>1 x <strong>$74</strong></span>
            <a href="product-details.html">Business solution book</a>
          </div>
        </div>
        <div class="ed-sidebar-cart-remove">
          <button type="button"><i class="fi-rr-cross"></i></button>
        </div>
      </div>

      <!-- Single Cart Item  -->
      <div class="ed-sidebar-cart-item">
        <div class="ed-sidebar-cart-main">
          <div class="ed-sidebar-cart-img">
            <img src="assets/images/product/cart-3.png" alt="cart-3" />
          </div>
          <div class="ed-sidebar-cart-info">
            <span>1 x <strong>$94</strong></span>
            <a href="product-details.html">Business type</a>
          </div>
        </div>
        <div class="ed-sidebar-cart-remove">
          <button type="button"><i class="fi-rr-cross"></i></button>
        </div>
      </div>
    </div>

    <div class="ed-sidebar-footer">
      <div class="ed-sidebar-cart-subtotal">
        <p>Subtotal:<span> $224</span></p>
        <a href="checkout.html" class="ed-sidebar-cart-btn">Checkout</a>
      </div>
    </div>
  </div>
  <!-- End Sidebar Cart -->

  <!-- Start Back To Top  -->
  <div class="progress-wrap">
    <svg
      class="progress-circle svg-content"
      width="100%"
      height="100%"
      viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
  </div>
  <!-- End Back To Top -->

  <!-- Jquery JS -->
  <script src="assets/plugins/js/jquery.min.js"></script>
  <script src="assets/plugins/js/jquery-migrate.js"></script>
  <!-- Bootstrap JS -->
  <script src="assets/plugins/js/bootstrap.min.js"></script>

  <!-- Gsap JS -->
  <script src="assets/plugins/js/gsap/gsap.js"></script>
  <script src="assets/plugins/js/gsap/gsap-scroll-to-plugin.js"></script>
  <script src="assets/plugins/js/gsap/gsap-scroll-smoother.js"></script>
  <script src="assets/plugins/js/gsap/gsap-scroll-trigger.js"></script>
  <script src="assets/plugins/js/gsap/gsap-split-text.js"></script>

  <!-- Wow JS -->
  <script src="assets/plugins/js/wow.min.js"></script>
  <!-- Owl Carousel JS -->
  <script src="assets/plugins/js/owl.carousel.min.js"></script>
  <!-- Swiper Slider JS -->
  <script src="assets/plugins/js/swiper-bundle.min.js"></script>
  <!-- Magnific Popup JS -->
  <script src="assets/plugins/js/magnific-popup.min.js"></script>
  <!-- CounterUp  JS -->
  <script src="assets/plugins/js/jquery.counterup.min.js"></script>
  <script src="assets/plugins/js/waypoints.min.js"></script>
  <!-- Nice Select JS -->
  <script src="assets/plugins/js/nice-select.min.js"></script>
  <!-- Back To Top JS -->
  <script src="assets/plugins/js/backToTop.js"></script>

  <script src="assets/js/clientScript.js"></script>

  <!-- Main JS -->
  <script src="assets/plugins/js/active.js"></script>
  <script src="assets/js/clientScript.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
  <!-- Checkbox JS -->
  <script type="text/javascript">
    document.querySelectorAll(".accordion-button").forEach((button) => {
      button.addEventListener("click", function() {
        const checkbox = this.querySelector(".payment-checkbox");
        if (checkbox) {
          checkbox.checked = true;
        }
      });
    });
  </script>
</body>

</html>