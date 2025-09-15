<?php
include('../api/request-filters/admin_request_filter.php');
include('../api/admin/services/init.php');
include('../utils/utils.php');
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


  <title>Topick Sir | Admin</title>
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
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">


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
</head>

<body>
  <div id="preloader">
    <div id="ed-preloader" class="ed-preloader">
      <div class="animation-preloader">
        <div class="spinner"></div>
      </div>
    </div>
  </div>
  <?php include 'include/sidebar.php' ?>
  <main class="dashboard-main">
    <?php
    include 'include/header.php';
    $userService = new UserService();
    $paperService = new PaperService();
    $incomeService = new incomeService();
    ?>
    <div class="dashboard-main-body">
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Dashboard</h6>
        <ul class="d-flex align-items-center gap-2">
          <li class="fw-medium">
            <a href="/dashboard" class="d-flex align-items-center gap-1 hover-text-primary">
              <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
              Dashboard
            </a>
          </li>
        </ul>
      </div>
      <div class="row gy-4 mb-24">
        <div class="col-xxl-12">
          <div class="card radius-8 border-0 p-20">
            <div class="row gy-4">
              <div class="col-xxl-4">
                <div class="card p-3 radius-8 shadow-none bg-gradient-dark-start-1 mb-12">
                  <div class="card-body p-0">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-0">
                      <div class="d-flex align-items-center gap-2 mb-12">
                        <span class="mb-0 w-48-px h-48-px bg-base text-pink text-2xl flex-shrink-0 d-flex justify-content-center align-items-center rounded-circle h6">
                          <i class="ri-group-fill"></i>
                        </span>
                        <div>
                          <span class="mb-0 fw-medium text-secondary-light text-lg">Total Students</span>
                        </div>
                      </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-8">
                      <h5 class="fw-semibold mb-0"><?= $userService->getUserCount() ?></h5>
                      <p class="text-sm mb-0 d-flex align-items-center gap-8">
                        <span class="text-white px-1 rounded-2 fw-medium bg-success-main text-sm"><?= formatNumber($userService->getStudentCountCurrentMonth()) ?></span>
                        This Month
                      </p>
                    </div>
                  </div>
                </div>
                <div class="card p-3 radius-8 shadow-none bg-gradient-dark-start-2 mb-12">
                  <div class="card-body p-0">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-0">
                      <div class="d-flex align-items-center gap-2 mb-12">
                        <span class="mb-0 w-48-px h-48-px bg-base text-purple text-2xl flex-shrink-0 d-flex justify-content-center align-items-center rounded-circle h6">
                          <i class="ri-booklet-line"></i>
                        </span>
                        <div>
                          <span class="mb-0 fw-medium text-secondary-light text-lg">Total Papers</span>
                        </div>
                      </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-8">
                      <h5 class="fw-semibold mb-0"><?= formatNumber($paperService->getPaperCount()) ?></h5>
                      <p class="text-sm mb-0 d-flex align-items-center gap-8">
                        <span class="text-white px-1 rounded-2 fw-medium bg-success-main text-sm"><?= formatNumber($paperService->getPapersPublishedThisMonth()) ?></span>
                        This Month
                      </p>
                    </div>
                  </div>
                </div>
                <div class="card p-3 radius-8 shadow-none bg-gradient-dark-start-3 mb-0">
                  <div class="card-body p-0">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-0">
                      <div class="d-flex align-items-center gap-2 mb-12">
                        <span class="mb-0 w-48-px h-48-px bg-base text-info text-2xl flex-shrink-0 d-flex justify-content-center align-items-center rounded-circle h6">
                          <i class="ri-money-dollar-circle-fill"></i>
                        </span>
                        <div>
                          <span class="mb-0 fw-medium text-secondary-light text-lg">Overall Revenue</span>
                        </div>
                      </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-8">
                      <h5 class="fw-semibold mb-0">LKR <?= formatNumber($incomeService->getTotalIncome()); ?></h5>
                      <p class="text-sm mb-0 d-flex align-items-center gap-8">
                        <span class="text-white px-1 rounded-2 fw-medium bg-success-main text-sm">LKR <?= formatNumber($incomeService->getMonthlyIncome()) ?></span>
                        This Month
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xxl-8">
                <div class="card-body p-0">
                  <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                    <h6 class="mb-2 fw-bold text-lg">Average Enrollment Rate
                    </h6>
                  </div>
                  <?php
                  $paymentCounts = $incomeService->getPaymentMethodCounts();
                  ?>
                  <ul class="d-flex flex-wrap align-items-center justify-content-center mt-3 gap-3">
                    <?php if ($paymentCounts && count($paymentCounts) > 0): ?>
                      <?php foreach ($paymentCounts as $payment): ?>
                        <li class="d-flex align-items-center gap-2">
                          <span
                            class="w-12-px h-12-px rounded-circle <?= ($payment['method'] === 'Payment Gateway') ? 'bg-primary-600' : 'bg-success-main'; ?>"></span>
                          <span class="text-secondary-light text-sm fw-semibold">
                            <?= ($payment['method'] === 'Payment Gateway') ? 'Paid From Payment Gateway:' : 'Direct Bank Transfer:'; ?>
                            <span class="text-primary-light fw-bold"><?= $payment['count']; ?></span>
                          </span>
                        </li>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <li class="text-secondary-light text-sm fw-semibold">No payment data available.</li>
                    <?php endif; ?>
                  </ul>
                  <div class="mt-40">
                    <div id="enrollmentChart" class="apexcharts-tooltip-style-1"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xxl-12">
          <div class="card h-100">
            <div class="card-header">
              <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                <h6 class="mb-2 fw-bold text-lg mb-0">Recent Registerd Students</h6>
                <a href="users-list" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                  View All
                  <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                </a>
              </div>
            </div>
            <div class="card-body p-24">
              <div class="table-responsive scroll-sm">
                <table class="table bordered-table mb-0">
                  <thead>
                    <tr>
                      <th scope="col">Name</th>
                      <th scope="col">Mobile No</th>
                      <th scope="col">Registered Date</th>
                      <th scope="col">Email</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $recentStudents = $userService->getRecentlyRegisteredStudents();
                    if (!empty($recentStudents)): ?>
                      <?php foreach ($recentStudents as $student): ?>
                        <tr>
                          <td>
                            <span class="text-secondary-light"><?= htmlspecialchars($student['full_name']); ?></span>
                          </td>
                          <td>
                            <span class="text-secondary-light"><?= htmlspecialchars($student['mobile']); ?></span>
                          </td>
                          <td>
                            <span class="text-secondary-light"><?= htmlspecialchars($student['registered_date']); ?></span>
                          </td>
                          <td>
                            <span class="text-secondary-light"><?= htmlspecialchars($student['email']); ?></span>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="4" class="text-center">
                          <span class="text-secondary-light">No students registered this month.</span>
                        </td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include('include/footer.php'); ?>
  </main>
  <script src="../assets/plugins/js/jquery.min.js"></script>
  <script src="../assets/plugins/js/jquery-migrate.js"></script>
  <script src="../assets/plugins/js/active.js"></script>
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
  <script src="assets/js/app.js"></script>
  <script src="assets/js/request.js"></script>
  <script src="assets/js/chart.js"></script>

</body>

</html>