<?php
include '../api/request-filters/admin_request_filter.php';
include '../api/admin/services/init.php';

$add_news = isset($_GET['add-news']) && $_GET['add-news'] === 'true';
$news_id = isset($_GET['news-id']) ? $_GET['news-id'] : null;
$edit_news = isset($_GET['edit-news']) && $_GET['edit-news'] === 'true';

?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description"
        content="EPS Topick Sir | Korean language proficiency test and secure your employment abroad." />
    <meta name="keywords"
        content="EPS Topick Sir | Korean language proficiency test and secure your employment abroad." />
    <meta name="author" content="Virul Nirmala Wickramasinghe" />
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token']; ?>">

    <title>Topick Sir | Admin | News</title>
    <meta name="description"
        content="Prepare for the EPS-TOPIK exam with our expert training program. Access study materials, mock tests, and personalized guidance to excel in your Korean language proficiency test and secure your employment abroad.">
    <meta property="og:url" content="https://topicksir.com">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Topick Sir">
    <meta property="og:description"
        content="Prepare for the EPS-TOPIK exam with our expert training program. Access study materials, mock tests, and personalized guidance to excel in your Korean language proficiency test and secure your employment abroad.">
    <meta property="og:image"
        content="https://ogcdn.net/e4b8c678-7bd5-445d-ba03-bfaad510c686/v4/epstopicksir.com/epstopicksir.com/https%3A%2F%2Fopengraph.b-cdn.net%2Fproduction%2Fimages%2Fda4ae288-c222-4d6c-9cbf-cb3e9ec3300c.png%3Ftoken%3DPV1V5JqRR5UcqNJrenyT6Lh_TEkyX-tGppr1-x4fCfw%26height%3D864%26width%3D864%26expires%3D33268049346/og.png">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="koreansir.lk">
    <meta property="twitter:url" content="https://topicksir.com">
    <meta name="twitter:title" content="Topick Sir">
    <meta name="twitter:description"
        content="Prepare for the EPS-TOPIK exam with our expert training program. Access study materials, mock tests, and personalized guidance to excel in your Korean language proficiency test and secure your employment abroad.">
    <meta name="twitter:image"
        content="https://ogcdn.net/e4b8c678-7bd5-445d-ba03-bfaad510c686/v4/epstopicksir.com/epstopicksir.com/https%3A%2F%2Fopengraph.b-cdn.net%2Fproduction%2Fimages%2Fda4ae288-c222-4d6c-9cbf-cb3e9ec3300c.png%3Ftoken%3DPV1V5JqRR5UcqNJrenyT6Lh_TEkyX-tGppr1-x4fCfw%26height%3D864%26width%3D864%26expires%3D33268049346/og.png">
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
                <h6 class="fw-semibold mb-0">
                    <?php
                    if ($add_news) {
                        echo "Add News";
                    } else if ($edit_news) {
                        echo "Edit News";
                    } else {
                        echo "News List";
                    }
                    ?>
                </h6>
                <ul class="d-flex align-items-center gap-2">
                    <li class="fw-medium">
                        <a href="/dashboard" class="d-flex align-items-center gap-1 hover-text-primary">
                            <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                            Dashboard
                        </a>
                    </li>
                    <li>-</li>
                    <?php
                    if ($add_news) {
                        ?>
                        <li class="fw-medium">
                            <a href="news" class="d-flex align-items-center gap-1 hover-text-primary">
                                New List
                            </a>
                        </li>
                        <li>-</li>
                        <li class="fw-medium">
                            <a href="news?add-news=true" class="d-flex align-items-center gap-1 hover-text-primary">
                                Add News
                            </a>
                        </li>
                        <?php
                    } else if ($edit_news) {
                        ?>
                            <li class="fw-medium">
                                <a href="news" class="d-flex align-items-center gap-1 hover-text-primary">
                                    New List
                                </a>
                            </li>
                            <li>-</li>
                            <li class="fw-medium">
                                <a href="news?edit-news=true" class="d-flex align-items-center gap-1 hover-text-primary">
                                    Edit News
                                </a>
                            </li>
                        <?php
                    } else {
                        ?>
                            <li class="fw-medium">News List</li>
                        <?php
                    }
                    ?>

                </ul>
            </div>

            <?php
            $newsService = new AdminNewsService();
            if ($add_news) {
                ?>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">News Adding Area</h5>
                        </div>
                        <div class="card-body">
                            <div class="row gy-3 needs-validation">
                                <div class="col-md-12">
                                    <label class="form-label">News Title</label>
                                    <input type="text" name="news_title" id="news_title" class="form-control" required />
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">News Description</label>
                                    <textarea id="news_des" class="form-control"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tag</label>
                                    <input type="text" name="" id="news_tag" class="form-control" required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Valid Days</label>
                                    <input type="number" min="0" value="0" id="news_valid_days" class="form-control"
                                        required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Add Image File</label>
                                    <input class="form-control" type="file" name="imageFile" id="imageFile"
                                        accept="image/*" />
                                </div>
                                <div class="col-md-12 justify-content-end align-items-end" id="imagePreviewContainer"
                                    style="display: none;">
                                    <div id="imagePreview"
                                        style="border: 1px solid #ddd; width: 200px; height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                        <img id="previewImage" src="" alt="Selected Image"
                                            style="max-width: 100%; max-height: 100%;" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 d-flex justify-content-end">
                                <button class="btn btn-success" onclick="addNews();">Add
                                    News</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } else if ($edit_news) {
                $news_data = $newsService->getNewsById($news_id);
                ?>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">News Editing Area</h5>
                            </div>
                            <div class="card-body">
                                <div class="row gy-3 needs-validation">
                                    <div class="col-md-12">
                                        <label class="form-label">News Title</label>
                                        <input type="text" name="" id="edit_title" class="form-control" required
                                            value="<?= $news_data["news_title"] ?>" />
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">News Description</label>
                                        <textarea id="edit_des" class="form-control"><?= $news_data["news"] ?></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tag</label>
                                        <input type="text" name="" id="edit_tag" class="form-control" required
                                            value="<?= $news_data["tag"] ?>" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Valid Days</label>
                                        <input type="number" min="0" value="<?= $news_data["valid_days"] ?>" id="edt_valid_days"
                                            class="form-control" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Add Image File</label>
                                        <input onchange="selectImage();" class="form-control" type="file" name="editNewsImageFile" id="editNewsImageFile"
                                            accept="image/*" />
                                    </div>
                                    <div class="col-md-12 justify-content-end align-items-end" id="imagePreviewContainer"
                                        >
                                        <div id="imagePreview"
                                            style="border: 1px solid #ddd; width: 200px; height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                            <img id="editNewsPreviewImage" src="../<?= $news_data["news_image"]?>" alt="Selected Image"
                                                style="max-width: 100%; max-height: 100%;" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-end">
                                    <button class="btn btn-success"
                                        onclick="updateNews('<?= htmlspecialchars($news_id, ENT_QUOTES) ?>')">Update
                                        News</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
            } else {
                ?>
                    <div class="card">
                        <div class="card-header d-flex flex-wrap align-items-center justify-content-end gap-3">
                            <button onclick="window.location.href = 'news?add-news=true';"
                                class="btn btn-success-600 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                                <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                                Add New News
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive scroll-sm">
                                <table class="table bordered-table mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">S.L</th>
                                            <th scope="col">News Title</th>
                                            <th scope="col">Tag</th>
                                            <th scope="col">Valid Days</th>
                                            <th scope="col">Published at</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $allNews = $newsService->getAllNews();
                                        if (!empty($allNews)):
                                            foreach ($allNews as $index => $news):
                                                ?>
                                                <tr>
                                                    <td><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></td>
                                                    <td><?= $news['news_title']; ?><br>
                                                    <?= mb_strimwidth($news['news'], 0, 40, '...'); ?></td>
                                                    <td><?= $news['tag']; ?></td>
                                                    <td><?= $news['valid_days']; ?></td>
                                                    <td><?= date('d M Y', strtotime($news['created_at'])); ?></td>
                                                    <td>
                                                        <button
                                                            onclick="window.location.href = 'news?edit-news=true&news-id=<?= $news['news_id']; ?>';"
                                                            class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                                            <iconify-icon icon="lucide:edit"></iconify-icon>
                                                        </button>
                                                        <button
                                                            onclick="deleteNews('<?= htmlspecialchars($news['news_id'], ENT_QUOTES) ?>','<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES) ?>')"
                                                            class="w-32-px ms-2 h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                                            <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                                                        </button>

                                                    </td>
                                                </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center text-secondary-light">No news found.</td>
                                            </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php
            }
            ?>

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
    <script src="assets/js/news.js"></script>
</body>

</html>