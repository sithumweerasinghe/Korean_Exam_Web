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

    <title>Topick Sir | Admin | Exam Time Slots</title>
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
        <?php include 'include/header.php'; ?>
        <div class="dashboard-main-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                <h6 class="fw-semibold mb-0">Time Slot List</h6>
                <ul class="d-flex align-items-center gap-2">
                    <li class="fw-medium">
                        <a href="/dashboard" class="d-flex align-items-center gap-1 hover-text-primary">
                            <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                            Dashboard
                        </a>
                    </li>
                    <li>-</li>
                    <li class="fw-medium">Time Slot List</li>
                </ul>
            </div>
            <div class="modal fade" id="addTimeSlotsModel" tabindex="-1" aria-labelledby="addTimeSlotsModelLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addTimeSlotsModelLabel">Add Time Slots</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addTimeSlotForm">
                                <div class="mb-3">
                                    <label for="startTime" class="form-label">Start Time</label>
                                    <input type="time" class="form-control" id="startTime" />
                                </div>
                                <div class="mb-3">
                                    <label for="endTime" class="form-label">End Time</label>
                                    <input type="time" class="form-control" id="endTime" />
                                </div>
                                <button id="save-button" type="submit" class="btn btn-primary"
                                    data-token="<?= $_SESSION['csrf_token'] ?>">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editTimeSlotsModel" tabindex="-1" aria-labelledby="editTimeSlotsModelLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editTimeSlotsModelLabel">Edit Time Slot</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editTimeSlotForm">
                                <input type="hidden" id="timeSlotId" />
                                <div class="mb-3">
                                    <label for="editStartTime" class="form-label">Start Time</label>
                                    <input type="time" class="form-control" id="editStartTime" />
                                </div>
                                <div class="mb-3">
                                    <label for="editEndTime" class="form-label">End Time</label>
                                    <input type="time" class="form-control" id="editEndTime" />
                                </div>
                                <button id="update-button" type="submit" class="btn btn-primary">Update Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex flex-wrap align-items-center justify-content-end gap-3">
                    <button class="btn btn-success-600 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addTimeSlotsModel">
                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        Add New Time Slots
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">S.L</th>
                                    <th scope="col">Start Time</th>
                                    <th scope="col">End Time</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $examService = new ExamService();
                                $time_slots = $examService->getTimeSlots();
                                if (!empty($time_slots)):
                                    foreach ($time_slots as $index => $time_slot):
                                ?>
                                        <tr>
                                            <td><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></td>
                                            <td><?= $time_slot['start_time']; ?></td>
                                            <td><?= $time_slot['end_time']; ?></td>
                                            <td>
                                                <button
                                                    class="edit-btn w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    data-id="<?= $time_slot['id']; ?>"
                                                    data-start-time="<?= $time_slot['start_time']; ?>"
                                                    data-end-time="<?= $time_slot['end_time']; ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editTimeSlotsModel">
                                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-secondary-light">No Time Slots found.</td>
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
    <script src="assets/js/time-slots.js"></script>
</body>

</html>