<?php
include('../api/request-filters/admin_request_filter.php');
include('../api/admin/services/init.php');

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
  <link rel="stylesheet" href="assets/css/nice-select2.css">
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
    <?php include 'include/header.php';
    $incomeService = new IncomeService();
    $invoices = $incomeService->getInvoices();
    ?>
    <div class="dashboard-main-body">
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Invoice List</h6>
        <ul class="d-flex align-items-center gap-2">
          <li class="fw-medium">
            <a href="/dashboard" class="d-flex align-items-center gap-1 hover-text-primary">
              <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
              Dashboard
            </a>
          </li>
          <li>-</li>
          <li class="fw-medium">Invoice List</li>
        </ul>
      </div>
      <div class="modal fade" id="editPaymentStatusModal" tabindex="-1" aria-labelledby="editPaymentStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editPaymentStatusModalLabel">Edit Payment Status</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="editPaymentStatusForm">
                <input type="hidden" id="invoiceId" name="invoice_id">
                <div class="mb-3">
                  <label for="paymentStatus" class="form-label">Payment Status</label>
                  <select id="paymentStatus" name="payment_status" class="form-select">
                    <option value="1">Pending</option>
                    <option value="2">Paid</option>
                  </select>
                </div>
                <button id="save-button" type="submit" class="btn btn-primary"
                  data-token="<?= $_SESSION['csrf_token'] ?>">Save Changes</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="addPackageInvoiceModel" tabindex="-1" aria-labelledby="addPackageInvoiceModelLable" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addPackageInvoiceModelLable">Add Package Invoice</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <span class="mt-10">Select the user</span>
              <select class="text-dark w-100" id="seachable-select-3">
                <option data-display="Select">Select User</option>
                <?php
                $userService = new UserService();
                $users = $userService->getUsers();
                if (!empty($users)):
                  foreach ($users as $user):
                ?>
                    <option value="<?= $user['id']; ?>">
                      <?= $user['email']; ?>
                      <?php if (!empty($user['mobile'])): ?>
                        - <?= $user['mobile']; ?>
                      <?php endif; ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <span class="mt-10">Select the Package</span>
              <select class="text-dark w-100" id="seachable-select-4">
                <option data-display="Select">Select Package</option>
                <?php
                $packageService = new PackageService();
                $validPackages = $packageService->getAllPackages();
                if (!empty($validPackages)):
                  foreach ($validPackages as $package):
                ?>
                    <option value="<?= $package['id']; ?>">
                      <?= $package['package_name']; ?> - LKR <?= $package['package_price']; ?>.00
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <div class="col-12 d-flex justify-content-end align-items-center gap-3">
                <button id="package-invoice-save-button" type="submit" class="btn btn-success-600 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2 mt-10"
                  data-token="<?= $_SESSION['csrf_token'] ?>" onclick="savePackageInvoice()">Save Changes</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="addExamInvoiceModel" tabindex="-1" aria-labelledby="addExamInvoiceModelLable" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addExamInvoiceModelLable">Add Exam Invoice</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <span class="mt-10">Select the user</span>
              <select class="text-dark w-100" id="seachable-select">
                <option data-display="Select">Select User</option>
                <?php
                $userService = new UserService();
                $users = $userService->getUsers();
                if (!empty($users)):
                  foreach ($users as $user):
                ?>
                    <option value="<?= $user['id']; ?>">
                      <?= $user['email']; ?>
                      <?php if (!empty($user['mobile'])): ?>
                        - <?= $user['mobile']; ?>
                      <?php endif; ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <span class="mt-10">Select the Exam</span>
              <select class="text-dark w-100" id="seachable-select-2">
                <option data-display="Select">Select Exam</option>
                <?php
                $examService = new ExamService();
                $validExams = $examService->getValidExams();
                if (!empty($validExams)):
                  foreach ($validExams as $exam):
                ?>
                    <option value="<?= $exam['id']; ?>">
                      <?= $exam['paper_name']; ?> - <?= $exam['exam_date']; ?>
                      (<?= $exam['start_time']; ?> - <?= $exam['end_time']; ?>)
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <div class="col-12 d-flex justify-content-end align-items-center gap-3">
                <button id="exam-invoice-save-button" type="submit" class="btn btn-success-600 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2 mt-10"
                  data-token="<?= $_SESSION['csrf_token'] ?>" onclick="handleSaveButtonClick()">Save Changes</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
          <div class="d-flex align-items-center justify-content-between gap-3">
            <div class="icon-field">
              <input type="text" name="#0" class="form-control form-control-sm w-auto" placeholder="Search">
              <span class="icon">
                <iconify-icon icon="ion:search-outline"></iconify-icon>
              </span>
            </div>
          </div>
          <div class="d-flex gap-13">
            <button class="btn btn-success-600 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addExamInvoiceModel">
              <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
              Add Exam Invoice
            </button>
            <button class="btn btn-success-600 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addPackageInvoiceModel">
              <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
              Add Package Invoice
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive scroll-sm">
            <table class="table bordered-table mb-0">
              <thead>
                <tr>
                  <th scope="col">S.L</th>
                  <th scope="col">Invoice No</th>
                  <th scope="col">Mobile</th>
                  <th scope="col">Name</th>
                  <th scope="col">Issued Date</th>
                  <th scope="col">Amount</th>
                  <th scope="col">Payment Method</th>
                  <th scope="col">Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $invoices = $incomeService->getInvoices();
                if (!empty($invoices)):
                  foreach ($invoices as $index => $invoice):
                ?>
                    <tr>
                      <td><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></td>
                      <td class="text-primary-600"><?= $invoice['invoice_no']; ?></td>
                      <td><?= $invoice['mobile']; ?></td>
                      <td>
                        <h6 class="text-md mb-0 fw-medium"><?= htmlspecialchars($invoice['user_name']); ?></h6>
                      </td>
                      <td><?= date('d M Y', strtotime($invoice['issued_date'])); ?></td>
                      <td>LKR <?= number_format($invoice['amount'], 2); ?></td>
                      <td><?= $invoice['payment_method']; ?></td>
                      <td>
                        <?php
                        $statusClass = $invoice['payment_status'] == 1 ? 'bg-warning-focus text-warning-main' : ($invoice['payment_status'] == 2 ? 'bg-success-focus text-success-main' : 'bg-danger-focus text-danger-main');
                        $statusText = $invoice['payment_status'] == 1 ? 'Pending' : ($invoice['payment_status'] == 2 ? 'Paid' : 'Expired');
                        ?>
                        <span class="<?= $statusClass; ?> px-24 py-4 rounded-pill fw-medium text-sm"><?= $statusText; ?></span>
                      </td>
                      <td>
                        <button class="edit-btn w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                          data-id="<?= $invoice['id']; ?>"
                          data-status="<?= $invoice['payment_status']; ?>"
                          <?php $invoice['payment_method'] == 'Payment Gateway' ? print('disabled') : ''; ?>>
                          <iconify-icon icon="lucide:edit"></iconify-icon>
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="7" class="text-center text-secondary-light">No invoices found.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php include('include/footer.php'); ?>
  </main>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
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
  <script src="assets/js/InvoiceList.js"></script>
  <script src="assets/js/nice-select2.js"></script>
  <script src="assets/js/add-exam-invoice.js"></script>
  <script src="assets/js/add-package-invoice.js"></script>
  <script>
    var options = {
      searchable: true
    };
    NiceSelect.bind(document.getElementById("seachable-select"), options);
    NiceSelect.bind(document.getElementById("seachable-select-2"), options);
    NiceSelect.bind(document.getElementById("seachable-select-3"), options);
    NiceSelect.bind(document.getElementById("seachable-select-4"), options);
  </script>
</body>

</html>