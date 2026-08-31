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
    header("Location: reg/user-login");
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
    header("Location: cart");
    exit();
}

$updated_count = 0;
$messages = array();

foreach ($quantities as $cart_product_id => $new_qty) {
    $cart_product_id = (int)$cart_product_id;
    $new_qty         = (int)$new_qty;

    if ($cart_product_id <= 0) continue;

    // STEP 1: Fetch item directly from invoiceorder (Cart)
    $item_stmt = mysqli_prepare($conn, "
        SELECT * FROM invoiceorder 
        WHERE product_id = ? AND invoicenumber = ? AND paymentstatus = 'Pending'
        LIMIT 1
    ");
    
    if (!$item_stmt) continue;

    mysqli_stmt_bind_param($item_stmt, 'is', $cart_product_id, $invoiceNumber);
    mysqli_stmt_execute($item_stmt);
    $item_res = mysqli_stmt_get_result($item_stmt);
    $item_row = mysqli_fetch_assoc($item_res);
    mysqli_stmt_close($item_stmt);

    if (!$item_row) continue;

    $old_qty  = (int)$item_row['quantity'];
    $item_uin = mysqli_real_escape_string($conn, $item_row['uin']);

    // If new quantity is 0 or less, remove item from cart and restore stock
    if ($new_qty <= 0) {
        $del_stmt = mysqli_prepare($conn, "DELETE FROM invoiceorder WHERE product_id = ? AND invoicenumber = ? AND paymentstatus = 'Pending'");
        if ($del_stmt) {
            mysqli_stmt_bind_param($del_stmt, 'is', $cart_product_id, $invoiceNumber);
            mysqli_stmt_execute($del_stmt);
            mysqli_stmt_close($del_stmt);

            if ($old_qty > 0) {
                mysqli_query($conn, "UPDATE product_table SET quantity = quantity + $old_qty WHERE uin = '$item_uin' OR product_id = '$cart_product_id'");
            }

            $updated_count++;
        }
        continue;
    }

    // STEP 2: Fetch stock and pricing directly from product_table (Warehouse)
    $available_stock = 999999;
    $costPrice       = 0;
    $sellingPrice    = (float)$item_row['amount'] / (int)$item_row['quantity'];

    $prod_stmt = mysqli_prepare($conn, "SELECT quantity, costprice, sellingprice FROM product_table WHERE uin = ? OR product_id = ? LIMIT 1");
    if ($prod_stmt) {
        mysqli_stmt_bind_param($prod_stmt, 'si', $item_uin, $cart_product_id);
        mysqli_stmt_execute($prod_stmt);
        $prod_res = mysqli_stmt_get_result($prod_stmt);
        if ($prod_row = mysqli_fetch_assoc($prod_res)) {
            $available_stock = (int)$prod_row['quantity'];
            $costPrice       = (float)$prod_row['costprice'];
            if ((float)$prod_row['sellingprice'] > 0) {
                $sellingPrice = (float)$prod_row['sellingprice'];
            }
        }
        mysqli_stmt_close($prod_stmt);
    }

    $max_allowed = $old_qty + $available_stock;

    if ($new_qty > $max_allowed) {
        $new_qty = $max_allowed;
        $messages[] = "Quantity for {$item_row['productname']} adjusted to available stock ({$max_allowed}).";
    }

    $delta = $new_qty - $old_qty;

    $newAmount = $sellingPrice * $new_qty;
    $newProfit = ($sellingPrice - $costPrice) * $new_qty;

    // STEP 3: Update cart item in invoiceorder
    $upd_stmt = mysqli_prepare($conn, "
        UPDATE invoiceorder 
        SET quantity = ?, amount = ?, profit = ? 
        WHERE product_id = ? AND invoicenumber = ? AND paymentstatus = 'Pending'
    ");

    if ($upd_stmt) {
        mysqli_stmt_bind_param($upd_stmt, 'iddis', $new_qty, $newAmount, $newProfit, $cart_product_id, $invoiceNumber);
        mysqli_stmt_execute($upd_stmt);
        mysqli_stmt_close($upd_stmt);

        // Adjust stock in product_table according to delta
        if ($delta > 0) {
            mysqli_query($conn, "UPDATE product_table SET quantity = GREATEST(0, quantity - $delta) WHERE uin = '$item_uin' OR product_id = '$cart_product_id'");
        } elseif ($delta < 0) {
            $restore = abs($delta);
            mysqli_query($conn, "UPDATE product_table SET quantity = quantity + $restore WHERE uin = '$item_uin' OR product_id = '$cart_product_id'");
        }

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

echo "<script>alert('{$alert_msg}'); window.location.href='cart';</script>";
exit();
?>
