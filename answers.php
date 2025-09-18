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
    p.isSample AS paperIsSample
FROM 
    questions q
JOIN 
    question_groups qg ON q.question_groups_id = qg.id
JOIN 
    papers p ON qg.papers_paper_id = p.paper_id
JOIN 
    question_categories qc ON q.question_categories_id = qc.id
WHERE 
    p.paper_id = :paper_id AND q.isSample = 0
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

// Fetch options for each question separately
foreach ($questions as &$question) {
    $optionsQuery = "SELECT 
        id,
        answer,
        is_media as isMedia,
        correct_answer as correctAnswer 
    FROM answers 
    WHERE questions_id = :question_id
    ORDER BY id";
    
    $optionsStmt = $conn->prepare($optionsQuery);
    $optionsStmt->execute(['question_id' => $question['question_id']]);
    $options = $optionsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Convert to the expected format
    $question['options'] = json_encode($options);
}

$userAnswers = isset($_GET["answers"]) ? array_map('intval', explode(',', $_GET["answers"])) : [];
$paper_id = $_GET["paper_id"] ?? null;

// Calculate score details
$totalScore = $_GET["score"] ?? 0;
$percentage = $_GET["percentage"] ?? 0;
$status = $_GET["status"] ?? 'unknown';
$examId = $_GET["exam_id"] ?? null;

// Time information
$totalTimeSpent = $_GET["time_spent"] ?? 0;
$readingTimeSpent = $_GET["reading_time"] ?? 0;
$listeningTimeSpent = $_GET["listening_time"] ?? 0;

// Helper function to format time
function formatTimeDisplay($seconds) {
    $minutes = floor($seconds / 60);
    $remainingSeconds = $seconds % 60;
    return $minutes . 'm ' . $remainingSeconds . 's';
}

// Calculate statistics
$correctCount = 0;
$wrongCount = 0;
$unansweredCount = 0;
$totalPossibleMarks = 0;
$readingCorrect = 0;
$listeningCorrect = 0;
$readingTotal = 0;
$listeningTotal = 0;

foreach ($questions as $index => $question) {
    $userAnswer = $userAnswers[$index] ?? 0;
    $options = json_decode($question['options'], true);
    $totalPossibleMarks += (int)$question['marks'];
    
    // Count by category
    if ($question['question_category'] === 'Reading') {
        $readingTotal++;
    } elseif ($question['question_category'] === 'Listening') {
        $listeningTotal++;
    }
    
    if ($userAnswer == 0) {
        $unansweredCount++;
    } else {
        $isCorrect = false;
        foreach ($options as $optionIndex => $option) {
            if ($option['correctAnswer'] == 1 && $userAnswer == ($optionIndex + 1)) {
                $isCorrect = true;
                break;
            }
        }
        
        if ($isCorrect) {
            $correctCount++;
            if ($question['question_category'] === 'Reading') {
                $readingCorrect++;
            } elseif ($question['question_category'] === 'Listening') {
                $listeningCorrect++;
            }
        } else {
            $wrongCount++;
        }
    }
}

