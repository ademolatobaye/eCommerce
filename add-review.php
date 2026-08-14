<?php
session_start();
include('db_conn.php');

$product_uin  = isset($_POST['product_uin']) ? trim($_POST['product_uin']) : '';
$rating       = isset($_POST['rating']) ? (int)$_POST['rating'] : 5;
$review_title = isset($_POST['review_title']) ? trim($_POST['review_title']) : '';
$review_text  = isset($_POST['review_text']) ? trim($_POST['review_text']) : '';

if (empty($product_uin) || empty($review_text)) {
    echo "<script>alert('Please fill in all required review fields.'); window.history.back();</script>";
    exit();
}

if ($rating < 1) $rating = 1;
if ($rating > 5) $rating = 5;

$customer_uin  = isset($_SESSION['customer_uin']) ? $_SESSION['customer_uin'] : '';
$customer_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : (isset($_POST['customer_name']) ? trim($_POST['customer_name']) : 'Guest Reviewer');

if (empty($customer_name)) {
    $customer_name = 'Anonymous';
}

$stmt = mysqli_prepare($conn, "
    INSERT INTO product_reviews 
    (product_uin, customer_uin, customer_name, rating, review_title, review_text, `timestamp`) 
    VALUES (?, ?, ?, ?, ?, ?, NOW())
");

mysqli_stmt_bind_param($stmt, 'sssiss', $product_uin, $customer_uin, $customer_name, $rating, $review_title, $review_text);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Thank you! Your review has been submitted successfully.'); window.location.href='product.php?uin=" . urlencode($product_uin) . "#reviews';</script>";
} else {
    echo "<script>alert('Failed to submit review. Please try again.'); window.history.back();</script>";
}
exit();
?>
