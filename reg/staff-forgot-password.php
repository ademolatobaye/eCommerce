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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'includes/Exception.php';
require 'includes/PHPMailer.php';
require 'includes/SMTP.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title><?php echo $business_name; ?> - FORGOT PASSWORD</title>

    <meta name="description" content="L">
    <meta name="author" content="">

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
                        <li>Staff Forgot Password</li>
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
                                    <a href="#sign-in" class="nav-link active">Forgot Password?</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="sign-in">
                                    <p class="text-muted text-center mb-4">Enter the email address associated with your account.</p>

                                    <form id="forgotForm" method="post">
                                        <?php
                                        include("db_conn.php");
                                        date_default_timezone_set("Africa/Lagos");
                                        $OTP= rand(1000,9999);
                                        $_SESSION['otp_time'] = time();
                                        error_reporting(E_ALL);
                                        if(isset($_REQUEST["submit"])){
                                            $email = trim(addslashes($_REQUEST["email"]));
                                            $_SESSION["email"] = $email;

                                            // CHECKING IF EMAIL EXISTS ON DATABASE
                                            $check = mysqli_query($conn, "SELECT * FROM stafftable WHERE otp='$OTP' OR email='$email'");
                                            $checkrows = mysqli_num_rows($check);

                                            if($checkrows < 1){
                                                echo "<script>alert('Email does not exist.')</script>";
                                            } else {
                                                // UPDATING NEW OTP ON THE DATABASE
                                                $sql="UPDATE stafftable SET otp='$OTP', `status`= 'Pending' WHERE email='$email'";
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
                                                    $mail->Subject    = "RESET PASSWORD OTP";
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
                                                                                    <h2 style='font-size: 18px; color: #4B0082; font-weight: 600; margin: 0;'>Reset Password OTP</h2>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style='padding: 0 30px 20px; text-align: center;'>
                                                                                    <p style='margin-bottom: 10px;'>Hi,</p>
                                                                                    <p style='margin-bottom: 10px;'>Your OTP to reset your staff password on $business_name is:</p>
                                                                                    <h1 style='font-size: 35px; color: #4B0082; font-weight: 600; margin: 0;'>$OTP</h1>
                                                                                    <h3 style='font-size: 16px; color: #4B0082; font-weight: 600; margin: 10px 0 0;'>Your OTP expires in 5 minutes!</h3>
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

                                                    if ($mail->send()) {
                                                        echo "<script>alert('An OTP has been sent to $email. Kindly check your email to reset your password.'); window.location.href='staff-newotp';</script>";
                                                    } else {
                                                        echo "<script>alert('Failed to send OTP email. Please try again.');</script>";
                                                    }
                                                }
                                            } 
                                        }
                                        ?>
                                        <div class="form-group mb-4">
                                            <label>Email Address *</label>
                                            <input type="email" class="form-control" name="email" id="emailAddress" required placeholder="Enter your email address">
                                        </div>

                                        <input type="hidden" class="form-control" required value="<?php echo $OTP;?>" name="otp">

                                        <button type="submit" name="submit" class="btn btn-primary w-100 mt-4">Continue</button>
                                    </form>

                                    <p class="text-center mt-4">Return to <a href="staff-login" class="text-primary">Sign In</a></p>
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