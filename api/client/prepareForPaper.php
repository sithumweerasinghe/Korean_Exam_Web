<?php
require_once __DIR__ . "/../config/dbconnection.php";
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid Request Method."
    ]);
    exit();
}

$requiredParams = ["paper_id", "application_no", "sample"];
foreach ($requiredParams as $param) {
    if (empty($_GET[$param])) {
        echo json_encode([
            "success" => false,
            "message" => "Parameter '$param' is missing or empty"
        ]);
        exit();
    }
}

$isSamplePaper = $_GET["sample"];
$paper_id = $_GET["paper_id"];
$application_no = $_GET["application_no"];
$isSample = $_GET["sample"] == "false" ? 0 : 1;
$isExam = false;
try {
    $database = new Database();
    $conn = $database->getConnection();

    $paperQuery = "SELECT isSample FROM papers WHERE paper_id = :paper_id";
    $paperStmt = $conn->prepare($paperQuery);
    $paperStmt->bindParam(":paper_id", $paper_id);
    $paperStmt->execute();
    $paper = $paperStmt->fetch(PDO::FETCH_ASSOC);

    if (!$paper) {
        echo json_encode([
            "success" => false,
            "message" => "Invalid paper ID."
        ]);
        exit();
    }

    if ($paper['isSample'] !== ($isSamplePaper == "false" ? 0 : 1)) {
        echo json_encode([
            "success" => false,
            "message" => "Mismatch between paper type and 'sample' parameter."
        ]);
        exit();
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Internal server error."
    ]);
    exit();
}

if ($isSamplePaper == "false") {
    include "../request-filters/client_request_filter.php";
    if (!isset($_SESSION["client_id"])) {
        echo json_encode([
            "success" => false,
            "message" => "Please login to continue"
        ]);
        exit();
    }


    if (isset($_GET["exam_id"])) {
        $isExam = true;
    }
    try {
        $query = "INSERT INTO admissions (admission_no,user_id) VALUES (:application_no,:user_id)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":application_no", $application_no);
        $stmt->bindParam(":user_id", $_SESSION["client_id"]);
        $stmt->execute();

        $admission_id = $conn->lastInsertId();

        if ($isExam) {
            $exam_id = $_GET["exam_id"];

            $examQuery = "SELECT COUNT(*) FROM exam_time_table WHERE id = :exam_id AND papers_paper_id = :paper_id";
            $examExistStmt = $conn->prepare($examQuery);
            $examExistStmt->bindParam(":exam_id", $exam_id);
            $examExistStmt->bindParam(":paper_id", $paper_id);
            $examExistStmt->execute();

            if ($examExistStmt->rowCount() == 0) {
                echo json_encode([
                    "success" => false,
                    "message" => "Invalid exam ID or exam does not match paper ID."
                ]);
                exit();
            }

            $insertQuery = "INSERT INTO exam_results (exam_time_table_id,admissions_id) VALUES (:exam_time_table_id,:admissions_id)";
            $examStmt = $conn->prepare($insertQuery);
            $examStmt->bindParam(":exam_time_table_id", $exam_id);
            $examStmt->bindParam(":admissions_id", $admission_id);
        } else {
            $insertQuery = "INSERT INTO student_results (papers_paper_id,admissions_id) VALUES (:papers_paper_id,:admissions_id)";
            $examStmt = $conn->prepare($insertQuery);
            $examStmt->bindParam(":papers_paper_id", $paper_id);
            $examStmt->bindParam(":admissions_id", $admission_id);
        }
        if (!$examStmt->execute()) {
            echo json_encode([
                "success" => false,
                "message" => "Error inserting the values"
            ]);
            exit();
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode([
            "success" => false,
            "message" => "Internal server error."
        ]);
        exit();
    }
}

try {
    // First, get all questions
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
    p.paper_id = :paper_id
ORDER BY 
    FIELD(qc.question_category, 'Reading', 'Listening'),
    qg.question_group_name ASC,                       
    CASE 
        WHEN q.isSample = 1 THEN 0                   
        ELSE 1 
    END, 
    q.id ASC                                                                                
";

    $questionStmt = $conn->prepare($questionsQuery);
    $questionStmt->bindParam(":paper_id", $paper_id);
    $questionStmt->execute();
    $questions = $questionStmt->fetchAll(PDO::FETCH_ASSOC);

    // Then, get all answers for each question
    foreach ($questions as &$question) {
        $answersQuery = "SELECT 
            a.id,
            a.answer,
            a.is_media AS isMedia,
            a.correct_answer AS correctAnswer
        FROM answers a 
        WHERE a.questions_id = :question_id
        ORDER BY a.id";
        
        $answersStmt = $conn->prepare($answersQuery);
        $answersStmt->bindParam(":question_id", $question['question_id']);
        $answersStmt->execute();
        $question['options'] = $answersStmt->fetchAll(PDO::FETCH_ASSOC);
    }
    unset($question); // break the reference

    $_SESSION["questions"] = $questions;
    echo json_encode([
        "success" => true,
        "isSample" => $isSamplePaper == "true" ? true : false,
        "isExam" => $isExam,
        "questions" => $questions
    ]);
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Internal server error."
    ]);
    exit();
}finally {
    $conn = null; 
}
