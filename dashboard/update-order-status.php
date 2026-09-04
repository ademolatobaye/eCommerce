<?php
include("session-check.php");
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
    header("Location: management/");
    exit();
}

ini_set('display_errors', '1');
	require 'includes/PHPMailer.php';
	require 'includes/SMTP.php';
	require 'includes/Exception.php';
//Define name spaces
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

if (!isset($_REQUEST['invoicenumber'])){
    header("Location: orders");
    exit();
}

$invoicenumber = mysqli_real_escape_string($conn, $_REQUEST['invoicenumber']);

// Fetch order details
$sql = "SELECT * FROM invoicesales WHERE invoicenumber = '$invoicenumber' OR order_id = '$invoicenumber' LIMIT 1";
$result = mysqli_query($conn, $sql);
$order = mysqli_fetch_array($result);

if (!$order) {
    echo "<script>alert('Order not found.'); window.location.href='orders';</script>";
    exit();
}

$order_id_disp = !empty($order['order_id']) ? $order['order_id'] : $order['invoicenumber'];
$current_status = !empty($order['order_status']) ? $order['order_status'] : 'Payment Confirmed';
$courier_val  = isset($order['courier_name']) ? $order['courier_name'] : '';
$tracking_val = isset($order['tracking_number']) ? $order['tracking_number'] : '';
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/brand/favicon.png">

    <!-- TITLE -->
    <title><?php echo $business_name; ?> – UPDATE ORDER STATUS</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- STYLE CSS -->
     <link href="assets/css/style.css" rel="stylesheet">

	<!-- Plugins CSS -->
    <link href="assets/css/plugins.css" rel="stylesheet">

    <!--- FONT-ICONS CSS -->
    <link href="assets/css/icons.css" rel="stylesheet">

    <!-- INTERNAL Switcher css -->
    <link href="assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="assets/switcher/demo.css" rel="stylesheet">
</head>

