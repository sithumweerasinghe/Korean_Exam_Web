<?php
class ExamService
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    public function createTimeSlot($startTime, $endTime)
    {
        try {
            $query = "INSERT INTO time_slots (start_time, end_time) VALUES (:start_time, :end_time)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':start_time', $startTime);
            $stmt->bindParam(':end_time', $endTime);
            return $stmt->execute();
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }
    public function updateTimeSlot($id, $startTime, $endTime)
    {
        try {
            $query = "UPDATE time_slots SET start_time = :start_time, end_time = :end_time WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':start_time', $startTime);
            $stmt->bindParam(':end_time', $endTime);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }
    public function createExamTime($paperId, $timeSlotId, $examDate, $examPrice)
    {
        try {
            $query = "INSERT INTO exam_time_table (papers_paper_id, time_slots_id, exam_date,exam_price) 
                      VALUES (:paper_id, :time_slot_id, :exam_date,:exam_price)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':paper_id', $paperId);
            $stmt->bindParam(':time_slot_id', $timeSlotId);
            $stmt->bindParam(':exam_date', $examDate);
            $stmt->bindParam(':exam_price', $examPrice);
            return $stmt->execute();
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }
    public function updateExamTime($id, $paperId, $timeSlotId, $examDate, $examPrice)
    {
        try {
            $checkQuery = "SELECT COUNT(*) FROM papers WHERE paper_id = :paper_id";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':paper_id', $paperId);
            $checkStmt->execute();
            if ($checkStmt->fetchColumn() == 0) {
                throw new Exception("Paper ID does not exist in papers table");
            }

            $query = "UPDATE exam_time_table 
                      SET papers_paper_id = :paper_id, time_slots_id = :time_slot_id, exam_date = :exam_date, exam_price = :exam_price 
                      WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':paper_id', $paperId);
            $stmt->bindParam(':time_slot_id', $timeSlotId);
            $stmt->bindParam(':exam_date', $examDate);
            $stmt->bindParam(':exam_price', $examPrice);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function getTimeSlots()
    {
        try {
            $query = "SELECT * FROM time_slots";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return [];
        }
    }
    public function getExamTimeTable()
    {
        try {
            $query = "SELECT ett.id, ett.exam_date, ts.start_time, ts.end_time, p.paper_name, ts.id AS time_slot_id,p.paper_id AS paper_id,ett.exam_price AS exam_price
                      FROM exam_time_table ett
                      INNER JOIN time_slots ts ON ett.time_slots_id = ts.id
                      INNER JOIN papers p ON ett.papers_paper_id = p.paper_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return [];
        }
    }
    public function getValidExams()
    {
        try {
            date_default_timezone_set('Asia/Colombo');
            $currentDate = date('Y-m-d');
            $query = "SELECT ett.id, ett.exam_date, ts.start_time, ts.end_time, p.paper_name, ts.id AS time_slot_id, p.paper_id AS paper_id, ett.exam_price 
                  FROM exam_time_table ett
                  INNER JOIN time_slots ts ON ett.time_slots_id = ts.id
                  INNER JOIN papers p ON ett.papers_paper_id = p.paper_id
                  WHERE ett.exam_date >= :current_date";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':current_date', $currentDate);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return [];
        }
    }
    public function __destruct()
    {
        $this->db = null; 
    }
}
