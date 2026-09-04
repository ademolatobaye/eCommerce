<?php
include('customer-session-check.php');
include('db_conn.php');

$sql = "SELECT * FROM system_setting LIMIT 1";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$setting_row = mysqli_fetch_assoc($result);
$phone = $setting_row['phone'];
$business_name = $setting_row['business_name'];
$address = $setting_row['address'];
$email = $setting_row['email'];

// Check if business_name is NULL or empty
if (empty($setting_row['business_name'])) {
    header("Location: management/");
    exit();
}

if (!isset($_SESSION['invoicenumber'])) {
    header("Location: index");
    exit();
}

// Pull from session
$invoicenumber    = $_SESSION['invoicenumber'];
$order_id         = $_SESSION['order_id'];
$customername     = $_SESSION['customername'];
$customer_email   = $_SESSION['customer_email'];
$customer_phone   = $_SESSION['customer_phone'];
$customer_address = $_SESSION['customer_address'];
$state            = $_SESSION['state'];
$city             = $_SESSION['city'];
$paymentmethod    = $_SESSION['paymentmethod'];
$delivery         = $_SESSION['delivery'];
$amount           = $_SESSION['amount'];
$date             = $_SESSION['date'];
$formatted_date   = date("l jS F, Y", strtotime($date));

// Calculate total from invoiceorder
$total = 0;
$sql = "SELECT * FROM invoiceorder WHERE invoicenumber='$invoicenumber'";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $total += (float)$row['amount'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title><?php echo $business_name; ?> || ORDER COMPLETE</title>
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
    <link rel="stylesheet" type="text/css" href="assets/vendor/magnific-popup/magnific-popup.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css">
</head>

<body>
    <div class="page-wrapper">

        <?php include("header.php"); ?>

        <main class="main order">
            <!-- Breadcrumb -->
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb shop-breadcrumb bb-no">
                        <li class="passed"><a href="cart">Shopping Cart</a></li>
                        <li class="passed"><a href="checkout">Checkout</a></li>
                        <li class="active">Order Complete</li>
                    </ul>
                </div>
            </nav>

            <div class="page-content mb-10 pb-2">
                <div class="container">

                    <!-- Success Banner -->
                    <div class="order-success text-center font-weight-bolder text-dark">
                        <i class="fas fa-check"></i>
                        Thank you. Your order has been received.
                    </div>

                    <div class="text-center mb-6">
                        <a href="track-order?order_id=<?php echo urlencode($order_id); ?>" class="btn btn-dark btn-rounded"><i class="w-icon-map-marker mr-2"></i>Track Your Order</a>
                    </div>

                    <!-- Order Summary Strip -->
                    <ul class="order-view list-style-none">
                        <li>
                            <label>Order number</label>
                            <strong><?php echo htmlspecialchars($order_id); ?></strong>
                        </li>
                        <li>
                            <label>Status</label>
                            <strong>Paid</strong>
                        </li>
                        <li>
                            <label>Date</label>
                            <strong><?php echo htmlspecialchars($formatted_date); ?></strong>
                        </li>
                        <li>
                            <label>Total</label>
                            <strong>&#8358;<?php echo number_format((float)$amount, 2); ?></strong>
                        </li>
                        <li>
                            <label>Payment method</label>
                            <strong><?php echo htmlspecialchars($paymentmethod); ?></strong>
                        </li>
                    </ul>

                    <!-- Order Items Table -->
                    <div class="order-details-wrapper mb-5">
                        <h4 class="title text-uppercase ls-25 mb-5">Order Details</h4>
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th class="text-dark">Product</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM invoiceorder WHERE invoicenumber='$invoicenumber'";
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo htmlspecialchars($row['productname']); ?>
                                        &nbsp;<strong>x <?php echo (int)$row['quantity']; ?></strong>
                                    </td>
                                    <td>&#8358;<?php echo number_format((float)$row['amount'], 2); ?></td>
                                </tr>
                                <?php }} ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Subtotal:</th>
                                    <td>&#8358;<?php echo number_format($total, 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Delivery:</th>
                                    <td><?php echo htmlspecialchars($delivery); ?></td>
                                </tr>
                                <tr>
                                    <th>Payment method:</th>
                                    <td><?php echo htmlspecialchars($paymentmethod); ?></td>
                                </tr>
                                <tr class="total">
                                    <th class="border-no">Total:</th>
                                    <td class="border-no"><strong>&#8358;<?php echo number_format((float)$amount, 2); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Billing & Shipping Address -->
                    <div id="account-addresses">
                        <div class="row">
                            <div class="col-sm-6 mb-8">
                                <div class="ecommerce-address billing-address">
                                    <h4 class="title title-underline ls-25 font-weight-bold">Billing Address</h4>
                                    <address class="mb-4">
                                        <table class="address-table">
                                            <tbody>
                                                <tr><td><?php echo htmlspecialchars($customername); ?></td></tr>
                                                <tr><td><?php echo htmlspecialchars($customer_address); ?></td></tr>
                                                <tr><td><?php echo htmlspecialchars($city); ?></td></tr>
                                                <tr><td><?php echo htmlspecialchars($state); ?></td></tr>
                                                <tr><td><?php echo htmlspecialchars($customer_phone); ?></td></tr>
                                                <tr class="email"><td><?php echo htmlspecialchars($customer_email); ?></td></tr>
                                            </tbody>
                                        </table>
                                    </address>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-8">
                                <div class="ecommerce-address shipping-address">
                                    <h4 class="title title-underline ls-25 font-weight-bold">Shipping Address</h4>
                                    <address class="mb-4">
                                        <table class="address-table">
                                            <tbody>
                                                <tr><td><?php echo htmlspecialchars($customername); ?></td></tr>
                                                <tr><td><?php echo htmlspecialchars($customer_address); ?></td></tr>
                                                <tr><td><?php echo htmlspecialchars($city); ?></td></tr>
                                                <tr><td><?php echo htmlspecialchars($state); ?></td></tr>
                                            </tbody>
                                        </table>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="shop" class="btn btn-dark btn-rounded btn-icon-left btn-back mt-6">
                        <i class="w-icon-long-arrow-left"></i>Continue Shopping
                    </a>

                </div>
            </div>
        </main>

        <?php include("footer.php"); ?>
    </div>

    <?php include("sticky-footer.php"); ?>

    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button">
        <i class="w-icon-angle-up"></i>
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70">
            <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34" style="stroke-dasharray: 16.4198, 400;"></circle>
        </svg>
    </a>

    <?php include("mobile-menu.php"); ?>

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/sticky/sticky.js"></script>
    <script src="assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/main.min.js"></script>
</body>

</html>