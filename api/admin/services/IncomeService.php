<?php
class IncomeService
{
    private $db;
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    public function getTotalIncome()
    {
        try {
            $query = "SELECT 
    SUM(
        CASE 
            WHEN i.exam_id IS NOT NULL THEN et.exam_price
            WHEN i.packages_id IS NOT NULL THEN p.package_price
            ELSE 0
        END
    ) AS total_income
FROM 
    invoice i
LEFT JOIN 
    packages p ON i.packages_id = p.id
LEFT JOIN 
    exam_time_table et ON i.exam_id = et.id";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_income'] ?? 0;
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
    public function getMonthlyIncome()
    {
        try {
            $query = "SELECT SUM(p.package_price) AS monthly_income
                      FROM invoice i
                      INNER JOIN packages p ON i.packages_id = p.id
                      WHERE MONTH(i.created_at) = MONTH(CURRENT_DATE())
                      AND YEAR(i.created_at) = YEAR(CURRENT_DATE())";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['monthly_income'] ?? 0;
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
    public function getPaymentMethodCounts()
    {
        try {
            $query = "SELECT pm.method, COUNT(i.payment_methods_payment_method_id) AS count
                      FROM invoice i
                      INNER JOIN payment_methods pm 
                      ON i.payment_methods_payment_method_id = pm.payment_method_id
                      GROUP BY i.payment_methods_payment_method_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
    public function getinvoices()
    {
        try {
            $query = "SELECT 
                      i.invoice_id AS id, 
                      i.invoice_no AS invoice_no,
                      u.mobile AS mobile, 
                      CONCAT(u.first_name, ' ', u.last_name) AS user_name, 
                      i.created_at AS issued_date, 
                      p.package_price AS amount, 
                      ps.status_id AS payment_status,
                      pm.method AS payment_method
                  FROM invoice i
                  INNER JOIN user u ON i.user_id = u.id
                  LEFT JOIN packages p ON i.packages_id = p.id
                  INNER JOIN payment_status ps ON i.payment_status_status_id = ps.status_id
                  INNER JOIN payment_methods pm ON i.payment_methods_payment_method_id = pm.payment_method_id
                  ORDER BY i.created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return [];
        }
    }
    public function updatePaymentStatus($invoiceId, $paymentStatus)
    {
        try {
            $query = "UPDATE invoice SET payment_status_status_id = :payment_status WHERE invoice_id = :invoice_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':payment_status', $paymentStatus, PDO::PARAM_INT);
            $stmt->bindParam(':invoice_id', $invoiceId, PDO::PARAM_INT);
            $result = $stmt->execute();
            if ($result && $paymentStatus == 2) {
                $query = "SELECT * FROM invoice WHERE invoice_id = :invoice_id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':invoice_id', $invoiceId, PDO::PARAM_INT);
                $stmt->execute();
                $invoice_data = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($invoice_data) {
                    $package_id = $invoice_data['packages_id'];
                    $user_id = $invoice_data['user_id'];
                    if (!is_null($package_id)) {
                        $query = "INSERT INTO user_has_packages (`user_id`, `packages_id`) VALUES (:user_id, :packages_id)";
                        $stmt = $this->db->prepare($query);
                        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                        $stmt->bindParam(':packages_id', $package_id, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }
            }
            return true;
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }
    public function __destruct()
    {
        $this->db = null; 
    }
}
