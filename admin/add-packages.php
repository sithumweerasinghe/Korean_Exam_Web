<?php
include '../api/request-filters/admin_request_filter.php';
include '../api/admin/services/init.php';

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
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token']; ?>">

    <title>Topick Sir | Admin | Add Packages</title>
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
    <?php include 'include/sidebar.php' ?>
    <main class="dashboard-main">
        <?php include 'include/header.php';
        $incomeService = new IncomeService();
        $paperService = new PaperService();
        $packageService = new PackageService();
        $invoices = $incomeService->getInvoices();
        $papers = $paperService->getAllPapers();
        $options = $packageService->getAllOptions();
        ?>
        <div class="dashboard-main-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                <h6 class="fw-semibold mb-0">Package List</h6>
                <ul class="d-flex align-items-center gap-2">
                    <li class="fw-medium">
                        <a href="/dashboard" class="d-flex align-items-center gap-1 hover-text-primary">
                            <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                            Dashboard
                        </a>
                    </li>
                    <li>-</li>
                    <li class="fw-medium">Package List</li>
                </ul>
            </div>
            <div class="modal fade" id="addPackageModel" tabindex="-1" aria-labelledby="addPackageModelLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addPackageModelLabel">Add Packages</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addPackageForm">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Package Name</label>
                                    <input type="text" class="form-control" id="name" />
                                </div>
                                <div class="mb-3">
                                    <label for="des" class="form-label">Package Description</label>
                                    <input type="text" class="form-control" id="des" />
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Package Price</label>
                                    <input type="number" class="form-control" id="price" />
                                </div>
                                <div class="mb-3">
                                    <label for="months" class="form-label">Valid Months</label>
                                    <input type="number" class="form-control" id="months" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Package Options</label>
                                    <?php
                                    $options = $packageService->getAllOptions();
                                    if (!empty($options)) {
                                        foreach ($options as $option) {
                                            echo '<div class="form-check style-check mb-1 d-flex align-items-center">
                                <input class="form-check-input border border-neutral-300" type="checkbox" value="' . htmlspecialchars($option['id']) . '" id="option-' . htmlspecialchars($option['id']) . '">
                                <label class="form-check-label" for="option-' . htmlspecialchars($option['id']) . '">' . htmlspecialchars($option['package_options']) . '</label>
                                </div>';
                                        }
                                    } else {
                                        echo '<p>No options available.</p>';
                                    }
                                    ?>
                                </div>

                                <!-- Section for Adding Papers -->
                                <div class="mb-3">
                                    <label class="form-label">Add Papers</label>
                                    <div id="paperContainer">
                                        <!-- Dynamic Papers Will Be Added Here -->
                                    </div>
                                    <button type="button" class="btn btn-success mt-2" id="addPaperBtn">Add Paper</button>
                                </div>

                                <button id="save-button" type="submit" class="btn btn-primary mt-3" data-token="<?= $_SESSION['csrf_token'] ?>">Save Changes</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal fade" id="editPackageModal" tabindex="-1" aria-labelledby="editPackageModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPackageModalLabel">Edit Package</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editPackageForm">
                                <input type="hidden" id="packageId" />
                                <div class="mb-3">
                                    <label for="editPackageName" class="form-label">Package Name</label>
                                    <input type="text" class="form-control" id="editPackageName" />
                                </div>
                                <div class="mb-3">
                                    <label for="editPackageDescription" class="form-label">Package Description</label>
                                    <input type="text" class="form-control" id="editPackageDescription" />
                                </div>
                                <div class="mb-3">
                                    <label for="editPackagePrice" class="form-label">Package Price</label>
                                    <input type="number" class="form-control" id="editPackagePrice" />
                                </div>
                                <div class="mb-3">
                                    <label for="editValidMonths" class="form-label">Valid Months</label>
                                    <input type="number" class="form-control" id="editValidMonths" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Package Options</label>
                                    <div id="editPackageOptions"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Associated Papers</label>
                                    <div id="editPackagePapers"></div>
                                    <button type="button" class="btn btn-success" id="editAddPaperBtn">Add Paper</button>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



            <div class="card">
                <div class="card-header d-flex flex-wrap align-items-center justify-content-end gap-3">
                    <button class="btn btn-success-600 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addPackageModel">
                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        Add New Package
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">S.L</th>
                                    <th scope="col">Package Name</th>
                                    <th scope="col">Package Description</th>
                                    <th scope="col">Package Price</th>
                                    <th scope="col">Valid Months</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $packages = $packageService->getAllPackages();
                                if (!empty($packages)):
                                    foreach ($packages as $index => $package):
                                ?>
                                        <tr>
                                            <td><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></td>
                                            <td><?= $package['package_name']; ?></td>
                                            <td><?= $package['package_description']; ?></td>
                                            <td>LKR <?= $package['package_price']; ?></td>
                                            <td><?= $package['valid_months']; ?></td>
                                            <td>
                                                <button
                                                    class="edit-btn w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    data-id="<?= $package['id']; ?>"
                                                    data-package-name="<?= $package['package_name']; ?>"
                                                    data-package-des="<?= $package['package_description']; ?>"
                                                    data-package-price="<?= $package['package_price']; ?>"
                                                    data-package-valid-months="<?= $package['valid_months']; ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editPackageModal">
                                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-secondary-light">No Packages found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-main-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                <h6 class="fw-semibold mb-0">Package Options</h6>
            </div>
            <div class="modal fade" id="addOptionModel" tabindex="-1" aria-labelledby="addOptionModelLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addOptionModelLabel">Add Option</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addPackageOptionsForm">
                                <div class="mb-3">
                                    <label for="optionName" class="form-label">Option Name</label>
                                    <input type="text" class="form-control" id="optionName" />
                                </div>
                                <button id="save-button" type="submit" class="btn btn-primary"
                                    data-token="<?= $_SESSION['csrf_token'] ?>">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editOptionModel" tabindex="-1" aria-labelledby="editOptionModelLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editOptionModelLabel">Edit Package Option</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editPackageOptionForm">
                                <input type="hidden" id="optionId" />
                                <div class="mb-3">
                                    <label for="editOptionName" class="form-label">Option Name</label>
                                    <input type="text" class="form-control" id="editOptionName" />
                                </div>
                                <button id="update-button" type="submit" class="btn btn-primary">Update Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex flex-wrap align-items-center justify-content-end gap-3">
                    <button class="btn btn-success-600 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addOptionModel">
                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        Add New Option
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">S.L</th>
                                    <th scope="col">Option Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                if (!empty($options)):
                                    foreach ($options as $index => $option):
                                ?>
                                        <tr>
                                            <td><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></td>
                                            <td><?= $option['package_options']; ?></td>
                                            <td>
                                                <button
                                                    class="edit-option-btn w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    data-id="<?= $option['id']; ?>"
                                                    data-option-name="<?= $option['package_options']; ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editOptionModel">
                                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-secondary-light">No Options found.</td>
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
    <script src="assets/js/packages.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        const options = <?php echo json_encode($options); ?>;
        const papers = <?php echo json_encode($papers); ?>;
        const packages = <?php echo json_encode($packages); ?>;
    </script>
</body>

</html>