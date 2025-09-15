<?php
class ExamTimetableService
{

    private $db;
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllExams()
    {
        date_default_timezone_set('Asia/Colombo');
        $today_date = date('Y-m-d');
        $current_time = date('H:i:s');
        try {
            $query = "SELECT papers_paper_id, exam_date, start_time, end_time, exam_time_table.id, exam_price 
          FROM exam_time_table 
          INNER JOIN time_slots ON exam_time_table.time_slots_id = time_slots.id 
          WHERE exam_date BETWEEN :today_date AND DATE_ADD(:exam_date, INTERVAL 21 DAY) 
          AND (exam_date > :today OR (exam_date = :to_day AND start_time >= :current_time)) 
          ORDER BY exam_date ASC, start_time ASC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":exam_date", $today_date);
            $stmt->bindParam(":today_date", $today_date);
            $stmt->bindParam(":today", $today_date);
            $stmt->bindParam(":to_day", $today_date);
            $stmt->bindParam(":current_time", $current_time);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: [];
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            return [];
        }
    }

    public function getUserHasExam($exam_id)
    {
        try {
            $sql = "SELECT * FROM invoice WHERE exam_id =:exam_id AND user_id = :user_id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(":exam_id", $exam_id);
            $stmt->bindParam(":user_id", $_SESSION["client_id"]);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }

    public function getUserPaidExam($exam_id)
    {
        try {
            $sql = "SELECT * FROM invoice WHERE exam_id =:exam_id AND user_id = :user_id AND payment_status_status_id=2";

            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(":exam_id", $exam_id);
            $stmt->bindParam(":user_id", $_SESSION["client_id"]);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }

    public function getExamDetails($exam_id)
    {
        $query = "SELECT exam_date, paper_name, start_time, end_time FROM exam_time_table INNER JOIN time_slots ON time_slots.id = exam_time_table.time_slots_id
                    INNER JOIN papers ON papers.paper_id= exam_time_table.papers_paper_id WHERE exam_time_table.id= :exam_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":exam_id", $exam_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: [];
    }

    public function getExamResults($exam_id)
    {
        $query = "SELECT 
    admission_no, 
    email, 
    admissions.created_at, 
    paper_name, 
    marks, 
    cutoff_mark.mark
FROM 
    exam_results 
INNER JOIN exam_time_table 
    ON exam_time_table.id = exam_results.exam_time_table_id 
INNER JOIN papers 
    ON papers.paper_id = exam_time_table.papers_paper_id
INNER JOIN admissions 
    ON admissions.id = exam_results.admissions_id
LEFT JOIN cutoff_mark 
    ON cutoff_mark.id = exam_results.cutoff_mark_id
INNER JOIN user 
    ON user.id = admissions.user_id
WHERE 
    exam_time_table.id = :exam_id 
ORDER BY 
    marks DESC;
";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":exam_id", $exam_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: [];
    }

    public function getLastExamResults()
    {
        $query = "SELECT 
    admission_no, 
    email, 
    admissions.created_at, 
    paper_name, 
    marks, 
    cutoff_mark.mark
FROM 
    exam_results 
INNER JOIN exam_time_table 
    ON exam_time_table.id = exam_results.exam_time_table_id 
INNER JOIN papers 
    ON papers.paper_id = exam_time_table.papers_paper_id
INNER JOIN admissions 
    ON admissions.id = exam_results.admissions_id
LEFT JOIN cutoff_mark 
    ON cutoff_mark.id = exam_results.cutoff_mark_id
INNER JOIN user 
    ON user.id = admissions.user_id
WHERE 
    exam_time_table.id = (SELECT exam_time_table_id FROM exam_results WHERE id = (SELECT MAX(id) FROM exam_results))
ORDER BY 
    marks DESC
";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: [];
    }

    public function getLastExamDetails()
    {
        $query = "SELECT exam_date, paper_name, start_time, end_time FROM exam_time_table INNER JOIN time_slots ON time_slots.id = exam_time_table.time_slots_id
                    INNER JOIN papers ON papers.paper_id= exam_time_table.papers_paper_id WHERE exam_time_table.id= (SELECT exam_time_table_id FROM exam_results WHERE id = (SELECT MAX(id) FROM exam_results))";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: [];
    }
    public function __destruct()
    {
        $this->db = null; 
    }
}
