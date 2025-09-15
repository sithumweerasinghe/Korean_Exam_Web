<?php
class PaperService
{
    private $db;
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    public function getAllPapers()
    {
        try {
            $query = "SELECT 
    paper_id,
    paper_status,
    papers.paper_name,
    papers.isSample,
    papers.publish_at,
    SUM(questions.marks) AS full_marks,
    (SELECT COUNT(*) 
     FROM question_groups 
     INNER JOIN questions 
     ON question_groups.id = questions.question_groups_id
     WHERE question_groups.papers_paper_id = papers.paper_id) AS question_count
FROM 
    papers
LEFT JOIN 
    question_groups 
    ON question_groups.papers_paper_id = papers.paper_id
LEFT JOIN 
    questions 
    ON question_groups.id = questions.question_groups_id
GROUP BY 
    papers.paper_id, papers.paper_name, papers.isSample, papers.publish_at;
";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
    public function getAllQuestionGroups($id)
    {
        try {
            $query = "SELECT 
    question_groups.id as `group_id`,
    question_groups.question_group_name as `group_name`
FROM 
    question_groups
LEFT JOIN 
    papers 
    ON question_groups.papers_paper_id = papers.paper_id
WHERE papers.paper_id = :id
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ? $result : null;
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
    public function addQuestionGroup($paper_id, $group_name)
    {
        try {
            $query = "INSERT INTO `question_groups` 
            (`question_group_name`, `papers_paper_id`) 
            VALUES 
            (:question_group_name, :papers_paper_id);
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':question_group_name', $group_name, PDO::PARAM_STR);
            $stmt->bindParam(':papers_paper_id', $paper_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }
    public function editQuestionGroup($group_id, $new_group_name)
    {
        try {
            $query = "UPDATE `question_groups` 
                  SET `question_group_name` = :new_group_name 
                  WHERE `id` = :group_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':new_group_name', $new_group_name, PDO::PARAM_STR);
            $stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }
    public function getPapersPublishedThisMonth()
    {
        try {
            $query = "SELECT COUNT(*) AS paper_count
                FROM papers
                WHERE MONTH(publish_at) = MONTH(CURRENT_DATE())
                AND YEAR(publish_at) = YEAR(CURRENT_DATE())
            ";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['paper_count'];
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
    public function getPaperCount()
    {
        try {
            $query = "SELECT COUNT(*) AS paper_count FROM papers";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['paper_count'];
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
    public function updatePaper($id, $title, $isSample, $paperStatus)
    {
        try {
            $query = "UPDATE papers
            SET paper_name = :title,
                isSample = :isSample,
                paper_status = :paperStatus
            WHERE paper_id = :id
        ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':isSample', $isSample, PDO::PARAM_INT);
            $stmt->bindParam('paperStatus', $paperStatus, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }
    public function deletePaper($id)
    {
        try {
            $query = "DELETE FROM papers WHERE paper_id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }
    public function addPaper($title, $isSample)
    {
        try {
            $query = "INSERT INTO papers (paper_name, isSample)
        VALUES (:title, :isSample)
        ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':isSample', $isSample, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }
    public function getQuestionsByPaperAndGroup($paper_id, $group_id)
    {
        try {
            $query = "SELECT 
                    questions.id as question_id,
                    questions.question as question_text,
                    questions.image_path,
                    questions.audio_path,
                    questions.isSample,
                    questions.marks,
                    questions.time_for_question
                FROM 
                    questions
                INNER JOIN 
                    question_groups 
                    ON questions.question_groups_id = question_groups.id
                INNER JOIN 
                    papers 
                    ON question_groups.papers_paper_id = papers.paper_id
                WHERE 
                    papers.paper_id = :paper_id 
                    AND question_groups.id = :group_id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':paper_id', $paper_id, PDO::PARAM_INT);
            $stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ? $result : null;
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
    public function addQuestionWithAnswers($questionData, $answersData)
    {
        try {
            $this->db->beginTransaction();
            $query = "INSERT INTO `questions` 
                        (`question`, `image_path`, `audio_path`, `isSample`, `marks`, `question_groups_id`, `time_for_question`, `question_categories_id`) 
                      VALUES 
                        (:question, :image_path, :audio_path, :isSample, :marks, :group_id, :time_for_question, :question_categories_id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':question', $questionData['questionText'], PDO::PARAM_STR);
            $stmt->bindParam(':image_path', $questionData['image_path'], PDO::PARAM_STR);
            $stmt->bindParam(':audio_path', $questionData['audio_path'], PDO::PARAM_STR);
            $stmt->bindParam(':isSample', $questionData['questionType'], PDO::PARAM_INT);
            $stmt->bindParam(':marks', $questionData['marks'], PDO::PARAM_INT);
            $stmt->bindParam(':group_id', $questionData['group_id'], PDO::PARAM_INT);
            $stmt->bindParam(':time_for_question', $questionData['questionTime'], PDO::PARAM_STR);
            $stmt->bindParam(':question_categories_id', $questionData['questionCategory'], PDO::PARAM_INT);
            $stmt->execute();
            $questionId = $this->db->lastInsertId();
            $answerQuery = "INSERT INTO `answers` 
                            (`answer`, `is_media`, `correct_answer`, `questions_id`) 
                            VALUES 
                            (:answer, :is_media, :correct_answer, :questions_id)";
            $answerStmt = $this->db->prepare($answerQuery);
            foreach ($answersData as $answer) {
                $answerStmt->bindParam(':answer', $answer['answer'], PDO::PARAM_STR);
                $answerStmt->bindParam(':is_media', $answer['is_media'], PDO::PARAM_INT);
                $answerStmt->bindParam(':correct_answer', $answer['correct_answer'], PDO::PARAM_INT);
                $answerStmt->bindParam(':questions_id', $questionId, PDO::PARAM_INT);
                $answerStmt->execute();
            }
            $this->db->commit();
            return true;
        } catch (PDOException $exception) {
            $this->db->rollBack();
            error_log($exception->getMessage());
            return false;
        }
    }
    public function getQuestionDataById($questionId)
    {
        try {
            $query = "SELECT q.question, q.image_path, q.audio_path, q.isSample, q.marks, q.question_groups_id, 
                          q.time_for_question, q.question_categories_id 
                  FROM questions q
                  WHERE q.id = :question_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
            $stmt->execute();
            $questionData = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$questionData) {
                return null;
            }
            $answerQuery = "SELECT a.id,a.answer, a.is_media, a.correct_answer 
                        FROM answers a 
                        WHERE a.questions_id = :question_id";
            $answerStmt = $this->db->prepare($answerQuery);
            $answerStmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
            $answerStmt->execute();
            $answersData = $answerStmt->fetchAll(PDO::FETCH_ASSOC);
            $questionData['answers'] = $answersData;
            return $questionData;
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }
    public function editQuestionWithAnswers($questionId, $questionData, $answersData)
    {
        $questionID = intval($questionId);
        try {
            $this->db->beginTransaction();
            $query = "UPDATE `questions` 
                      SET `question` = :question, 
                          `image_path` = :image_path, 
                          `audio_path` = :audio_path, 
                          `isSample` = :isSample, 
                          `marks` = :marks,  
                          `time_for_question` = :time_for_question, 
                          `question_categories_id` = :question_categories_id 
                      WHERE `id` = :question_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':question', $questionData['questionText'], PDO::PARAM_STR);
            $stmt->bindParam(':image_path', $questionData['image_path'], PDO::PARAM_STR);
            $stmt->bindParam(':audio_path', $questionData['audio_path'], PDO::PARAM_STR);
            $stmt->bindParam(':isSample', $questionData['questionType'], PDO::PARAM_INT);
            $stmt->bindParam(':marks', $questionData['marks'], PDO::PARAM_INT);
            $stmt->bindParam(':time_for_question', $questionData['questionTime'], PDO::PARAM_STR);
            $stmt->bindParam(':question_categories_id', $questionData['questionCategory'], PDO::PARAM_INT);
            $stmt->bindParam(':question_id', $questionID, PDO::PARAM_INT);
            $stmt->execute();
            $result = array_filter($answersData, fn($item) => isset($item['is_media']) && $item['is_media'] == 0);
            if (!empty($result)) {
                $deleteQuery = "DELETE FROM `answers` WHERE `questions_id` = :questionID";
                $deleteStmt = $this->db->prepare($deleteQuery);
                $deleteStmt->bindParam(':questionID', $questionID, PDO::PARAM_INT);
                $deleteStmt->execute();
            }
            foreach ($answersData as $answer) {
                if ($answer['is_media'] === "1" && $answer['answer'] !== null) {
                    $answerType = gettype($answer['answer']) !== "integer";
                    if ($answerType) {
                        $answerQuery = "INSERT INTO `answers` (`questions_id`, `answer`, `is_media`, `correct_answer`) 
                                    VALUES (:question_id, :answer, :is_media, :correct_answer)
                                    ON DUPLICATE KEY UPDATE 
                                    `answer` = :new_answer, `correct_answer` = :correct_answerr";
                        $answerStmt = $this->db->prepare($answerQuery);
                        $answerStmt->bindParam(':question_id', $questionID, PDO::PARAM_INT);
                        $answerStmt->bindParam(':answer', $answer['answer'], PDO::PARAM_STR);
                        $answerStmt->bindParam(':is_media', $answer['is_media'], PDO::PARAM_INT);
                        $answerStmt->bindParam(':correct_answer', $answer['correct_answer'], PDO::PARAM_INT);
                        $answerStmt->bindParam(':new_answer', $answer['answer'], PDO::PARAM_STR);
                        $answerStmt->bindParam(':correct_answerr', $answer['correct_answer'], PDO::PARAM_INT);
                        $answerStmt->execute();
                    } else {
                        $selectQuery = "SELECT `answer` FROM `answers` WHERE `id` = :answer_id";
                        $selectStmt = $this->db->prepare($selectQuery);
                        $selectStmt->bindParam(':answer_id', $answer['id'], PDO::PARAM_INT);
                        $selectStmt->execute();
                        $existingFilePath = $selectStmt->fetchColumn();
                        if ($existingFilePath && file_exists("../../" . $existingFilePath)) {
                            if (!unlink("../../" . $existingFilePath)) {
                                error_log("Failed to delete file: " . $existingFilePath);
                            }
                        }
                        $updateMediaQuery = "UPDATE `answers` 
                                         SET `answer` = :new_answer, `correct_answer` = :correct_answer 
                                         WHERE `id` = :answer_id";
                        $updateMediaStmt = $this->db->prepare($updateMediaQuery);
                        $updateMediaStmt->bindParam(':new_answer', $answer['answer'], PDO::PARAM_STR);
                        $updateMediaStmt->bindParam(':correct_answer', $answer['correct_answer'], PDO::PARAM_INT);
                        $updateMediaStmt->bindParam(':answer_id', $answer['id'], PDO::PARAM_INT);
                        $updateMediaStmt->execute();
                    }
                } elseif ($answer['is_media'] === "0") {
                    $isMedia = intval($answer['is_media']);
                    $updateNonMediaQuery = "INSERT INTO `answers` 
                                            ( `answer`, `is_media`, `correct_answer`, `questions_id`) 
                                            VALUES (:answer, :is_media, :correct_answer, :questions_id)
                                            ON DUPLICATE KEY UPDATE 
                                            `answer` = :answerr, `correct_answer` = :correct_answerr";
                    $updateNonMediaStmt = $this->db->prepare($updateNonMediaQuery);
                    $updateNonMediaStmt->bindParam(':answer', $answer['answer'], PDO::PARAM_STR);
                    $updateNonMediaStmt->bindParam(':answerr', $answer['answer'], PDO::PARAM_STR);
                    $updateNonMediaStmt->bindParam(':is_media', $isMedia, PDO::PARAM_INT);
                    $updateNonMediaStmt->bindParam(':correct_answer', $answer['correct_answer'], PDO::PARAM_INT);
                    $updateNonMediaStmt->bindParam(':correct_answerr', $answer['correct_answer'], PDO::PARAM_INT);
                    $updateNonMediaStmt->bindParam(':questions_id', $questionID, PDO::PARAM_INT);
                    $updateNonMediaStmt->execute();
                }
            }
            $correctAnswerId = null;
            $answerType = null;
            foreach ($answersData as $answer) {
                if ($answer['correct_answer'] === "1" && $answer['correct_answer_id'] !== null) {
                    $correctAnswerId = $answer['correct_answer_id'];
                    $answerType = $answer['is_media'];
                    break;
                }
            }
            $type = gettype($correctAnswerId);
            if ($answerType == 1) {
                error_log("Answer type is media");
                if ($correctAnswerId) {
                    $updateCorrectAnswerQuery = "UPDATE `answers` 
                                         SET `correct_answer` = 1 
                                         WHERE `id` = :correct_answer_id";
                    $updateCorrectAnswerStmt = $this->db->prepare($updateCorrectAnswerQuery);
                    $updateCorrectAnswerStmt->bindParam(':correct_answer_id', $correctAnswerId, PDO::PARAM_INT);
                    $updateCorrectAnswerStmt->execute();
                    $resetOtherAnswersQuery = "UPDATE `answers` 
                    SET `correct_answer` = 0 
                    WHERE `questions_id` = :question_id 
                    AND `id` != :correct_answer_id";
                    $resetOtherAnswersStmt = $this->db->prepare($resetOtherAnswersQuery);
                    $resetOtherAnswersStmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
                    $resetOtherAnswersStmt->bindParam(':correct_answer_id', $correctAnswerId, PDO::PARAM_INT);
                    $resetOtherAnswersStmt->execute();
                }
            } else {
                if ($type === "integer") {
                    if ($correctAnswerId) {
                        $updateCorrectAnswerQuery = "UPDATE `answers` 
                                         SET `correct_answer` = 1 
                                         WHERE `id` = :correct_answer_id";
                        $updateCorrectAnswerStmt = $this->db->prepare($updateCorrectAnswerQuery);
                        $updateCorrectAnswerStmt->bindParam(':correct_answer_id', $correctAnswerId, PDO::PARAM_INT);
                        $updateCorrectAnswerStmt->execute();
                    }
                }
            }
            $this->db->commit();
            return true;
        } catch (PDOException $exception) {
            $this->db->rollBack();
            error_log($exception);
            return false;
        }
    }

    public function getAllNonSamplePapers()
    {
        try {
            $query = "SELECT 
            paper_id,
            papers.paper_name,
            papers.isSample,
            papers.publish_at,
            SUM(questions.marks) AS full_marks,
            (SELECT COUNT(*) 
             FROM question_groups 
             INNER JOIN questions 
             ON question_groups.id = questions.question_groups_id
             WHERE question_groups.papers_paper_id = papers.paper_id) AS question_count
        FROM 
            papers
        LEFT JOIN 
            question_groups 
            ON question_groups.papers_paper_id = papers.paper_id
        LEFT JOIN 
            questions 
            ON question_groups.id = questions.question_groups_id
        WHERE 
            papers.isSample = 0  
        GROUP BY 
            papers.paper_id, papers.paper_name, papers.isSample, papers.publish_at;
        ";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
    public function addCutoffMark($mark)
    {
        try {
            $query = "INSERT INTO cutoff_mark (mark, created_at) VALUES (:mark, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':mark', $mark, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
    public function getLatestCutoffMark()
    {
        try {
            $query = "SELECT mark FROM cutoff_mark ORDER BY created_at DESC LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['mark'] : 'Not set';
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
    public function deleteQuestion($questionId)
    {
        try {
            $this->db->beginTransaction();
            $deleteAnswersQuery = "DELETE FROM answers WHERE questions_id = :question_id";
            $answerStmt = $this->db->prepare($deleteAnswersQuery);
            $answerStmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
            $answerStmt->execute();
            $deleteQuestionQuery = "DELETE FROM questions WHERE id = :question_id";
            $questionStmt = $this->db->prepare($deleteQuestionQuery);
            $questionStmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
            $questionStmt->execute();
            $this->db->commit();
            return true;
        } catch (PDOException $exception) {
            $this->db->rollBack();
            error_log($exception->getMessage());
            return false;
        }
    }
    public function deleteQuestionGroup($group_id)
    {
        try {
            $this->db->beginTransaction();
            $deleteAnswersQuery = "DELETE FROM answers WHERE questions_id IN (
                SELECT id FROM questions WHERE question_groups_id = :group_id
            )";
            $deleteAnswersStmt = $this->db->prepare($deleteAnswersQuery);
            $deleteAnswersStmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
            $deleteAnswersStmt->execute();
            $deleteQuestionsQuery = "DELETE FROM questions WHERE question_groups_id = :group_id";
            $deleteQuestionsStmt = $this->db->prepare($deleteQuestionsQuery);
            $deleteQuestionsStmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
            $deleteQuestionsStmt->execute();
            $deleteGroupQuery = "DELETE FROM question_groups WHERE id = :group_id";
            $deleteGroupStmt = $this->db->prepare($deleteGroupQuery);
            $deleteGroupStmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
            $deleteGroupStmt->execute();
            $this->db->commit();
            return true;
        } catch (PDOException $exception) {
            $this->db->rollBack();
            error_log($exception->getMessage());
            return false;
        }
    }
    public function __destruct()
    {
        $this->db = null; 
    }
}
