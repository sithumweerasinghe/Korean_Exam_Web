<?php
include("includes/lang/lang-check.php");


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
    $contact = "";
    $papers = "";
    $leadboard = "active";
    include("includes/header.php");
    include("api/client/services/examTimetable.php");
    $examTimetableService = new ExamTimetableService();

    $examResults;
    $examDetails;

    if (!isset($_GET["exam_id"]) && empty($_GET["exam_id"])) {
        $examResults = $examTimetableService->getLastExamResults();
        $examDetails = $examTimetableService->getLastExamDetails();
    } else {
        $examResults = $examTimetableService->getExamResults($_GET["exam_id"]);
        $examDetails = $examTimetableService->getExamDetails($_GET["exam_id"]);
    }

    // Ensure $examResults is an array to prevent errors
    if (!is_array($examResults)) {
        $examResults = [];
    }

    $topResults = array_slice($examResults, 0, 3); // First 3
    $remainingResults = array_slice($examResults, 3);

    // Ensure $examDetails is valid to prevent errors
    if (!is_array($examDetails) || empty($examDetails)) {
        $examDetails = [
            "start_time" => "00:00:00",
            "end_time" => "00:00:00", 
            "exam_date" => "No Date",
            "paper_name" => "No Paper"
        ];
    }

    $start = new DateTime($examDetails["start_time"]);
    $end = new DateTime($examDetails["end_time"]);

    $start_formatted = $start->format("h:i A");
    $end_formatted = $end->format("h:i A");
    ?>
    <div id="smooth-wrapper">
        <div id="smooth-content">
            <main>
                <section
                    class="section-gap position-relative mt-5">
                    <div class="container ed-container">
                        <div class="row">
                            <h3 class="ed-breadcrumbs__title text-center"><?= $examDetails["exam_date"] ?> <?= $translations['leaderboard']['heading']?> <?= htmlspecialchars($start_formatted) ?> - <?= htmlspecialchars($end_formatted) ?> <?= $examDetails["paper_name"] ?> <?= $translations['leaderboard']['heading_2']?></h3>
                            <div class="col-md-12 d-flex justify-content-end align-items-center my-3">
                                <p class="me-2 fw-semibold"><?= $translations['leaderboard']['search']['label']?></p>
                                <div class="form-group">
                                    <input
                                        id="examNumberInput"
                                        type="text"
                                        name="name"
                                        placeholder="<?= $translations['leaderboard']['search']['placeholder']?>"
                                        class="form-control" style="border: 1px solid #2ca347" />
                                </div>
                            </div>
                            <div class="p-4">
                                <?php if (!empty($topResults) && is_array($topResults)): ?>
                                    <?php foreach ($topResults as $index => $topResult): ?>
                                        <?php if ($index == 0): ?>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-12 d-flex justify-content-center align-items-center">
                                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                                        <img src="assets/images/rankings/1stPlace.png" width="100px" alt="1st Place Badge">
                                                        <span>1st Place</span>
                                                        <span><?= htmlspecialchars($topResult["admission_no"]); ?></span>
                                                    <span><?= htmlspecialchars($topResult["email"]); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php elseif ($index == 1 || $index == 2): ?>
                                        <?php if ($index == 1): ?>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-12 d-flex justify-content-evenly mt-5">
                                                <?php endif; ?>

                                                <div class="d-flex flex-column justify-content-center align-items-center">
                                                    <img src="assets/images/rankings/<?= $index == 1 ? '2ndPlace' : '3rdPlace'; ?>.png" width="100px" alt="<?= $index == 1 ? '2nd Place Badge' : '3rd Place Badge'; ?>">
                                                    <span><?= $index == 1 ? '2nd Place' : '3rd Place'; ?></span>
                                                    <span><?= htmlspecialchars($topResult["admission_no"]); ?></span>
                                                    <span><?= htmlspecialchars($topResult["email"]); ?></span>
                                                </div>

                                                <?php if ($index == 2): ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <p>No top results available</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-12">
                                <table class="table align-middle mb-5 bg-white mt-5" id="resultsTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th><?=$translations['leaderboard']['table_headers']['exam_number'] ?> </th>
                                            <th><?=$translations['leaderboard']['table_headers']['date'] ?></th>
                                            <th><?=$translations['leaderboard']['table_headers']['question_paper'] ?></th>
                                            <th><?=$translations['leaderboard']['table_headers']['marks'] ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <?php if (!empty($remainingResults) && is_array($remainingResults)): ?>
                                            <?php foreach ($remainingResults as $result): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($result["exam_number"]); ?></td>
                                                    <td><?= htmlspecialchars($result["date"]); ?></td>
                                                    <td><?= htmlspecialchars($result["question_paper"]); ?></td>
                                                    <td><?= htmlspecialchars($result["marks"]); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center">No results available</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="ed-pagination">
                                        <ul class="ed-pagination__list" id="pagination"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
            <?php include("includes/footer.php") ?>
        </div>
    </div>
    <?php include("includes/models/login-model.php") ?>
    <?php include("includes/models/register-model.php") ?>
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
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
    <script>
        const results = <?= json_encode($remainingResults); ?>;
        const rowsPerPage = 5;
        let currentPage = 1;

        function renderTable(page = 1) {
            currentPage = page;
            const startIndex = (page - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;
            const paginatedResults = results.slice(startIndex, endIndex);

            const tableBody = document.getElementById("tableBody");
            tableBody.innerHTML = "";

            if (paginatedResults.length === 0) {
                // Add "No results" row if paginatedResults is empty
                const noResultsRow = `<tr id="noResultsRow">
            <td colspan="4" class="text-center">No results</td>
        </tr>`;
                tableBody.insertAdjacentHTML("beforeend", noResultsRow);
            } else {
                // Populate the table with paginated results
                paginatedResults.forEach(result => {
                    const row = `<tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="ms-3">
                            <p class="fw-bold mb-1">${result.admission_no}</p>
                            <p class="text-muted mb-0">${result.email}</p>
                        </div>
                    </div>
                </td>
                <td><p class="fw-normal">${new Date().toLocaleDateString()}</p></td>
                <td><p class="fw-normal">${result.paper_name}</p></td>
                <td><p class="fw-normal">${result.marks}</p></td>
            </tr>`;
                    tableBody.insertAdjacentHTML("beforeend", row);
                });
            }

            renderPagination();
        }

        function renderPagination() {
            const totalPages = Math.ceil(results.length / rowsPerPage);
            const pagination = document.getElementById("pagination");
            pagination.innerHTML = "";

            if (currentPage > 1) {
                pagination.innerHTML += `<li><a onclick="renderTable(${currentPage - 1})">&laquo;</a></li>`;
            }

            for (let i = 1; i <= totalPages; i++) {
                pagination.innerHTML += `<li class="${i === currentPage ? "active" : ""}">
            <a onclick="renderTable(${i})">${i}</a>
        </li>`;
            }

            if (currentPage < totalPages) {
                pagination.innerHTML += `<li><a onclick="renderTable(${currentPage + 1})">&raquo;</a></li>`;
            }
        }

        // Initial render
        renderTable();
    </script>
    <script>
        document.getElementById('examNumberInput').addEventListener('input', function() {
            const filterValue = this.value.trim().toLowerCase();
            const tableRows = document.querySelectorAll('#resultsTable tbody tr');
            let visibleRowCount = 0; // Counter for visible rows

            tableRows.forEach(row => {
                const examNumber = row.cells[0]?.textContent.trim().toLowerCase();
                if (examNumber && examNumber.includes(filterValue)) {
                    row.style.display = ''; // Show matching row
                    visibleRowCount++;
                } else {
                    row.style.display = 'none'; // Hide non-matching row
                }
            });

            const noResultsRow = document.getElementById('noResultsRow');
            if (visibleRowCount === 0) {
                // If no rows are visible, show the "No results" row
                if (!noResultsRow) {
                    const tbody = document.querySelector('#resultsTable tbody');
                    const row = document.createElement('tr');
                    row.id = 'noResultsRow';
                    row.innerHTML = '<td colspan="100%" class="text-center">No results</td>';
                    tbody.appendChild(row);
                }
            } else {
                // If there are visible rows, remove the "No results" row
                if (noResultsRow) {
                    noResultsRow.remove();
                }
            }
        });
    </script>

</body>

</html>