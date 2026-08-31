<?php
session_start();
include('db_conn.php');

$product_uin = isset($_GET['uin']) ? trim($_GET['uin']) : (isset($_GET['product_uin']) ? trim($_GET['product_uin']) : '');

if (!empty($product_uin)) {
    $customer_uin = isset($_SESSION['customer_uin']) ? $_SESSION['customer_uin'] : '';

    if (!empty($customer_uin)) {
        $del_stmt = mysqli_prepare($conn, "DELETE FROM wishlist WHERE customer_uin = ? AND product_uin = ?");
        mysqli_stmt_bind_param($del_stmt, 'ss', $customer_uin, $product_uin);
        mysqli_stmt_execute($del_stmt);
    } else {
        if (isset($_SESSION['wishlist']) && is_array($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = array_diff($_SESSION['wishlist'], array($product_uin));
            $_SESSION['wishlist'] = array_values($_SESSION['wishlist']);
        }
    }
}

$redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'wishlist';
header("Location: " . $redirect_url);
exit();
?>
