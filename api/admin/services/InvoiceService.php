<?php
class InvoiceService
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function createExamInvoice($exam_id, $user_id, $invoice_no)
    {
        try {
            $query = "INSERT INTO invoice (invoice_no, user_id, exam_id, payment_methods_payment_method_id, payment_status_status_id) 
        VALUES (:invoice_no, :user_id, :exam_id, 2,2)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':invoice_no', $invoice_no);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':exam_id', $exam_id);
            return $stmt->execute();
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }

    public function createPackageInvoice($package_id, $user_id, $invoice_no){
        try {
            $query = "INSERT INTO invoice (invoice_no, user_id, packages_id, payment_methods_payment_method_id, payment_status_status_id) 
        VALUES (:invoice_no, :user_id, :packages_id, 2,2)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':invoice_no', $invoice_no);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':packages_id', $package_id);
            $stmt->execute();

            $userHasPackageQuery = "INSERT INTO user_has_packages (user_id, packages_id) VALUES (:user_id, :packages_id)";
            $userHasPackageStmt = $this->db->prepare($userHasPackageQuery);
            $userHasPackageStmt->bindParam(':user_id', $user_id);
            $userHasPackageStmt->bindParam(':packages_id', $package_id);
            return $userHasPackageStmt->execute();
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
