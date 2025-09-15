<?php
require_once('api/config/dbconnection.php');
$database = new Database();
$conn = $database->getConnection();

$questionsQuery = "SELECT 
    q.id AS question_id,
    q.question,
    qc.question_category,
    q.isSample AS questionIsSample, 
    q.image_path AS image,
    q.audio_path AS audio,
    q.time_for_question AS timeLimit,
    q.marks,
    q.isSample,
    qg.question_group_name,
    p.isSample AS paperIsSample,
    JSON_ARRAYAGG(
        JSON_OBJECT(
            'id', a.id,
            'answer', a.answer,
            'isMedia', a.is_media,
            'correctAnswer', a.correct_answer 
        )
    ) AS options
FROM 
    questions q
JOIN 
    question_groups qg ON q.question_groups_id = qg.id
JOIN 
    papers p ON qg.papers_paper_id = p.paper_id
JOIN 
    question_categories qc ON q.question_categories_id = qc.id
LEFT JOIN 
    answers a ON a.questions_id = q.id
WHERE 
    p.paper_id = :paper_id AND q.isSample = 0
GROUP BY 
    q.id, qc.question_category, p.isSample, q.isSample
ORDER BY 
    FIELD(qc.question_category, 'Reading', 'Listening'),
    qg.question_group_name ASC,                       
    CASE 
        WHEN q.isSample = 1 THEN 0                   
        ELSE 1 
    END, 
    q.id ASC";

$stmt = $conn->prepare($questionsQuery);
$stmt->execute(['paper_id' => $_GET['paper_id']]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$userAnswers = isset($_GET["answers"]) ? array_map('intval', explode(',', $_GET["answers"])) : [];
$paper_id = $_GET["paper_id"] ?? null;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="Topik Sir | Korean language proficiency test and secure your employment abroad." />
    <meta name="keywords" content="Topik Sir | Korean language proficiency test and secure your employment abroad." />
    <meta name="author" content="Virul Nirmala Wickramasinghe" />
    <meta property="og:url" content="https://topiksir.com/">
    <title>Topik Sir | Paper - <?= $paper_id ?> Answers</title>
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
    <link rel="stylesheet" href="style.css" />
    <style>
        .correct-answer {
            background-color: #d4edda;
            padding: 10px;
            border-radius: 8px;
        }

        .wrong-answer {
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 8px;
        }

        .media-option {
            width: 100%;
            height: auto;
            max-width: 200px;
        }

        .audio-option {
            width: 100%;
        }
    </style>
</head>

<body>
    <main class="d-flex justify-content-center align-items-center" style="padding: 20px;">
        <div class="col-8 shadow p-3 rounded-2" style="padding: 10px;" id="paperContainer">
            <div class="d-flex flex-row justify-content-between align-items-center p-10" style="position: sticky; top: 0; background-color: white; z-index: 10;">
                <span class="text-center fs-3 ms-5 mt-3">Paper <?= $paper_id ?> - Answers</span>
            </div>
            <div class="d-flex flex-row pt-10">
                <div class="col-12">
                    <?php foreach ($questions as $index => $question):
                        $questionId = $question['question_id'];
                        $userAnswer = $userAnswers[$index];
                        $options = json_decode($question['options'], true);
                    ?>
                        <div class="clo-12 row p-5" style="overflow-y: auto;">
                            <p><strong>Group:</strong> <?php echo htmlspecialchars($question['question_group_name']); ?> (<?php echo htmlspecialchars($question['question_category']); ?>)</p>
                            <h6>
                                <span class="question-number"><?php echo ($index + 1); ?>.</span>
                                <?php echo htmlspecialchars($question['question']); ?>
                            </h6>
                            <?php if ($question['image']): ?>
                                <div class="col-lg-12 mt-3 mb-3">
                                    <img src="<?php echo htmlspecialchars($question['image']); ?>" width="200px" height="200px" alt="Image" class="img-fluid">
                                </div>
                            <?php endif; ?>
                            <?php if ($userAnswer == 0): ?>
                                <p style="color: red; font-weight: bold;">User didn't select an answer!</p>
                            <?php endif; ?>
                            <form id="question-form-<?php echo $questionId; ?>" class="mt-4 d-flex flex-column row-gap-3">
                                <?php foreach ($options as $optionIndex => $option):
                                    $isCorrect = $option['correctAnswer'] == 1;
                                    $isUserAnswer = $userAnswer == ($optionIndex + 1);
                                    $isMedia = $option['isMedia'] == 1;
                                    $class = '';
                                    $isChecked = '';
                                    if ($isCorrect) {
                                        $class = 'correct-answer';
                                    } elseif ($isUserAnswer) {
                                        $class = 'wrong-answer';
                                    }
                                    if ($isUserAnswer) {
                                        $isChecked = 'checked';
                                    }
                                ?>
                                    <div class="form-check d-flex align-items-center gap-2 <?php echo $class; ?>">
                                        <input
                                            class="form-check-input ms-2"
                                            type="radio"
                                            name="question-<?php echo $questionId; ?>"
                                            id="option-<?php echo $optionIndex; ?>"
                                            value="<?php echo ($optionIndex + 1); ?>"
                                            <?php echo $isChecked; ?>
                                            disabled>
                                        <label class="form-label mb-0"><?php echo ($optionIndex + 1) . ')'; ?></label>

                                        <?php if (!$isMedia): ?>
                                            <label class="form-check-label mb-0" for="option-<?php echo $optionIndex; ?>">
                                                <?php echo htmlspecialchars($option['answer']); ?>
                                            </label>
                                        <?php endif; ?>

                                        <?php if ($isMedia): ?>
                                            <?php if (strpos($option['answer'], '.mp3') !== false || strpos($option['answer'], '.wav') !== false): ?>
                                                <audio controls class="audio-option">
                                                    <source src="<?php echo htmlspecialchars($option['answer']); ?>" type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            <?php else: ?>
                                                <img src="<?php echo htmlspecialchars($option['answer']); ?>" class="media-option" alt="media">
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </form>
                        </div>
                    <?php endforeach; ?>

                    <div class="ed-hero__btn text-center mt-4">
                        <a href="leadboard?exam_id=<?= $_GET["exam_id"] ?>" class="ed-btn">Go to Leaderboard<i
                                class="fi fi-rr-arrow-small-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>