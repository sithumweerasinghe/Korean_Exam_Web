<?php
class ResultService
{

    private $db;
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllExamsResults()
    {
        try {
            $query = "SELECT marks,admission_no,admissions.created_at,exam_date, paper_name,cutoff_mark.mark AS cutoff_mark
                        FROM exam_results
                        LEFT JOIN admissions ON admissions.id = exam_results.admissions_id
                        LEFT JOIN cutoff_mark ON cutoff_mark.id = exam_results.cutoff_mark_id
                        LEFT JOIN exam_time_table ON exam_time_table.id = exam_results.exam_time_table_id
                        LEFT JOIN papers ON papers.paper_id = exam_time_table.papers_paper_id
                        WHERE user_id = :user_id
                        ORDER BY admissions.created_at DESC;
                        ";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":user_id", $_SESSION["client_id"]);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result ?: null;
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }

    public function getAllPapersResults()
    {
        try {
            $query = "SELECT result,admission_no,admissions.created_at, paper_name,cutoff_mark.mark AS cutoff_mark
                        FROM student_results
                        LEFT JOIN admissions ON admissions.id = student_results.admissions_id
                        LEFT JOIN cutoff_mark ON cutoff_mark.id = student_results.cutoff_mark_id
                        LEFT JOIN papers ON papers.paper_id = student_results.papers_paper_id
                        WHERE user_id = :user_id
                        ORDER BY admissions.created_at DESC";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":user_id", $_SESSION["client_id"]);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result ?: null;
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
    public function __destruct()
    {
        $this->db = null; 
    }
}
