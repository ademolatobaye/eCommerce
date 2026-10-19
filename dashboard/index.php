<?php
include('session-check.php');
include('db_conn.php');

$sql = "SELECT * FROM system_setting LIMIT 1";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$setting_row = mysqli_fetch_assoc($result);
$phone = $setting_row['phone'];
$business_name = $setting_row['business_name'];
$address = $setting_row['address'];
$email = $setting_row['email'];
$business_model = !empty($setting_row['business_model']) ? $setting_row['business_model'] : 'Multi-Vendor';
$is_multivendor = ($business_model === 'Multi-Vendor');

// Check if business_name is NULL or empty
if (empty($setting_row['business_name'])) {
    header("Location: management/");
    exit();
}

$id = 1;
$sql = "SELECT * FROM stafftable WHERE id='$id'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$rows = mysqli_fetch_array($result);
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Super Admin Dashboard">
    <meta name="author" content="">
    <meta name="keywords" content="">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/brand/favicon.png">

    <!-- TITLE -->
    <title><?php echo $business_name; ?> || SUPER ADMIN DASHBOARD</title>

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

    <!-- KPI DASHBOARD CSS -->
    <link href="assets/css/kpi.css" rel="stylesheet">
</head>

<body class="app sidebar-mini ltr light-mode">
    <?php
    include("menu.php");
    ?>

    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src="assets/images/loader.svg" class="loader-img" alt="Loader">
    </div>
    <!-- /GLOBAL-LOADER -->

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">

            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <?php
                        // ==========================================
                        // LIVE MARKETPLACE KPI METRICS CALCULATION
                        // ==========================================

                        // 1. Total Marketplace GMV (Paid Sales)
                        $sql_rev = "SELECT SUM(amount) AS totalrev FROM invoicesales WHERE paymentstatus = 'Paid'";
                        $res_rev = mysqli_query($conn, $sql_rev);
                        $count_rev = mysqli_fetch_assoc($res_rev);
                        $total_revenue = (float)($count_rev['totalrev'] ?? 0);

                        // 2. Platform Net Profit
                        $sql_prof = "SELECT SUM(profit) AS totalprofit FROM invoicesales WHERE paymentstatus = 'Paid'";
                        $res_prof = mysqli_query($conn, $sql_prof);
                        $count_prof = mysqli_fetch_assoc($res_prof);
                        $total_profit = (float)($count_prof['totalprofit'] ?? 0);

                        // 3. Total Paid Orders
                        $sql_porders = "SELECT COUNT(*) AS totalporders FROM invoicesales WHERE paymentstatus = 'Paid'";
                        $res_porders = mysqli_query($conn, $sql_porders);
                        $count_porders = mysqli_fetch_assoc($res_porders);
                        $total_paid_orders = (int)($count_porders['totalporders'] ?? 0);

                        // 4. Pending / Abandoned Checkout Orders
                        $sql_pend = "SELECT COUNT(*) AS totalpend FROM invoiceorder WHERE paymentstatus = 'Pending'";
                        $res_pend = mysqli_query($conn, $sql_pend);
                        $count_pend = mysqli_fetch_assoc($res_pend);
                        $total_pending_orders = (int)($count_pend['totalpend'] ?? 0);

                        // 5. Active Vendors (Multi-Vendor) OR Product Categories (Single Store)
                        if ($is_multivendor) {
                            $sql_vnd = "SELECT 
                                COUNT(*) AS total_vendors,
                                SUM(CASE WHEN `status` = 'Active' THEN 1 ELSE 0 END) AS active_vendors,
                                SUM(CASE WHEN `status` != 'Active' OR `status` IS NULL THEN 1 ELSE 0 END) AS pending_vendors
                                FROM vendor_table";
                            $res_vnd = mysqli_query($conn, $sql_vnd);
                            $count_vnd = mysqli_fetch_assoc($res_vnd);
                            $active_vendors = (int)($count_vnd['active_vendors'] ?? 0);
                            $pending_vendors = (int)($count_vnd['pending_vendors'] ?? 0);
                        } else {
                            $sql_cat = "SELECT COUNT(*) AS total_cats FROM category";
                            $res_cat = mysqli_query($conn, $sql_cat);
                            $count_cat = mysqli_fetch_assoc($res_cat);
                            $total_categories = (int)($count_cat['total_cats'] ?? 0);
                        }

                        // 6. Registered Customers
                        $sql_cust = "SELECT COUNT(*) AS totalcustomers FROM customertable";
                        $res_cust = mysqli_query($conn, $sql_cust);
                        $count_cust = mysqli_fetch_assoc($res_cust);
                        $total_customers = (int)($count_cust['totalcustomers'] ?? 0);

                        // 7. Total Products & Approval Queue
                        $sql_prods = "SELECT 
                            COUNT(*) AS total_prods,
                            SUM(CASE WHEN approval_status = 'Approved' THEN 1 ELSE 0 END) AS approved_prods,
                            SUM(CASE WHEN approval_status != 'Approved' OR approval_status IS NULL THEN 1 ELSE 0 END) AS pending_prods,
                            SUM(CASE WHEN quantity <= CAST(COALESCE(NULLIF(lowlevel, ''), '5') AS UNSIGNED) OR quantity = 0 THEN 1 ELSE 0 END) AS low_stock_prods
                            FROM product_table";
                        $res_prods = mysqli_query($conn, $sql_prods);
                        $count_prods = mysqli_fetch_assoc($res_prods);
                        $total_products = (int)($count_prods['total_prods'] ?? 0);
                        $pending_products = (int)($count_prods['pending_prods'] ?? 0);
                        $low_stock_products = (int)($count_prods['low_stock_prods'] ?? 0);

                        // 8. Orders Needing Fulfillment (Paid orders awaiting dispatch/delivery)
                        $sql_ful = "SELECT COUNT(*) AS totalfulfill FROM invoicesales WHERE paymentstatus = 'Paid' AND (order_status IS NULL OR order_status = '' OR order_status = 'Payment Confirmed' OR order_status = 'Processing')";
                        $res_ful = mysqli_query($conn, $sql_ful);
                        $count_ful = mysqli_fetch_assoc($res_ful);
                        $orders_to_fulfill = (int)($count_ful['totalfulfill'] ?? 0);
                        ?>

                        <!-- PAGE-HEADER -->
                        <div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <h1 class="page-title mb-1">
                                    <?php 
                                    date_default_timezone_set("Africa/Lagos");
                                    $hour = date("H");
                                    if ($hour >= 5 && $hour < 12) {
                                        echo "Good Morning, " . htmlspecialchars($session_role . " " . $session_fullname);
                                    } elseif ($hour >= 12 && $hour < 16) {
                                        echo "Good Afternoon, " . htmlspecialchars($session_role . " " . $session_fullname);
                                    } elseif ($hour >= 16 && $hour < 21) {
                                        echo "Good Evening, " . htmlspecialchars($session_role . " " . $session_fullname);
                                    } else {
                                        echo "Welcome Back, " . htmlspecialchars($session_role . " " . $session_fullname);
                                    }
                                    ?>
                                </h1>
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $is_multivendor ? 'Marketplace' : 'Store'; ?> Analytics &amp; Command Center</li>
                                </ol>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="add-product" class="btn btn-primary"><i class="fe fe-plus me-1"></i> Add Product</a>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newcategory"><i class="fe fe-folder-plus me-1"></i> Add Category</button>
                                <a href="orders" class="btn btn-outline-primary"><i class="fe fe-shopping-cart me-1"></i> All Orders</a>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <?php
                        $rev_parts = explode('.', number_format($total_revenue, 2));
                        $prof_parts = explode('.', number_format($total_profit, 2));
                        ?>

                        <!-- ROW 1: COMMERCIAL & REVENUE OVERVIEW -->
                        <div class="kpi-section-header">
                            <div class="kpi-section-title">
                                <i class="fe fe-activity text-muted"></i> Commercial &amp; Revenue Overview
                            </div>
                            <span class="kpi-section-tag"><?php echo $is_multivendor ? 'Platform Gross Metrics' : 'Store Sales Metrics'; ?></span>
                        </div>

                        <div class="row row-sm mb-3">
                            <!-- METRIC 1: GMV / Total Revenue -->
                            <div class="col-sm-6 col-md-6 col-xl-3 mb-3">
                                <a href="orders" class="kpi-card-link">
                                    <div class="card kpi-card kpi-accent-emerald">
                                        <div class="kpi-header">
                                            <span class="kpi-label"><?php echo $is_multivendor ? 'Marketplace GMV' : 'Total Revenue'; ?></span>
                                            <span class="kpi-icon-indicator kpi-icon-emerald">
                                                <i class="fe fe-dollar-sign"></i>
                                            </span>
                                        </div>
                                        <div class="kpi-value-wrap">
                                            <span class="kpi-value"><span class="kpi-currency">&#8358;</span><?php echo $rev_parts[0]; ?><span class="kpi-decimals">.<?php echo $rev_parts[1]; ?></span></span>
                                        </div>
                                        <div class="kpi-footer">
                                            <span class="kpi-desc">Gross paid volume</span>
                                            <span class="kpi-badge kpi-badge-success"><span class="status-dot status-dot-emerald status-dot-pulse"></span> Settled</span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- METRIC 2: Net Profit -->
                            <div class="col-sm-6 col-md-6 col-xl-3 mb-3">
                                <div class="card kpi-card kpi-accent-indigo">
                                    <div class="kpi-header">
                                        <span class="kpi-label"><?php echo $is_multivendor ? 'Platform Net Margin' : 'Store Net Profit'; ?></span>
                                        <span class="kpi-icon-indicator kpi-icon-indigo">
                                            <i class="fe fe-trending-up"></i>
                                        </span>
                                    </div>
                                    <div class="kpi-value-wrap">
                                        <span class="kpi-value"><span class="kpi-currency">&#8358;</span><?php echo $prof_parts[0]; ?><span class="kpi-decimals">.<?php echo $prof_parts[1]; ?></span></span>
                                    </div>
                                    <div class="kpi-footer">
                                        <span class="kpi-desc">Realized margin</span>
                                        <span class="kpi-badge kpi-badge-neutral">Accrued</span>
                                    </div>
                                </div>
                            </div>

                            <!-- METRIC 3: Total Paid Orders -->
                            <div class="col-sm-6 col-md-6 col-xl-3 mb-3">
                                <a href="orders" class="kpi-card-link">
                                    <div class="card kpi-card kpi-accent-blue">
                                        <div class="kpi-header">
                                            <span class="kpi-label">Paid Orders</span>
                                            <span class="kpi-icon-indicator kpi-icon-blue">
                                                <i class="fe fe-shopping-bag"></i>
                                            </span>
                                        </div>
                                        <div class="kpi-value-wrap">
                                            <span class="kpi-value"><?php echo number_format($total_paid_orders); ?></span>
                                        </div>
                                        <div class="kpi-footer">
                                            <span class="kpi-desc">Completed checkouts</span>
                                            <span class="kpi-badge kpi-badge-success"><span class="status-dot status-dot-emerald"></span> Processed</span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- METRIC 4: Pending / Abandoned Orders -->
                            <div class="col-sm-6 col-md-6 col-xl-3 mb-3">
                                <a href="pending-orders" class="kpi-card-link">
                                    <div class="card kpi-card kpi-accent-amber">
                                        <div class="kpi-header">
                                            <span class="kpi-label">Pending Orders</span>
                                            <span class="kpi-icon-indicator kpi-icon-amber">
                                                <i class="fe fe-clock"></i>
                                            </span>
                                        </div>
                                        <div class="kpi-value-wrap">
                                            <span class="kpi-value"><?php echo number_format($total_pending_orders); ?></span>
                                        </div>
                                        <div class="kpi-footer">
                                            <span class="kpi-desc">Unsettled carts</span>
                                            <?php if ($total_pending_orders > 0): ?>
                                                <span class="kpi-badge kpi-badge-warning"><span class="status-dot status-dot-amber status-dot-pulse"></span> <?php echo $total_pending_orders; ?> Unpaid</span>
                                            <?php else: ?>
                                                <span class="kpi-badge kpi-badge-neutral">All cleared</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- ROW 2: MARKETPLACE / STORE OPERATIONS -->
                        <div class="kpi-section-header mt-2">
                            <div class="kpi-section-title">
                                <i class="fe fe-grid text-muted"></i> <?php echo $is_multivendor; ?>
                            </div>
                            <span class="kpi-section-tag"><?php echo $is_multivendor; ?></span>
                        </div>

                        <div class="row row-sm mb-3">
                            <?php if ($is_multivendor): ?>
                            <!-- METRIC 5: Active Vendors (Multi-Vendor Mode) -->
                            <div class="col-sm-6 col-md-6 col-xl-3 mb-3">
                                <a href="vendors<?php echo $pending_vendors > 0 ? '?filter=pending' : ''; ?>" class="kpi-card-link">
                                    <div class="card kpi-card kpi-accent-slate">
                                        <div class="kpi-header">
                                            <span class="kpi-label">Active Vendors</span>
                                            <span class="kpi-icon-indicator kpi-icon-slate">
                                                <i class="fe fe-users"></i>
                                            </span>
                                        </div>
                                        <div class="kpi-value-wrap">
                                            <span class="kpi-value"><?php echo number_format($active_vendors); ?></span>
                                        </div>
                                        <div class="kpi-footer">
                                            <span class="kpi-desc">Merchant partners</span>
                                            <?php if ($pending_vendors > 0): ?>
                                                <span class="kpi-badge kpi-badge-warning"><span class="status-dot status-dot-amber status-dot-pulse"></span> <?php echo $pending_vendors; ?> in review</span>
                                            <?php else: ?>
                                                <span class="kpi-badge kpi-badge-success"><span class="status-dot status-dot-emerald"></span> Verified</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php else: ?>
                            <!-- METRIC 5: Product Categories (Single Store Mode) -->
                            <div class="col-sm-6 col-md-6 col-xl-3 mb-3">
                                <a href="view-category" class="kpi-card-link">
                                    <div class="card kpi-card kpi-accent-slate">
                                        <div class="kpi-header">
                                            <span class="kpi-label">Product Categories</span>
                                            <span class="kpi-icon-indicator kpi-icon-slate">
                                                <i class="fe fe-folder"></i>
                                            </span>
                                        </div>
                                        <div class="kpi-value-wrap">
                                            <span class="kpi-value"><?php echo number_format($total_categories); ?></span>
                                        </div>
                                        <div class="kpi-footer">
                                            <span class="kpi-desc">Store departments</span>
                                            <span class="kpi-badge kpi-badge-neutral">Active</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php endif; ?>

                            <!-- METRIC 6: Registered Customers -->
                            <div class="col-sm-6 col-md-6 col-xl-3 mb-3">
                                <a href="customers" class="kpi-card-link">
                                    <div class="card kpi-card kpi-accent-violet">
                                        <div class="kpi-header">
                                            <span class="kpi-label">Registered Customers</span>
                                            <span class="kpi-icon-indicator kpi-icon-violet">
                                                <i class="fe fe-user-check"></i>
                                            </span>
                                        </div>
                                        <div class="kpi-value-wrap">
                                            <span class="kpi-value"><?php echo number_format($total_customers); ?></span>
                                        </div>
                                        <div class="kpi-footer">
                                            <span class="kpi-desc">Buyer accounts</span>
                                            <span class="kpi-badge kpi-badge-neutral">Active</span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- METRIC 7: Total Products & Inventory Health -->
                            <div class="col-sm-6 col-md-6 col-xl-3 mb-3">
                                <a href="product<?php echo ($is_multivendor && $pending_products > 0) ? '?filter=pending' : ($low_stock_products > 0 ? '?filter=lowstock' : ''); ?>" class="kpi-card-link">
                                    <div class="card kpi-card kpi-accent-purple">
                                        <div class="kpi-header">
                                            <span class="kpi-label">Listed Products</span>
                                            <span class="kpi-icon-indicator kpi-icon-purple">
                                                <i class="fe fe-box"></i>
                                            </span>
                                        </div>
                                        <div class="kpi-value-wrap">
                                            <span class="kpi-value"><?php echo number_format($total_products); ?></span>
                                        </div>
                                        <div class="kpi-footer">
                                            <span class="kpi-desc">Store inventory</span>
                                            <?php if ($is_multivendor && $pending_products > 0): ?>
                                                <span class="kpi-badge kpi-badge-warning"><span class="status-dot status-dot-amber status-dot-pulse"></span> <?php echo $pending_products; ?> review</span>
                                            <?php elseif ($low_stock_products > 0): ?>
                                                <span class="kpi-badge kpi-badge-danger"><span class="status-dot status-dot-rose status-dot-pulse"></span> <?php echo $low_stock_products; ?> low</span>
                                            <?php else: ?>
                                                <span class="kpi-badge kpi-badge-success"><span class="status-dot status-dot-emerald"></span> In stock</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- METRIC 8: Orders Needing Fulfillment -->
                            <div class="col-sm-6 col-md-6 col-xl-3 mb-3">
                                <a href="orders?filter=fulfillment" class="kpi-card-link">
                                    <div class="card kpi-card kpi-accent-rose">
                                        <div class="kpi-header">
                                            <span class="kpi-label">Orders to Fulfill</span>
                                            <span class="kpi-icon-indicator kpi-icon-rose">
                                                <i class="fe fe-truck"></i>
                                            </span>
                                        </div>
                                        <div class="kpi-value-wrap">
                                            <span class="kpi-value"><?php echo number_format($orders_to_fulfill); ?></span>
                                        </div>
                                        <div class="kpi-footer">
                                            <span class="kpi-desc">Awaiting dispatch</span>
                                            <?php if ($orders_to_fulfill > 0): ?>
                                                <span class="kpi-badge kpi-badge-danger"><span class="status-dot status-dot-rose status-dot-pulse"></span> Action needed</span>
                                            <?php else: ?>
                                                <span class="kpi-badge kpi-badge-success"><span class="status-dot status-dot-emerald"></span> Dispatched</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                    <?php
            // Get last 7 days platform-wide sales & orders
            $labels = array();
            $sales_data = array();
            $orders_data = array();
            
            for ($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $label = date('D d', strtotime("-$i days"));
                array_push($labels, $label);
            
                // Total Sales (amount) from invoicesales across platform for that day
                $sales_sql = "SELECT SUM(amount) as totalsales FROM invoicesales WHERE DATE(`date`) = '$date' AND paymentstatus = 'Paid'";
                $sales_result = mysqli_query($conn, $sales_sql);
                $sales_row = mysqli_fetch_array($sales_result);
                array_push($sales_data, $sales_row['totalsales'] ? (float)$sales_row['totalsales'] : 0);
            
                // Total Orders (count) from invoicesales for that day
                $orders_sql = "SELECT COUNT(*) as totalorders FROM invoicesales WHERE DATE(`date`) = '$date' AND paymentstatus = 'Paid'";
                $orders_result = mysqli_query($conn, $orders_sql);
                $orders_row = mysqli_fetch_array($orders_result);
                array_push($orders_data, $orders_row['totalorders'] ? (int)$orders_row['totalorders'] : 0);
            }
            
            // Convert to JSON for Chart.js
            $labels_json = json_encode($labels);
            $sales_json = json_encode($sales_data);
            $orders_json = json_encode($orders_data);
            ?>
                       
                       
                        <!-- ROW-2 -->
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="card">
                                    <div class="card-header border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                                        <h3 class="card-title">Recent Customer Orders Across All Stores</h3>
                                        <div class="d-flex flex-wrap gap-1">
                                            <a href="orders" class="btn btn-sm btn-primary">Paid Orders</a>
                                            <a href="pending-orders" class="btn btn-sm btn-warning">Pending Orders</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-nowrap key-buttons border-bottom">
                                                <thead>
                                                    <tr>
                                                        <th>S/N</th>
                                                        <th>Order ID / Invoice #</th>
                                                        <th>Date</th>
                                                        <th>Product</th>
                                                        <th>Customer Name</th>
                                                        <th>Customer Phone</th>
                                                        <th>Amount</th>
                                                        <th>Payment Status</th>
                                                        <th>Order Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $rec_stmt = mysqli_prepare($conn, "SELECT * FROM invoicesales ORDER BY id DESC LIMIT 10");
                                                    mysqli_stmt_execute($rec_stmt);
                                                    $rec_orders = mysqli_stmt_get_result($rec_stmt);
                                                    if ($rec_orders && mysqli_num_rows($rec_orders) > 0) {
                                                        $r_cnt = 1;
                                                        while ($r_row = mysqli_fetch_assoc($rec_orders)) {
                                                            $r_oid = !empty($r_row['order_id']) ? $r_row['order_id'] : $r_row['invoicenumber'];
                                                            $r_stat = !empty($r_row['order_status']) ? $r_row['order_status'] : 'Processing';
                                                    ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo $r_cnt++; ?></td>
                                                                <td><strong><?php echo htmlspecialchars($r_oid); ?></strong></td>
                                                                <td><?php echo date('jS-F-Y', strtotime($r_row['date'])); ?></td>
                                                                <td><strong><?php echo htmlspecialchars($r_row['productname']); ?></strong></td>
                                                                <td><?php echo htmlspecialchars($r_row['customername']); ?></td>
                                                                <td><?php echo htmlspecialchars($r_row['customer_phone']); ?></td>
                                                                <td><strong>&#8358;<?php echo number_format((float)$r_row['amount'], 2); ?></strong></td>
                                                                <td class="text-center"><span class="badge bg-success"><?php echo htmlspecialchars($r_row['paymentstatus']); ?></span></td>
                                                                <td class="text-center"><span class="badge bg-info"><?php echo htmlspecialchars($r_stat); ?></span></td>
                                                            </tr>
                                                    <?php
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='9' class='text-center py-4 text-muted'>No recent customer orders found.</td></tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ROW-3 -->
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Sales Analytics (7 days)</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex mx-auto text-center justify-content-center mb-4">
                                            <div class="d-flex text-center justify-content-center me-3"><span
                                                    class="dot-label bg-primary my-auto"></span>Total Sales</div>
                                            <div class="d-flex text-center justify-content-center"><span
                                                    class="dot-label bg-secondary my-auto"></span>Total Orders</div>
                                        </div>
                                        <div class="chartjs-wrapper-demo">
                                            <canvas id="transactions" class="chart-dropshadow"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             
                        </div>
                        <!-- ROW-3 END -->

                <script>
                    var labels = <?php echo $labels_json; ?>;
                    var salesData = <?php echo $sales_json; ?>;
                    var ordersData = <?php echo $orders_json; ?>;
                
                    var ctx = document.getElementById('transactions').getContext('2d');
                    var transactionsChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Total Sales (₦)',
                                    data: salesData,
                                    borderColor: '#6259ca',
                                    backgroundColor: 'rgba(98, 89, 202, 0.1)',
                                    borderWidth: 2,
                                    fill: true,
                                    tension: 0.4,
                                    pointBackgroundColor: '#6259ca',
                                    pointRadius: 4
                                },
                                {
                                    label: 'Total Orders',
                                    data: ordersData,
                                    borderColor: '#f1388b',
                                    backgroundColor: 'rgba(241, 56, 139, 0.1)',
                                    borderWidth: 2,
                                    fill: true,
                                    tension: 0.4,
                                    pointBackgroundColor: '#f1388b',
                                    pointRadius: 4
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            if (context.datasetIndex === 0) {
                                                return ' ₦' + context.raw.toLocaleString();
                                            }
                                            return ' ' + context.raw + ' Orders';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(255,255,255,0.1)' }
                                },
                                x: {
                                    grid: { color: 'rgba(255,255,255,0.1)' }
                                }
                            }
                        }
                    });
                </script>

                    </div>
                    <!-- CONTAINER END -->
                </div>
            </div>
            <!--app-content close-->

        </div>

    <?php
    include("footer.php");
    ?>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>
    
    <script>
        var transactionLabels = <?php echo $labels_json; ?>;
        var transactionSales = <?php echo $sales_json; ?>;
        var transactionOrders = <?php echo $orders_json; ?>;
    </script>

    <!-- JQUERY JS -->
    <script src="assets/js/jquery.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- SPARKLINE JS-->
    <script src="assets/js/jquery.sparkline.min.js"></script>

    <!-- Sticky js -->
    <script src="assets/js/sticky.js"></script>

    <!-- CHART-CIRCLE JS-->
    <script src="assets/js/circle-progress.min.js"></script>

    <!-- PIETY CHART JS-->
    <script src="assets/plugins/peitychart/jquery.peity.min.js"></script>
    <script src="assets/plugins/peitychart/peitychart.init.js"></script>

    <!-- SIDEBAR JS -->
    <script src="assets/plugins/sidebar/sidebar.js"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="assets/plugins/p-scroll/perfect-scrollbar.js"></script>
    <script src="assets/plugins/p-scroll/pscroll.js"></script>
    <script src="assets/plugins/p-scroll/pscroll-1.js"></script>

    <!-- INTERNAL CHARTJS CHART JS-->
    <script src="assets/plugins/chart/Chart.bundle.js"></script>
    <script src="assets/plugins/chart/utils.js"></script>

    <!-- INTERNAL SELECT2 JS -->
    <script src="assets/plugins/select2/select2.full.min.js"></script>

    <!-- INTERNAL Data tables js-->
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
    <script src="assets/plugins/datatable/dataTables.responsive.min.js"></script>

    <!-- INTERNAL APEXCHART JS -->
    <script src="assets/js/apexcharts.js"></script>
    <script src="assets/plugins/apexchart/irregular-data-series.js"></script>

    <!-- INTERNAL Flot JS -->
    <script src="assets/plugins/flot/jquery.flot.js"></script>
    <script src="assets/plugins/flot/jquery.flot.fillbetween.js"></script>
    <script src="assets/plugins/flot/chart.flot.sampledata.js"></script>
    <script src="assets/plugins/flot/dashboard.sampledata.js"></script>

    <!-- INTERNAL Vector js -->
    <script src="assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

    <!-- SIDE-MENU JS-->
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>

	<!-- TypeHead js -->
	<script src="assets/plugins/bootstrap5-typehead/autocomplete.js"></script>
    <script src="assets/js/typehead.js"></script>

    <!-- INTERNAL INDEX JS -->
    <script src="assets/js/index1.js"></script>

    <!-- Color Theme js -->
    <script src="assets/js/themeColors.js"></script>

    <!-- CUSTOM JS -->
    <script src="assets/js/custom.js"></script>

    <!-- Custom-switcher -->
    <script src="assets/js/custom-swicher.js"></script>

    <!-- Switcher js -->
    <script src="assets/switcher/js/switcher.js"></script>

</body>

</html>