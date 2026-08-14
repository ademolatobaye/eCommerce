<?php
session_start();
include('db_conn.php');

header('Content-Type: application/json');

$product_uin = isset($_REQUEST['product_uin']) ? trim($_REQUEST['product_uin']) : (isset($_REQUEST['uin']) ? trim($_REQUEST['uin']) : '');

if (empty($product_uin)) {
    echo json_encode(array('success' => false, 'message' => 'Product identifier is missing.'));
    exit();
}

// Check product existence
$stmt = mysqli_prepare($conn, "SELECT product_id, productname FROM product_table WHERE uin = ?");
mysqli_stmt_bind_param($stmt, 's', $product_uin);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($res) === 0) {
    echo json_encode(array('success' => false, 'message' => 'Product not found.'));
    exit();
}

$customer_uin = isset($_SESSION['customer_uin']) ? $_SESSION['customer_uin'] : '';

if (!empty($customer_uin)) {
    // Database storage for logged-in user
    $check_stmt = mysqli_prepare($conn, "SELECT id FROM wishlist WHERE customer_uin = ? AND product_uin = ?");
    mysqli_stmt_bind_param($check_stmt, 'ss', $customer_uin, $product_uin);
    mysqli_stmt_execute($check_stmt);
    $check_res = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_res) > 0) {
        // Toggle remove
        $del_stmt = mysqli_prepare($conn, "DELETE FROM wishlist WHERE customer_uin = ? AND product_uin = ?");
        mysqli_stmt_bind_param($del_stmt, 'ss', $customer_uin, $product_uin);
        mysqli_stmt_execute($del_stmt);
        $action = 'removed';
        $message = 'Item removed from your wishlist.';
    } else {
        // Insert item
        $ins_stmt = mysqli_prepare($conn, "INSERT INTO wishlist (customer_uin, product_uin, `timestamp`) VALUES (?, ?, NOW())");
        mysqli_stmt_bind_param($ins_stmt, 'ss', $customer_uin, $product_uin);
        mysqli_stmt_execute($ins_stmt);
        $action = 'added';
        $message = 'Item added to your wishlist!';
    }

    // Get total count
    $cnt_stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM wishlist WHERE customer_uin = ?");
    mysqli_stmt_bind_param($cnt_stmt, 's', $customer_uin);
    mysqli_stmt_execute($cnt_stmt);
    $cnt_res = mysqli_stmt_get_result($cnt_stmt);
    $cnt_row = mysqli_fetch_assoc($cnt_res);
    $count = (int)$cnt_row['total'];

} else {
    // Session storage for guest user
    if (!isset($_SESSION['wishlist']) || !is_array($_SESSION['wishlist'])) {
        $_SESSION['wishlist'] = array();
    }

    if (in_array($product_uin, $_SESSION['wishlist'])) {
        $key = array_search($product_uin, $_SESSION['wishlist']);
        if ($key !== false) {
            unset($_SESSION['wishlist'][$key]);
        }
        $_SESSION['wishlist'] = array_values($_SESSION['wishlist']);
        $action = 'removed';
        $message = 'Item removed from your wishlist.';
    } else {
        $_SESSION['wishlist'][] = $product_uin;
        $action = 'added';
        $message = 'Item added to your wishlist!';
    }
    $count = count($_SESSION['wishlist']);
}

$is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || isset($_GET['ajax']);

if (!$is_ajax) {
    $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'wishlist.php';
    header("Location: " . $redirect_url);
    exit();
}

echo json_encode(array(
    'success' => true,
    'action'  => $action,
    'message' => $message,
    'count'   => $count
));
exit();
?>
