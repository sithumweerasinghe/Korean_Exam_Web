<?php
require_once "../config/dbconnection.php";
header("Content-Type: application/json");

include "../request-filters/client_request_filter.php";
$inputData = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
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

if (json_last_error() != JSON_ERROR_NONE) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid JSON."
    ]);
    exit();
}

$requiredFields = ["fname", "lname", "address", "mobile", "email", "payment_method", "id", "package_price"];

foreach ($requiredFields as $field) {
    if (!isset($inputData[$field]) || empty(trim($inputData[$field]))) {
        echo json_encode([
            "success" => false,
            "message" => ucfirst($field) . " is required."
        ]);
        exit();
    }
}

$email = $inputData["email"];
$address = $inputData["address"];
$mobile = $inputData["mobile"];
$fname = $inputData["fname"];
$lname = $inputData["lname"];
$payment_method = $inputData["payment_method"];
$id = $inputData["id"];
$isExam = $inputData["isExam"];
$package_name = $inputData["package_name"];
$package_price = $inputData["package_price"];
$city = $inputData["city"];

if (!preg_match("/^[0]{1}[7]{1}[01245678]{1}[0-9]{7}$/", $inputData["mobile"])) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid mobile number"
    ]);
    exit();
}

if (strlen($address) > 100) {
    echo json_encode([
        "success" => false,
        "message" => "Address should be lower than 100 characters"
    ]);
    exit();
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT * FROM user WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() == 0) {
        echo json_encode([
            "success" => false,
            "message" => "User not found"
        ]);
        exit();
    }

    if ($isExam) {
        $checkExamQuery = "SELECT * FROM exam_time_table WHERE id = :id";
        $checkExamStmt = $conn->prepare($checkExamQuery);
        $checkExamStmt->bindParam(":id", $id);
        $checkExamStmt->execute();

        if ($checkExamStmt->rowCount() == 0) {
            echo json_encode([
                "success" => false,
                "message" => "Exam not found"
            ]);
            exit();
        }
    } else {
        $checkPackageQuery = "SELECT * FROM packages WHERE id = :id";
        $checkPkgStmt = $conn->prepare($checkPackageQuery);
        $checkPkgStmt->bindParam(":id", $id);
        $checkPkgStmt->execute();

        if ($checkPkgStmt->rowCount() == 0) {
            echo json_encode([
                "success" => false,
                "message" => "Package not found"
            ]);
            exit();
        }
    }

    $updateUserQuery = "UPDATE user SET mobile=:mobile, address=:address, city=:city WHERE email=:email";
    $updateUserStmt = $conn->prepare($updateUserQuery);
    $updateUserStmt->bindParam(":mobile", $mobile);
    $updateUserStmt->bindParam(":address", $address);
    $updateUserStmt->bindParam(":city", $city);
    $updateUserStmt->bindParam(":email", $email);

    if (!$updateUserStmt->execute()) {
        echo json_encode([
            "success" => false,
            "message" => "Couldn't update user"
        ]);
        exit();
    }

    $_SESSION["client_mobile"] = $mobile;
    $_SESSION["client_address"] = $address;
    $_SESSION["client_city"] = $city;
    $invoiceNo = time();

    if($isExam){
        $insertQuery = "INSERT INTO invoice (invoice_no, exam_id, user_id, payment_methods_payment_method_id,payment_status_status_id) VALUES (:invoice_no, :exam_id, :user_id, :payment_method_id, 1)";
    }else{
        $insertQuery = "INSERT INTO invoice (invoice_no, packages_id, user_id, payment_methods_payment_method_id,payment_status_status_id) VALUES (:invoice_no, :package_id, :user_id, :payment_method_id, 1)";
    }

    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bindParam(":invoice_no", $invoiceNo);
    if($isExam){
        $insertStmt->bindParam(":exam_id", $id);
    }else{
        $insertStmt->bindParam(":package_id", $id);
    }
    $insertStmt->bindParam(":user_id", $client["id"]);
    $insertStmt->bindParam(":payment_method_id", $payment_method);

    if (!$insertStmt->execute()) {
        echo json_encode([
            "success" => false,
            "message" => "Failed to insert invoice"
        ]);
        exit();
    }

    if ($payment_method == 1) {
        $array = array();

        $order_id = $invoiceNo;
        $merchant_id = "224995";
        $merchant_secret = "MzE1MTEyODkyNDM3NzU0MjM2MjUxNjI4MDQxOTMwMTM3NDQxNDYzMg==";
        $currency = 'LKR';
        $amount = $package_price;
        $hash = strtoupper(
            md5(
                $merchant_id .
                    $order_id .
                    number_format($amount, 2, '.', '') .
                    $currency .
                    strtoupper(md5($merchant_secret))
            )
        );

        $array["fname"] = $fname;
        $array["lname"] = $lname;
        $array["email"] = $email;
        $array["mobile"] = $mobile;
        $array["address"] = $address;
        $array["city"] = $city;
        $array["item"] = $package_name;
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
    } else {
        echo json_encode([
            "success" => true,
            "order_id" => $invoiceNo,
            "address" => $address,
            "message" => "Successfully made the order. You can upload the slip to whatsapp"
        ]);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Internal server error."
    ]);
}finally {
    $conn = null; 
}

