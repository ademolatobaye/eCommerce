<?php
include('customer-session-check.php');
include('db_conn.php');

// Escape and prepare all POST variables
$date             = mysqli_real_escape_string($conn, $_POST['date']);
$order_id         = mysqli_real_escape_string($conn, $_POST['order_id']);
$customer_address = mysqli_real_escape_string($conn, $_POST['customer_address']);
$customername     = mysqli_real_escape_string($conn, $_POST['customername']);
$customer_email   = mysqli_real_escape_string($conn, $_POST['customer_email']);
$customer_phone   = mysqli_real_escape_string($conn, $_POST['customer_phone']);
$state            = mysqli_real_escape_string($conn, $_POST['state']);
$city             = mysqli_real_escape_string($conn, $_POST['city']);
$ordernote        = mysqli_real_escape_string($conn, $_POST['ordernote']);
$paymentmethod    = mysqli_real_escape_string($conn, $_POST['paymentmethod']);
$customer_uin     = mysqli_real_escape_string($conn, $_POST['customer_uin']);
$invoicenumber    = mysqli_real_escape_string($conn, $_POST['invoiceorder']);
$vendor_uin       = mysqli_real_escape_string($conn, $_POST['vendor_uin']);
$productname      = mysqli_real_escape_string($conn, $_POST['productname']);
$profit           = mysqli_real_escape_string($conn, $_POST['profit']);

// Safe delivery handling
$delivery = isset($_POST['delivery']) ? mysqli_real_escape_string($conn, implode(",", $_POST['delivery'])) : '';

// Pulling total and totalQty fresh from DB
$totalResult = mysqli_query($conn, "SELECT SUM(amount) AS total, SUM(quantity) AS totalQty FROM invoiceorder WHERE invoicenumber='$invoicenumber' AND paymentstatus='Pending'");
$totalRow    = mysqli_fetch_assoc($totalResult);
$total       = (float)$totalRow['total'];
$totalQty    = (int)$totalRow['totalQty'];

// Store everything in session for use in paycallback.php
$_SESSION['date']             = $date;
$_SESSION['order_id']         = $order_id;
$_SESSION['customer_address'] = $customer_address;
$_SESSION['customername']     = $customername;
$_SESSION['customer_email']   = $customer_email;
$_SESSION['customer_phone']   = $customer_phone;
$_SESSION['amount']           = $total;
$_SESSION['state']            = $state;
$_SESSION['city']             = $city;
$_SESSION['ordernote']        = $ordernote;
$_SESSION['delivery']         = $delivery;
$_SESSION['paymentmethod']    = $paymentmethod;
$_SESSION['customer_uin']     = $customer_uin;
$_SESSION['invoicenumber']    = $invoicenumber;
$_SESSION['totalQty']         = $totalQty;
$_SESSION['productname']      = $productname;
$_SESSION['profit']           = $profit;
$_SESSION['vendor_uin']       = $vendor_uin;

// Paystack initialization
$email = "$customer_email";
$amountKobo   = $total * 100; // Paystack requires amount in kobo
$callback_url = "https://ademolathedev.name.ng/e-commerce/paycallback.php";

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL            => "https://api.paystack.co/transaction/initialize",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST  => "POST",
    CURLOPT_POSTFIELDS     => json_encode([
        'email'        => $email,
        'amount'       => $amountKobo,
        'callback_url' => $callback_url
    ]),
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer",
        "Content-Type: application/json"
    ],
));

$response = curl_exec($curl);
$err      = curl_error($curl);
curl_close($curl);

if ($err) {
    echo "cURL Error: " . $err;
    exit;
}

$data = json_decode($response, true);

if ($data['status']) {
    $auth_url = $data['data']['authorization_url'];
    header("Location: $auth_url");
    exit;
} else {
    echo "Payment Initialization Failed: " . $data['message'];
}
?>