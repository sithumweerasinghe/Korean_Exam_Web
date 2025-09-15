<?php
include '../api/request-filters/admin_request_filter.php';
include '../api/admin/services/init.php';
$viewGroup = isset($_GET['viewGroup']) && $_GET['viewGroup'] === 'true';
$paperId = isset($_GET['paper-id']) ? $_GET['paper-id'] : null;
$viewQuestions = isset($_GET['viewQuestions']) && $_GET['viewQuestions'] === 'true';
$groupId = isset($_GET['group-id']) ? $_GET['group-id'] : null;
$paper_name = isset($_GET['paper-name']) ? $_GET['paper-name'] : null;
$group_name = isset($_GET['question-group-name']) ? $_GET['question-group-name'] : null;
$add_question = isset($_GET['add-question']) && $_GET['add-question'] === 'true';
$question_id = isset($_GET['question-id']) ? $_GET['question-id'] : null;
$edit_question = isset($_GET['edit-question']) && $_GET['edit-question'] === 'true';
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

    <title>Topick Sir | Admin | Add Papers</title>
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
        <div class="modal fade" id="editPaperModal" tabindex="-1" aria-labelledby="editPaperModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPaperModalLabel">Edit Paper</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editPaperForm" method="post">
                            <input type="hidden" name="paper_id" id="paperId">
                            <div class="mb-3">
                                <label for="editPaperName" class="form-label">Paper Name</label>
                                <input type="text" class="form-control" id="editPaperName" name="paper_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="editPaperType" class="form-label">Paper Type</label>
                                <select class="form-select" id="editPaperType" name="paper_type" required>
                                    <option value="1">Example</option>
                                    <option value="0">Paid</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editPaperStatus" class="form-label">Paper Status</label>
                                <select class="form-select" id="editPaperStatus" name="paper_status" required>
                                    <option value="1">Publish</option>
                                    <option value="0">Unpublish</option>
                                </select>
                            </div>
                            <button type="submit" id="saveChanges" class="btn btn-success-600" data-token="<?= $_SESSION['csrf_token'] ?>">Edit Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editQuestionModal" tabindex="-1" aria-labelledby="editQuestionModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editQuestionModal">Edit Question Groups</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="group_id" id="group_id">
                        <div class="mb-3">
                            <label for="editQuestionGroupName" class="form-label">Group Name</label>
                            <input type="text" class="form-control" id="editQuestionGroupName" name="paper_name" required>
                        </div>
                        <button type="submit" id="edit-question-group-btn" class="btn btn-success-600" data-token="<?= $_SESSION['csrf_token'] ?>">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addQuestionGroup" tabindex="-1" aria-labelledby="addQuestionGroupLable" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPaperModalLabel">Add Paper Questions Groups</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="paperName" class="form-label">Question Group Name</label>
                            <input type="text" class="form-control" id="GroupName" required>
                        </div>
                        <button class="btn btn-success-600" id="addGroupNameBtn" onclick="sendGroupName(<?= $paperId ?>)"
                            data-token="<?= $_SESSION['csrf_token'] ?>">Add Question Group</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addPaperModal" tabindex="-1" aria-labelledby="addPaperModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPaperModalLabel">Add Paper</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addPaperForm">
                            <div class="mb-3">
                                <label for="paperName" class="form-label">Paper Name</label>
                                <input type="text" class="form-control" id="paperName" required>
                            </div>
                            <div class="mb-3">
                                <label for="paperType" class="form-label">Paper Type</label>
                                <select class="form-select" id="AddPaperType" required>
                                    <option value="1">Example</option>
                                    <option value="0">Paid</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success-600">Add Paper</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'include/header.php' ?>
        <div class="dashboard-main-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                <h6 class="fw-semibold mb-0">
                    <?php
                    if ($viewGroup && $paperId) {
                        echo "Add Questions Groups";
                    } elseif ($viewQuestions && $groupId) {
                        echo "Add Questions";
                    } elseif ($add_question) {
                        echo "Add Question";
                    } elseif ($edit_question) {
                        echo "Edit Question";
                    } else {
                        echo "Add Papers";
                    }
                    ?>
                </h6>
                <ul class="breadcrumb d-flex flex-wrap align-items-center gap-2">
                    <li class="fw-medium">
                        <a href="dashboard" class="d-flex align-items-center gap-1 hover-text-success">
                            <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                            Dashboard
                        </a>
                    </li>
                    <?php if ($viewGroup && $paperId) { ?>
                        <li class="separator">-</li>
                        <li class="fw-medium">
                            <a href="add-papers" class="d-flex align-items-center gap-1 hover-text-success">
                                <iconify-icon icon="heroicons:document" class="menu-icon"></iconify-icon>
                                Add Papers ( <?= $paper_name ?> )
                            </a>
                        </li>
                        <li class="separator">-</li>
                        <li class="fw-medium">Add Questions Group</li>
                    <?php } elseif ($viewQuestions && $groupId) { ?>
                        <li class="separator">-</li>
                        <li class="fw-medium">
                            <a href="add-papers" class="d-flex align-items-center gap-1 hover-text-success">
                                <iconify-icon icon="heroicons:document" class="menu-icon"></iconify-icon>
                                Add Papers ( <?= $paper_name ?> )
                            </a>
                        </li>
                        <li class="separator">-</li>
                        <li class="fw-medium">
                            <a href="add-papers?viewGroup=true&paper-id=<?= $paperId; ?>&paper-name=<?= $paper_name; ?>" class="d-flex align-items-center gap-1 hover-text-success">
                                <iconify-icon icon="heroicons:document" class="menu-icon"></iconify-icon>
                                Add Question Groups ( <?= $group_name ?> )
                            </a>
                        </li>
                        <li class="separator">-</li>
                        <li class="fw-medium">Add Questions</li>
                    <?php } elseif ($add_question) {
                    ?>
                        <li class="separator">-</li>
                        <li class="fw-medium">
                            <a href="add-papers" class="d-flex align-items-center gap-1 hover-text-success">
                                <iconify-icon icon="heroicons:document" class="menu-icon"></iconify-icon>
                                Add Papers ( <?= $paper_name ?> )
                            </a>
                        </li>
                        <li class="separator">-</li>
                        <li class="fw-medium">
                            <a href="add-papers?viewGroup=true&paper-id=<?= $paperId; ?>&paper-name=<?= $paper_name; ?>" class="d-flex align-items-center gap-1 hover-text-success">
                                <iconify-icon icon="heroicons:document" class="menu-icon"></iconify-icon>
                                Add Question Groups ( <?= $group_name ?> )
                            </a>
                        </li>
                        <li class="separator">-</li>
                        <li class="fw-medium">Add Questions</li>
                    <?php
                    } elseif ($edit_question) {
                    ?>
                        <li class="separator">-</li>
                        <li class="fw-medium">
                            <a href="add-papers" class="d-flex align-items-center gap-1 hover-text-success">
                                <iconify-icon icon="heroicons:document" class="menu-icon"></iconify-icon>
                                Add Papers ( <?= $paper_name ?> )
                            </a>
                        </li>
                        <li class="separator">-</li>
                        <li class="fw-medium">
                            <a href="add-papers?viewGroup=true&paper-id=<?= $paperId; ?>&paper-name=<?= $paper_name; ?>" class="d-flex align-items-center gap-1 hover-text-success">
                                <iconify-icon icon="heroicons:document" class="menu-icon"></iconify-icon>
                                Add Question Groups ( <?= $group_name ?> )
                            </a>
                        </li>
                        <li class="separator">-</li>
                        <li class="fw-medium">Edit Questions</li>
                    <?php
                    } else { ?>
                        <li class="separator">-</li>
                        <li class="fw-medium">Add Papers</li>
                    <?php } ?>
                </ul>
            </div>
            <div class="card h-100 p-0 radius-12">
                <?php
                if (!$edit_question) {
                    if (!$add_question) {
                ?>
                        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-end">
                            <?php
                            if ($viewGroup && $paperId) {
                            ?>
                                <button class="btn btn-success-600 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addQuestionGroup">
                                    <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                                    Add New Question Group
                                </button>
                            <?php
                            } elseif ($viewQuestions && $groupId) {
                            ?>
                                <a href="add-papers?paper-name=<?= $paper_name ?>&group-id=<?= $groupId; ?>&question-group-name=<?= $group_name; ?>&paper-id=<?= $paperId ?>&add-question=true" type="button" class="btn btn-success-600 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                                    <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                                    Add New Question
                                </a>
                            <?php
                            } else {
                            ?>
                                <button class="btn btn-success-600 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addPaperModal">
                                    <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                                    Add New Paper
                                </button>
                            <?php
                            }
                            ?>
                        </div>
                <?php
                    }
                }
                ?>
                <div class="card-body p-24">
                    <div class="table-responsive scroll-sm">
                        <?php
                        if ($viewGroup && $paperId) {
                        ?>
                            <?php
                            ?>
                            <table class="table bordered-table sm-table mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <div class="d-flex align-items-center gap-10">
                                                #
                                            </div>
                                        </th>
                                        <th scope="col">Group Name</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $paperService = new PaperService();
                                    $PapersGroup = $paperService->getAllQuestionGroups($paperId);
                                    if ($PapersGroup) {
                                        foreach ($PapersGroup as $index => $groups) {
                                    ?>
                                            <tr>
                                                <td><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></td>
                                                <td><span class="text-md mb-0 fw-normal text-secondary-light"><?= $groups['group_name']; ?></span></td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center gap-10 justify-content-center">
                                                        <button type="button"
                                                            class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle edit-question-group-btn"
                                                            data-id="<?= $groups['group_id']; ?>"
                                                            data-name="<?= $groups['group_name']; ?>"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editQuestionModal"
                                                            data-token="<?= $_SESSION['csrf_token'] ?>">
                                                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                                        </button>
                                                        <a href="add-papers?paper-name=<?= $paper_name ?>&viewQuestions=true&group-id=<?= $groups['group_id']; ?>&question-group-name=<?= $groups['group_name']; ?>&paper-id=<?= $paperId ?>" type="button" class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                                            <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                                        </a>
                                                        <button type="button" class="delate-group bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" id="deleteGroup" onclick="deleteQuestionGroup(<?= $groups['group_id']; ?>);" data-token="<?= $_SESSION['csrf_token'] ?>">
                                                            <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-secondary-light">No Question Groups found.</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php
                        } elseif ($viewQuestions && $groupId) {
                        ?>
                            <table class="table bordered-table sm-table mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <div class="d-flex align-items-center gap-10">
                                                #
                                            </div>
                                        </th>
                                        <th scope="col">Question</th>
                                        <th scope="col">Question Type</th>
                                        <th scope="col">Question Marks</th>
                                        <th scope="col">Question Time</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $paperService = new PaperService();
                                    $allQuestions = $paperService->getQuestionsByPaperAndGroup($paperId, $groupId);
                                    if ($allQuestions) {
                                        foreach ($allQuestions as $index => $questions) {
                                    ?>
                                            <td><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></td>
                                            <td>
                                                <span class="text-md mb-0 fw-normal text-secondary-light">
                                                    <?= $questions['question_text']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-md mb-0 fw-normal text-secondary-light">
                                                    <?= $questions['isSample'] == 1 ? "Sample" : "Normal" ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-md mb-0 fw-normal text-secondary-light ps-32 w-100">
                                                    <?= $questions['marks']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-md mb-0 fw-normal text-secondary-light ps-32 w-100">
                                                    <?= $questions['time_for_question']; ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center gap-10 justify-content-center">
                                                    <a href="add-papers?paper-name=<?= $paper_name ?>&group-id=<?= $groupId; ?>&question-group-name=<?= $group_name; ?>&paper-id=<?= $paperId ?>&edit-question=true&question-id=<?= $questions['question_id'] ?>" type="button"
                                                        class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle edit-question-group-btn">
                                                        <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                                    </a>
                                                    <button type="button" class="delate-group bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" id="deleteQuestion" onclick="deleteQuestion(<?= $questions['question_id']; ?>);" data-token="<?= $_SESSION['csrf_token'] ?>">
                                                        <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                                    </button>
                                                </div>
                                            </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-secondary-light">No Questions found.</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php
                        } elseif ($add_question) {
                        ?>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Question Adding Area</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row gy-3 needs-validation">
                                            <div class="col-md-6">
                                                <label class="form-label">Select the question Type</label>
                                                <select id="questionType" class="form-select ">
                                                    <option value="">Select Question Type</option>
                                                    <option value="1">Sample</option>
                                                    <option value="0">Normal</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Select the question Category</label>
                                                <select id="questionCategory" class="form-select ">
                                                    <option value="">Select Question Category</option>
                                                    <option value="1">Reading</option>
                                                    <option value="2">Listen</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Question</label>
                                                <textarea id="question" class="form-control"></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Question Time (in seconds)</label>
                                                <input type="number" name="questionTime" id="questionTime" class="form-control" value="0" required min="0" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Question Marks</label>
                                                <input type="text" id="questionMarks" class="form-control" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Add Audio File</label>
                                                <input class="form-control" type="file" name="audioFile" id="audioFile" accept="audio/*" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Add Image File</label>
                                                <input class="form-control" type="file" name="imageFile" id="imageFile" accept="image/*" />
                                            </div>
                                            <div class="col-md-6" id="audioControls" style="display: none;">
                                                <button type="button" id="playBtn" class="btn btn-primary rounded" disabled>
                                                    <iconify-icon icon="hugeicons:play-circle" class="menu-icon"></iconify-icon>
                                                </button>
                                                <button type="button" id="pauseBtn" class="btn btn-secondary" disabled>
                                                    <iconify-icon icon="hugeicons:pause" class="menu-icon"></iconify-icon>
                                                </button>
                                            </div>
                                            <div class="col-md-12 justify-content-end align-items-end" id="imagePreviewContainer" style="display: none;">
                                                <div id="imagePreview" style="border: 1px solid #ddd; width: 200px; height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                                    <img id="previewImage" src="" alt="Selected Image" style="max-width: 100%; max-height: 100%;" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Answers Adding Area</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="answersForm" class="needs-validation" novalidate>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Select Answer Type</label>
                                                    <select id="answerTypeSelect" class="form-select" required>
                                                        <option value="no">Select Answer Type</option>
                                                        <option value="media">With Media</option>
                                                        <option value="text">No Media</option>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Please select an answer type first.
                                                    </div>
                                                </div>
                                                <div id="answersContainer"></div>
                                                <button type="button" id="addAnswerBtn" class="btn btn-success mt-3" disabled>Add Answer</button>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <button class="btn btn-success" onclick="sendAddQuestionRequest(<?= $groupId ?>)">Add Question</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        } elseif ($edit_question) {
                            $paperService = new PaperService();
                            $question_data = $paperService->getQuestionDataById($question_id);
                        ?>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Question Edit Area</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row gy-3 needs-validation">
                                            <div class="col-md-6">
                                                <label class="form-label">Select the Question Type</label>
                                                <select id="editQuestionType" class="form-select">
                                                    <option value="">Select Question Type</option>
                                                    <option value="1" <?= ($question_data['isSample'] == 1) ? 'selected' : ''; ?>>Sample</option>
                                                    <option value="0" <?= ($question_data['isSample'] == 0) ? 'selected' : ''; ?>>Normal</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Select the Question Category</label>
                                                <select id="editQuestionCategory" class="form-select">
                                                    <option value="">Select Question Category</option>
                                                    <option value="1" <?= ($question_data['question_categories_id'] == 1) ? 'selected' : ''; ?>>Reading</option>
                                                    <option value="2" <?= ($question_data['question_categories_id'] == 2) ? 'selected' : ''; ?>>Listening</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Question</label>
                                                <textarea id="editQuestion" class="form-control"><?= $question_data['question'] ?></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Question Time (in seconds)</label>
                                                <input type="number" id="editQuestionTime" class="form-control" value="<?= $question_data['time_for_question'] ?>" required min="0" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Question Marks</label>
                                                <input type="text" id="editQuestionMarks" class="form-control" value="<?= $question_data['marks'] ?>" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Add Audio File</label>
                                                <input type="file" id="editAudioFile" class="form-control" accept="audio/*" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Add Image File</label>
                                                <input type="file" id="editImageFile" class="form-control" accept="image/*" />
                                            </div>
                                            <div class="col-md-6 <?= $question_data['audio_path'] ? "d-block" : "d-none" ?>" id="editAudioControls">
                                                <audio controls>
                                                    <source src="../<?= $question_data['audio_path'] ?>" type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            </div>
                                            <div class="col-md-12 justify-content-end align-items-end <?= $question_data['image_path'] ? "d-flex" : "d-none" ?>" id="editImagePreviewContainer">
                                                <div id="editImagePreview" style="border: 1px solid #ddd; width: 200px; height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                                    <img id="editPreviewImage" src="../<?= $question_data['image_path'] ?>" alt="Selected Image" style="max-width: 100%; max-height: 100%;" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Answers Edit Area</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="editAnswersForm" class="needs-validation">
                                                <div class="col-6 pb-20">
                                                    <label class="form-label">Select Answer Type</label>
                                                    <?php
                                                    $hasMedia = array_search(1, array_column($question_data['answers'], 'is_media')) !== false;
                                                    ?>
                                                    <select id="EditAnswerTypeSelect" class="form-select" required>
                                                        <option value="no" <?= !$hasMedia ? 'selected' : '' ?>>Select Answer Type</option>
                                                        <option value="1" <?= $hasMedia ? 'selected' : '' ?>>With Media</option>
                                                        <option value="0" <?= !$hasMedia ? 'selected' : '' ?>>No Media</option>
                                                    </select>
                                                </div>
                                                <div id="editAnswersContainer">
                                                    <?php foreach ($question_data['answers'] as $index => $answer): ?>
                                                        <div class="edit-answer-details d-flex align-items-center mb-3" data-index="<?= $index ?>" style="gap: 10px;">
                                                            <?php if ($answer['is_media'] == 1): ?>
                                                                <div>
                                                                    <label class="form-label">Add Image</label>
                                                                    <input type="file" class="form-control" id="editImageUpload-<?= $index ?>" accept="image/*" style="display:none;" onchange="previewEditImage(this, <?= $index ?>)" data-answer-id="<?= $answer['id']?>"/>
                                                                    <label for="editImageUpload-<?= $index ?>" class="add-image-btn">Add Image</label>
                                                                    <div id="editPreviewContainer-<?= $index ?>" style="border: 1px solid #ddd; width: 100px; height: 100px; margin-top: 10px; display: <?= $answer['answer'] ? 'block' : 'none' ?>;">
                                                                        <img id="editPreviewImage-<?= $index ?>" src="../<?= $answer['answer'] ?>" style="max-width: 100%; max-height: 100%;" />
                                                                    </div>
                                                                </div>
                                                            <?php else: ?>
                                                                <div class="col-6">
                                                                    <input type="text" class="form-control" value="<?= htmlspecialchars($answer['answer'], ENT_QUOTES) ?>" placeholder="Enter Answer" />
                                                                </div>
                                                            <?php endif; ?>
                                                            <div>
                                                                <input type="checkbox" class="form-check-input" id="editCorrectAnswer-<?= $index ?>" <?= $answer['correct_answer'] ? 'checked' : '' ?>  data-id="<?= $answer['id']?>"/>
                                                                <label for="editCorrectAnswer-<?= $index ?>" class="form-check-label">Correct</label>
                                                            </div>
                                                            <div>
                                                                <button type="button" class="btn btn-danger remove-answer-btn" onclick="removeEditAnswer(<?= $index ?>,<?= $answer['id']?>)">Remove Answer</button>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <button type="button" id="addEditAnswerBtn" class="btn btn-success mt-3">Add Answer</button>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <button class="btn btn-success" onclick="sendEditQuestionRequest(<?= $question_id ?>)">Save Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="d-flex justify-content-end align-items-center pb-20 gap-20">
                                <?php
                                $paperService = new PaperService();
                                $latestCutoffMark = $paperService->getLatestCutoffMark();
                                ?>
                                <span class="fw-bold">
                                    Cutoff Mark for this session -
                                    <span class="text-red fw-bolder">
                                        <?php echo htmlspecialchars($latestCutoffMark); ?>
                                    </span>
                                </span>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cutoffMarkModal">
                                    Add Cutoff Mark
                                </button>
                            </div>
                            <div class="modal fade" id="cutoffMarkModal" tabindex="-1" aria-labelledby="cutoffMarkModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="cutoffMarkModalLabel">Add Cutoff Mark</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="cutoffMarkForm">
                                                <div class="mb-3">
                                                    <label for="cutoffMarkInput" class="form-label">Cutoff Mark</label>
                                                    <input type="number" class="form-control" id="cutoffMarkInput" placeholder="Enter cutoff mark" required>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary" onclick="saveCutoffMark()">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table bordered-table sm-table mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <div class="d-flex align-items-center gap-10">
                                                #
                                            </div>
                                        </th>
                                        <th scope="col">Paper Name</th>
                                        <th scope="col">Publish Date</th>
                                        <th scope="col">Qustion Count</th>
                                        <th scope="col">Full Marks</th>
                                        <th scope="col" class="text-center">Paper Type</th>
                                        <th scope="col" class="text-center">Paper Status</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $paperService = new PaperService();
                                    $allPapers = $paperService->getAllPapers();
                                    if ($allPapers) {
                                        foreach ($allPapers as $index => $paper) {
                                    ?>
                                            <td><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></td>
                                            <td><span class="text-md mb-0 fw-normal text-secondary-light"><?= $paper['paper_name']; ?></span></td>
                                            <td><?= date('d M Y', strtotime($paper['publish_at'])); ?></td>
                                            <td>
                                                <span class="text-md mb-0 fw-normal text-secondary-light text-center w-75">
                                                    <?= $paper['question_count']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-md mb-0 fw-normal text-secondary-light text-center w-75">
                                                    <?= $paper['full_marks']; ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($paper['isSample'] == 0): ?>
                                                    <span class="bg-success-focus text-success-600 border border-success-main px-24 py-4 radius-4 fw-medium text-sm">Paid Paper</span>
                                                <?php else: ?>
                                                    <span class="bg-primary-focus text-primary-500 border border-primary-500 px-24 py-4 radius-4 fw-medium text-sm">Sample Paper</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($paper['paper_status'] == 0): ?>
                                                    <span class="bg-warning-focus text-warning-600 border border-warning-main px-24 py-4 radius-4 fw-medium text-sm">Not Yet Publish</span>
                                                <?php else: ?>
                                                    <span class="bg-success-focus text-success-500 border border-success-500 px-24 py-4 radius-4 fw-medium text-sm">Published</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center gap-10 justify-content-center">
                                                    <button type="button"
                                                        class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle edit-paper-btn"
                                                        data-id="<?= $paper['paper_id']; ?>"
                                                        data-name="<?= $paper['paper_name']; ?>"
                                                        data-type="<?= $paper['isSample'] == 0 ? '0' : '1'; ?>"
                                                        data-status="<?= $paper['paper_status']; ?>"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editPaperModal"
                                                        data-token="<?= $_SESSION['csrf_token'] ?>">
                                                        <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                                    </button>
                                                    <a href="add-papers?viewGroup=true&paper-id=<?= $paper['paper_id']; ?>&paper-name=<?= $paper['paper_name']; ?>" type="button" class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                                        <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                                    </a>
                                                    <button type="button" class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" data-id="<?= $paper['paper_id']; ?>" data-token="<?= $_SESSION['csrf_token'] ?>">
                                                        <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                                    </button>
                                                </div>
                                            </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-secondary-light">No Papers found.</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php include('include/footer.php'); ?>
    </main>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
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
    <script src="assets/js/app.js"></script>
    <script src="assets/js/papers-request.js"></script>
    <script src="assets/js/question-adding.js"></script>
    <script src="assets/js/question-editing.js"></script>
    <script src="assets/js/question-group-delete.js"></script>
    <script src="assets/js/question-delete.js "></script>
</body>

</html>