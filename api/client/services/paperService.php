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
    p.paper_id,
    p.paper_name,
    p.isSample,
    p.paper_status,
    CASE
        WHEN p.isSample = 1 THEN 'unlocked'
        WHEN EXISTS (
            SELECT 1
            FROM user_has_packages up
            JOIN packages_has_papers php ON up.packages_id = php.packages_id
            JOIN packages pkg ON up.packages_id = pkg.id
            JOIN invoice i ON up.packages_id = i.packages_id
            WHERE up.user_id = :user_id 
            AND php.papers_paper_id = p.paper_id
            AND i.created_at >= NOW() - INTERVAL pkg.valid_months MONTH
        ) THEN 'unlocked' ELSE 'locked' END AS status FROM papers p
        ORDER BY 
    CASE
        WHEN p.isSample = 1 THEN 0
        WHEN EXISTS (
            SELECT 1
            FROM user_has_packages up
            JOIN packages_has_papers php ON up.packages_id = php.packages_id
            JOIN packages pkg ON up.packages_id = pkg.id
            JOIN invoice i ON up.packages_id = i.packages_id
            WHERE 
                up.user_id = :user_id2 
                AND php.papers_paper_id = p.paper_id
                AND i.created_at >= NOW() - INTERVAL pkg.valid_months MONTH
        ) THEN 0
    ELSE 1 END, p.paper_name";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":user_id", $_SESSION["client_id"]);
            $stmt->bindParam(":user_id2", $_SESSION["client_id"]);
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
