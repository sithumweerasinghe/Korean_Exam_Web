<?php
require_once "../config/dbconnection.php";
header("Content-Type: application/json");

include "../request-filters/client_request_filter.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid Request Method."
    ]);
    exit();
}

if (!isset($_SESSION["client_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Please login to continue"
    ]);
    exit();
}

if (!isset($_GET["invoice_no"]) || empty(trim($_GET["invoice_no"]))) {
    echo json_encode([
        "success" => false,
        "message" => "Invoice Number is required."
    ]);
    exit();
}

$invoice_no = $_GET["invoice_no"];
$isExam = $_GET["isExam"];

try {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT * FROM invoice LEFT JOIN user ON user.id = invoice.user_id LEFT JOIN packages ON packages.id = invoice.packages_id LEFT JOIN exam_time_table ON exam_time_table.id= invoice.exam_id WHERE invoice_no = :invoice_no;";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":invoice_no", $invoice_no);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        echo json_encode([
            "success" => false,
            "message" => "Invoice not found"
        ]);
        exit();
    }

    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    $array = array();

    $order_id = $invoice_no;
    $merchant_id = "224995";
    $merchant_secret = "MzE1MTEyODkyNDM3NzU0MjM2MjUxNjI4MDQxOTMwMTM3NDQxNDYzMg==";
    $currency = 'LKR';
    $amount = $isExam == "true" ? $invoice["exam_price"] : $invoice["package_price"];
    $hash = strtoupper(
        md5(
            $merchant_id .
                $order_id .
                number_format($amount, 2, '.', '') .
                $currency .
                strtoupper(md5($merchant_secret))
        )
    );

    $array["fname"] = $invoice["first_name"];
    $array["lname"] = $invoice["last_name"];
    $array["email"] = $invoice["email"];
    $array["mobile"] = $invoice["mobile"];
    $array["address"] = $invoice["address"];
    $array["city"] = $invoice["city"];
    $array["item"] = $isExam == "true" ? "Exam paper" : $invoice["package_name"];
    $array["order_id"] = $order_id;
    $array["amount"] = $amount;
    $array["currency"] = $currency;
    $array["hash"] = $hash;
    $array["merchant_secret"] = $merchant_secret;
    $array["merchant_id"] = $merchant_id;
    echo json_encode([
        "success" => true,
        "message" => $array
    ]);
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Internal server error."
    ]);
}finally {
    $conn = null; 
}
