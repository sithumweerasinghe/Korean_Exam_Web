<?php
require_once "../config/dbconnection.php";
header("Content-Type: application/json");
include "../request-filters/client_request_filter.php";


if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid Request Method."
    ]);
    exit();
}

if (!isset($_SESSION["client_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Please login to continue"
    ]);
    exit();
}

$requiredParams = ["paper_id", "result", "isExam"];
foreach ($requiredParams as $param) {
    if (!isset($_GET[$param]) || $_GET[$param] === "") {
        echo json_encode([
            "success" => false,
            "message" => "Parameter '$param' is missing or empty"
        ]);
        exit();
    }
}

$paper_id = $_GET["paper_id"];
$result = $_GET["result"];
$isExam = $_GET["isExam"];
try {
    $database = new Database();
    $conn = $database->getConnection();

    $cutoffQuery = "SELECT id, mark FROM cutoff_mark ORDER BY created_at DESC LIMIT 1";
    $cutoffStmt = $conn->prepare($cutoffQuery);
    $cutoffStmt->execute();
    $latestCutoff = $cutoffStmt->fetch(PDO::FETCH_ASSOC);

    if (!$latestCutoff) {
        echo json_encode([
            "success" => false,
            "message" => "No cutoff mark found."
        ]);
        exit();
    }

    $cutoffId = $latestCutoff['id'];
    $cutoffMark = $latestCutoff['mark'];

    $paperQuery = "SELECT isSample FROM papers WHERE paper_id = :paper_id";
    $paperStmt = $conn->prepare($paperQuery);
    $paperStmt->bindParam(":paper_id", $paper_id);
    $paperStmt->execute();

    if ($paperStmt->rowCount() == 0) {
        echo json_encode([
            "success" => false,
            "message" => "Invalid paper ID."
        ]);
        exit();
    }

    if ($isExam == 'true') {
        $exam_id = $_GET["exam_id"];

        $examQuery = "SELECT * FROM exam_time_table WHERE id = :exam_id AND papers_paper_id = :paper_id";
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

        $examResultUpdateQuery = "UPDATE exam_results 
                                  SET marks = :marks, cutoff_mark_id = :cutoff_id 
                                  WHERE exam_time_table_id = :exam_id 
                                  AND admissions_id = (
                                      SELECT id
                                      FROM admissions
                                      WHERE user_id = :user_id
                                      ORDER BY created_at DESC
                                      LIMIT 1
                                  );";
        $examUpdateStmt = $conn->prepare($examResultUpdateQuery);
        $examUpdateStmt->bindParam(":marks", $result);
        $examUpdateStmt->bindParam(":cutoff_id", $cutoffId);
        $examUpdateStmt->bindParam(":exam_id", $exam_id);
        $examUpdateStmt->bindParam(":user_id", $_SESSION["client_id"]);

        if (!$examUpdateStmt->execute()) {
            echo json_encode([
                "success" => false,
                "message" => "Error updating the values"
            ]);
            exit();
        }
    } else {
        $paperResultUpdateQuery = "UPDATE student_results 
                                   SET result = :result, cutoff_mark_id = :cutoff_id 
                                   WHERE papers_paper_id = :paper_id 
                                   AND admissions_id = (
                                       SELECT id
                                       FROM admissions
                                       WHERE user_id = :user_id
                                       ORDER BY created_at DESC
                                       LIMIT 1
                                   );";
        $paperUpdateStmt = $conn->prepare($paperResultUpdateQuery);
        $paperUpdateStmt->bindParam(":result", $result);
        $paperUpdateStmt->bindParam(":cutoff_id", $cutoffId);
        $paperUpdateStmt->bindParam(":paper_id", $paper_id);
        $paperUpdateStmt->bindParam(":user_id", $_SESSION["client_id"]);

        if (!$paperUpdateStmt->execute()) {
            echo json_encode([
                "success" => false,
                "message" => "Error updating the values"
            ]);
            exit();
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Successfully updated the mark with the latest cutoff'
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

