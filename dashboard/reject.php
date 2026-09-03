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

require 'includes/Exception.php';
require 'includes/PHPMailer.php';
require 'includes/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if(isset($_REQUEST['id'])){
    $vendor_id = intval($_REQUEST['id']);

    $v_query = mysqli_query($conn, "SELECT * FROM vendor_table WHERE id = '$vendor_id' LIMIT 1");
    $vendor = mysqli_fetch_assoc($v_query);

    $sql = "UPDATE vendor_table SET `status` = 'Rejected' WHERE id='$vendor_id'";
    if(mysqli_query($conn, $sql)){
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
            $mail->Subject    = "Notice: Vendor Application Rejected";
            $mail->setFrom('noreply@pocketvest.com.ng', "$business_name");
            $mail->isHTML(true);
            $mail->addAddress($vendor_email);

            $mail->Body = "
            <html>
            <body style='font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px;'>
                <div style='max-width: 600px; background: #fff; margin: 0 auto; padding: 30px; border-radius: 8px;'>
                    <h2 style='color: #d9534f;'>Vendor Application Status Update</h2>
                    <p>Dear <b>" . htmlspecialchars($vendor_name) . "</b>,</p>
                    <p>We regret to inform you that your application for store <b>" . htmlspecialchars($store_name) . "</b> has been <b>REJECTED</b> after review.</p>
                    <p>If you have any questions regarding this decision, please feel free to reach out to our management team.</p>
                    <hr style='border: none; border-top: 1px solid #eee; margin-top: 20px;'>
                    <p style='font-size: 12px; color: #888;'>&copy; " . date('Y') . " $business_name. All rights reserved.</p>
                </div>
            </body>
            </html>";

            $mail->send();
        }

        echo "<script>alert('Vendor application successfully rejected.'); 
        window.location.href='vendors';
        </script>";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>
