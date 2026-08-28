<?php
include("db_conn.php");
if(isset($_REQUEST['product_id'])){
    $product_id = intval($_REQUEST['product_id']);
    
    // Also cleanup product images
    $get_uin_q = mysqli_query($conn, "SELECT uin FROM product_table WHERE product_id = '$product_id'");
    if ($get_uin_q && mysqli_num_rows($get_uin_q) > 0) {
        $uin_row = mysqli_fetch_assoc($get_uin_q);
        $uin_escaped = mysqli_real_escape_string($conn, $uin_row['uin']);
        mysqli_query($conn, "DELETE FROM product_images WHERE uin = '$uin_escaped'");
    }

    $sql = "DELETE FROM product_table WHERE product_id='$product_id'";
    if(mysqli_query($conn, $sql)){
        if (class_exists('CacheManager')) {
            CacheManager::flush();
        }
        echo "<script>alert('Product successfully deleted.'); window.location.href='product.php';</script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>