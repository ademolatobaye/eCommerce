<?php
session_start();
include("db_conn.php");

if(!isset($_SESSION['email'])){
    header("Location: staff-newotp.php");
    exit();
}

$email = $_SESSION['email'];

$otp = rand(1000,9999);
$_SESSION['otp_time'] = time();

$sql = "UPDATE stafftable SET otp='$otp', `status`='Pending' WHERE email='$email'";
mysqli_query($conn, $sql);

$otp = $_SESSION['otp'];

header("Location: staff-newotp.php");
exit();
?>