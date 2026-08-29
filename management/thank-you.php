<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title>REGISTRATION SUCCESSFUL</title>
    <meta name="description" content="Registration Successful">
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
                        <li><a href="../index.php">Home</a></li>
                        <li>Registration Successful</li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of PageContent -->
            <div class="page-content">
                <div class="container">
                    <div class="login-popup text-center" style="margin: 0 auto; max-width: 700px; padding: 50px 30px; background: #fff; border-radius: 8px; box-shadow: 0 5px 25px rgba(0,0,0,0.06); text-align: center !important;">
                        <div class="mb-4 text-center" style="text-align: center !important;">
                            <i class="fas fa-check-circle text-primary" style="font-size: 72px; display: inline-block;"></i>
                        </div>
                        
                        <h2 class="title text-center text-capitalize mb-2" style="font-size: 28px; font-weight: 700; text-align: center !important; display: block; width: 100%; margin-left: auto; margin-right: auto;">Registration Successful!</h2>
                        
                        <p class="text-secondary mb-6 text-center" style="font-size: 16px; color: #666; line-height: 1.6; text-align: center !important;">
                            Thank you for completing your registration.<br>
                            Please check your email address for account confirmation and further instructions.
                        </p>

                        <div class="pt-2 text-center" style="text-align: center !important;">
                            <a href="../index.php" class="btn btn-primary btn-rounded" style="padding: 12px 35px; font-size: 15px; font-weight: 600; text-transform: uppercase; display: inline-block;">
                                Go Back Home <i class="w-icon-long-arrow-right ml-2"></i>
                            </a>
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