<body class="app sidebar-mini ltr light-mode">
    <?php include("menu.php"); ?>

    <div class="page">
        <div class="page-main">
            <div class="main-content app-content mt-0">
                <div class="side-app">
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Update Order Status</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="orders">Orders</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Update Order</li>
                                </ol>
                            </div>
                        </div>

                        <!-- ROW OPEN -->
                        <div class="row">
                            <div class="col-lg-8 offset-lg-2">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Order #<?php echo htmlspecialchars($order_id_disp); ?></h3>
                                    </div>
                                    <div class="card-body">
                                        <form method="post" action="">
                                            <?php
                                            // Handle Form Submission
                                            if (isset($_POST['submit'])) {
                                                $order_status    = mysqli_real_escape_string($conn, trim($_POST['order_status']));
                                                $courier_name    = mysqli_real_escape_string($conn, trim($_POST['courier_name']));
                                                $tracking_number = mysqli_real_escape_string($conn, trim($_POST['tracking_number']));

                                                $update_query = "UPDATE invoicesales SET 
                                                    order_status = '$order_status', 
                                                    courier_name = '$courier_name', 
                                                    tracking_number = '$tracking_number' 
                                                    WHERE invoicenumber = '$invoicenumber' OR order_id = '$invoicenumber'";

                                                if (mysqli_query($conn, $update_query)) {
                                                    // Retrieve recipient email & name
                                                    $customer_email = !empty($order['customer_email']) ? $order['customer_email'] : '';
                                                    $customer_name  = !empty($order['customername']) ? $order['customername'] : 'Valued Customer';
                                                    $order_id_disp  = !empty($order['order_id']) ? $order['order_id'] : $order['invoicenumber'];

                                                    if (empty($customer_email) && !empty($order['customer_uin'])) {
                                                        $cust_uin = mysqli_real_escape_string($conn, $order['customer_uin']);
                                                        $cust_q = mysqli_query($conn, "SELECT customer_email, fullname FROM customertable WHERE customer_uin='$cust_uin' LIMIT 1");
                                                        if ($cust_q && $cust_row = mysqli_fetch_array($cust_q)) {
                                                            $customer_email = $cust_row['customer_email'];
                                                            if (empty($customer_name) || $customer_name === 'Valued Customer') {
                                                                $customer_name = $cust_row['fullname'];
                                                            }
                                                        }
                                                    }

                                                    // Dispatch Email to Background Queue
                                                    if (!empty($customer_email)) {
                                                        $jobPayload = array(
                                                            'customer_email'  => $customer_email,
                                                            'customer_name'   => $customer_name,
                                                            'order_id_disp'   => $order_id_disp,
                                                            'order_status'    => $order_status,
                                                            'courier_name'    => $courier_name,
                                                            'tracking_number' => $tracking_number
                                                        );
                                                        Queue::dispatch('send_order_status_email', $jobPayload, $conn);
                                                    }

                                                    echo "<script>alert('Order status successfully updated to {$order_status} and notification email queued for delivery.');
                                                    window.location.href='orders';</script>";
                                                    exit();
                                                } else {
                                                    echo "<div class='alert alert-danger'>Error updating order status: " . mysqli_error($conn) . "</div>";
                                                }
                                            }
                                            ?>
                                            <div class="mb-4">
                                                <label class="form-label font-weight-bold">Customer Name</label>
                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($order['customername']); ?>" readonly>
                                            </div>

                                            <div class="mb-4">
                                                <label class="form-label font-weight-bold">Total Amount Paid</label>
                                                <input type="text" class="form-control" value="&#8358;<?php echo number_format($order['amount'], 2); ?>" readonly>
                                            </div>

                                            <div class="mb-4">
                                                <label class="form-label font-weight-bold">Order Status *</label>
                                                <select name="order_status" class="form-control form-select" required>
                                                    <option value="Payment Confirmed" <?php if ($current_status === 'Payment Confirmed') echo 'selected'; ?>>Payment Confirmed</option>
                                                    <option value="Processing" <?php if ($current_status === 'Processing') echo 'selected'; ?>>Processing / Packaging</option>
                                                    <option value="Shipped" <?php if ($current_status === 'Shipped') echo 'selected'; ?>>Shipped / Out for Delivery</option>
                                                    <option value="Delivered" <?php if ($current_status === 'Delivered') echo 'selected'; ?>>Delivered</option>
                                                    <option value="Cancelled" <?php if ($current_status === 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                                                </select>
                                            </div>

                                            <div class="mb-4">
                                                <label class="form-label font-weight-bold">Courier / Logistics Carrier (Optional)</label>
                                                <input type="text" name="courier_name" class="form-control" placeholder="e.g. DHL, GIG Logistics, FEDEX" value="<?php echo htmlspecialchars($courier_val); ?>">
                                            </div>

                                            <div class="mb-4">
                                                <label class="form-label font-weight-bold">Tracking Code / Reference (Optional)</label>
                                                <input type="text" name="tracking_number" class="form-control" placeholder="e.g. TRK-987654" value="<?php echo htmlspecialchars($tracking_val); ?>">
                                            </div>

                                            <div class="mt-5">
                                                <button type="submit" name="submit" class="btn btn-primary" onclick="return confirm('Are you sure to update order status?')">Update Order Status</button>
                                                <a href="orders" class="btn btn-secondary ms-2">Cancel</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php 
     include("footer.php");
    ?>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- JQUERY JS -->
    <script src="assets/js/jquery.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- INPUT MASK JS-->
    <script src="assets/plugins/input-mask/jquery.mask.min.js"></script>

    <!-- INTERNAL SELECT2 JS -->
    <script src="assets/plugins/select2/select2.full.min.js"></script>
    <script src="assets/js/select2.js"></script>

	<!-- TypeHead js -->
	<script src="assets/plugins/bootstrap5-typehead/autocomplete.js"></script>
    <script src="assets/js/typehead.js"></script>

    <!-- INTERNAL WYSIWYG Editor JS -->
    <script src="assets/plugins/wysiwyag/jquery.richtext.js "></script>
    <script src="assets/plugins/wysiwyag/wysiwyag.js "></script>

    <!-- INTERNAL File-Uploads Js-->
    <!-- <script src="assets/plugins/fancyuploder/jquery.ui.widget.js"></script>
    <script src="assets/plugins/fancyuploder/jquery.fileupload.js"></script>
    <script src="assets/plugins/fancyuploder/jquery.iframe-transport.js"></script>
    <script src="assets/plugins/fancyuploder/jquery.fancy-fileupload.js"></script>
    <script src="assets/plugins/fancyuploder/fancy-uploader.js"></script> -->

    <!-- Perfect SCROLLBAR JS-->
    <script src="assets/plugins/p-scroll/perfect-scrollbar.js"></script>
    <script src="assets/plugins/p-scroll/pscroll.js"></script>
    <script src="assets/plugins/p-scroll/pscroll-1.js"></script>

    <!-- SIDE-MENU JS -->
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>

    <!-- SIDEBAR JS -->
    <script src="assets/plugins/sidebar/sidebar.js"></script>

    <!-- Color Theme js -->
    <script src="assets/js/themeColors.js"></script>

    <!-- Sticky js -->
    <script src="assets/js/sticky.js"></script>

    <!-- CUSTOM JS -->
    <script src="assets/js/custom.js"></script>

    <!-- Custom-switcher -->
    <script src="assets/js/custom-swicher.js"></script>

    <!-- Switcher js -->
    <script src="assets/switcher/js/switcher.js"></script>

</body>
</html>
