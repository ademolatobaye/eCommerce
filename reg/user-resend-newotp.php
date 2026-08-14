<?php
session_start();
include("db_conn.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'includes/Exception.php';
require 'includes/PHPMailer.php';
require 'includes/SMTP.php';

if(!isset($_SESSION['customer_email'])){
    header("Location: user-otp.php");
    exit();
}

$customer_email = $_SESSION['customer_email'];
$otp = rand(1000,9999);
$_SESSION['otp'] = $otp;
$_SESSION['otp_time'] = time();
$year = date("Y");

$sql = "UPDATE customertable SET otp='$otp', `status`='Pending' WHERE customer_email='$customer_email'";
mysqli_query($conn, $sql);

// Create instance of PHPMailer
	$mail = new PHPMailer();
//Set mailer to use smtp
	$mail->isSMTP();
//Define smtp host
	$mail->Host = "mail.pocketvest.com.ng";
//Enable smtp authentication
	$mail->SMTPAuth = true;
//Set smtp encryption type (ssl/tls)
	$mail->SMTPSecure = "ssl";
//Port to connect smtp
	$mail->Port = "465";
//Set gmail username
	$mail->Username = "ademolaomomeji@pocketvest.com.ng";
//Set gmail password
	$mail->Password = "Omomejih08";
//Email subject
	$mail->Subject = "NEW OTP";
//Set sender email
	$mail->setFrom('ademolaomomeji@pocketvest.com.ng', 'DEE MART');
//Enable HTML
	$mail->isHTML(true);
//Attachment


//Email body
	$mail->Body = "<style>
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            font-family: 'Roboto', sans-serif !important;
            font-size: 14px;
            margin-bottom: 10px;
            line-height: 24px;
            color: #8094ae;
            font-weight: 400;
        }
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        table table table {
            table-layout: auto;
        }
        a {
            text-decoration: none;
        }
        img {
            -ms-interpolation-mode:bicubic;
        }
    </style>

    <center style='width: 100%; background-color: #f5f6fa;'>
        <table width='100%' border='0' cellpadding='0' cellspacing='0' bgcolor='#f5f6fa'>
            <tr>
                <td style='padding: 40px 0;'>
                    <table style='width:100%;max-width:620px;margin:0 auto;'>
                        <tbody>
                            <tr>
                                <td style='text-align: center; padding-bottom:25px'>
                                    <a href='#'><img style='height: 60px' src='https://pocketvest.com.ng/e-commerce/assets/images/logo.png' alt='DEE MART'></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style='width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;'>
                        <tbody>
                            <tr>
                                <td style='padding: 30px 30px 15px 30px; text-align: center;'>
                                    <h2 style='font-size: 18px; color: #4B0082; font-weight: 600; margin: 0;'>One Time Password</h2>
                                </td>
                            </tr>
                            <tr>
                                <td style='padding: 0 30px 20px; text-align: center;'>
                                    <p style='margin-bottom: 10px;'>Hi,</p>
                                    <p style='margin-bottom: 10px;'>Your NEW OTP to reset your password on DEE MART is:</p>
                                    <h1 style='font-size: 35px; color: #4B0082; font-weight: 600; margin: 0;'> $otp</h1>
                                    
                                    <h1 style='font-size: 35px; color: #4B0082; font-weight: 600; margin: 0;'> Your OTP expires in 5 minutes!</h1>
                                    
                                
                                </td>
                            </tr>
                           
                           
                        </tbody>
                    </table>
                    <table style='width:100%;max-width:620px;margin:0 auto;'>
                        <tbody>
                            <tr>
                                <td style='text-align: center; padding:25px 20px 0;'>
                                    <p style='font-size: 13px;'>Copyright © $year DEE MART. All rights reserved. <br> </p>
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </center>";
//Add recipient
	$mail->addAddress("$customer_email");
//Finally send email
	if ($mail->send()) {
    header("Location: user-newotp.php");
    exit();
}
?>