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
  header("Location: staff-otp");
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

    <title><?php echo $business_name; ?> - STAFF REGISTER</title>

    <meta name="description" content="">
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
                        <li>Staff Account Registration</li>
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
                                    <a href="#sign-up" class="nav-link active">Staff Account</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="sign-up">
                                    <form id="registerForm" method="post" onsubmit="return check()" enctype="multipart/form-data">

                                        <?php
                                        include("db_conn.php");
                                        date_default_timezone_set("Africa/Lagos");
                                        error_reporting(E_ALL);
                                        if(isset($_REQUEST["submit"])){
                                            $fullname =trim(addslashes($_REQUEST["fullname"]));
                                            $phone =trim(addslashes($_REQUEST["phone"]));
                                            $password =trim(addslashes($_REQUEST["password"]));
                                            $role =$_REQUEST["role"];

                                            // PHOTO UPLOAD
                                            $img = $_POST['image'];
                                            $folderPath = "../dashboard/assets/photo/";

                                            $image_parts = explode(";base64,", $img);
                                            $image_type_aux = explode("image/", $image_parts[0]);
                                            $image_type = $image_type_aux[1];

                                            $image_base64 = base64_decode($image_parts[1]);
                                            $fileName = uniqid() . '.png';

                                            $file = $folderPath . $fileName;
                                            file_put_contents($file, $image_base64);

                                            // UPDATING VALUES INTO THE DATABASE.
                                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                                            $sql="UPDATE stafftable SET fullname ='$fullname', phone ='$phone', `password`= '$hashed_password', `role`='$role', photo='$fileName' WHERE email ='$email' OR uin ='$UIN' ";
                                            mysqli_query($conn, $sql) or die(mysqli_error($conn));
                                            $num = mysqli_insert_id($conn);

                                            if(mysqli_affected_rows($conn)!= 1){
                                                $message = "Error inserting record into database.";
                                            } else {
                                                // Send registration completion email via SMTP
                                                $mail = new PHPMailer();
                                                $mail->isSMTP();
                                                $mail->Host       = "mail.pocketvest.com.ng";
                                                $mail->SMTPAuth   = true;
                                                $mail->SMTPSecure = "ssl";
                                                $mail->Port       = "465";
                                                $mail->Username   = "noreply@pocketvest.com.ng";
                                                $mail->Password   = "ecommerce@2026";
                                                $mail->Subject    = "STAFF REGISTRATION COMPLETION";
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
                                                                                <h2 style='font-size: 18px; color: #4B0082; font-weight: 600; margin: 0;'>Staff Registration Successful</h2>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style='padding: 0 30px 20px; text-align: center;'>
                                                                                <p style='margin-bottom: 10px;'>Dear <b>$fullname</b>,</p>
                                                                                <p style='margin-bottom: 10px;'>Your registration as a staff member on $business_name has been successfully completed!</p>
                                                                                <p style='margin-bottom: 10px;'>You can now sign in using your registered email address.</p>
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

                                                echo "<script>alert('Dear $fullname, your staff account has been successfully created.');
                                                window.location.href='staff-login'</script>";
                                            }
                                        }
                                        ?>

                                        <div class="form-group mb-4">
                                            <label for="webcam">Photo Capture *</label>
                                            <div id="my_camera" class="mb-3"></div>
                                            <input class="btn btn-primary btn-block mb-3" type="button" value="Take Snapshot" onClick="take_snapshot()">
                                            <input type="hidden" name="image" class="image-tag">
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="webcam">Photo Result</label>
                                            <div id="results" class="border p-2">Your captured image will appear here.</div>
                                        </div>

                                        <script src="js/webcam.min.js"></script>

                                        <script language="JavaScript">
                                            Webcam.set({
                                                width: 490,
                                                height: 390,
                                                image_format: 'jpeg',
                                                jpeg_quality: 100
                                            });

                                            Webcam.attach('#my_camera');

                                            function take_snapshot() {
                                                Webcam.snap(function(data_uri) {
                                                    $(".image-tag").val(data_uri);
                                                    document.getElementById('results').innerHTML = '<img src="' + data_uri + '" style="max-width: 100%; height: auto;"/>';
                                                });
                                            }
                                        </script>

                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email" class="form-control" id="emailAddress" readonly value="<?php echo $email;?>" name="email">
                                        </div>

                                        <div class="form-group">
                                            <label>Fullname *</label>
                                            <input type="text" name="fullname" class="form-control" required placeholder="Enter your fullname">
                                        </div>

                                        <div class="form-group">
                                            <label>Phone Number *</label>
                                            <input type="text" name="phone" class="form-control" required placeholder="Enter your phone number">
                                        </div>

                                        <div class="form-group">
                                            <label>Designation *</label>
                                                <select name="role" class="form-control" required>
                                                    <option value="">Select Role</option>
                                                    <option value="Super Admin">Super Admin</option>
                                                </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Password *</label>
                                            <div style="position: relative;">
                                                <input type="password" name="password" class="form-control" id="password" required placeholder="Enter your password" oninput="return check()" style="padding-right: 45px;">
                                                <span onclick="togglePassword('password', 'eyeIcon1')" style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer; color:#4B0082; display:inline-flex; align-items:center; z-index:10;">
                                                    <svg id="eyeIcon1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="form-group mb-0">
                                            <label>Confirm Password *</label>
                                            <div style="position: relative;">
                                                <input type="password" name="passwordreg" class="form-control" id="passwordReg" required placeholder="Confirm your password" oninput="return check()" style="padding-right: 45px;">
                                                <span onclick="togglePassword('passwordReg', 'eyeIcon2')" style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer; color:#4B0082; display:inline-flex; align-items:center; z-index:10;">
                                                    <svg id="eyeIcon2" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                        <span id="error" class="d-block mt-2 font-weight-bold"></span>

                                        <div class="form-checkbox d-flex align-items-center justify-content-between mt-4">
                                            <input type="checkbox" class="custom-checkbox" id="agree" name="agree" required>
                                            <label for="agree">I agree to the <a href="#" class="text-primary">Terms</a> and <a href="#" class="text-primary">Privacy</a>.</label>
                                        </div>

                                        <button type="submit" name="submit" class="btn btn-primary w-100 mt-4" onclick="return confirm('Are you sure to create this account?')">Create Account</button>

                                    </form>

                                    <script type="text/javascript">
                                        function check(){
                                            let password = document.getElementById("password").value;
                                            let passwordReg = document.getElementById("passwordReg").value;

                                            if(passwordReg !== password){
                                                document.getElementById("error").textContent = "Password does not match!";
                                                document.getElementById("error").style.color = "red";
                                                return false;
                                            }
                                            else if(passwordReg == password){
                                                document.getElementById("error").textContent = "Password matches correctly!";
                                                document.getElementById("error").style.color = "green";
                                                return true;
                                            }
                                        }

                                        function togglePassword(inputId, iconId) {
								const input = document.getElementById(inputId);
								const icon = document.getElementById(iconId);
								const isHidden = input.type === 'password';

								input.type = isHidden ? 'text' : 'password';

								// Swap between eye and eye-slash icon
								icon.innerHTML = isHidden
									? `<path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
										<path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
										<path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709z"/>
										<path fill-rule="evenodd" d="M13.646 14.354l-12-12 .708-.708 12 12-.708.708z"/>`
									: `<path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
										<path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>`;
								}
                                    </script>

                                    <p class="text-center mt-4">Already have an account? <a href="staff-login" class="text-primary">Sign In</a></p>
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

    <?php 
    include("../mobile-menu.php");
     ?>

    <!-- Plugin JS File -->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="../assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="../assets/js/main.min.js"></script>
</body>
</html>