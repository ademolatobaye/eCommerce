<?php
include('db_conn.php');
include("customer-session-check.php");
date_default_timezone_set('Africa/Lagos');

$timeout_duration = 600;
$current_url = $_SERVER['REQUEST_URI'];

if (!isset($_SESSION['customer_email'])) {
    header("Location: reg/user-login.php");
    exit();
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    $_SESSION['redirect_after_login'] = $current_url;
    session_unset();
    session_destroy();
    echo "<script>alert('Session expired due to inactivity. Please log in again.'); window.location.href = 'reg/user-login.php';</script>";
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();

$customer_email = $_SESSION['customer_email'];

// Fetch customer details
$query = "SELECT * FROM customertable WHERE customer_email = ? AND `status` = 'Verified'";
$stmt = mysqli_prepare($conn, $query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $customer_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $session_customer_uin = $row['customer_uin'];
        $session_fullname     = $row['fullname'];
        $session_email        = $row['customer_email'];
        $session_phone        = $row['phone'];
    } else {
        session_unset(); session_destroy();
        header("Location: reg/user-login.php"); exit();
    }
} else {
    die("Error preparing query.");
}

// Fetch orders from invoicesales
$orders = array();
$order_query = mysqli_prepare($conn, "SELECT order_id, invoicenumber, `date`, amount, paymentstatus, COALESCE(order_status, 'Payment Confirmed') AS order_status FROM invoicesales WHERE customer_uin = ? ORDER BY `date` DESC");
if ($order_query) {
    mysqli_stmt_bind_param($order_query, "s", $session_customer_uin);
    mysqli_stmt_execute($order_query);
    $order_result = mysqli_stmt_get_result($order_query);
    while ($orow = mysqli_fetch_assoc($order_result)) {
        $orders[] = $orow;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>DEE MART || MY ACCOUNT</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="D-THEMES">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/icons/favicon.png">

    <script>
        WebFontConfig = { google: { families: ['Poppins:400,500,600,700'] } };
        (function(d) {
            var wf = d.createElement('script'), s = d.scripts[0];
            wf.src = 'assets/js/webfont.js';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>

    <link rel="preload" href="assets/vendor/fontawesome-free/webfonts/fa-regular-400.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="assets/vendor/fontawesome-free/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="assets/vendor/fontawesome-free/webfonts/fa-brands-400.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="assets/fonts/wolmart.woff?png09e" as="font" type="font/woff" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/magnific-popup/magnific-popup.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css">
</head>

<body class="my-account">
    <div class="page-wrapper">

        <?php include("header.php"); ?>

        <main class="main">
            <div class="page-header">
                <div class="container">
                    <h1 class="page-title mb-0">My Account</h1>
                </div>
            </div>

            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li>My Account</li>
                    </ul>
                </div>
            </nav>

            <div class="page-content pt-2">
                <div class="container">
                    <div class="tab tab-vertical row gutter-lg">
                        <ul class="nav nav-tabs mb-6" role="tablist">
                            <li class="nav-item">
                                <a href="#account-dashboard" class="nav-link active">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a href="#account-orders" class="nav-link">Orders</a>
                            </li>
                            <li class="nav-item">
                                <a href="#account-details" class="nav-link">Account Details</a>
                            </li>
                            <li class="link-item">
                                <a href="wishlist.php">Wishlist</a>
                            </li>
                            <li class="link-item">
                                <a href="signout.php">Sign Out</a>
                            </li>
                        </ul>

                        <div class="tab-content mb-6">

                            <!-- Dashboard -->
                            <div class="tab-pane active in" id="account-dashboard">
                                <p class="greeting">
                                    Hello
                                    <span class="text-dark font-weight-bold"><?php echo htmlspecialchars($session_fullname); ?>
                                </p>

                                <p class="mb-4">
                                    From your account dashboard you can view your
                                    <a href="#account-orders" class="text-primary link-to-tab">recent orders</a> and
                                    <a href="#account-details" class="text-primary link-to-tab">edit your password and account details.</a>
                                </p>

                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-4 col-xs-6 mb-4">
                                        <a href="#account-orders" class="link-to-tab">
                                            <div class="icon-box text-center">
                                                <span class="icon-box-icon icon-orders"><i class="w-icon-orders"></i></span>
                                                <div class="icon-box-content"><p class="text-uppercase mb-0">Orders</p></div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-4 col-xs-6 mb-4">
                                        <a href="#account-details" class="link-to-tab">
                                            <div class="icon-box text-center">
                                                <span class="icon-box-icon icon-account"><i class="w-icon-user"></i></span>
                                                <div class="icon-box-content"><p class="text-uppercase mb-0">Account Details</p></div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-4 col-xs-6 mb-4">
                                       
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-4 col-xs-6 mb-4">
                                        <a href="signout.php">
                                            <div class="icon-box text-center">
                                                <span class="icon-box-icon icon-logout"><i class="w-icon-logout"></i></span>
                                                <div class="icon-box-content"><p class="text-uppercase mb-0">Sign Out</p></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Orders -->
                            <div class="tab-pane mb-4" id="account-orders">
                                <div class="icon-box icon-box-side icon-box-light">
                                    <span class="icon-box-icon icon-orders"><i class="w-icon-orders"></i></span>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title text-capitalize ls-normal mb-0">Orders</h4>
                                    </div>
                                </div>

                                <?php if (empty($orders)): ?>
                                    <p class="mb-4">You have no orders yet.</p>
                                <?php else: ?>
                                <table class="shop-table account-orders-table mb-6">
                                    <thead>
                                        <tr>
                                            <th class="order-id">Order ID</th>
                                            <th class="order-date">Date</th>
                                            <th class="order-status">Payment</th>
                                            <th class="order-status">Order Status</th>
                                            <th class="order-total">Amount</th>
                                            <th class="order-action">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $order): 
                                            $ordIdentifier = !empty($order['order_id']) ? $order['order_id'] : $order['invoicenumber'];
                                        ?>
                                        <tr>
                                            <td class="order-id"><?php echo htmlspecialchars($ordIdentifier); ?></td>
                                            <td class="order-date"><?php echo date('F j, Y', strtotime($order['date'])); ?></td>
                                            <td class="order-status"><span class="badge badge-success" style="background:#28a745; color:#fff; padding:4px 8px; border-radius:4px;"><?php echo htmlspecialchars($order['paymentstatus']); ?></span></td>
                                            <td class="order-status"><span class="badge badge-info" style="background:#17a2b8; color:#fff; padding:4px 8px; border-radius:4px;"><?php echo htmlspecialchars($order['order_status']); ?></span></td>
                                            <td class="order-total">
                                                <span class="order-price">&#8358;<?php echo number_format($order['amount'], 2); ?></span>
                                            </td>
                                            <td class="order-action">
                                                <a href="track-order.php?order_id=<?php echo urlencode($ordIdentifier); ?>" class="btn btn-outline btn-default btn-block btn-sm btn-rounded"><i class="w-icon-map-marker mr-1"></i>Track</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php endif; ?>

                                <a href="shop.php" class="btn btn-dark btn-rounded btn-icon-right">Go Shop<i class="w-icon-long-arrow-right"></i></a>
                            </div>

                            <!-- Account Details -->
                            <div class="tab-pane" id="account-details">
                                <div class="icon-box icon-box-side icon-box-light">
                                    <span class="icon-box-icon icon-account mr-2"><i class="w-icon-user"></i></span>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title mb-0 ls-normal">Account Details</h4>
                                    </div>
                                </div>

                                <form class="form account-details-form" action="" method="post">
                                    <?php
                                    // Handle Account Details Update
                                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {
                                        $new_fullname = mysqli_real_escape_string($conn, trim($_POST['fullname']));
                                        $new_phone    = mysqli_real_escape_string($conn, trim($_POST['phone']));
                                        $new_email    = mysqli_real_escape_string($conn, trim($_POST['email']));
                                        $cur_password = trim($_POST['cur_password']);
                                        $new_password = trim($_POST['new_password']);
                                        $conf_password = trim($_POST['conf_password']);

                                        // Check if email already taken by another user
                                        if ($new_email !== $session_email) {
                                            $email_check = mysqli_query($conn, "SELECT customer_id FROM customertable WHERE customer_email = '$new_email' LIMIT 1");
                                            if (mysqli_num_rows($email_check) > 0) {
                                                echo '<div class="alert alert-danger">Email already in use by another account.</div>';
                                                goto skip_update;
                                            }
                                        }

                                        // Password change logic
                                        $password_sql = '';
                                        if (!empty($cur_password)) {
                                            $cur_escaped = mysqli_real_escape_string($conn, $cur_password);
                                            $pass_check = mysqli_query($conn, "SELECT customer_id FROM customertable WHERE customer_email = '$session_email' AND password = PASSWORD('$cur_escaped') LIMIT 1");
                                            if (mysqli_num_rows($pass_check) === 0) {
                                                echo '<div class="alert alert-danger">Current password is incorrect.</div>';
                                                goto skip_update;
                                            }
                                            if ($new_password !== $conf_password) {
                                                echo '<div class="alert alert-danger">New passwords do not match.</div>';
                                                goto skip_update;
                                            }
                                            if (strlen($new_password) < 6) {
                                                echo '<div class="alert alert-danger">New password must be at least 6 characters.</div>';
                                                goto skip_update;
                                            }
                                            $new_escaped = mysqli_real_escape_string($conn, $new_password);
                                            $password_sql = ", password = PASSWORD('$new_escaped')";
                                        }

                                        $update = mysqli_prepare($conn, "UPDATE customertable SET fullname = ?, customer_email = ?, phone = ? $password_sql WHERE customer_email = ?");
                                        mysqli_stmt_bind_param($update, "ssss", $new_fullname, $new_email, $new_phone, $session_email);
                                        if (mysqli_stmt_execute($update)) {
                                            $_SESSION['customer_email'] = $new_email;
                                            $_SESSION['fullname']       = $new_fullname;
                                            $session_fullname = $new_fullname;
                                            $session_email    = $new_email;
                                            $session_phone    = $new_phone;
                                            $customer_email   = $new_email;
                                            echo '<div class="alert alert-success">Account updated successfully. An email notification has been sent.</div>';

                                            // Send notification email via PHPMailer inside form handler
                                            if (!empty($new_email)) {
                                                require_once 'includes/PHPMailer.php';
                                                require_once 'includes/SMTP.php';
                                                require_once 'includes/Exception.php';

                                                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                                                try {
                                                    $mail->isSMTP();
                                                    $mail->Host       = 'mail.pocketvest.com.ng';
                                                    $mail->SMTPAuth   = true;
                                                    $mail->Username   = 'ademolaomomeji@pocketvest.com.ng';
                                                    $mail->Password   = 'Omomejih08';
                                                    $mail->SMTPSecure = 'ssl';
                                                    $mail->Port       = 465;

                                                    $mail->setFrom('ademolaomomeji@pocketvest.com.ng', 'DEE MART');
                                                    $mail->addAddress($new_email, $new_fullname);

                                                    $mail->isHTML(true);
                                                    $mail->Subject = "Account Profile Updated - DEE MART";
                                                    $mail->Body = "
                                                    <div style='font-family: Arial, sans-serif; background-color: #f5f6fa; padding: 20px;'>
                                                        <div style='max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
                                                            <div style='background-color: #4B0082; padding: 20px; text-align: center; color: #ffffff;'>
                                                                <h2 style='margin: 0;'>DEE MART</h2>
                                                                <p style='margin: 5px 0 0 0; font-size: 14px;'>Profile Update Notification</p>
                                                            </div>
                                                            <div style='padding: 30px; color: #333333;'>
                                                                <p>Hello <strong>" . htmlspecialchars($new_fullname) . "</strong>,</p>
                                                                <p>Your DEE MART account profile details were successfully updated.</p>
                                                                <p>If you did not make these changes, please contact our support team immediately.</p>
                                                                <p style='margin-top: 20px;'>Thank you,<br>DEE MART Team</p>
                                                            </div>
                                                            <div style='background-color: #f9f9f9; padding: 15px; text-align: center; font-size: 12px; color: #777777;'>
                                                                <p style='margin: 0;'>This email was automatically sent from DEE MART.</p>
                                                            </div>
                                                        </div>
                                                    </div>";

                                                    $mail->send();
                                                } catch (Exception $e) {
                                                    error_log("Account update email error: " . $mail->ErrorInfo);
                                                }
                                            }
                                        } else {
                                            echo '<div class="alert alert-danger">Update failed. Please try again.</div>';
                                        }

                                        skip_update:;
                                    }
                                    ?>
                                    <input type="hidden" name="update_account" value="1">

                                    <div class="form-group">
                                        <label for="fullname">Full Name *</label>
                                        <input type="text" id="fullname" name="fullname"
                                            value="<?php echo htmlspecialchars($session_fullname); ?>"
                                            class="form-control form-control-md" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="phone">Phone Number *</label>
                                        <input type="text" id="phone" name="phone"
                                            value="<?php echo htmlspecialchars($session_phone); ?>"
                                            class="form-control form-control-md" required>
                                    </div>

                                    <div class="form-group mb-6">
                                        <label for="email_1">Email Address *</label>
                                        <input type="email" id="email_1" name="email"
                                            value="<?php echo htmlspecialchars($session_email); ?>"
                                            class="form-control form-control-md" required>
                                    </div>

                                    <h4 class="title title-password ls-25 font-weight-bold">Password Change</h4>
                                    <div class="form-group">
                                        <label class="text-dark" for="cur-password">Current Password</label>
                                        <input type="password" class="form-control form-control-md" id="cur-password" name="cur_password">
                                    </div>
                                    <div class="form-group">
                                        <label class="text-dark" for="new-password">New Password</label>
                                        <input type="password" class="form-control form-control-md" id="new-password" name="new_password">
                                    </div>
                                    <div class="form-group mb-10">
                                        <label class="text-dark" for="conf-password">Confirm New Password</label>
                                        <input type="password" class="form-control form-control-md" id="conf-password" name="conf_password">
                                    </div>

                                    <button type="submit" class="btn btn-dark btn-rounded btn-sm mb-4">Save Changes</button>
                                </form>
                            </div>

                        </div><!-- end tab-content -->
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
       <?php
       include("footer.php");
       ?>
    </div>

    <!-- Sticky Footer -->
   <?php
   include("sticky-footer.php");
   ?>

    <!-- Scroll Top -->
    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button">
        <i class="w-icon-angle-up"></i>
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70">
            <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34" style="stroke-dasharray: 16.4198, 400;"></circle>
        </svg>
    </a>

    <!-- Mobile Menu -->
    <?php
    include("mobile-menu.php"); 
    ?>

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/main.min.js"></script>
</body>
</html>