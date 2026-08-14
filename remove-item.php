<?php
include('customer-session-check.php');
include('db_conn.php');
if(isset($_REQUEST['product_id'])){
    $pid = intval($_REQUEST['product_id']);
    $sql = "DELETE FROM invoiceorder WHERE product_id='$pid'";
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Cart item successfully removed.'); window.location.href='cart.php';</script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>