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