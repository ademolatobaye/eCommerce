<?php
include('customer-session-check.php');
include('db_conn.php');
if(isset($_REQUEST['product_id'])){
    $pid = intval($_REQUEST['product_id']);
    
    // Fetch item details before deletion
    $get_item = mysqli_query($conn, "SELECT uin, quantity FROM invoiceorder WHERE product_id = '$pid' LIMIT 1");
    
    if ($get_item && $item_row = mysqli_fetch_assoc($get_item)) {
        $item_uin = mysqli_real_escape_string($conn, $item_row['uin']);
        $item_qty = (int)$item_row['quantity'];

        $sql = "DELETE FROM invoiceorder WHERE product_id='$pid'";
        if(mysqli_query($conn, $sql)){
            // Restore stock only after successful deletion
            if ($item_qty > 0) {
                mysqli_query($conn, "UPDATE product_table SET quantity = quantity + $item_qty WHERE uin = '$item_uin' OR product_id = '$pid'");
            }
            echo "<script>alert('Cart item successfully removed.'); window.location.href='cart';</script>";
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    } else {
        $sql = "DELETE FROM invoiceorder WHERE product_id='$pid'";
        if(mysqli_query($conn, $sql)){
            echo "<script>alert('Cart item successfully removed.'); window.location.href='cart';</script>";
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    }
    mysqli_close($conn);
}
?>