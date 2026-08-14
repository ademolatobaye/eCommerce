<?php
include('customer-session-check.php');
include('db_conn.php');

date_default_timezone_set('Africa/Lagos');

// Fetch user's orders from invoicesales
$user_uin = $_SESSION['customer_uin'];
$user_orders = array();

$stmt = mysqli_prepare($conn, "SELECT * FROM invoicesales WHERE customer_uin = ? ORDER BY `date` DESC, id DESC");
if($stmt){
    mysqli_stmt_bind_param($stmt, "s", $user_uin);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while($row = mysqli_fetch_assoc($res)){
        $user_orders[] = $row;
    }
    mysqli_stmt_close($stmt);
}

// Determine selected order
$selected_order = null;
$requested_id = isset($_GET['order_id']) ? trim($_GET['order_id']) : '';

if (!empty($user_orders)) {
    if (!empty($requested_id)) {
        foreach ($user_orders as $ord) {
            if ($ord['order_id'] === $requested_id || $ord['invoicenumber'] === $requested_id) {
                $selected_order = $ord;
                break;
            }
        }
    }
    // Default to latest order if requested ID not found or not passed
    if (!$selected_order) {
        $selected_order = $user_orders[0];
    }
}

// Fetch line items for selected order
$order_items = array();
if ($selected_order) {
    $inv_num = $selected_order['invoicenumber'];
    $item_stmt = mysqli_prepare($conn, "SELECT * FROM invoiceorder WHERE invoicenumber = ? AND customer_uin = ?");
    if ($item_stmt) {
        mysqli_stmt_bind_param($item_stmt, "ss", $inv_num, $user_uin);
        mysqli_stmt_execute($item_stmt);
        $item_res = mysqli_stmt_get_result($item_stmt);
        while ($irow = mysqli_fetch_assoc($item_res)) {
            $order_items[] = $irow;
        }
        mysqli_stmt_close($item_stmt);
    }
}

// Map current order status to milestone index (1 to 4)
$current_status = isset($selected_order['order_status']) && !empty($selected_order['order_status']) 
    ? $selected_order['order_status'] 
    : 'Payment Confirmed';

$status_clean = strtolower(trim($current_status));
$status_level = 1;