$totalQuestions = count($questions);
$cutoffPercentage = 60; // Default cutoff
$isPassed = $percentage >= $cutoffPercentage;
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
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            padding: 15px;
            border-radius: 10px;
            border-left: 5px solid #28a745;
            position: relative;
        }

        .correct-answer::before {
            content: "✓";
            position: absolute;
            left: -12px;
            top: 50%;
            transform: translateY(-50%);
            background: #28a745;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }

        .wrong-answer {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            padding: 15px;
            border-radius: 10px;
            border-left: 5px solid #dc3545;
            position: relative;
        }

        .wrong-answer::before {
            content: "✗";
            position: absolute;
            left: -12px;
            top: 50%;
            transform: translateY(-50%);
            background: #dc3545;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }

        .unanswered-question {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            padding: 15px;
            border-radius: 10px;
            border-left: 5px solid #ffc107;
            position: relative;
        }

        .unanswered-question::before {
            content: "?";
            position: absolute;
            left: -12px;
            top: 50%;
            transform: translateY(-50%);
            background: #ffc107;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }

        .question-container {
            background: white;
            border-radius: 15px;
            border: 1px solid #e9ecef;
            margin-bottom: 25px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }

        .question-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .question-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
        }

        .question-number {
            background: #007bff;
            color: white;
            padding: 8px 12px;
            border-radius: 50px;
            font-weight: bold;
            margin-right: 15px;
            display: inline-block;
        }

        .category-badge {
            background: #6c757d;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .category-badge.reading {
            background: #007bff;
        }

        .category-badge.listening {
            background: #17a2b8;
        }

        .media-option {
            width: 100%;
            height: auto;
            max-width: 200px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .audio-option {
            width: 100%;
            margin-top: 10px;
        }

        .option-container {
            margin: 8px 0;
            transition: all 0.2s ease;
        }

        .option-container:hover {
            transform: translateX(5px);
        }

        .score-summary {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .stat-card, .category-card, .time-card {
            transition: transform 0.2s ease;
        }

        .stat-card:hover, .category-card:hover, .time-card:hover {
            transform: translateY(-2px);
        }

        .badge {
            font-size: 11px;
            padding: 6px 10px;
            border-radius: 20px;
        }

        .badge-success { background-color: #28a745; }
        .badge-danger { background-color: #dc3545; }
        .badge-warning { background-color: #ffc107; color: #212529; }

        .audio-option {
            width: 100%;
        }
    </style>
</head>

<body>
    <main class="d-flex justify-content-center align-items-center" style="padding: 20px;">
        <div class="col-10 shadow p-3 rounded-2" style="padding: 10px;" id="paperContainer">
            
            <!-- Score Summary Section -->
            <div class="score-summary mb-4 p-4 rounded" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 2px solid <?= $isPassed ? '#28a745' : '#dc3545' ?>;">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        <div class="score-badge p-3 rounded" style="background-color: <?= $isPassed ? '#28a745' : '#dc3545' ?>; color: white;">
                            <h2 class="mb-0"><?= $isPassed ? 'PASSED' : 'FAILED' ?></h2>
                            <div class="mt-2">
                                <i class="fa <?= $isPassed ? 'fa-check-circle' : 'fa-times-circle' ?> fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <div class="stat-card p-3 mb-3 bg-white rounded shadow-sm">
                                    <h3 class="text-primary mb-0"><?= $totalScore ?></h3>
                                    <small class="text-muted">Total Score</small>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="stat-card p-3 mb-3 bg-white rounded shadow-sm">
                                    <h3 class="text-info mb-0"><?= number_format($percentage, 1) ?>%</h3>
                                    <small class="text-muted">Percentage</small>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="stat-card p-3 mb-3 bg-white rounded shadow-sm">
                                    <h3 class="text-success mb-0"><?= $correctCount ?>/<?= $totalQuestions ?></h3>
                                    <small class="text-muted">Correct</small>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="stat-card p-3 mb-3 bg-white rounded shadow-sm">
                                    <h3 class="text-warning mb-0"><?= $totalPossibleMarks ?></h3>
                                    <small class="text-muted">Max Score</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Category Breakdown -->
                <?php if ($readingTotal > 0 || $listeningTotal > 0): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <h5 class="text-center mb-3">
                            <i class="fa fa-chart-bar"></i> Category Performance
                        </h5>
                        <div class="row">
                            <?php if ($readingTotal > 0): ?>
                            <div class="col-md-6">
                                <div class="category-card p-3 bg-white rounded shadow-sm text-center">
                                    <i class="fa fa-book fa-2x text-primary mb-2"></i>
                                    <h5>Reading</h5>
                                    <h4 class="text-success"><?= $readingCorrect ?>/<?= $readingTotal ?></h4>
                                    <small class="text-muted"><?= $readingTotal > 0 ? number_format(($readingCorrect/$readingTotal)*100, 1) : 0 ?>% Accuracy</small>
                                    <?php if ($readingTimeSpent > 0): ?>
                                    <small class="d-block text-info mt-1">
                                        <i class="fa fa-clock-o"></i> <?= formatTimeDisplay($readingTimeSpent) ?>
                                    </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($listeningTotal > 0): ?>
                            <div class="col-md-6">
                                <div class="category-card p-3 bg-white rounded shadow-sm text-center">
                                    <i class="fa fa-headphones fa-2x text-info mb-2"></i>
                                    <h5>Listening</h5>
                                    <h4 class="text-success"><?= $listeningCorrect ?>/<?= $listeningTotal ?></h4>
                                    <small class="text-muted"><?= $listeningTotal > 0 ? number_format(($listeningCorrect/$listeningTotal)*100, 1) : 0 ?>% Accuracy</small>
                                    <?php if ($listeningTimeSpent > 0): ?>
                                    <small class="d-block text-info mt-1">
                                        <i class="fa fa-clock-o"></i> <?= formatTimeDisplay($listeningTimeSpent) ?>
                                    </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Time Summary -->
                <?php if ($totalTimeSpent > 0): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <h5 class="text-center mb-3">
                            <i class="fa fa-clock-o"></i> Time Analysis
                        </h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="time-card p-3 bg-white rounded shadow-sm text-center">
                                    <i class="fa fa-hourglass-half fa-2x text-secondary mb-2"></i>
                                    <h5>Total Time</h5>
                                    <h4 class="text-dark"><?= formatTimeDisplay($totalTimeSpent) ?></h4>
                                    <small class="text-muted">Overall Duration</small>
                                </div>
                            </div>
                            <?php if ($readingTimeSpent > 0): ?>
                            <div class="col-md-4">
                                <div class="time-card p-3 bg-white rounded shadow-sm text-center">
                                    <i class="fa fa-book fa-2x text-primary mb-2"></i>
                                    <h5>Reading</h5>
                                    <h4 class="text-primary"><?= formatTimeDisplay($readingTimeSpent) ?></h4>
                                    <small class="text-muted">Reading Duration</small>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($listeningTimeSpent > 0): ?>
                            <div class="col-md-4">
                                <div class="time-card p-3 bg-white rounded shadow-sm text-center">
                                    <i class="fa fa-headphones fa-2x text-info mb-2"></i>
                                    <h5>Listening</h5>
                                    <h4 class="text-info"><?= formatTimeDisplay($listeningTimeSpent) ?></h4>
                                    <small class="text-muted">Listening Duration</small>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Quick Stats -->
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <small class="text-muted">
                            <span class="badge badge-success me-2">✓ <?= $correctCount ?> Correct</span>
                            <span class="badge badge-danger me-2">✗ <?= $wrongCount ?> Wrong</span>
                            <?php if ($unansweredCount > 0): ?>
                            <span class="badge badge-warning">? <?= $unansweredCount ?> Unanswered</span>
                            <?php endif; ?>
                        </small>
                    </div>
                </div>
            </div>

            <!-- Paper Title -->
            <div class="d-flex flex-row justify-content-between align-items-center p-3" style="position: sticky; top: 0; background-color: white; z-index: 10; border-bottom: 2px solid #e9ecef;">
                <span class="text-center fs-3 ms-3">
                    <i class="fa fa-file-text-o me-2"></i>Paper <?= $paper_id ?> - Detailed Answers
                </span>
                <?php if ($examId): ?>
                <a href="leadboard?exam_id=<?= $examId ?>" class="btn btn-primary">
                    <i class="fa fa-trophy me-1"></i>View Leaderboard
                </a>
                <?php endif; ?>
            </div>
            <div class="d-flex flex-row pt-3">
                <div class="col-12">
                    <?php foreach ($questions as $index => $question):
                        $questionId = $question['question_id'];
                        $userAnswer = $userAnswers[$index] ?? 0;
                        $options = json_decode($question['options'], true);
                        
                        // Determine if answer is correct, wrong, or unanswered
                        $isUnanswered = ($userAnswer == 0);
                        $isCorrect = false;
                        
                        if (!$isUnanswered) {
                            foreach ($options as $optionIndex => $option) {
                                if ($option['correctAnswer'] == 1 && $userAnswer == ($optionIndex + 1)) {
                                    $isCorrect = true;
                                    break;
                                }
                            }
                        }
                        
                        $questionClass = $isUnanswered ? 'unanswered-question' : ($isCorrect ? 'correct-answer' : 'wrong-answer');
                        $statusIcon = $isUnanswered ? 'fa-question-circle text-warning' : ($isCorrect ? 'fa-check-circle text-success' : 'fa-times-circle text-danger');
                        $statusText = $isUnanswered ? 'Not Answered' : ($isCorrect ? 'Correct' : 'Incorrect');
                        $categoryClass = strtolower($question['question_category']);
                    ?>
                        <div class="question-container">
                            <div class="question-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="question-number"><?php echo ($index + 1); ?></span>
                                        <span class="category-badge <?= $categoryClass ?>">
                                            <?php echo htmlspecialchars($question['question_category']); ?>
                                        </span>
                                        <small class="text-muted ms-2">
                                            Group: <?php echo htmlspecialchars($question['question_group_name']); ?>
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <i class="fa <?= $statusIcon ?> fa-lg me-2"></i>
                                        <span class="fw-bold <?= $isUnanswered ? 'text-warning' : ($isCorrect ? 'text-success' : 'text-danger') ?>">
                                            <?= $statusText ?>
                                        </span>
                                        <small class="d-block text-muted">
                                            Marks: <?= $question['marks'] ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-4">
                                <h6 class="mb-3">
                                    <?php echo htmlspecialchars($question['question']); ?>
                                </h6>
                                
                                <?php if ($question['image']): ?>
                                    <div class="text-center mb-4">
                                        <img src="<?php echo htmlspecialchars($question['image']); ?>" 
                                             alt="Question Image" 
                                             class="img-fluid rounded shadow-sm" 
                                             style="max-width: 400px; max-height: 300px;">
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($isUnanswered): ?>
                                    <div class="alert alert-warning mb-3">
                                        <i class="fa fa-exclamation-triangle me-2"></i>
                                        <strong>No answer selected!</strong> This question was left unanswered.
                                    </div>
                                <?php endif; ?>
                                
                                <div class="options-container">
                                    <?php foreach ($options as $optionIndex => $option):
                                        $isCorrectOption = $option['correctAnswer'] == 1;
                                        $isUserOption = $userAnswer == ($optionIndex + 1);
                                        $isMedia = $option['isMedia'] == 1;
                                        
                                        $optionClass = '';
                                        if ($isCorrectOption) {
                                            $optionClass = 'correct-answer';
                                        } elseif ($isUserOption && !$isCorrectOption) {
                                            $optionClass = 'wrong-answer';
                                        }
                                        
                                        $checked = $isUserOption ? 'checked' : '';
                                    ?>
                                        <div class="option-container">
                                            <div class="form-check d-flex align-items-center gap-3 p-3 rounded <?php echo $optionClass; ?>">
                                                <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    name="question-<?php echo $questionId; ?>"
                                                    id="option-<?php echo $optionIndex; ?>"
                                                    value="<?php echo ($optionIndex + 1); ?>"
                                                    <?php echo $checked; ?>
                                                    disabled>
                                                
                                                <label class="form-label mb-0 fw-bold">
                                                    <?php echo ($optionIndex + 1) . ')'; ?>
                                                </label>

                                                <?php if (!$isMedia): ?>
                                                    <label class="form-check-label mb-0 flex-grow-1" for="option-<?php echo $optionIndex; ?>">
                                                        <?php echo htmlspecialchars($option['answer']); ?>
                                                    </label>
                                                <?php endif; ?>

                                                <?php if ($isMedia): ?>
                                                    <div class="flex-grow-1">
                                                        <?php if (strpos($option['answer'], '.mp3') !== false || strpos($option['answer'], '.wav') !== false): ?>
                                                            <audio controls class="audio-option">
                                                                <source src="<?php echo htmlspecialchars($option['answer']); ?>" type="audio/mpeg">
                                                                Your browser does not support the audio element.
                                                            </audio>
                                                        <?php else: ?>
                                                            <img src="<?php echo htmlspecialchars($option['answer']); ?>" 
                                                                 class="media-option" 
                                                                 alt="Option Image">
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if ($isCorrectOption): ?>
                                                    <div class="ms-auto">
                                                        <span class="badge bg-success">
                                                            <i class="fa fa-check me-1"></i>Correct Answer
                                                        </span>
                                                    </div>
                                                <?php elseif ($isUserOption): ?>
                                                    <div class="ms-auto">
                                                        <span class="badge bg-danger">
                                                            <i class="fa fa-times me-1"></i>Your Answer
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if ($examId): ?>
                    <div class="text-center mt-5 mb-4">
                        <a href="leadboard?exam_id=<?= $examId ?>" class="btn btn-lg btn-primary px-5 py-3">
                            <i class="fa fa-trophy me-2"></i>View Leaderboard
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</body>

</html>