<?php
session_start();
if (isset($_SESSION['admin_id']) || isset($_COOKIE['remember_me'])) {
    header("Location: dashboard");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <meta name="description" content="EPS Topick Sir | Korean language proficiency test and secure your employment abroad." />
  <meta name="keywords" content="EPS Topick Sir | Korean language proficiency test and secure your employment abroad." />
  <meta name="author" content="Virul Nirmala Wickramasinghe" />


  <title>EPS Topick Sir | Admin</title>
  <meta name="description" content="Prepare for the EPS-TOPIK exam with our expert training program. Access study materials, mock tests, and personalized guidance to excel in your Korean language proficiency test and secure your employment abroad.">
  <meta property="og:url" content="https://topicksir.com">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Topick Sir">
  <meta property="og:description" content="Prepare for the EPS-TOPIK exam with our expert training program. Access study materials, mock tests, and personalized guidance to excel in your Korean language proficiency test and secure your employment abroad.">
  <meta property="og:image" content="https://ogcdn.net/e4b8c678-7bd5-445d-ba03-bfaad510c686/v4/epstopicksir.com/epstopicksir.com/https%3A%2F%2Fopengraph.b-cdn.net%2Fproduction%2Fimages%2Fda4ae288-c222-4d6c-9cbf-cb3e9ec3300c.png%3Ftoken%3DPV1V5JqRR5UcqNJrenyT6Lh_TEkyX-tGppr1-x4fCfw%26height%3D864%26width%3D864%26expires%3D33268049346/og.png">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="koreansir.lk">
  <meta property="twitter:url" content="https://topicksir.com">
  <meta name="twitter:title" content="Topick Sir">
  <meta name="twitter:description" content="Prepare for the EPS-TOPIK exam with our expert training program. Access study materials, mock tests, and personalized guidance to excel in your Korean language proficiency test and secure your employment abroad.">
  <meta name="twitter:image" content="https://ogcdn.net/e4b8c678-7bd5-445d-ba03-bfaad510c686/v4/epstopicksir.com/epstopicksir.com/https%3A%2F%2Fopengraph.b-cdn.net%2Fproduction%2Fimages%2Fda4ae288-c222-4d6c-9cbf-cb3e9ec3300c.png%3Ftoken%3DPV1V5JqRR5UcqNJrenyT6Lh_TEkyX-tGppr1-x4fCfw%26height%3D864%26width%3D864%26expires%3D33268049346/og.png">


  <link rel="shortcut icon" href="../assets/images/favicon.png" />
  <link rel="stylesheet" href="assets/css/remixicon.css">
  <link rel="stylesheet" href="assets/css/lib/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/lib/apexcharts.css">
  <link rel="stylesheet" href="assets/css/lib/dataTables.min.css">
  <link rel="stylesheet" href="assets/css/lib/editor-katex.min.css">
  <link rel="stylesheet" href="assets/css/lib/editor.atom-one-dark.min.css">
  <link rel="stylesheet" href="assets/css/lib/editor.quill.snow.css">
  <link rel="stylesheet" href="assets/css/lib/flatpickr.min.css">
  <link rel="stylesheet" href="assets/css/lib/full-calendar.css">
  <link rel="stylesheet" href="assets/css/lib/jquery-jvectormap-2.0.5.css">
  <link rel="stylesheet" href="assets/css/lib/magnific-popup.css">
  <link rel="stylesheet" href="assets/css/lib/slick.css">
  <link rel="stylesheet" href="assets/css/lib/prism.css">
  <link rel="stylesheet" href="assets/css/lib/file-upload.css">
  <link rel="stylesheet" href="assets/css/lib/audioplayer.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="../style.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body>
  <div id="preloader">
    <div id="ed-preloader" class="ed-preloader">
      <div class="animation-preloader">
        <div class="spinner"></div>
      </div>
    </div>
  </div>
  <section class="auth bg-base d-flex flex-wrap">
    <div class="auth-left d-lg-block d-none" style="background-image: url('assets/images/login/login-bg.jpg'); background-size: cover; background-position: center;">
    </div>
    <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
      <div class="max-w-464-px mx-auto w-100">
        <div>
          <a href="index.html" class="mb-40 max-w-135-px">
            <img src="assets/images/login/logo.png" alt="">
          </a>
          <h4 class="mb-12">Sign In to your Account</h4>
          <p class="mb-32 text-secondary-light text-lg">Welcome back Admin! please enter your detail</p>
        </div>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="icon-field mb-16">
          <span class="icon top-50 translate-middle-y">
            <iconify-icon icon="mage:email"></iconify-icon>
          </span>
          <input type="email" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Email">
        </div>
        <div class="position-relative mb-20">
          <div class="icon-field">
            <span class="icon top-50 translate-middle-y">
              <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
            </span>
            <input type="password" class="form-control h-56-px bg-neutral-50 radius-12" id="your-password" placeholder="Password">
          </div>
          <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#your-password"></span>
        </div>
        <div class="">
          <div class="d-flex justify-content-between gap-2">
            <div class="form-check style-check d-flex align-items-center">
              <input class="form-check-input border border-neutral-300" type="checkbox" value="" id="remeber">
              <label class="form-check-label" for="remeber">Remember me </label>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-success-600 text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32" onclick="sendSignInRequest()"> Sign In</button>
      </div>
    </div>
  </section>
  <script src="../assets/plugins/js/jquery.min.js"></script>
  <script src="../assets/plugins/js/jquery-migrate.js"></script>
  <script src="assets/js/lib/jquery-3.7.1.min.js"></script>
  <script src="assets/js/lib/bootstrap.bundle.min.js"></script>
  <script src="assets/js/lib/apexcharts.min.js"></script>
  <script src="assets/js/lib/dataTables.min.js"></script>
  <script src="assets/js/lib/iconify-icon.min.js"></script>
  <script src="assets/js/lib/jquery-ui.min.js"></script>
  <script src="assets/js/lib/jquery-jvectormap-2.0.5.min.js"></script>
  <script src="assets/js/lib/jquery-jvectormap-world-mill-en.js"></script>
  <script src="assets/js/lib/magnifc-popup.min.js"></script>
  <script src="assets/js/lib/slick.min.js"></script>
  <script src="assets/js/lib/prism.js"></script>
  <script src="assets/js/lib/file-upload.js"></script>
  <script src="assets/js/lib/audioplayer.js"></script>
  <script src="../assets/plugins/js/active.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <script src="assets/js/app.js"></script>
  <script src="assets/js/request.js"></script>

  <script>
    function initializePasswordToggle(toggleSelector) {
      $(toggleSelector).on('click', function() {
        $(this).toggleClass("ri-eye-off-line");
        var input = $($(this).attr("data-toggle"));
        if (input.attr("type") === "password") {
          input.attr("type", "text");
        } else {
          input.attr("type", "password");
        }
      });
    }
    initializePasswordToggle('.toggle-password');
  </script>

</body>

</html>