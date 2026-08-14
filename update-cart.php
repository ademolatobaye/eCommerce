<?php
include('customer-session-check.php');
include('db_conn.php');

date_default_timezone_set("Africa/Lagos");

$is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || isset($_REQUEST['ajax']);

if (!isset($_SESSION['customer_email']) || !isset($_SESSION['invoicenumber'])) {
    if ($is_ajax) {
        echo json_encode(array('success' => false, 'message' => 'Session expired. Please log in again.'));
        exit();
    }
    header("Location: reg/user-login.php");
    exit();
}

$invoiceNumber = $_SESSION['invoicenumber'];

// Handle array of quantities from cart form: $_POST['quantities'][$product_id] = $qty
$quantities = isset($_POST['quantities']) && is_array($_POST['quantities']) ? $_POST['quantities'] : array();

// Handle single item update fallback: $_REQUEST['product_id'] and $_REQUEST['quantity']
if (empty($quantities) && isset($_REQUEST['product_id']) && isset($_REQUEST['quantity'])) {
    $quantities[(int)$_REQUEST['product_id']] = (int)$_REQUEST['quantity'];
}

if (empty($quantities)) {
    if ($is_ajax) {
        echo json_encode(array('success' => false, 'message' => 'No items provided to update.'));
        exit();
    }
    header("Location: cart.php");
    exit();
}

$updated_count = 0;
$messages = array();

foreach ($quantities as $cart_product_id => $new_qty) {
    $cart_product_id = (int)$cart_product_id;
    $new_qty         = (int)$new_qty;

    if ($cart_product_id <= 0) continue;

    // Fetch existing item in invoiceorder
    $item_stmt = mysqli_prepare($conn, "
        SELECT io.*, pt.quantity AS available_stock, pt.costprice, pt.sellingprice 
        FROM invoiceorder io
        LEFT JOIN product_table pt ON (io.uin = pt.uin OR io.product_id = pt.product_id)
        WHERE io.product_id = ? AND io.invoicenumber = ? AND io.paymentstatus = 'Pending'
        LIMIT 1
    ");
    
    if (!$item_stmt) continue;

    mysqli_stmt_bind_param($item_stmt, 'is', $cart_product_id, $invoiceNumber);
    mysqli_stmt_execute($item_stmt);
    $item_res = mysqli_stmt_get_result($item_stmt);
    $item_row = mysqli_fetch_assoc($item_res);
    mysqli_stmt_close($item_stmt);

    if (!$item_row) continue;

    // If new quantity is 0 or less, remove item from cart
    if ($new_qty <= 0) {
        $del_stmt = mysqli_prepare($conn, "DELETE FROM invoiceorder WHERE product_id = ? AND invoicenumber = ? AND paymentstatus = 'Pending'");
        if ($del_stmt) {
            mysqli_stmt_bind_param($del_stmt, 'is', $cart_product_id, $invoiceNumber);
            mysqli_stmt_execute($del_stmt);
            mysqli_stmt_close($del_stmt);
            $updated_count++;
        }
        continue;
    }

    $available_stock = isset($item_row['available_stock']) ? (int)$item_row['available_stock'] : 999999;
    if ($new_qty > $available_stock) {
        $new_qty = $available_stock;
        $messages[] = "Quantity for {$item_row['productname']} adjusted to available stock ({$available_stock}).";
    }

    $costPrice    = isset($item_row['costprice']) ? (float)$item_row['costprice'] : 0;
    $sellingPrice = isset($item_row['sellingprice']) ? (float)$item_row['sellingprice'] : ((float)$item_row['amount'] / (int)$item_row['quantity']);

    $newAmount = $sellingPrice * $new_qty;
    $newProfit = ($sellingPrice - $costPrice) * $new_qty;

    $upd_stmt = mysqli_prepare($conn, "
        UPDATE invoiceorder 
        SET quantity = ?, amount = ?, profit = ? 
        WHERE product_id = ? AND invoicenumber = ? AND paymentstatus = 'Pending'
    ");

    if ($upd_stmt) {
        mysqli_stmt_bind_param($upd_stmt, 'iddis', $new_qty, $newAmount, $newProfit, $cart_product_id, $invoiceNumber);
        mysqli_stmt_execute($upd_stmt);
        mysqli_stmt_close($upd_stmt);
        $updated_count++;
    }
}

if ($is_ajax) {
    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => true,
        'message' => 'Cart updated successfully!',
        'details' => implode(' ', $messages)
    ));
    exit();
}

$alert_msg = 'Cart updated successfully!';
if (!empty($messages)) {
    $alert_msg .= ' ' . implode(' ', $messages);
}

echo "<script>alert('{$alert_msg}'); window.location.href='cart.php';</script>";
exit();
?>
