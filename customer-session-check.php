<?php
session_start();
include('db_conn.php');
date_default_timezone_set('Africa/Lagos');

// Session Timeout: 10 minutes
$timeout_duration = 600;

// Capture current page for redirect after login
$current_url = $_SERVER['REQUEST_URI'];

// Ensure this is a CUSTOMER session (avoid conflict with admin/staff)
if (!isset($_SESSION['customer_email'])) {
    header("Location: reg/user-login");
    exit();
}

// Handle session timeout
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    
    $_SESSION['redirect_after_login'] = $current_url;

    session_unset();
    session_destroy();
     echo "<script>
        alert('Session expired due to inactivity. Please log in again.');
        window.location.href = 'reg/user-login';
    </script>";
    exit();
}

// Update last activity time
$_SESSION['LAST_ACTIVITY'] = time();

// Assign session values safely
$customer_email    = $_SESSION['customer_email'];

// Fetch customer details securely
$query = "SELECT * FROM customertable WHERE customer_email = ? AND `status` = 'Verified'";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $customer_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Validate category (extra protection)
        // if ($row['category'] !== $customer_category) {
        //     session_unset();
        //     session_destroy();

        //     header("Location: sign_in?error=invalid_user");
        //     exit();
        // }

        // Assign customer data to variables
        $session_customer_id     = $row['customer_id'];
        $session_customer_uin     = $row['customer_uin'];
        $session_fullname        = $row['fullname'];
        $session_email           = $row['customer_email'];
        $session_phone           = $row['phone'];
        $session_status          = $row['status'];
        $session_address          = $row['address'];

    } else {
        session_unset();
        session_destroy();

        header("Location: sign_in?error=not_found");
        exit();
    }

} else {
    die("Error preparing query.");
}
?>