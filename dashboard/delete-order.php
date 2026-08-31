<?php
include("db_conn.php");

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