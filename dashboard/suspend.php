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

    $upd_stmt = mysqli_prepare($conn, "UPDATE vendor_table SET `status` = 'Suspended' WHERE id = ?");
    mysqli_stmt_bind_param($upd_stmt, "i", $vendor_id);
    if(mysqli_stmt_execute($upd_stmt)){
        mysqli_stmt_close($upd_stmt);
        if ($vendor && !empty($vendor['vendor_email'])) {
            $vendor_email = $vendor['vendor_email'];
            $vendor_name  = $vendor['vendor_name'];
            $store_name   = $vendor['store_name'];

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host       = "mail.pocketvest.com.ng";
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "ssl";
            $mail->Port       = "465";
            $mail->Username   = "noreply@pocketvest.com.ng";
            $mail->Password   = "ecommerce@2026";
            $mail->Subject    = "Notice: Vendor Account Suspended";
            $mail->setFrom('noreply@pocketvest.com.ng', "$business_name");
            $mail->isHTML(true);
            $mail->addAddress($vendor_email);

            $mail->Body = "
            <html>
            <body style='font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px;'>
                <div style='max-width: 600px; background: #fff; margin: 0 auto; padding: 30px; border-radius: 8px;'>
                    <h2 style='color: #d9534f;'>Account Suspended</h2>
                    <p>Dear <b>" . htmlspecialchars($vendor_name) . "</b>,</p>
                    <p>This email is to notify you that your vendor account for <b>" . htmlspecialchars($store_name) . "</b> has been <b>SUSPENDED</b> by management.</p>
                    <p>If you believe this is an error or need assistance resolving this issue, please contact our vendor support desk.</p>
                    <hr style='border: none; border-top: 1px solid #eee; margin-top: 20px;'>
                    <p style='font-size: 12px; color: #888;'>&copy; " . date('Y') . " $business_name. All rights reserved.</p>
                </div>
            </body>
            </html>";

            $mail->send();
        }

        echo "<script>alert('Vendor successfully suspended.'); 
        window.location.href='vendors';
        </script>";
    } else {
        echo "Error suspending record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>