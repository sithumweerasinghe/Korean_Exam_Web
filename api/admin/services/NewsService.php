<?php
class AdminNewsService
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
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
    public function insertNews($title, $news, $tag, $validDays, $newsImage)
    {
        try {
            $query = "INSERT INTO news (news_title, news, tag, valid_days, news_image) 
                      VALUES (:title, :news, :tag, :valid_days, :news_image)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':news', $news);
            $stmt->bindParam(':tag', $tag);
            $stmt->bindParam(':valid_days', $validDays);
            $stmt->bindParam(':news_image', $newsImage);
            return $stmt->execute();
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }
    public function editNews($id, $title, $news, $tag, $validDays, $newsImage)
    {
        try {
            $query = "UPDATE news 
                      SET news_title = :title, news = :news, tag = :tag, valid_days = :valid_days, news_image = :news_image 
                      WHERE news_id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':news', $news);
            $stmt->bindParam(':tag', $tag);
            $stmt->bindParam(':valid_days', $validDays);
            $stmt->bindParam(':news_image', $newsImage);
            return $stmt->execute();
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }
    public function deleteNews($id)
    {
        try {
            $query = "DELETE FROM news WHERE news_id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }
    public function getAllNews()
    {
        try {
            $query = "SELECT * FROM news ORDER BY created_at DESC";
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return [];
        }
    }
    public function getNewsImagePath($newsId)
{
    try {
        $query = "SELECT news_image FROM news WHERE news_id = :news_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':news_id', $newsId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['news_image'] : null;
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
