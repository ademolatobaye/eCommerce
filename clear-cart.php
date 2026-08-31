<?php
include('customer-session-check.php');
include("db_conn.php");

$invoiceNumber = isset($_SESSION['invoicenumber']) ? mysqli_real_escape_string($conn, $_SESSION['invoicenumber']) : '';

if (!empty($invoiceNumber)) {
    $res = mysqli_query($conn, "SELECT uin, product_id, quantity FROM invoiceorder WHERE invoicenumber = '$invoiceNumber' AND paymentstatus = 'Pending'");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $qty = (int)$row['quantity'];
            $uin = mysqli_real_escape_string($conn, $row['uin']);
            $pid = (int)$row['product_id'];
            if ($qty > 0) {
                mysqli_query($conn, "UPDATE product_table SET quantity = quantity + $qty WHERE uin = '$uin' OR product_id = '$pid'");
            }
        }
    }
    $sql = "DELETE FROM invoiceorder WHERE invoicenumber = '$invoiceNumber' AND paymentstatus = 'Pending'";
} else {
    $res = mysqli_query($conn, "SELECT uin, product_id, quantity FROM invoiceorder WHERE paymentstatus = 'Pending'");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $qty = (int)$row['quantity'];
            $uin = mysqli_real_escape_string($conn, $row['uin']);
            $pid = (int)$row['product_id'];
            if ($qty > 0) {
                mysqli_query($conn, "UPDATE product_table SET quantity = quantity + $qty WHERE uin = '$uin' OR product_id = '$pid'");
            }
        }
    }
    $sql = "DELETE FROM invoiceorder WHERE paymentstatus = 'Pending'";
}

if(mysqli_query($conn, $sql)){
    echo "<script>
            alert('Cart successfully cleared.');
            window.location.href='cart';
          </script>";
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>