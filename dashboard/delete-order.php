<?php
include("db_conn.php");

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
    $inv = mysqli_real_escape_string($conn, $_REQUEST['invoicenumber']);
    mysqli_query($conn, "DELETE FROM invoicesales WHERE invoicenumber='$inv' OR order_id='$inv'");
    mysqli_query($conn, "DELETE FROM invoiceorder WHERE invoicenumber='$inv'");
    echo "<script>alert('Order successfully deleted.'); window.location.href='orders';</script>";
    exit();
} elseif (isset($_REQUEST['product_id'])) {
    $pid = mysqli_real_escape_string($conn, $_REQUEST['product_id']);
    mysqli_query($conn, "DELETE FROM invoiceorder WHERE product_id='$pid'");
    echo "<script>alert('Order item successfully deleted.'); window.location.href='orders';</script>";
    exit();
}
?>