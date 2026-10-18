<?php
include("session-check.php");
include("db_conn.php");
check_access(array('Super Admin'));

$sql = "SELECT * FROM system_setting LIMIT 1";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$setting_row = mysqli_fetch_assoc($result);
$phone = $setting_row['phone'];
$business_name = $setting_row['business_name'];
$address = $setting_row['address'];
$email = $setting_row['email'];

// Check if business_name is NULL or empty
if (empty($setting_row['business_name'])) {
    header("Location: ../management/");
    exit();
}

if (isset($_REQUEST['invoicenumber'])) {
    $inv = trim($_REQUEST['invoicenumber']);
    $stmt1 = mysqli_prepare($conn, "DELETE FROM invoicesales WHERE invoicenumber = ? OR order_id = ?");
    if ($stmt1) {
        mysqli_stmt_bind_param($stmt1, "ss", $inv, $inv);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_close($stmt1);
    }
    $stmt2 = mysqli_prepare($conn, "DELETE FROM invoiceorder WHERE invoicenumber = ?");
    if ($stmt2) {
        mysqli_stmt_bind_param($stmt2, "s", $inv);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);
    }
    echo "<script>alert('Order successfully deleted.');
        window.location.href='orders';</script>";
    exit();
} elseif (isset($_REQUEST['product_id'])) {
    $pid = intval($_REQUEST['product_id']);
    $stmt = mysqli_prepare($conn, "DELETE FROM invoiceorder WHERE product_id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $pid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    echo "<script>alert('Order item successfully deleted.'); 
    window.location.href='orders';</script>";
    exit();
}
?>