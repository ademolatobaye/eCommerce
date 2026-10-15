<?php
session_start();
include('db_conn.php'); 

$sql = "SELECT * FROM system_setting LIMIT 1";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$setting_row = mysqli_fetch_assoc($result);
$phone = $setting_row['phone'];
$business_name = $setting_row['business_name'];
$address = $setting_row['address'];
$email = $setting_row['email'];
$rand = rand(1000, 9999);
$today = date("Y-m-d");

$CLIENT_ID = '819598202326-9ncesagc8ap8rsmbe3oe3ch6o9j1e6oe.apps.googleusercontent.com';

// 1. CSRF check
if (empty($_POST['g_csrf_token']) || empty($_COOKIE['g_csrf_token'])
    || $_POST['g_csrf_token'] !== $_COOKIE['g_csrf_token']) {
    http_response_code(400);
    exit('Invalid CSRF token');
}

// 2. Verify the ID token with Google (no library)
$idToken = $_POST['credential'] ?? '';
$ch = curl_init('https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($idToken));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
}
$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$payload = ($code === 200) ? json_decode($response, true) : null;

if (!$payload || ($payload['aud'] ?? '') !== $CLIENT_ID) {
    http_response_code(401);
    exit('Invalid ID token');
}


$email    = $payload['email'] ?? '';
$name     = $payload['name'] ?? '';
$verified = ($payload['email_verified'] ?? 'false') === 'true';

if (!$verified || $email === '') {
    exit('Google email not verified');
}

// 3. Find existing customer by email
$stmt = $conn->prepare("SELECT * FROM customertable WHERE customer_email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    // Sign up: create a new customer
    $uin      = $business_name . $rand . $today;
    $password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT); // unusable random password
    $otp      = '';
    $phone    = '';
    $address  = '';
    $status   = 'Verified';
    $date     = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO customertable
        (customer_email, customer_uin, otp, fullname, phone, `address`, `password`, `status`, `date`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $email, $uin, $otp, $name, $phone, $address, $password, $status, $date);
    $stmt->execute();
    $stmt->close();

    $user = [
        'customer_email' => $email,
        'customer_uin'   => $uin,
        'fullname'       => $name,
    ];
}

// 4. Log in 
session_regenerate_id(true);
$_SESSION['customer_email'] = $user['customer_email'];
$_SESSION['fullname']       = $user['fullname'];
$_SESSION['customer_uin']   = $user['customer_uin'];

// Restore existing invoicenumber if user has a pending cart
$customer_uin = $user['customer_uin'];
$invoice_check = mysqli_query($conn, "SELECT invoicenumber FROM invoiceorder WHERE customer_uin = '$customer_uin' AND paymentstatus = 'Pending' LIMIT 1");
if ($invoice_check && mysqli_num_rows($invoice_check) > 0) {
    $invoice_row = mysqli_fetch_assoc($invoice_check);
    $_SESSION['invoicenumber'] = $invoice_row['invoicenumber'];
}

$redirect_url = $_SESSION['redirect_after_login'] ?? '../index';
unset($_SESSION['redirect_after_login']);

header("Location: $redirect_url");
exit();