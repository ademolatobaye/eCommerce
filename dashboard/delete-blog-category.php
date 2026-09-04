<?php
include("db_conn.php");

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

if(isset($_REQUEST['id'])){
    $cat_id = intval($_REQUEST['id']);
    $sql = "DELETE FROM blog_category WHERE id='$cat_id'";
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Blog Category successfully deleted.'); window.location.href='view-blog-category';</script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>