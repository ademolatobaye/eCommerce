<?php
session_start();
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

if(!isset($_SESSION["email"])){
  header("Location: staff-newotp");
  exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'includes/Exception.php';
require 'includes/PHPMailer.php';
require 'includes/SMTP.php';

$sql="SELECT * FROM stafftable WHERE email='$_SESSION[email]'";
$result=mysqli_query($conn, $sql);
$row=mysqli_fetch_array($result);
$email=$row['email'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title><?php echo $business_name; ?> - NEW PASSWORD</title>

    <meta name="description" content="Login and Register Form Html Template">
    <meta name="author" content="harnishdesign.net">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/icons/favicon.png">

    <!-- WebFont.js -->
    <script>
        WebFontConfig = {
            google: { families: ['Poppins:400,500,600,700'] }
        };
        ( function ( d ) {
            var wf = d.createElement( 'script' ), s = d.scripts[0];
            wf.src = '../assets/js/webfont.js';
            wf.async = true;
            s.parentNode.insertBefore( wf, s );
        } )( document );
    </script>

    <link rel="preload" href="../assets/vendor/fontawesome-free/webfonts/fa-regular-400.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="../assets/vendor/fontawesome-free/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="../assets/vendor/fontawesome-free/webfonts/fa-brands-400.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="../assets/fonts/wolmart.woff?png09e" as="font" type="font/woff" crossorigin="anonymous">

    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="../assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../assets/vendor/swiper/swiper-bundle.min.css">

    <!-- Plugin CSS -->
    <link rel="stylesheet" type="text/css" href="../assets/vendor/magnific-popup/magnific-popup.min.css">

    <!-- Default CSS -->
    <link rel="stylesheet" type="text/css" href="../assets/css/style.min.css">
</head>

<body>
    <div class="page-wrapper">

        <!-- Start of Main -->
        <main class="main login-page">
            <!-- Start of Page Header -->
            <div class="page-header">
                <div class="container">
                    <img src="../assets/images/logo.png" alt="logo" width="144" height="45" />
                </div>
            </div>
            <!-- End of Page Header -->

            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav mb-10 pb-1">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="../index">Home</a></li>
                        <li>Staff Reset Password</li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of PageContent -->
            <div class="page-content">
                <div class="container">
                    <div class="login-popup" style="margin: 0 auto; max-width: 500px; padding: 30px;">
                        <div class="tab tab-nav-boxed tab-nav-center tab-nav-underline">
                            <ul class="nav nav-tabs text-uppercase" role="tablist">
                                <li class="nav-item">
                                    <a href="#sign-up" class="nav-link active">Set New Password</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="sign-up">
                                    <p class="text-center mb-4">Set up your new password.</p>

                                    <form id="loginForm" method="post" onsubmit="return check()">
                                        <?php
                                        include("db_conn.php");
                                        date_default_timezone_set("Africa/Lagos");
                                        error_reporting(E_ALL);
                                        if(isset($_REQUEST["submit"])){
                                            $password1 = trim(addslashes($_REQUEST["password"]));
                                            $password = trim(addslashes($_REQUEST["passwordReg"]));

                                            $_SESSION["password"] = $password;

                                            // UPDATING NEWLY CREATED PASSWORD ON DATABASE.
                                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                                            $sql = "UPDATE stafftable SET `password`= '$hashed_password' WHERE email = '$email'";
                                            $result=mysqli_query($conn, $sql);
                                            if($result){
                                                $mail = new PHPMailer();
                                                $mail->isSMTP();
                                                $mail->Host       = "mail.pocketvest.com.ng";
                                                $mail->SMTPAuth   = true;
                                                $mail->SMTPSecure = "ssl";
                                                $mail->Port       = "465";
                                                $mail->Username   = "noreply@pocketvest.com.ng";
                                                $mail->Password   = "ecommerce@2026";
                                                $mail->Subject    = "PASSWORD CHANGED SUCCESSFULLY";
                                                $mail->setFrom('noreply@pocketvest.com.ng', "$business_name");
                                                $mail->isHTML(true);
                                                $mail->addAddress($email);

                                                $year = date("Y");
                                                $mail->Body = "<style>
                                                    html, body { margin: 0 auto !important; padding: 0 !important; height: 100% !important; width: 100% !important; font-family: 'Roboto', sans-serif !important; font-size: 14px; margin-bottom: 10px; line-height: 24px; color: #8094ae; font-weight: 400; }
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
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style='text-align: center; padding-bottom:25px'>
                                                                                <a href='#'><img style='height: 60px' src='https://ademolathedev.name.ng/e-commerce/assets/images/logo.png' alt='$business_name'></a>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <table style='width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;'>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style='padding: 30px 30px 15px 30px; text-align: center;'>
                                                                                <h2 style='font-size: 18px; color: #4B0082; font-weight: 600; margin: 0;'>Password Changed Successfully</h2>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style='padding: 0 30px 20px; text-align: center;'>
                                                                                <p style='margin-bottom: 10px;'>Hi,</p>
                                                                                <p style='margin-bottom: 10px;'>Your staff account password on $business_name has been successfully changed.</p>
                                                                                <p style='margin-bottom: 10px;'>If you did not perform this action, please contact support immediately.</p>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <table style='width:100%;max-width:620px;margin:0 auto;'>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style='text-align: center; padding:25px 20px 0;'>
                                                                                <p style='font-size: 13px;'>Copyright &copy; $year $business_name. All rights reserved.</p>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </center>";
                                                $mail->send();

                                                echo "<script>alert('Password successfully changed!');
                                                window.location.href='staff-login'</script>";
                                            }
                                        }
                                        ?>
                                        <div class="form-group mb-4">
                                            <label>Create New Password *</label>
                                            <input type="password" name="password" class="form-control" id="password" required placeholder="Enter new password" oninput="return check()">
                                        </div>

                                        <div class="form-group mb-0">
                                            <label>Confirm Password *</label>
                                            <input type="password" name="passwordReg" class="form-control" id="passwordReg" required placeholder="Confirm your password" oninput="return check()">
                                            <span id="error" class="d-block mt-2 font-weight-bold"></span>
                                        </div>

                                        <button type="submit" name="submit" class="btn btn-primary w-100 mt-4">Submit</button>
                                    </form>

                                    <script>
                                        // CHECKING IF PASSWORD MATCHES.
                                        function check(){
                                            let password = document.getElementById("password").value;
                                            let passwordReg = document.getElementById("passwordReg").value;

                                            if(passwordReg !== password){
                                                document.getElementById("error").textContent = `Password does not match!`;
                                                document.getElementById("error").style.color = `red`;
                                                return false;
                                            }else if(passwordReg == password){
                                                document.getElementById("error").textContent = `Password matches correctly!`;
                                                document.getElementById("error").style.color = `green`;
                                                return true;
                                            }
                                        }
                                    </script>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of PageContent -->
        </main>
        <!-- End of Main -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Start of Scroll Top -->
    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button"> <i class="w-icon-angle-up"></i> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70"> <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34" style="stroke-dasharray: 16.4198, 400;"></circle> </svg> </a>
    <!-- End of Scroll Top -->

    <?php include("../mobile-menu.php"); ?>

    <!-- Plugin JS File -->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="../assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="../assets/js/main.min.js"></script>
</body>
</html>