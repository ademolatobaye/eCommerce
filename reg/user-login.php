<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
    require('db_conn.php');

    if (isset($_POST['identifier']) && isset($_POST['password'])) {
        $identifier = stripslashes($_POST['identifier']);
        $identifier = mysqli_real_escape_string($conn, $identifier);

        $password = stripslashes($_POST['password']);

        // Check if identifier matches either email or phone
        $query = "
        SELECT * FROM `customertable` 
        WHERE (`customer_email` = '$identifier' OR `phone` = '$identifier') 
        AND `status` = 'Verified' 
        LIMIT 1
    ";

        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $rows = mysqli_num_rows($result);

        if ($rows == 1) {
            $customer = mysqli_fetch_assoc($result);
            $stored_pass = $customer['password'];
            $pass_valid = false;

            if (password_verify($password, $stored_pass)) {
                $pass_valid = true;
            } else {
                // Check legacy MySQL PASSWORD() format
                $legacy_hash = '*' . strtoupper(sha1(sha1($password, true)));
                if ($stored_pass === $legacy_hash) {
                    $pass_valid = true;
                    // Automatically rehash legacy password to modern password_hash
                    $new_hash = password_hash($password, PASSWORD_DEFAULT);
                    $customer_id = $customer['customer_id'];
                    mysqli_query($conn, "UPDATE `customertable` SET `password` = '$new_hash' WHERE `customer_id` = '$customer_id'");
                }
            }

            if ($pass_valid) {

                // Set session variables
                $_SESSION['customer_email'] = $customer['customer_email'];
                $_SESSION['fullname'] = $customer['fullname'];
                $_SESSION['customer_uin'] = $customer['customer_uin'];

                // Restore existing invoicenumber if user has a pending cart
                $customer_uin = $customer['customer_uin'];
                $invoice_check = mysqli_query($conn, "SELECT invoicenumber FROM invoiceorder WHERE customer_uin = '$customer_uin' AND paymentstatus = 'Pending' LIMIT 1");
                if (mysqli_num_rows($invoice_check) > 0) {
                    $invoice_row = mysqli_fetch_assoc($invoice_check);
                    $_SESSION['invoicenumber'] = $invoice_row['invoicenumber'];
                }

                header("Location: ../index.php");
                exit();

            } else {
                echo "<script>alert('Invalid customer login credential.'); window.location.href='user-login.php';</script>";
            }
        } else {
            echo "<script>alert('Invalid customer login credential.'); window.location.href='user-login.php';</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title>DEE MART - CUSTOMER LOGIN</title>

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
                        <li>Sign In</li>
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
                                    <a href="#sign-in" class="nav-link active">Sign In</a>
                                </li>

                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="sign-in">

                                    <form method="post">
                                        <div class="form-group">
                                            <label>Email Address or Phone Number *</label>
                                            <input type="text" class="form-control" name="identifier" id="identifier" required placeholder="Enter your email address or phone number">
                                        </div>

                                        <div class="form-group mb-0">
                                            <label>Password *</label>
                                            <input type="password" class="form-control" name="password" id="password" required placeholder="Enter your password">
                                            <span onclick="togglePassword('password', 'eyeIcon2')" style="position:absolute; right:15px; top:52%; transform:translateY(-50%); cursor:pointer; color:#4B0082;">
									        <svg id="eyeIcon2" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
										    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
										<path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
									</svg>
								    </span>
                                        </div>

                                        <div class="form-checkbox d-flex align-items-center justify-content-between">
                                            <input type="checkbox" class="custom-checkbox" id="remember" name="remember">
                                            <label for="remember">Keep me signed in</label>
                                            <a href="user-forgot-password.php">Forgot Password?</a>
                                        </div>

                                        <button type="submit" name="submit" class="btn btn-primary w-100">Sign In</button>
                                         <a href="index.php">Don't have an account? Register Here</a>
                                    </form>

                                    <script>
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