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

if(isset($_REQUEST['customer_id'])){
    $customer_id = intval($_REQUEST['customer_id']);
    $sql = "DELETE FROM customertable WHERE customer_id='$customer_id'";
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Customer successfully deleted.');
                window.location.href='customers';
             </script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>