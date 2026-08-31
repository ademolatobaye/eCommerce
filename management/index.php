<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title>MANAGEMENT REGISTRATION</title>
    <meta name="description" content="DEE MART Management Registration Prerequisites">
    <meta name="author" content="DEE MART">

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
            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav mb-10 pb-1">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="../index">Home</a></li>
                        <li>Management Registration Prerequisites</li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of PageContent -->
            <div class="page-content">
                <div class="container">
                    <div class="login-popup" style="margin: 0 auto; max-width: 800px; padding: 40px; background: #fff; border-radius: 8px; box-shadow: 0 5px 25px rgba(0,0,0,0.06);">
                        <div class="tab tab-nav-boxed tab-nav-center tab-nav-underline">
                            <ul class="nav nav-tabs text-uppercase" role="tablist">
                                <li class="nav-item">
                                    <a href="#prerequisites" class="nav-link active" style="font-size: 20px; font-weight: 700; color: #333;">Registration Prerequisites</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="prerequisites">
                                    <div class="alert alert-icon alert-inline mb-6" style="background-color: #f8f9fa; border-left: 4px solid #336699; padding: 15px 20px;">
                                        <i class="w-icon-exclamation-circle" style="color: #336699; font-size: 20px; margin-right: 10px;"></i>
                                        <span style="font-size: 15px; color: #444;">Kindly take note of the following requirements before proceeding to register your account.</span>
                                    </div>

                                    <div class="prerequisites-list my-4" style="font-size: 15px; line-height: 1.8; color: #555;">
                                        <ul class="list-style-none pl-0">
                                            <li class="mb-3 d-flex align-items-center">
                                                <i class="w-icon-check text-primary mr-3" style="font-size: 18px; font-weight: bold;"></i>
                                                <span>You must have a <strong>Valid Email Address</strong>.</span>
                                            </li>
                                            <li class="mb-3 d-flex align-items-center">
                                                <i class="w-icon-check text-primary mr-3" style="font-size: 18px; font-weight: bold;"></i>
                                                <span>You must have a <strong>Valid Phone Number</strong>.</span>
                                            </li>
                                            <li class="mb-3 d-flex align-items-center">
                                                <i class="w-icon-check text-primary mr-3" style="font-size: 18px; font-weight: bold;"></i>
                                                <span>Provide your full <strong>Business Name</strong> (registration with CAC is optional).</span>
                                            </li>
                                            <li class="mb-3 d-flex align-items-center">
                                                <i class="w-icon-check text-primary mr-3" style="font-size: 18px; font-weight: bold;"></i>
                                                <span>Provide your <strong>Business Address, Country, State, and City</strong>.</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="text-center mt-6 pt-4 border-top">
                                        <p class="mb-4" style="font-size: 14px; color: #777;">
                                            Signed,<br>
                                            <strong style="color: #333; font-size: 16px;">THEADEMOLADEV</strong>
                                        </p>

                                        <a href="register" class="btn btn-primary w-100 btn-rounded" style="padding: 14px; font-size: 16px; font-weight: 600; text-transform: uppercase; display: inline-block;">
                                            Sign Up Now <i class="w-icon-long-arrow-right ml-2"></i>
                                        </a>
                                    </div>

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
    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button"> 
        <i class="w-icon-angle-up"></i> 
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70"> 
            <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34" style="stroke-dasharray: 16.4198, 400;"></circle> 
        </svg> 
    </a>
    <!-- End of Scroll Top -->

    <?php include("../mobile-menu.php"); ?>

    <!-- Plugin JS File -->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="../assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="../assets/js/main.min.js"></script>
</body>
</html>