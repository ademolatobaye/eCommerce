<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>DEE MART - VENDOR REGISTER</title>
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
    <link rel="preload" href="../assets/fonts/wolmart.woff?png09e" as="font" type="font/woff" crossorigin="anonymous">

    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="../assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.min.css">
</head>

<body>
    <div class="page-wrapper">

        <!-- Start of Main -->
        <main class="main login-page">
            <!-- Start of Page Header -->
            <div class="page-header">
                <div class="container text-center">
                    <a href="../index.php">
                        <img src="../assets/images/logo.png" alt="DEE MART Logo" width="144" height="45" />
                    </a>
                </div>
            </div>
            <!-- End of Page Header -->

            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav mb-10 pb-1">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="../index.php">Home</a></li>
                        <li>Vendor Account Registration</li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of PageContent -->
            <div class="page-content mb-10">
                <div class="container">
                    <div class="login-popup" style="margin: 0 auto; max-width: 600px; padding: 35px; background: #fff; border-radius: 8px; box-shadow: 0 5px 25px rgba(0,0,0,0.08);">
                        <div class="tab tab-nav-boxed tab-nav-center tab-nav-underline">
                            <ul class="nav nav-tabs text-uppercase" role="tablist">
                                <li class="nav-item">
                                    <a href="#vendor-register" class="nav-link active">Vendor Store Registration</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="vendor-register">

                                    <?php if (!empty($message)): ?>
                                        <div class="alert alert-<?php echo $messageType === 'danger' ? 'warning' : $messageType; ?> mb-4 p-3" style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; border-radius: 4px;">
                                            <?php echo $message; ?>
                                        </div>
                                    <?php endif; ?>

                                    <form method="post" enctype="multipart/form-data">

                                    <?php
                                    include("db_conn.php");
                                    date_default_timezone_set("Africa/Lagos");
                                    $date = date("Y-m-d");
                                    $year = date("Y");
                                    error_reporting(E_ALL);

                                    $message = "";
                                    $messageType = "";

                                    if (isset($_POST["register_vendor"])) {
                                        $store_name   = mysqli_real_escape_string($conn, trim($_POST["store_name"]));
                                        $vendor_name  = mysqli_real_escape_string($conn, trim($_POST["vendor_name"]));
                                        $vendor_email = mysqli_real_escape_string($conn, trim($_POST["vendor_email"]));
                                        $vendor_phone = mysqli_real_escape_string($conn, trim($_POST["vendor_phone"]));
                                        $address      = mysqli_real_escape_string($conn, trim($_POST["address"]));
                                        $description  = mysqli_real_escape_string($conn, trim($_POST["description"]));
                                        $raw_pass     = trim($_POST["password"]);
                                        $confirm_pass = trim($_POST["confirm_password"]);

                                        // Check for duplicate email
                                        $check = mysqli_query($conn, "SELECT * FROM vendor_table WHERE vendor_email = '$vendor_email'
                                         OR vendor_phone = '$vendor_phone'");
                                         $checkrows = mysqli_num_rows($check);

                                        if ($checkrows > 0) {
                                            echo "<script>alert('Invalid email.')</script>";
                                        } else if ($raw_pass !== $confirm_pass) {
                                            $message = "Passwords do not match!";
                                            $messageType = "danger";
                                        } else if (!filter_var($vendor_email, FILTER_VALIDATE_EMAIL)) {
                                            $message = "Invalid email address.";
                                            $messageType = "danger";
                                        } else {
                                            $hashed_password = password_hash($raw_pass, PASSWORD_DEFAULT);

                                            // Generate UIN and Store Slug
                                            $prefix = "VNDR";
                                            $random_part = strtoupper(substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6));
                                            $vendor_uin = $prefix . $random_part;
                                            $store_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $store_name)));

                                            // File uploads: logo & banner
                                            $uploadDir = "vendorupload/";
                                            if (!file_exists($uploadDir)) {
                                                mkdir($uploadDir, 0777, true);
                                            }

                                            $logo_filename = "";
                                            if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
                                                $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
                                                $logo_filename = "logo_" . $vendor_uin . "_" . time() . "." . $ext;
                                                move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . $logo_filename);
                                            }

                                            $banner_filename = "";
                                            if (isset($_FILES['banner']) && $_FILES['banner']['error'] == 0) {
                                                $ext = pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);
                                                $banner_filename = "banner_" . $vendor_uin . "_" . time() . "." . $ext;
                                                move_uploaded_file($_FILES['banner']['tmp_name'], $uploadDir . $banner_filename);
                                            }

                                            $status = "Pending";

                                            $sql = "INSERT INTO vendor_table(vendor_uin, store_name, store_slug, vendor_name, vendor_email, vendor_phone, `password`, store_address, `date`, `status`, logo, banner, `description`)
                                                    VALUES ('$vendor_uin', '$store_name', '$store_slug', '$vendor_name', '$vendor_email', '$vendor_phone', '$hashed_password', '$address', '$date', '$status', '$logo_filename', '$banner_filename', '$description')";

                                            if (mysqli_query($conn, $sql)) {
                                                echo "<script>
                                                    alert('Registration successful! Your vendor account is currently PENDING approval. The management will review your application, and you will receive an email once approved.');
                                                    window.location.href = 'login.php';
                                                </script>";
                                                exit();
                                            } else {
                                                $message = "Error registering vendor account: " . mysqli_error($conn);
                                                $messageType = "danger";
                                            }
                                        }
                                    }
                                    ?>


                                        <div class="row gutter-sm">
                                            <div class="col-md-6 form-group">
                                                <label>Store / Business Name *</label>
                                                <input type="text" class="form-control" name="store_name" required placeholder="e.g. Apex Electronics">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Full Name (Owner) *</label>
                                                <input type="text" class="form-control" name="vendor_name" required placeholder="e.g. Femi Atiba">
                                            </div>
                                        </div>

                                        <div class="row gutter-sm">
                                            <div class="col-md-6 form-group">
                                                <label>Email Address *</label>
                                                <input type="email" class="form-control" name="vendor_email" required placeholder="e.g. vendor@example.com">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Phone Number *</label>
                                                <input type="text" class="form-control" name="vendor_phone" required placeholder="e.g. 0806279823">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Business Address *</label>
                                            <input type="text" class="form-control" name="address" required placeholder="Physical store address">
                                        </div>

                                        <div class="form-group">
                                            <label>Store Description / Bio</label>
                                            <textarea class="form-control" name="description" rows="3" placeholder="Tell customers about your store..."></textarea>
                                        </div>

                                        <div class="row gutter-sm">
                                            <div class="col-md-6 form-group">
                                                <label>Store Logo</label>
                                                <input type="file" class="form-control" name="logo" accept="image/*">
                                                <span class="fs-12 text-muted">Upload store logo image</span>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Store Banner</label>
                                                <input type="file" class="form-control" name="banner" accept="image/*">
                                                <span class="fs-12 text-muted">Upload store header banner</span>
                                            </div>
                                        </div>

                                        <div class="row gutter-sm">
                                            <div class="col-md-6 form-group">
                                                <label>Password *</label>
                                                <div style="position: relative;">
                                                    <input type="password" class="form-control" name="password" id="password" required placeholder="••••••••" oninput="return check()" style="padding-right: 45px;">
                                                    <span onclick="togglePassword('password', 'eyeIcon1')" style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer; color:#4B0082; display:inline-flex; align-items:center; z-index:10;">
                                                        <svg id="eyeIcon1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Confirm Password *</label>
                                                <div style="position: relative;">
                                                    <input type="password" class="form-control" name="confirm_password" id="passwordReg" required placeholder="••••••••" oninput="return check()" style="padding-right: 45px;">
                                                    <span onclick="togglePassword('passwordReg', 'eyeIcon2')" style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer; color:#4B0082; display:inline-flex; align-items:center; z-index:10;">
                                                        <svg id="eyeIcon2" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <span id="error" class="d-block mb-3 font-weight-bold"></span>

                                        <button type="submit" name="register_vendor" class="btn btn-primary btn-block my-4" onclick="return confirm('Register account?')">
                                            Register Vendor Account
                                        </button>

                                        <div class="text-center mt-3">
                                            <p>Already have a vendor account? <a href="login.php" class="text-primary font-weight-bold">Log In Here</a></p>
                                        </div>
                                    </form>

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

    <!-- Plugin JS Files -->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/js/main.min.js"></script>

    <script type="text/javascript">
        function check(){
            let password = document.getElementById("password").value;
            let passwordReg = document.getElementById("passwordReg").value;
            let error = document.getElementById("error");

            if (passwordReg && passwordReg !== password){
                error.textContent = "Password does not match!";
                error.style.color = "red";
                return false;
            } else if (passwordReg && passwordReg === password){
                error.textContent = "Password matches correctly!";
                error.style.color = "green";
                return true;
            } else {
                error.textContent = "";
                return true;
            }
        }

        // Block form submission if password fails validation
        const formEl = document.querySelector('form');
        if (formEl) {
            formEl.addEventListener('submit', function(e){
                if(!check()){
                    e.preventDefault();
                }
            });
        }

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';

            icon.innerHTML = isHidden
                ? `<path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
                   <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
                   <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709z"/>
                   <path fill-rule="evenodd" d="M13.646 14.354l-12-12 .708-.708 12 12-.708.708z"/>`
                : `<path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                   <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>`;
        }
    </script>
</body>
</html>