<?php
class UserService
{
    private $db;
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    public function getUserCount()
    {
        try {
            $query = "SELECT COUNT(*) AS user_count FROM user";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['user_count'];
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }

    public function getUsers()
    {
        try {
            $query = "SELECT * FROM user";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ? $result : null;
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }

    public function getStudentCountCurrentMonth()
    {
        try {
            $query = "
            SELECT COUNT(*) AS student_count
            FROM user
            WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
            AND YEAR(created_at) = YEAR(CURRENT_DATE())
        ";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['student_count'];
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
    public function getRecentlyRegisteredStudents()
    {
        try {
            $query = "
            SELECT 
                CONCAT(first_name, ' ', last_name) AS full_name,
                mobile,
                created_at AS registered_date,
                email
            FROM user
            WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
            AND YEAR(created_at) = YEAR(CURRENT_DATE())
            ORDER BY created_at DESC LIMIT 4
        ";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
    public function __destruct()
    {
        $this->db = null; 
    }
}
