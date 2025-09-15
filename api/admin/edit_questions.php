<?php
require_once '../config/dbconnection.php'; 
require_once './services/init.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['admin_id'])) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Unauthorized access. Please sign in."]);
        exit();
    }

    $errors = [];
    $questionId = $_GET['question_id'] ?? $_POST['question_id'] ?? null;

    if (!$questionId) {
        $errors[] = "Missing question_id.";
    }

    $database = new Database();
    $db = $database->getConnection();

    try {
        $query = "SELECT image_path, audio_path FROM questions WHERE id = :question_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
        $stmt->execute();
        $existingQuestion = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existingQuestion) {
            $errors[] = "Question not found in the database.";
        }
    } catch (PDOException $e) {
        $errors[] = "Error fetching existing question data: " . $e->getMessage();
    }

    $questionData = [
        'questionType' => $_POST['questionType'] ?? null,
        'questionCategory' => $_POST['questionCategory'] ?? null,
        'questionText' => $_POST['questionText'] ?? null,
        'questionTime' => $_POST['questionTime'] ?? null,
        'marks' => $_POST['questionMarks'] ?? null,
        'image_path' => $existingQuestion['image_path'] ?? null,
        'audio_path' => $existingQuestion['audio_path'] ?? null,
    ];
    function generateTimestampedFilename($originalName) {
        $timestamp = time();
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        return $timestamp . "_" . uniqid() . ($extension ? ".$extension" : "");
    }

    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] == UPLOAD_ERR_OK) {
        $newImageName = generateTimestampedFilename($_FILES['imageFile']['name']);
        $imageFilePath = 'uploads/images/' . $newImageName;
        if (!move_uploaded_file($_FILES['imageFile']['tmp_name'], '../../' . $imageFilePath)) {
            $errors[] = "Failed to upload image file.";
        } else {
            $questionData['image_path'] = $imageFilePath;
        }
    }

    if (isset($_FILES['audioFile']) && $_FILES['audioFile']['error'] == UPLOAD_ERR_OK) {
        $newAudioName = generateTimestampedFilename($_FILES['audioFile']['name']);
        $audioFilePath = 'uploads/audio/' . $newAudioName;
        if (!move_uploaded_file($_FILES['audioFile']['tmp_name'], '../../' . $audioFilePath)) {
            $errors[] = "Failed to upload audio file.";
        } else {
            $questionData['audio_path'] = $audioFilePath;
        }
    }

    $answersData = [];
    if (isset($_POST['answers']) && is_array($_POST['answers'])) {
        foreach ($_POST['answers'] as $index => $answer) {
            $fileName = isset($_FILES["answers"]['name'][$index]['file']) ? $_FILES["answers"]['name'][$index]['file'] : null;
            $newAnswerFileName = $fileName ? generateTimestampedFilename($fileName) : null;
            $answerData = [
                'id' => $answer['id'],
                'answer' => $answer['text'] ?? ($newAnswerFileName ? 'uploads/answers/' . $newAnswerFileName : null),
                'is_media' => $_POST['is_media'],
                'correct_answer' => $answer['isCorrect'],
                'correct_answer_id' => $answer['correctAnswerId'],
            ];
            if ($newAnswerFileName && isset($_FILES["answers"]['error'][$index]['file']) && $_FILES["answers"]['error'][$index]['file'] == UPLOAD_ERR_OK) {
                $filePath = '../../uploads/answers/' . $newAnswerFileName;
                if (!move_uploaded_file($_FILES["answers"]['tmp_name'][$index]['file'], $filePath)) {
                    $errors[] = "Failed to upload answer file for index $index.";
                }
            }
            $answersData[] = $answerData;
        }
    }

    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'message' => $errors]);
        exit;
    }

    try {
        $paperService = new PaperService();
        if ($paperService->editQuestionWithAnswers($questionId, $questionData, $answersData)) {
            http_response_code(200);
            echo json_encode(["success" => true, "message" => "Question updated successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to update the question."]);
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Internal server error."]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
