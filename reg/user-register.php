<?php
session_start();

// ini_set('display_errors', '1');
// 	require 'includes/PHPMailer.php';
// 	require 'includes/SMTP.php';
// 	require 'includes/Exception.php';
// //Define name spaces
// 	use PHPMailer\PHPMailer\PHPMailer;
// 	use PHPMailer\PHPMailer\SMTP;
// 	use PHPMailer\PHPMailer\Exception;

if(!isset($_SESSION["customer_email"])){
  header("Location: otp.php");
  exit();
}

 include("db_conn.php");
  $sql="SELECT * FROM customertable WHERE customer_email='$_SESSION[customer_email]'";
  $result=mysqli_query($conn, $sql);
  $row=mysqli_fetch_array($result);
  $customer_email=$row['customer_email'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title>DEE MART - USER REGISTER</title>

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
                        <li><a href="../index.php">Home</a></li>
                        <li>Create an Account</li>
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
                                    <a href="#sign-up" class="nav-link active">Create an Account</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="sign-up">
                                    <form id="registerForm" method="post" onsubmit="return check()">

                                        <?php
                                        include("db_conn.php");
                                        date_default_timezone_set("Africa/Lagos");
                                        $year =date("Y");
                                        error_reporting(E_ALL);
                                        if(isset($_REQUEST["submit"])){
                                            $fullname = mysqli_real_escape_string($conn, trim($_REQUEST["fullname"]));
                                            $phone = mysqli_real_escape_string($conn, trim($_REQUEST["phone"]));
                                            $address = mysqli_real_escape_string($conn, trim($_REQUEST["address"]));
                                            $password = mysqli_real_escape_string($conn, trim($_REQUEST["password"]));

                                            // UPDATING VALUES INTO THE DATABASE.
                                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                                            $sql = "UPDATE customertable SET fullname ='$fullname', phone ='$phone', `address` ='$address', `password`='$hashed_password'
                                             WHERE customer_email ='$customer_email'";
                                            mysqli_query($conn, $sql) or die(mysqli_error($conn));
                                            $num = mysqli_insert_id($conn);

                                            if(mysqli_affected_rows($conn)!= 1){
                                                $message = "Error inserting record.";
                                            } else {
                                                
// Create instance of PHPMailer
// 	$mail = new PHPMailer();
// //Set mailer to use smtp
// 	$mail->isSMTP();
// //Define smtp host
// 	$mail->Host = "mail.pocketvest.com.ng";
// //Enable smtp authentication
// 	$mail->SMTPAuth = true;
// //Set smtp encryption type (ssl/tls)
// 	$mail->SMTPSecure = "ssl";
// //Port to connect smtp
// 	$mail->Port = "465";
// //Set gmail username
// 	$mail->Username = "ademolaomomeji@pocketvest.com.ng";
// //Set gmail password
// 	$mail->Password = "Omomejih08";
// //Email subject
// 	$mail->Subject = "REGISTRATON COMPLETION";
// //Set sender email
// 	$mail->setFrom('ademolaomomeji@pocketvest.com.ng', 'DEE MART');
// //Enable HTML
// 	$mail->isHTML(true);
//                 $mail->Body    = "
//     <style>
//         html, body { margin: 0 auto !important; padding: 0 !important; height: 100% !important; width: 100% !important; font-family: 'Roboto', sans-serif !important; font-size: 14px; margin-bottom: 10px; line-height: 24px; color: #4B0082; font-weight: 400; }
//         * { -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; margin: 0; padding: 0; }
//         table, td { mso-table-lspace: 0pt !important; mso-table-rspace: 0pt !important; }
//         table { border-spacing: 0 !important; border-collapse: collapse !important; table-layout: fixed !important; margin: 0 auto !important; }
//         a { text-decoration: none; }
//         img { -ms-interpolation-mode: bicubic; }
//     </style>

//     <center style='width: 100%; background-color: #f5f6fa;'>
//         <table width='100%' border='0' cellpadding='0' cellspacing='0' bgcolor='#f5f6fa'>
//             <tr>
//                 <td style='padding: 40px 0;'>
//                     <table style='width:100%;max-width:620px;margin:0 auto;'>
//                         <tbody align='center'>
//                             <a href='https://pocketvest.com.ng' target='_blank'>
//                                 <img style='height: 60px' src='https://pocketvest.com.ng/e-commerce/assets/images/logo.png' alt='DEE MART'>
//                             </a>
//                         </tbody>
//                     </table>
//                     <table style='width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;'>
//                         <tbody align='left'>
//                             <tr>
//                                 <td style='padding: 0 30px 20px;'>
//                                     <p></p><br>
//                                     <p style='margin-bottom: 10px;'>Dear <b>$fullname,</b></p>
//                                     <p style='margin-bottom: 10px;'>Your account has been successfully registered. Kindly log in to your account to explore our products.</p>
                                    
//                                     <hr>
//                                     <p style='margin-bottom: 10px;'>If you have any questions or need help, feel free to contact us. Do well to enjoy <strong>DEE MART</strong>.</p>
//                                     <hr>
//                                     <p style='margin-bottom: 10px;'><em>Warm regards,</em><br><b>DEE MART.</b></p>
//                                 </td>
//                             </tr>
//                         </tbody>
//                     </table>
//                     <table style='width:100%;max-width:620px;margin:0 auto;'>
//                         <tbody>
//                             <tr>
//                                 <td style='text-align: center; padding:25px 20px 0;'>
//                                     <p style='font-size: 13px;'>Copyright &copy; $year <strong>DEE MART</strong>. All Rights Reserved.</p>
//                                     <p style='padding-top: 15px; font-size: 12px;'>This email was sent to you as a registered member on <a style='color: #4B0082; text-decoration:none;' href='#'><strong>DEE MART</strong></a>.</p>
//                                 </td>
//                             </tr>
//                         </tbody>
//                     </table>
//                 </td>
//             </tr>
//         </table>
//     </center>";
//     //Add recipient
// 	$mail->addAddress("$customer_email");
// //Finally send email
// 	if ($mail->send()) {
    echo "<script>alert('Dear $fullname, your account has been successfully created.'); window.location.href='user-login.php'</script>";
                                            }
// } else {
//     error_log("Mailer Error: " . $mail->ErrorInfo);
//     echo "<script>alert('Account created but confirmation email failed.'); window.location.href='user-login.php'</script>";
// }
            }
        // }
                                    
                                        ?>

                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email" class="form-control" id="emailAddress" readonly value="<?php echo $customer_email;?>" name="customer_email">
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
                                            <label>Home Address *</label>
                                            <input type="text" name="address" class="form-control" required placeholder="Enter your home address">
                                        </div>

                                        <div class="form-group" style="position: relative;">
                                            <label>Password *</label>
                                            <input type="password" name="password" class="form-control" id="password" required placeholder="Enter your password" oninput="return check()">
                                            <span onclick="togglePassword('password', 'eyeIcon1')" style="position:absolute; right:15px; top:75%; transform:translateY(-50%); cursor:pointer; color:#4B0082;">
									<svg id="eyeIcon1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
										<path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
										<path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
									</svg>
   									 </span>
                                        </div>

                                        <div class="form-group mb-0" style="position: relative;">
                                            <label>Confirm Password *</label>
                                            <input type="password" name="passwordreg" class="form-control" id="passwordReg" required placeholder="Confirm your password" oninput="return check()">
                                            <span onclick="togglePassword('passwordReg', 'eyeIcon2')" style="position:absolute; right:15px; top:75%; transform:translateY(-50%); cursor:pointer; color:#4B0082;">
									<svg id="eyeIcon2" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
										<path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
										<path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
									</svg>
								</span>
                                        </div>
                                        <span id="error" class="d-block mt-2 font-weight-bold"></span>

                                        <div class="form-checkbox d-flex align-items-center justify-content-between mt-4">
                                            <input type="checkbox" class="custom-checkbox" id="agree" name="agree" required>
                                            <label for="agree">I agree to the <a href="" class="text-primary">Terms</a> and <a href="" class="text-primary">Privacy</a>.</label>
                                        </div>

                                        <button type="submit" name="submit" class="btn btn-primary w-100 mt-4" onclick="return confirm('Are you sure to create this account?')">Create Account</button>

                                    </form>

                                    <script type="text/javascript">
                                        function check(){
									let password = document.getElementById("password").value;
									let passwordReg = document.getElementById("passwordReg").value;
									let error = document.getElementById("error");

									// Validation rules
									// let hasMinLength = password.length >= 8;
									// let hasLetter = /[a-zA-Z]/.test(password);
									// let hasNumber = /[0-9]/.test(password);
									// let hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);

									// if(!hasMinLength){
									// 	error.textContent = "Password must be at least 8 characters!";
									// 	error.style.color = "red";
									// 	return false;
									// } else if(!hasLetter){
									// 	error.textContent = "Password must include a letter!";
									// 	error.style.color = "red";
									// 	return false;	
									// } else if(!hasNumber){
									// 	error.textContent = "Password must include at least one number!";
									// 	error.style.color = "red";
									// 	return false;
									// } else if(!hasSpecial){
									// 	error.textContent = "Password must include at least one special character!";
									// 	error.style.color = "red";
									// 	return false;
									if(passwordReg && passwordReg !== password){
										error.textContent = "Password does not match!";
										error.style.color = "red";
										return false;
									} else if(passwordReg && passwordReg === password){
										error.textContent = "Password matches correctly!";
										error.style.color = "green";
										return true;
									// } else {
									// 	error.textContent = "Password is strong!";
									// 	error.style.color = "green";
									// 	return true;
									}
								}

								// Block form submission if password fails validation
								document.querySelector('form').addEventListener('submit', function(e){
									if(!check()){
										e.preventDefault();
									}
								});


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

                                    <p class="text-center mt-4">Already have an account? <a href="user-login.php" class="text-primary">Sign In</a></p>
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