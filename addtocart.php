<?php
include('customer-session-check.php');
include('db_conn.php');

date_default_timezone_set("Africa/Lagos");

// Check Login Status
if (!isset($_SESSION['customer_email'])) {
    header("Location: reg/user-login");
    exit();
}

$invoiceNumber = $_SESSION['invoicenumber'];
$uin           = $_POST['uin']; 
$product_id    = $_POST['product_id'];
$quantity      = (int)$_POST['quantity'];
$date          = date('Y-m-d');
$paymentStatus = "Pending";

$customerUin  = isset($session_customer_uin) ? $session_customer_uin : $_SESSION['customer_uin'];
$customerName = isset($session_fullname) ? $session_fullname : 'Customer';
$customerPhone = isset($session_phone) ? $session_phone : $_SESSION['customer_phone'];
$customerEmail = isset($session_email) ? $session_email : $_SESSION['customer_email'];

if ($quantity <= 0) {
    echo "<script>alert('Quantity must be greater than zero.'); window.location='product.php?uin=$uin';</script>";
    exit();
}

// Fetch Product Details and Check Stock
$stmt = mysqli_prepare($conn, "SELECT * FROM product_table WHERE product_id = ?");
mysqli_stmt_bind_param($stmt, 'i', $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "<script>alert('Product not found'); window.location='product.php?uin=$uin';</script>";
    exit();
}

$availableQuantity = (int)$row['quantity'];
if ($quantity > $availableQuantity) {
    echo "<script>
        alert('Not enough stock for {$row['productname']}. Available: {$availableQuantity}');
        window.location='product.php?uin=$uin';
    </script>";
    exit();
}

// Set Product Variables
$productName  = $row['productname'];
$productImage = $row['productimage'];
$category     = $row['category'];
$costPrice    = (float)$row['costprice'];
$sellingPrice = (float)$row['sellingprice'];

if ($sellingPrice <= 0) {
    echo "<script>alert('Price of product is not fixed yet'); window.location='product.php?uin=$uin';</script>";
    exit();
}

// Calculate Price & Profit
$amount = $sellingPrice * $quantity;
$profit = ($sellingPrice - $costPrice) * $quantity;

// Check if product already exists in this specific Pending invoice
$check = mysqli_prepare($conn, "SELECT * FROM invoiceorder WHERE invoicenumber = ? AND uin = ? AND paymentstatus = 'Pending'");
mysqli_stmt_bind_param($check, 'ss', $invoiceNumber, $uin);
mysqli_stmt_execute($check);
$checkResult     = mysqli_stmt_get_result($check);
$existingProduct = mysqli_fetch_assoc($checkResult);

if ($existingProduct) {
    // UPDATE EXISTING ENTRY
    $newQuantity = (int)$existingProduct['quantity'] + $quantity;
    
    if ($newQuantity > $availableQuantity) {
        echo "<script>alert('Cannot add more. Total in cart exceeds available stock.'); window.location='product.php?uin=$uin';</script>";
        exit();
    }

    $newAmount = $sellingPrice * $newQuantity;
    $newProfit = ($sellingPrice - $costPrice) * $newQuantity;

    $update = mysqli_prepare($conn, "
        UPDATE invoiceorder 
        SET quantity = ?, amount = ?, profit = ?
        WHERE invoicenumber = ? AND uin = ? AND paymentstatus = 'Pending'
    ");
    mysqli_stmt_bind_param($update, 'iddss', $newQuantity, $newAmount, $newProfit, $invoiceNumber, $uin);
    mysqli_stmt_execute($update);

} else {
    $vendorUin = isset($row['vendor_uin']) ? $row['vendor_uin'] : null;

    $insert = mysqli_prepare($conn, "
        INSERT INTO invoiceorder 
        (invoicenumber, quantity, amount, profit, productname, category, uin, date, customername, customer_phone, customer_email, customer_uin, paymentstatus, productimage, vendor_uin)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    mysqli_stmt_bind_param(
        $insert,
        'siddsssssssssss',
        $invoiceNumber,
        $quantity,
        $amount,
        $profit,
        $productName,
        $category,
        $uin,
        $date,
        $customerName,
        $customerPhone,
        $customerEmail,
        $customerUin,
        $paymentStatus,
        $productImage,
        $vendorUin
    );
    mysqli_stmt_execute($insert);
}

// Deduct added quantity from product_table stock
$deduct_stock = mysqli_prepare($conn, "UPDATE product_table SET quantity = GREATEST(0, quantity - ?)
 WHERE product_id = ? OR uin = ?");
if ($deduct_stock) {
    mysqli_stmt_bind_param($deduct_stock, 'iis', $quantity, $product_id, $uin);
    mysqli_stmt_execute($deduct_stock);
    mysqli_stmt_close($deduct_stock);
}

echo "<script>alert('Item added to cart successfully!');
 window.location.href='product?uin=$uin';</script>";
exit();
?>