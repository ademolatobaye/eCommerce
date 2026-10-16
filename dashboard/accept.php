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

require 'includes/Exception.php';
require 'includes/PHPMailer.php';
require 'includes/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if(isset($_REQUEST['id'])){
    $vendor_id = intval($_REQUEST['id']);

    $v_stmt = mysqli_prepare($conn, "SELECT * FROM vendor_table WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($v_stmt, "i", $vendor_id);
    mysqli_stmt_execute($v_stmt);
    $v_res = mysqli_stmt_get_result($v_stmt);
    $vendor = mysqli_fetch_assoc($v_res);
    mysqli_stmt_close($v_stmt);

    $upd_stmt = mysqli_prepare($conn, "UPDATE vendor_table SET `status` = 'Active' WHERE id = ?");
    mysqli_stmt_bind_param($upd_stmt, "i", $vendor_id);
    if(mysqli_stmt_execute($upd_stmt)){
        mysqli_stmt_close($upd_stmt);
        if ($vendor && !empty($vendor['vendor_email'])) {
            $vendor_email = $vendor['vendor_email'];
            $vendor_name  = $vendor['vendor_name'];
            $store_name   = $vendor['store_name'];
            $login_url    = "http://" . $_SERVER['HTTP_HOST'] . "/e-commerce/vendor/login";

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host       = "mail.pocketvest.com.ng";
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "ssl";
            $mail->Port       = "465";
            $mail->Username   = "noreply@pocketvest.com.ng";
            $mail->Password   = "ecommerce@2026";
            $mail->Subject    = "Vendor Application Accepted - Welcome to $business_name!";
            $mail->setFrom('noreply@pocketvest.com.ng', "$business_name");
            $mail->isHTML(true);
            $mail->addAddress($vendor_email);

            $mail->Body = "
            <html>
            <body style='font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px;'>
                <div style='max-width: 600px; background: #fff; margin: 0 auto; padding: 30px; border-radius: 8px;'>
                    <h2 style='color: #4B0082;'>Vendor Application Accepted!</h2>
                    <p>Dear <b>" . htmlspecialchars($vendor_name) . "</b>,</p>
                    <p>We are delighted to inform you that your application for store <b>" . htmlspecialchars($store_name) . "</b> has been <b>ACCEPTED</b>!</p>
                    <p>You can now log in to your vendor dashboard and begin setting up your shop and listing your items.</p>
                    <p><a href='" . $login_url . "' style='background: #4B0082; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Log In to Vendor Portal</a></p>
                    <hr style='border: none; border-top: 1px solid #eee; margin-top: 20px;'>
                    <p style='font-size: 12px; color: #888;'>&copy; " . date('Y') . " $business_name. All rights reserved.</p>
                </div>
            </body>
            </html>";

            $mail->send();
        }

        echo "<script>alert('Vendor application successfully accepted.'); 
        window.location.href='vendors';
        </script>";
    } else {
        echo "Error accepting record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>