if ($status_clean === 'processing') {
    $status_level = 2;
} elseif ($status_clean === 'shipped' || $status_clean === 'out for delivery' || $status_clean === 'in transit') {
    $status_level = 3;
} elseif ($status_clean === 'delivered' || $status_clean === 'completed') {
    $status_level = 4;
} else {
    $status_level = 1;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>DEE MART || TRACK ORDER</title>
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
    <link rel="preload" href="assets/fonts/wolmart.woff?png09e" as="font" type="font/woff" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/magnific-popup/magnific-popup.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css">

    <style>
        .tracker-wrapper {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 30px 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }
        .tracker-steps {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            margin: 40px 0 20px;
        }
        .tracker-steps::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 50px;
            right: 50px;
            height: 4px;
            background: #e9ecef;
            z-index: 1;
        }
        .tracker-progress-line {
            position: absolute;
            top: 25px;
            left: 50px;
            height: 4px;
            background: #336699;
            z-index: 2;
            transition: width 0.4s ease;
        }
        .tracker-step {
            position: relative;
            z-index: 3;
            text-align: center;
            flex: 1;
        }
        .tracker-icon {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 12px;
            border: 4px solid #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        .tracker-step.completed .tracker-icon {
            background: #336699;
            color: #fff;
        }
        .tracker-step.active .tracker-icon {
            background: #28a745;
            color: #fff;
            transform: scale(1.1);
            box-shadow: 0 4px 14px rgba(40,167,69,0.4);
        }
        .tracker-title {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }
        .tracker-step.active .tracker-title {
            color: #28a745;
        }
        .tracker-desc {
            font-size: 12px;
            color: #777;
        }
        .order-meta-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        @media (max-width: 767px) {
            .tracker-steps {
                flex-direction: column;
                align-items: flex-start;
            }
            .tracker-steps::before, .tracker-progress-line {
                display: none;
            }
            .tracker-step {
                display: flex;
                align-items: center;
                text-align: left;
                margin-bottom: 20px;
                width: 100%;
            }
            .tracker-icon {
                margin-bottom: 0;
                margin-right: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="page-wrapper">

        <?php include("header.php"); ?>

        <main class="main order">
            <!-- Breadcrumb -->
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb shop-breadcrumb bb-no">
                        <li class="passed"><a href="index.php">Home</a></li>
                        <li class="passed"><a href="my-account.php">My Account</a></li>
                        <li class="active">Track Order</li>
                    </ul>
                </div>
            </nav>

            <div class="page-content mb-10 pb-2">
                <div class="container">

                    <?php if (empty($user_orders)): ?>
                        <div class="text-center py-10 my-5">
                            <i class="w-icon-orders" style="font-size: 64px; color: #ccc;"></i>
                            <h3 class="mt-4">No Confirmed Orders Found</h3>
                            <p class="mb-6">You haven't placed any paid orders yet.</p>
                            <a href="shop.php" class="btn btn-dark btn-rounded">Start Shopping</a>
                        </div>
                    <?php else: ?>

                        <!-- Select Order Dropdown if customer has multiple orders -->
                        <?php if (count($user_orders) > 1): ?>
                            <div class="row align-items-center mb-6">
                                <div class="col-md-6 offset-md-3 text-center">
                                    <label for="selectOrder" class="font-weight-bold mr-2 text-dark">Select Order to Track:</label>
                                    <select id="selectOrder" class="form-control d-inline-block w-auto" onchange="location = this.value;">
                                        <?php foreach ($user_orders as $uord): 
                                            $oid = !empty($uord['order_id']) ? $uord['order_id'] : $uord['invoicenumber'];
                                            $is_selected = ($selected_order && ($selected_order['order_id'] === $oid || $selected_order['invoicenumber'] === $oid)) ? 'selected' : '';
                                        ?>
                                            <option value="track-order.php?order_id=<?php echo urlencode($oid); ?>" <?php echo $is_selected; ?>>
                                                Order #<?php echo htmlspecialchars($oid); ?> — <?php echo date('M d, Y', strtotime($uord['date'])); ?> (&#8358;<?php echo number_format($uord['amount'], 2); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php 
                        $display_id = !empty($selected_order['order_id']) ? $selected_order['order_id'] : $selected_order['invoicenumber'];
                        ?>

                        <div class="tracker-wrapper">
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom">
                                <div>
                                    <h3 class="mb-1 text-dark">Order #<?php echo htmlspecialchars($display_id); ?></h3>
                                    <span class="text-muted font-size-md">Placed on <?php echo date('l, F j, Y', strtotime($selected_order['date'])); ?></span>
                                </div>
                                <div class="mt-2 mt-sm-0">
                                    <span class="badge" style="background: #28a745; color: #fff; padding: 6px 14px; font-size: 14px; border-radius: 20px;">
                                        Payment Status: <?php echo htmlspecialchars($selected_order['paymentstatus']); ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Progress Line Calculation -->
                            <?php 
                            $line_width = "0%";
                            if ($status_level == 2) $line_width = "33%";
                            if ($status_level == 3) $line_width = "66%";
                            if ($status_level == 4) $line_width = "100%";
                            ?>

                            <!-- Interactive Visual Progress Timeline -->
                            <div class="tracker-steps">
                                <div class="tracker-progress-line d-none d-md-block" style="width: <?php echo $line_width; ?>;"></div>

                                <!-- Step 1 -->
                                <div class="tracker-step <?php echo ($status_level > 1) ? 'completed' : (($status_level == 1) ? 'active' : ''); ?>">
                                    <div class="tracker-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="tracker-title">Payment Confirmed</div>
                                    <div class="tracker-desc">Order Received</div>
                                </div>

                                <!-- Step 2 -->
                                <div class="tracker-step <?php echo ($status_level > 2) ? 'completed' : (($status_level == 2) ? 'active' : ''); ?>">
                                    <div class="tracker-icon">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                    <div class="tracker-title">Processing</div>
                                    <div class="tracker-desc">Packaging Order</div>
                                </div>

                                <!-- Step 3 -->
                                <div class="tracker-step <?php echo ($status_level > 3) ? 'completed' : (($status_level == 3) ? 'active' : ''); ?>">
                                    <div class="tracker-icon">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="tracker-title">Out for Delivery</div>
                                    <div class="tracker-desc">Dispatched to Courier</div>
                                </div>

                                <!-- Step 4 -->
                                <div class="tracker-step <?php echo ($status_level == 4) ? 'active completed' : ''; ?>">
                                    <div class="tracker-icon">
                                        <i class="fas fa-home"></i>
                                    </div>
                                    <div class="tracker-title">Delivered</div>
                                    <div class="tracker-desc">Order Completed</div>
                                </div>
                            </div>

                            <!-- Courier / Shipping Info if available -->
                            <?php if (!empty($selected_order['courier_name']) || !empty($selected_order['tracking_number'])): ?>
                                <div class="alert alert-info alert-bg alert-button align-items-center mt-6 mb-4" style="background:#e8f4fd; border-color:#b6effb;">
                                    <i class="fas fa-shipping-fast text-info mr-3" style="font-size:24px;"></i>
                                    <div>
                                        <strong>Courier Information:</strong> 
                                        <?php if (!empty($selected_order['courier_name'])): ?>
                                            <span>Carrier: <strong><?php echo htmlspecialchars($selected_order['courier_name']); ?></strong></span>
                                        <?php endif; ?>
                                        <?php if (!empty($selected_order['tracking_number'])): ?>
                                            <span class="ml-3">Tracking Code: <strong><?php echo htmlspecialchars($selected_order['tracking_number']); ?></strong></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Order Summary & Shipping Details Cards -->
                        <div class="row">
                            <div class="col-lg-6 mb-4">
                                <div class="order-meta-card h-100">
                                    <h4 class="title text-uppercase ls-25 mb-4">Delivery Details</h4>
                                    <p class="mb-2"><strong>Customer Name:</strong> <?php echo htmlspecialchars($selected_order['customername']); ?></p>
                                    <p class="mb-2"><strong>Phone Number:</strong> <?php echo htmlspecialchars($selected_order['customer_phone']); ?></p>
                                    <p class="mb-2"><strong>Email:</strong> <?php echo htmlspecialchars($selected_order['customer_email']); ?></p>
                                    <p class="mb-2"><strong>Shipping Address:</strong> <?php echo htmlspecialchars($selected_order['customer_address']); ?></p>
                                    <p class="mb-0"><strong>Delivery Method:</strong> <?php echo htmlspecialchars(!empty($selected_order['deliverymethod']) ? $selected_order['deliverymethod'] : 'Standard Delivery'); ?></p>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-4">
                                <div class="order-meta-card h-100">
                                    <h4 class="title text-uppercase ls-25 mb-4">Payment Summary</h4>
                                    <p class="mb-2"><strong>Invoice Number:</strong> <?php echo htmlspecialchars($selected_order['invoicenumber']); ?></p>
                                    <p class="mb-2"><strong>Payment Method:</strong> <?php echo htmlspecialchars($selected_order['paymentmethod']); ?></p>
                                    <p class="mb-2"><strong>Total Amount:</strong> <span class="text-primary font-weight-bolder">&#8358;<?php echo number_format($selected_order['amount'], 2); ?></span></p>
                                    <p class="mb-0"><strong>Order Status:</strong> <span class="badge badge-info" style="background:#17a2b8; color:#fff; padding:4px 8px;"><?php echo htmlspecialchars($current_status); ?></span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Purchased Items -->
                        <div class="order-details-wrapper mb-8">
                            <h4 class="title text-uppercase ls-25 mb-5">Items In This Order</h4>
                            <table class="order-table shop-table">
                                <thead>
                                    <tr>
                                        <th class="text-dark">Product</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-right">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($order_items)): ?>
                                        <?php foreach ($order_items as $item): ?>
                                            <tr>
                                                <td class="product-name">
                                                    <div class="d-flex align-items-center">
                                                        <?php if (!empty($item['productimage'])): ?>
                                                            <img src="dashboard/productupload/<?php echo htmlspecialchars($item['productimage']); ?>" alt="product" width="60" height="60" class="mr-4 style-rounded" style="object-fit:cover; border-radius:6px;">
                                                        <?php endif; ?>
                                                        <div>
                                                            <a href="product.php?uin=<?php echo urlencode($item['uin']); ?>" class="font-weight-bold text-dark">
                                                                <?php echo htmlspecialchars($item['productname']); ?>
                                                            </a>
                                                            <br><small class="text-muted">Category: <?php echo htmlspecialchars($item['category']); ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center"><strong>x <?php echo (int)$item['quantity']; ?></strong></td>
                                                <td class="text-right font-weight-bolder">&#8358;<?php echo number_format($item['amount'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center">Line item breakdown not available for this invoice.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    <?php endif; ?>

                </div>
            </div>
        </main>

        <?php include("footer.php"); ?>

    </div>
</body>
</html>
