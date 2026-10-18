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

if(isset($_REQUEST['product_id'])){
    $product_id = intval($_REQUEST['product_id']);
    
    // Also cleanup product images
    $uin_stmt = mysqli_prepare($conn, "SELECT uin FROM product_table WHERE product_id = ?");
    if ($uin_stmt) {
        mysqli_stmt_bind_param($uin_stmt, "i", $product_id);
        mysqli_stmt_execute($uin_stmt);
        $uin_res = mysqli_stmt_get_result($uin_stmt);
        if ($uin_res && mysqli_num_rows($uin_res) > 0) {
            $uin_row = mysqli_fetch_assoc($uin_res);
            $img_del = mysqli_prepare($conn, "DELETE FROM product_images WHERE uin = ?");
            if ($img_del) {
                mysqli_stmt_bind_param($img_del, "s", $uin_row['uin']);
                mysqli_stmt_execute($img_del);
                mysqli_stmt_close($img_del);
            }
        }
        mysqli_stmt_close($uin_stmt);
    }

    $del_stmt = mysqli_prepare($conn, "DELETE FROM product_table WHERE product_id = ?");
    if($del_stmt){
        mysqli_stmt_bind_param($del_stmt, "i", $product_id);
        if (mysqli_stmt_execute($del_stmt)) {
            if (class_exists('CacheManager')) {
                CacheManager::flush();
            }
            mysqli_stmt_close($del_stmt);
            echo "<script>alert('Product successfully deleted.');
                    window.location.href='product';
            </script>";
            exit();
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
            mysqli_stmt_close($del_stmt);
        }
    }
    mysqli_close($conn);
}
?>