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
    $group_id = $_GET['group_id'] ?? $_POST['group_id'] ?? null;
    if (!$group_id) {
        $errors[] = "Missing group_id.";
    }

    $questionData = [
        'questionType' => $_POST['questionType'] ?? null,
        'questionCategory' => $_POST['questionCategory'] ?? null,
        'questionText' => $_POST['questionText'] ?? null,
        'questionTime' => $_POST['questionTime'] ?? null,
        'marks' => $_POST['questionMarks'] ?? null,
        'group_id' => $group_id,
        'image_path' => null,
        'audio_path' => null,
    ];
    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] == UPLOAD_ERR_OK) {
        $imageFileExtension = pathinfo($_FILES['imageFile']['name'], PATHINFO_EXTENSION);
        $imageFileName = 'image_' . time() . '.' . $imageFileExtension;
        $imageFilePath = '../../uploads/images/' . $imageFileName;
        if (move_uploaded_file($_FILES['imageFile']['tmp_name'], $imageFilePath)) {
            $questionData['image_path'] = 'uploads/images/' . $imageFileName;
        } else {
            $errors[] = "Failed to upload image file.";
        }
    }
    if (isset($_FILES['audioFile']) && $_FILES['audioFile']['error'] == UPLOAD_ERR_OK) {
        $audioFileExtension = pathinfo($_FILES['audioFile']['name'], PATHINFO_EXTENSION);
        $audioFileName = 'audio_' . time() . '.' . $audioFileExtension;
        $audioFilePath = '../../uploads/audio/' . $audioFileName;
        if (move_uploaded_file($_FILES['audioFile']['tmp_name'], $audioFilePath)) {
            $questionData['audio_path'] = 'uploads/audio/' . $audioFileName;
        } else {
            $errors[] = "Failed to upload audio file.";
        }
    }
    $answersData = [];
    if (isset($_POST['answers']) && is_array($_POST['answers'])) {
        foreach ($_POST['answers'] as $index => $answer) {
            $fileName = null;
            if (isset($_FILES["answers"]['name'][$index]['file']) && $_FILES["answers"]['error'][$index]['file'] == UPLOAD_ERR_OK) {
                $answerFileExtension = pathinfo($_FILES["answers"]['name'][$index]['file'], PATHINFO_EXTENSION);
                $answerFileName = 'answer_' . time() . '_' . $index . '.' . $answerFileExtension;
                $filePath = '../../uploads/answers/' . $answerFileName;
                if (move_uploaded_file($_FILES["answers"]['tmp_name'][$index]['file'], $filePath)) {
                    $fileName = 'uploads/answers/' . $answerFileName;
                } else {
                    $errors[] = "Failed to upload answer file for index $index.";
                }
            }
            $answersData[] = [
                'answer' => $answer['text'] ?? $fileName,
                'is_media' => $fileName ? 1 : 0,
                'correct_answer' => $answer['isCorrect'],
            ];
        }
    } else {
        $errors[] = "Missing answers data.";
    }
    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'message' => $errors]);
        exit;
    }
    try {
        $paperService = new PaperService();
        if ($paperService->addQuestionWithAnswers($questionData, $answersData)) {
            http_response_code(200);
            echo json_encode(["success" => true, "message" => "Question added successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to add the question."]);
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Internal server error."]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
