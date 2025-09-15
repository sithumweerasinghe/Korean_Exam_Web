<?php include("includes/lang/lang-check.php"); ?>
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
        <div class="section-bg hero-bg-2 ">
          <section
            class="ed-breadcrumbs background-image"
            style="background-image: url('assets/images/breadcrumbs-bg.png');">
            <div class="container ">
              <div class="row justify-content-center">
                <div class="col-lg-6 col-md-6 col-12">
                  <div class="ed-breadcrumbs__content">
                    <h3 class="ed-breadcrumbs__title"><?= $translations['contact_page']['page_title']?></h3>
                    <ul class="ed-breadcrumbs__menu">
                      <li class="active"><a href="./"><?= $translations['contact_page']['breadcrumb_home']?></a></li>
                      <li>/ <?= $translations['contact_page']['breadcrumb_contact']?></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
        <section
          class="ed-contact ed-contact--style2  pt-5 position-relative">
          <div class="container ed-container pt-5">
            <div class="row">
              <div class="col-12">
                <div class="ed-contact__inner">
                  <div class="ed-contact__img">
                    <img
                      src="assets/images/contact/contact-img.webp"
                      alt="contact-img" />
                  </div>
                  <div class="ed-contact__form">
                    <div class="ed-contact__form-head">
                      <span class="ed-contact__form-sm-title"><?= $translations['contact_page']['page_title']?></span>
                      <h3
                        class="ed-contact__form-big-title  right">
                        <?= $translations['contact_page']['contact_us_title']?>
                      </h3>
                    </div>
                    <form
                      action="#"
                      method="post"
                      class="ed-contact__form-main">
                      <div class="form-group">
                        <input
                          type="text"
                          id="name"
                          name="name"
                          placeholder="<?= $translations['contact_page']['contact_form_name_placeholder']?>"
                          required />
                      </div>
                      <div class="form-group">
                        <input
                          type="email"
                          id="email"
                          name="email"
                          placeholder="<?= $translations['contact_page']['contact_form_email_placeholder']?>"
                          required />
                      </div>
                      <div class="form-group">
                        <textarea
                          id="message"
                          name="message"
                          placeholder="<?= $translations['contact_page']['contact_form_message_placeholder']?>"
                          required></textarea>
                      </div>
                      <div class="ed-contact__form-btn">
                        <button type="submit" class="ed-btn">
                        <?= $translations['contact_page']['contact_form_send_button']?><i
                            class="fi fi-rr-arrow-small-right"></i>
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <div class="ed-contact__card section-gap">
          <div class="container ed-container">
            <div class="row">
              <div class="col-lg-4 col-md-6 col-12">
                <div class="ed-contact__card-item">
                  <div class="ed-contact__card-icon">
                    <img
                      src="assets/images/icons/icon-white-phone.svg"
                      alt="icon-white-phone" />
                  </div>
                  <div class="ed-contact__card-info">
                    <a href="https://wa.me/94771444404" target="_blank">+94 77 144 4404</a>
                    <a href="https://wa.me/94771444404" target="_blank">+94 77 144 4404</a>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-6 col-12">
                <div class="ed-contact__card-item">
                  <div class="ed-contact__card-icon">
                    <img
                      src="assets/images/icons/icon-white-message.svg"
                      alt="icon-white-phone" />
                  </div>
                  <div class="ed-contact__card-info">
                    <a href="mailto:koreansirlk@gmail.com">koreansirlk@gmail.com</a>
                    <a href="mailto:aouranetwork@gmail.com">aouranetwork@gmail.com</a>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-6 col-12">
                <div class="ed-contact__card-item">
                  <div class="ed-contact__card-icon">
                    <img
                      src="assets/images/icons/icon-white-map.svg"
                      alt="icon-white-phone" />
                  </div>
                  <div class="ed-contact__card-info">
                    <a href="#" target="_blank">
                      경북 칠곡군 왜관읍 2산업단지3길 97 센템빌 102동<br />
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
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
  <script src="assets/js/language.js"></script>

</body>

</html>