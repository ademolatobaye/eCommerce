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

if(!isset($_SESSION['email'])){
  header("Location: staff-index");
  exit();
}

$sql="SELECT * FROM stafftable WHERE email='$_SESSION[email]'";
$result=mysqli_query($conn, $sql);
$row=mysqli_fetch_array($result);
$dbotp=$row['otp'];
$email=$row['email'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title><?php echo $business_name; ?> - STAFF OTP</title>

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
                        <li>Staff OTP Validation</li>
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
                                    <a href="#sign-up" class="nav-link active">Validate OTP</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="sign-up">
                                    <p class="text-center"><img class="img-fluid" src="images/otp-icon.png" alt="verification"></p>
                                    <p class="text-muted text-center mb-4">Please enter the OTP (one time password) to verify your account. A code has been sent to <span class="text-dark text-4"><?php echo $email;?></span></p>
                                    <p class="text-dark text-center fw-600 mb-1">Enter 4 digit code</p>
                                    <?php 
                                        $elapsed = isset($_SESSION['otp_time']) ? time() - $_SESSION['otp_time'] : 0;
                                        $remaining = max(0, 300 - $elapsed);
                                    ?>
                                    <p class="text-danger text-center fw-600 mb-3"><i class="w-icon-clock"></i> OTP expires in <span id="countdown"><?php echo $remaining; ?></span> seconds</p>

                                    <form id="otp-screen" method="post">
                                        <?php 
                                        include('db_conn.php');
                                        if(isset($_REQUEST["verify"])){
                                            if (isset($_SESSION['otp_time']) && (time() - $_SESSION['otp_time']) > 300) {
                                                echo "<script>alert('OTP has expired after 5 minutes! Please request a new one.'); window.location.href='staff-otp';</script>";
                                                exit();
                                            }
                                            $n1=$_REQUEST["n1"];
                                            $n2=$_REQUEST["n2"];
                                            $n3=$_REQUEST["n3"];
                                            $n4=$_REQUEST["n4"];
                                            $newotp=$n1.$n2.$n3.$n4;

                                            if($dbotp!=$newotp){
                                                echo "<script>alert('Invalid OTP!')</script>";
                                            } else {
                                                // UPDATING OTP STATUS FROM PENDING TO VERIFIED AND REDIRECTING TO A NEW PAGE.
                                                $sql="UPDATE stafftable SET `status`='Verified' WHERE email='$email'";
                                                $result=mysqli_query($conn, $sql);
                                                if($result){
                                                    echo "<script>alert('Email successfully verified!');
                                                    window.location.href='staff-register'</script>";
                                                }
                                            }
                                        }
                                        ?>
                                        <div class="row g-3">
                                            <div class="col-3">
                                                <input type="text" class="form-control text-center py-2" maxlength="1" required autocomplete="on" name="n1">
                                            </div>

                                            <div class="col-3">
                                                <input type="text" class="form-control text-center py-2" maxlength="1" required autocomplete="on" name="n2">
                                            </div>
                                            
                                            <div class="col-3">
                                                <input type="text" class="form-control text-center py-2" maxlength="1" required autocomplete="on" name="n3">
                                            </div>
                                            
                                            <div class="col-3">
                                                <input type="text" class="form-control text-center py-2" maxlength="1" required autocomplete="on" name="n4">
                                            </div>
                                        </div>

                                        <button type="submit" name="verify" class="btn btn-primary w-100 mt-4">Verify</button>
                                    </form>

                                    <script>
                                        // --- Resend cooldown timer ---
                                        const resendLink = document.getElementById('resend-link');
                                        const resendTimerEl = document.getElementById('resend-timer');
                                        const resendCountEl = document.getElementById('resend-countdown');

                                        const otpSentAt = <?php echo isset($_SESSION['otp_time']) ? $_SESSION['otp_time'] : time(); ?>;
                                        const nowSec = Math.floor(Date.now() / 1000);
                                        let resendSec = Math.max(0, 60 - (nowSec - otpSentAt));

                                        if (resendSec === 0) {
                                            if (resendLink) {
                                                resendLink.style.pointerEvents = 'auto';
                                                resendLink.style.opacity = '1';
                                            }
                                            if (resendTimerEl) resendTimerEl.style.display = 'none';
                                        } else {
                                            if (resendCountEl) resendCountEl.textContent = resendSec;
                                            const resendInterval = setInterval(function() {
                                                resendSec--;
                                                if (resendCountEl) resendCountEl.textContent = resendSec;
                                                if (resendSec <= 0) {
                                                    clearInterval(resendInterval);
                                                    if (resendLink) {
                                                        resendLink.style.pointerEvents = 'auto';
                                                        resendLink.style.opacity = '1';
                                                    }
                                                    if (resendTimerEl) resendTimerEl.style.display = 'none';
                                                }
                                            }, 1000);
                                        }

                                        // --- OTP expiry countdown ---
                                        let remaining = <?php echo $remaining; ?>;
                                        const countdownEl = document.getElementById('countdown');
                                        if (remaining > 0) {
                                            const interval = setInterval(() => {
                                                remaining--;
                                                if (remaining <= 0) {
                                                    clearInterval(interval);
                                                    countdownEl.textContent = "0";
                                                    alert('OTP has expired after 5 minutes! Please request a new one.');
                                                    window.location.href = 'staff-otp';
                                                } else {
                                                    countdownEl.textContent = remaining;
                                                }
                                            }, 1000);
                                        }

                                        // Auto-focus OTP inputs
                                        const otpInputs = document.querySelectorAll('#otp-screen input[type="text"][maxlength="1"]');
                                        otpInputs.forEach((input, index) => {
                                            input.addEventListener('input', function() {
                                                if (this.value.length === this.maxLength && index < otpInputs.length - 1) {
                                                    otpInputs[index + 1].focus();
                                                }
                                            });
                                            input.addEventListener('keydown', function(e) {
                                                if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                                                    otpInputs[index - 1].focus();
                                                }
                                            });
                                        });
                                    </script>

                                    <p class="text-center mt-4">Not received your code? 
                                        <a href="staff-resendotp" class="text-primary" id="resend-link" style="pointer-events: none; opacity: 0.4;">Resend code</a>
                                        <span id="resend-timer" class="text-muted"> (available in <span id="resend-countdown">60</span>s)</span>
                                    </p>
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