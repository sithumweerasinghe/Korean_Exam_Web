<?php
class InvoiceService
{

    private $db;
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllInvoices()
    {
        try {
            $query = "SELECT packages_id,exam_date, invoice_no, package_price, package_name, created_at, package_price, status, payment_methods_payment_method_id, exam_id,start_time,end_time,exam_price FROM invoice LEFT JOIN packages ON packages.id=invoice.packages_id LEFT JOIN payment_status ON payment_status.status_id = invoice.payment_status_status_id LEFT JOIN exam_time_table ON exam_time_table.id=invoice.exam_id LEFT JOIN time_slots ON time_slots.id = exam_time_table.time_slots_id WHERE user_id=:user_id ORDER BY invoice.invoice_id DESC";

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

    public function getInvoiceById($invoice_id)
    {
        try {
            $query = "SELECT packages_id,exam_date, invoice_no, package_price, package_name, created_at, package_price, status, payment_methods_payment_method_id, exam_id,start_time,end_time,exam_price FROM invoice LEFT JOIN packages ON packages.id=invoice.packages_id LEFT JOIN payment_status ON payment_status.status_id = invoice.payment_status_status_id LEFT JOIN exam_time_table ON exam_time_table.id=invoice.exam_id LEFT JOIN time_slots ON time_slots.id = exam_time_table.time_slots_id  WHERE invoice_no=:invoice_no ORDER BY invoice.invoice_id DESC";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":invoice_no", $invoice_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

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
