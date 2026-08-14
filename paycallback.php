<?php
include('customer-session-check.php');
include('db_conn.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'includes/Exception.php';
require 'includes/PHPMailer.php';
require 'includes/SMTP.php';

if (!isset($_GET['reference'])) {
    die("No reference supplied");
}

$reference = $_GET['reference'];

// Verify transaction with Paystack
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL            => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => array(
        "Authorization: Bearer",
        "Content-Type: application/json"
    ),
));

$response = curl_exec($curl);
$err      = curl_error($curl);
curl_close($curl);

if ($err) {
    die("Error verifying transaction: " . $err);
}

$paymentData = json_decode($response, true);

if ($paymentData['status'] && $paymentData['data']['status'] == 'success') {

    // Pulling session info
    $date             = $_SESSION['date'];
    $formatted_date   = date("l jS F, Y", strtotime($date));
    $order_id         = mysqli_real_escape_string($conn, $_SESSION['order_id']);
    $customer_address = mysqli_real_escape_string($conn, $_SESSION['customer_address']);
    $customername     = mysqli_real_escape_string($conn, $_SESSION['customername']);
    $customer_email   = mysqli_real_escape_string($conn, $_SESSION['customer_email']);
    $customer_phone   = mysqli_real_escape_string($conn, $_SESSION['customer_phone']);
    $amount           = mysqli_real_escape_string($conn, $_SESSION['amount']);
    $state            = mysqli_real_escape_string($conn, $_SESSION['state']);
    $city             = mysqli_real_escape_string($conn, $_SESSION['city']);
    $ordernote        = mysqli_real_escape_string($conn, $_SESSION['ordernote']);
    $delivery         = mysqli_real_escape_string($conn, $_SESSION['delivery']);
    $paymentmethod    = mysqli_real_escape_string($conn, $_SESSION['paymentmethod']);
    $customer_uin     = mysqli_real_escape_string($conn, $_SESSION['customer_uin']);
    $invoicenumber    = mysqli_real_escape_string($conn, $_SESSION['invoicenumber']);
    $totalQty         = intval($_SESSION['totalQty']);
    $status           = "Paid";
    $year             = date("Y");

    // Check if this order was already processed
    $checkPayment = mysqli_query($conn, "SELECT * FROM invoicesales WHERE order_id = '$order_id' LIMIT 1");
    if (mysqli_num_rows($checkPayment) > 0) {
        echo "<script>alert('Payment already processed.'); window.location.href='index.php';</script>";
        exit;
    }

    // Verify customer exists
    $userQuery = mysqli_query($conn, "SELECT * FROM customertable WHERE customer_uin = '$customer_uin' LIMIT 1");
    if (mysqli_num_rows($userQuery) > 0) {

        // Update payment status on invoiceorder
        $updateUser = mysqli_query($conn, "UPDATE invoiceorder SET paymentstatus='$status' 
            WHERE invoicenumber='$invoicenumber' AND customer_uin='$customer_uin'");

        // Insert order into invoicesales using billing details from session
        $insertOrder = mysqli_query($conn, "INSERT INTO invoicesales
            (order_id, invoicenumber, `date`, amount, customer_uin, customername, customer_phone, customer_address, customer_email, paymentmethod, quantity, paymentstatus, ordernote, deliverymethod, order_status) 
            VALUES ('$order_id', '$invoicenumber', '$date', '$amount', '$customer_uin', '$customername', '$customer_phone', '$customer_address', '$customer_email', '$paymentmethod', '$totalQty', '$status', '$ordernote', '$delivery', 'Payment Confirmed')");

        if ($updateUser && $insertOrder) {

            // Deduct stock for each item in this invoice
            $stockResult = mysqli_query($conn, "SELECT * FROM invoiceorder WHERE invoicenumber='$invoicenumber' AND customer_uin='$customer_uin'");
            while ($stockRow = mysqli_fetch_assoc($stockResult)) {
                $quantity = (int)$stockRow['quantity'];
                $product_id = mysqli_real_escape_string($conn, $stockRow['product_id']);
                mysqli_query($conn, "UPDATE product_table SET quantity = GREATEST(0, quantity - $quantity) WHERE product_id='$product_id'");
            }

            // Send confirmation email
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = 'mail.pocketvest.com.ng';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'ademolaomomeji@pocketvest.com.ng';
                $mail->Password   = 'Omomejih08';
                $mail->SMTPSecure = 'ssl';
                $mail->Port       = 465;

                $mail->setFrom('ademolaomomeji@pocketvest.com.ng', 'DEE MART');
                $mail->addAddress($customer_email, $customername);

                $mail->isHTML(true);
                $mail->Subject = 'Order Confirmation - DEE MART';
                $mail->Body    = "
    <style>
        html, body { margin: 0 auto !important; padding: 0 !important; height: 100% !important; width: 100% !important; font-family: 'Roboto', sans-serif !important; font-size: 14px; margin-bottom: 10px; line-height: 24px; color: #4B0082; font-weight: 400; }
        * { -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; margin: 0; padding: 0; }
        table, td { mso-table-lspace: 0pt !important; mso-table-rspace: 0pt !important; }
        table { border-spacing: 0 !important; border-collapse: collapse !important; table-layout: fixed !important; margin: 0 auto !important; }
        a { text-decoration: none; }
        img { -ms-interpolation-mode: bicubic; }
    </style>

    <center style='width: 100%; background-color: #f5f6fa;'>
        <table width='100%' border='0' cellpadding='0' cellspacing='0' bgcolor='#f5f6fa'>
            <tr>
                <td style='padding: 40px 0;'>
                    <table style='width:100%;max-width:620px;margin:0 auto;'>
                        <tbody align='center'>
                            <a href='https://pocketvest.com.ng' target='_blank'>
                                <img style='height: 60px' src='https://pocketvest.com.ng/e-commerce/assets/images/logo.png' alt='DEE MART'>
                            </a>
                        </tbody>
                    </table>
                    <table style='width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;'>
                        <tbody align='left'>
                            <tr>
                                <td style='padding: 0 30px 20px;'>
                                    <p></p><br>
                                    <p style='margin-bottom: 10px;'>Dear <b>$customername,</b></p>
                                    <p style='margin-bottom: 10px;'>Your order has been received. Kindly wait while we process it.</p>
                                    <p style='margin-bottom: 10px;'>Your payment of <strong>&#8358;$amount</strong> was successfully received on <strong>$formatted_date</strong>.</p>
                                    <hr>
                                    <p style='margin-bottom: 10px;'>If you have any questions or need help, feel free to contact us. Thank you for shopping with us at <strong>DEE MART</strong>.</p>
                                    <hr>
                                    <p style='margin-bottom: 10px;'><em>Warm regards,</em><br><b>DEE MART.</b></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style='width:100%;max-width:620px;margin:0 auto;'>
                        <tbody>
                            <tr>
                                <td style='text-align: center; padding:25px 20px 0;'>
                                    <p style='font-size: 13px;'>Copyright &copy; $year <strong>DEE MART</strong>. All Rights Reserved.</p>
                                    <p style='padding-top: 15px; font-size: 12px;'>This email was sent to you as a registered member on <a style='color: #4B0082; text-decoration:none;' href='#'><strong>DEE MART</strong></a>.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </center>";

                $mail->send();
            } catch (Exception $e) {
                error_log("Mailer Error: " . $mail->ErrorInfo);
            }

            echo "<script>alert('Payment successful.'); window.location.href='order.php';</script>";
            exit;

        } else {
            echo "<script>alert('Payment was successful but failed to update records. Please contact support.'); window.location.href='index.php';</script>";
        }

    } else {
        echo "<script>alert('Payment successful but user not found. Please contact support.'); window.location.href='index.php';</script>";
    }

} else {
    echo "<script>alert('Payment failed or was cancelled.'); window.location.href='checkout.php';</script>";
}
?>