<?php
require_once '../request-filters/admin_request_filter.php';

class IncomeService
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getMonthlyIncomes()
    {
        try {
            $query = "SELECT 
    pm.method AS payment_method, 
    MONTH(i.created_at) AS month,
    SUM(
        CASE 
            WHEN i.exam_id IS NOT NULL THEN et.exam_price
            WHEN i.packages_id IS NOT NULL THEN p.package_price
            ELSE 0
        END
    ) AS monthly_income
FROM 
    invoice i
LEFT JOIN 
    packages p ON i.packages_id = p.id
LEFT JOIN 
    exam_time_table et ON i.exam_id = et.id
INNER JOIN 
    payment_methods pm ON i.payment_methods_payment_method_id = pm.payment_method_id
WHERE 
    YEAR(i.created_at) = YEAR(CURRENT_DATE())
GROUP BY 
    pm.method, MONTH(i.created_at)
ORDER BY 
    MONTH(i.created_at);

            ";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $incomes = [
                'Payment Gateway' => array_fill(0, 12, 0),
                'Bank Transfer' => array_fill(0, 12, 0),
            ];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $paymentMethod = $row['payment_method'];
                $month = $row['month'] - 1;
                $incomes[$paymentMethod][$month] = $row['monthly_income'];
            }

            return $incomes;
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
}

header('Content-Type: application/json');

$incomeService = new IncomeService();
$incomes = $incomeService->getMonthlyIncomes();
echo json_encode($incomes);
