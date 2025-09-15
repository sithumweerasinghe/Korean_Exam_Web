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

    <title>Topick Sir | Admin | Schedule Exam</title>
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
        $examService = new ExamService();
        $exams = $examService->getExamTimeTable();
        $paperService = new PaperService();
        ?>
        <div class="dashboard-main-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                <h6 class="fw-semibold mb-0">Schedule Exam</h6>
                <ul class="d-flex align-items-center gap-2">
                    <li class="fw-medium">
                        <a href="/dashboard" class="d-flex align-items-center gap-1 hover-text-primary">
                            <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                            Dashboard
                        </a>
                    </li>
                    <li>-</li>
                    <li class="fw-medium">Schedule Exams</li>
                </ul>
            </div>
            <div class="modal fade" id="addExamModel" tabindex="-1" aria-labelledby="addExamModelLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addExamModelLabel">Schedule Exam</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="addTimeSlot" class="form-label">Add Time Slot</label>
                                <select class="form-control" id="addTimeSlot">
                                    <option value="">Select Time Slot</option>
                                    <?php
                                    $timeSlots = $examService->getTimeSlots();
                                    foreach ($timeSlots as $index => $timeSlot): ?>
                                        <option value="<?= $timeSlot['id']; ?>"><?= $timeSlot['start_time']; ?> - <?= $timeSlot['end_time']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="addExamDate" class="form-label">Add Exam Date</label>
                                <input type="date" class="form-control" id="addExamDate" />
                            </div>
                            <div class="mb-3">
                                <label for="addExamPaper" class="form-label">Add Exam Paper</label>
                                <select class="form-control" id="addExamPaper">
                                    <option value="">Select Exam Paper</option>
                                    <?php
                                    $paperData = $paperService->getAllNonSamplePapers();
                                    foreach ($paperData as $index => $paper): ?>
                                        <option value="<?= $paper['paper_id']; ?>"><?= $paper['paper_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="addExamPrice" class="form-label">Add Exam price</label>
                                <input type="number" class="form-control" id="addExamPrice" min="0" />
                            </div>
                            <button id="save-button" type="submit" class="btn btn-primary"
                                data-token="<?= $_SESSION['csrf_token'] ?>">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editExamSlotsModel" tabindex="-1" aria-labelledby="editExamSlotsModelLable" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editExamSlotsModelLable">Edit Exam</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="examTimeTableID" />
                            <div class="mb-3">
                                <label for="editTimeSlot" class="form-label">Edit Time Slot</label>
                                <select class="form-control" id="editTimeSlot">
                                    <option value="">Select Time Slot</option>
                                    <?php
                                    $timeSlots = $examService->getTimeSlots();
                                    foreach ($timeSlots as $index => $timeSlot): ?>
                                        <option value="<?= $timeSlot['id']; ?>"><?= $timeSlot['start_time']; ?> - <?= $timeSlot['end_time']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editExamDate" class="form-label">Edit Exam Date</label>
                                <input type="date" class="form-control" id="editExamDate" />
                            </div>
                            <div class="mb-3">
                                <label for="editExamPaper" class="form-label">Edit Exam Paper</label>
                                <select class="form-control" id="editExamPaper">
                                    <option value="">Select Exam Paper</option>
                                    <?php
                                    $paperData = $paperService->getAllNonSamplePapers();
                                    foreach ($paperData as $index => $paper): ?>
                                        <option value="<?= $paper['paper_id']; ?>"><?= $paper['paper_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editExamPrice" class="form-label">Edit Exam price</label>
                                <input type="number" class="form-control" id="editExamPrice" min="0" value="<?= $paper['exam_price']; ?>"/>
                            </div>
                            <button id="update-button" type="submit" class="btn btn-primary">Update Changes</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex flex-wrap align-items-center justify-content-end gap-3">
                    <button class="btn btn-success-600 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addExamModel">
                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        Schedule Exam
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">S.L</th>
                                    <th scope="col">Paper Name</th>
                                    <th scope="col">Start Time</th>
                                    <th scope="col">End Time</th>
                                    <th scope="col">Exam Date</th>
                                    <th scope="col">Exam Price</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $exams = $examService->getExamTimeTable();
                                if (!empty($exams)):
                                    foreach ($exams as $index => $exam):
                                ?>
                                        <tr>
                                            <td><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></td>
                                            <td><?= $exam['paper_name']; ?></td>
                                            <td><?= $exam['start_time']; ?></td>
                                            <td><?= $exam['end_time']; ?></td>
                                            <td><?= $exam['exam_date']; ?></td>
                                            <td><?= $exam['exam_price']; ?></td>
                                            <td>
                                                <button
                                                    class="edit-btn w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    data-id="<?= $exam['id']; ?>"
                                                    data-time-slot="<?= $exam['time_slot_id']; ?>"
                                                    data-paper-id="<?= $exam['paper_id']; ?>"
                                                    data-exam-date="<?= $exam['exam_date']; ?>"
                                                    data-exam-price="<?= $exam['exam_price']; ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editExamSlotsModel">
                                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-secondary-light">No Exams yet.</td>
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
    <script src="assets/js/add-exam.js"></script>
</body>

</html>