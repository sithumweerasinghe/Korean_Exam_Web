<?php
class ClientNewsService
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    public function getValidAllNews($page, $newsPerPage)
    {
        try {
            date_default_timezone_set('Asia/Colombo');
            $currentDate = date('Y-m-d');
            $offset = ($page - 1) * $newsPerPage;
            $query = "SELECT news_id, news_title, news, tag, valid_days, created_at, news_image 
                  FROM news 
                  WHERE DATE_ADD(created_at, INTERVAL valid_days DAY) >= :current_date
                  ORDER BY created_at DESC 
                  LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':current_date', $currentDate);
            $stmt->bindParam(':limit', $newsPerPage, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $countQuery = "SELECT COUNT(*) AS total FROM news 
                       WHERE DATE_ADD(created_at, INTERVAL valid_days DAY) >= :current_date";
            $countStmt = $this->db->prepare($countQuery);
            $countStmt->bindParam(':current_date', $currentDate);
            $countStmt->execute();
            $totalNews = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            return [
                "news" => $stmt->fetchAll(PDO::FETCH_ASSOC),
                "total" => $totalNews
            ];
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return [
                "news" => [],
                "total" => 0
            ];
        }
    }
    public function getValidNews()
    {
        try {
            date_default_timezone_set('Asia/Colombo');
            $currentDate = date('Y-m-d');
            $query = "SELECT news_id, news_title, news, tag, valid_days, created_at, news_image 
                      FROM news 
                      WHERE DATE_ADD(created_at, INTERVAL valid_days DAY) >= :current_date
                      ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':current_date', $currentDate);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return [];
        }
    }
    public function getNewsById($id)
    {
        try {
            $query = "SELECT * FROM news WHERE news_id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
    public function getNewestNews()
    {
        try {
            date_default_timezone_set('Asia/Colombo');
            $currentDate = date('Y-m-d');
            $query = "SELECT news_id, news_title, news, tag, valid_days, created_at, news_image 
                      FROM news 
                      WHERE DATE_ADD(created_at, INTERVAL valid_days DAY) >= :current_date
                      ORDER BY created_at DESC 
                      LIMIT 4";
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
