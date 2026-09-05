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

// Check if business_name is NULL or empty
if (empty($setting_row['business_name'])) {
    header("Location: ../management/");
    exit();
}

$search = isset($_REQUEST['search']) ? trim($_REQUEST['search']) : '';
$is_date_check = isset($_POST['check']);
$startdate = isset($_POST['startdate']) ? mysqli_real_escape_string($conn, $_POST['startdate']) : '';
$enddate = isset($_POST['enddate']) ? mysqli_real_escape_string($conn, $_POST['enddate']) : '';

$products = [];
$customers = [];
$paid_orders = [];
$pending_orders = [];
$staff_list = [];
$vendors = [];
$blogs = [];

if ($is_date_check && !empty($startdate) && !empty($enddate)) {
    $search_title = "Report by Date Range ($startdate to $enddate)";
    // Product query by date range
    $prod_res = mysqli_query($conn, "SELECT * FROM product_table WHERE DATE(`date`) BETWEEN '$startdate' AND '$enddate' ORDER BY product_id DESC");
    if ($prod_res) { while ($r = mysqli_fetch_assoc($prod_res)) { $products[] = $r; } }

    // Paid orders query by date range
    $sales_res = mysqli_query($conn, "SELECT * FROM invoicesales WHERE DATE(`date`) BETWEEN '$startdate' AND '$enddate' ORDER BY id DESC");
    if ($sales_res) { while ($r = mysqli_fetch_assoc($sales_res)) { $paid_orders[] = $r; } }
} else if (!empty($search)) {
    $search_esc = mysqli_real_escape_string($conn, $search);
    $search_title = "Global Search Results for: \"" . htmlspecialchars($search) . "\"";

    // 1. Products
    $prod_res = mysqli_query($conn, "SELECT * FROM product_table WHERE productname LIKE '%$search_esc%' OR uin LIKE '%$search_esc%' OR category LIKE '%$search_esc%' OR description LIKE '%$search_esc%' ORDER BY product_id DESC");
    if ($prod_res) { while ($r = mysqli_fetch_assoc($prod_res)) { $products[] = $r; } }

    // 2. Customers
    $cust_res = mysqli_query($conn, "SELECT * FROM customertable WHERE fullname LIKE '%$search_esc%' OR customer_email LIKE '%$search_esc%' OR phone LIKE '%$search_esc%' OR customer_uin LIKE '%$search_esc%' OR address LIKE '%$search_esc%' ORDER BY customer_id DESC");
    if ($cust_res) { while ($r = mysqli_fetch_assoc($cust_res)) { $customers[] = $r; } }

    // 3. Paid Orders
    $sales_res = mysqli_query($conn, "SELECT * FROM invoicesales WHERE invoicenumber LIKE '%$search_esc%' OR order_id LIKE '%$search_esc%' OR customername LIKE '%$search_esc%' OR customer_phone LIKE '%$search_esc%' OR courier_name LIKE '%$search_esc%' OR tracking_number LIKE '%$search_esc%' ORDER BY id DESC");
    if ($sales_res) { while ($r = mysqli_fetch_assoc($sales_res)) { $paid_orders[] = $r; } }

    // 4. Pending Orders
    $pending_res = mysqli_query($conn, "SELECT * FROM invoiceorder WHERE invoicenumber LIKE '%$search_esc%' OR customername LIKE '%$search_esc%' OR customer_email LIKE '%$search_esc%' OR customer_phone LIKE '%$search_esc%' OR productname LIKE '%$search_esc%' OR customer_uin LIKE '%$search_esc%' ORDER BY product_id DESC");
    if ($pending_res) { while ($r = mysqli_fetch_assoc($pending_res)) { $pending_orders[] = $r; } }

    // 5. Staff
    $staff_res = mysqli_query($conn, "SELECT * FROM stafftable WHERE fullname LIKE '%$search_esc%' OR email LIKE '%$search_esc%' OR phone LIKE '%$search_esc%' OR uin LIKE '%$search_esc%' OR role LIKE '%$search_esc%' ORDER BY id DESC");
    if ($staff_res) { while ($r = mysqli_fetch_assoc($staff_res)) { $staff_list[] = $r; } }

    // 6. Vendors
    $vendor_res = mysqli_query($conn, "SELECT * FROM vendor_table WHERE store_name LIKE '%$search_esc%' OR vendor_name LIKE '%$search_esc%' OR vendor_email LIKE '%$search_esc%' OR vendor_phone LIKE '%$search_esc%' OR vendor_uin LIKE '%$search_esc%' ORDER BY id DESC");
    if ($vendor_res) { while ($r = mysqli_fetch_assoc($vendor_res)) { $vendors[] = $r; } }

    // 7. Blogs
    $blog_res = mysqli_query($conn, "SELECT * FROM blog WHERE headline LIKE '%$search_esc%' OR content LIKE '%$search_esc%' OR category LIKE '%$search_esc%' OR staff LIKE '%$search_esc%' OR uin LIKE '%$search_esc%' ORDER BY id DESC");
    if ($blog_res) { while ($r = mysqli_fetch_assoc($blog_res)) { $blogs[] = $r; } }
} else {
    $search_title = "Global Admin Search";
}

$count_prod = count($products);
$count_cust = count($customers);
$count_paid = count($paid_orders);
$count_pend = count($pending_orders);
$count_staff = count($staff_list);
$count_vendor = count($vendors);
$count_blog = count($blogs);

$total_count = $count_prod + $count_cust + $count_paid + $count_pend + $count_staff + $count_vendor + $count_blog;

// Determine active tab
$active_tab = 'products';
if ($count_prod > 0) {
    $active_tab = 'products';
} elseif ($count_cust > 0) {
    $active_tab = 'customers';
} elseif ($count_paid > 0) {
    $active_tab = 'paid_orders';
} elseif ($count_pend > 0) {
    $active_tab = 'pending_orders';
} elseif ($count_staff > 0) {
    $active_tab = 'staff';
} elseif ($count_vendor > 0) {
    $active_tab = 'vendors';
} elseif ($count_blog > 0) {
    $active_tab = 'blogs';
}
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/brand/favicon.png">
    <title><?php echo htmlspecialchars($business_name); ?> - GLOBAL SEARCH</title>
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="assets/switcher/demo.css" rel="stylesheet">
</head>

<body class="app sidebar-mini ltr light-mode">
    <?php include("menu.php"); ?>

    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src="assets/images/loader.svg" class="loader-img" alt="Loader">
    </div>

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">

            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Search Result</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Search Result</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- SEARCH BAR CARD -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="search" method="get" class="row g-3 align-items-center">
                                            <div class="col-md-10">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light"><i class="fe fe-search"></i></span>
                                                    <input type="text" name="search" class="form-control form-control-lg" placeholder="Search products, customers, orders, staff, vendors, blogs..." value="<?php echo htmlspecialchars($search); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-primary btn-lg w-100"><i class="fe fe-search me-1"></i> Search</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SEARCH TITLE & SUMMARY -->
                        <?php if (!empty($search) || $is_date_check): ?>
                        <div class="row mb-3">
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <h4 class="fw-bold mb-0"><?php echo $search_title; ?></h4>
                                <span class="badge bg-primary fs-14 py-2 px-3">Total Matches: <?php echo $total_count; ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ((!empty($search) || $is_date_check) && $total_count == 0): ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card text-center py-5">
                                    <div class="card-body">
                                        <i class="fe fe-search fs-50 text-muted d-block mb-3"></i>
                                        <h3 class="fw-bold text-dark">No Matching Results Found</h3>
                                        <p class="text-muted">We couldn't find any products, customers, orders, staff, vendors, or blogs matching "<strong><?php echo htmlspecialchars($search); ?></strong>".</p>
                                        <a href="index" class="btn btn-secondary mt-2"><i class="fe fe-arrow-left me-1"></i> Return to Dashboard</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php elseif (!empty($search) || $is_date_check): ?>

                        <!-- RESULTS TAB CONTENT -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header border-bottom-0">
                                        <ul class="nav nav-pills card-header-pills" id="searchTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link <?php echo ($active_tab == 'products') ? 'active' : ''; ?>" id="products-tab" data-bs-toggle="tab" data-bs-target="#products-pane" type="button" role="tab">
                                                    <i class="fe fe-box me-1"></i> Products <span class="badge bg-<?php echo ($count_prod > 0) ? 'success' : 'secondary'; ?> ms-1"><?php echo $count_prod; ?></span>
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link <?php echo ($active_tab == 'customers') ? 'active' : ''; ?>" id="customers-tab" data-bs-toggle="tab" data-bs-target="#customers-pane" type="button" role="tab">
                                                    <i class="fe fe-users me-1"></i> Customers <span class="badge bg-<?php echo ($count_cust > 0) ? 'success' : 'secondary'; ?> ms-1"><?php echo $count_cust; ?></span>
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link <?php echo ($active_tab == 'paid_orders') ? 'active' : ''; ?>" id="paid-orders-tab" data-bs-toggle="tab" data-bs-target="#paid-orders-pane" type="button" role="tab">
                                                    <i class="fe fe-check-circle me-1"></i> Paid Orders <span class="badge bg-<?php echo ($count_paid > 0) ? 'success' : 'secondary'; ?> ms-1"><?php echo $count_paid; ?></span>
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link <?php echo ($active_tab == 'pending_orders') ? 'active' : ''; ?>" id="pending-orders-tab" data-bs-toggle="tab" data-bs-target="#pending-orders-pane" type="button" role="tab">
                                                    <i class="fe fe-clock me-1"></i> Pending Orders <span class="badge bg-<?php echo ($count_pend > 0) ? 'warning text-dark' : 'secondary'; ?> ms-1"><?php echo $count_pend; ?></span>
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link <?php echo ($active_tab == 'staff') ? 'active' : ''; ?>" id="staff-tab" data-bs-toggle="tab" data-bs-target="#staff-pane" type="button" role="tab">
                                                    <i class="fe fe-shield me-1"></i> Staff <span class="badge bg-<?php echo ($count_staff > 0) ? 'success' : 'secondary'; ?> ms-1"><?php echo $count_staff; ?></span>
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link <?php echo ($active_tab == 'vendors') ? 'active' : ''; ?>" id="vendors-tab" data-bs-toggle="tab" data-bs-target="#vendors-pane" type="button" role="tab">
                                                    <i class="fe fe-shopping-bag me-1"></i> Vendors <span class="badge bg-<?php echo ($count_vendor > 0) ? 'success' : 'secondary'; ?> ms-1"><?php echo $count_vendor; ?></span>
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link <?php echo ($active_tab == 'blogs') ? 'active' : ''; ?>" id="blogs-tab" data-bs-toggle="tab" data-bs-target="#blogs-pane" type="button" role="tab">
                                                    <i class="fe fe-book-open me-1"></i> Blogs <span class="badge bg-<?php echo ($count_blog > 0) ? 'success' : 'secondary'; ?> ms-1"><?php echo $count_blog; ?></span>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="searchTabContent">
                                            
                                            <!-- PRODUCTS TAB -->
                                            <div class="tab-pane fade <?php echo ($active_tab == 'products') ? 'show active' : ''; ?>" id="products-pane" role="tabpanel">
                                                <?php if ($count_prod > 0): ?>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered text-nowrap border-bottom file-datatable">
                                                        <thead>
                                                            <tr>
                                                                <th>S/N</th>
                                                                <th>Product Image</th>
                                                                <th>UIN</th>
                                                                <th>Product Name</th>
                                                                <th>Category</th>
                                                                <th>Cost Price</th>
                                                                <th>Selling Price</th>
                                                                <th>Quantity</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $sn=1; foreach($products as $p): ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo $sn++; ?></td>
                                                                <td class="text-center">
                                                                        <img src="../vendor/vendorupload/<?php echo htmlspecialchars($p['productimage']); ?>" style="height:50px; width:70px; object-fit:cover; border-radius:4px;">
                                                                </td>
                                                                <td><?php echo htmlspecialchars($p['uin']); ?></td>
                                                                <td><strong><?php echo htmlspecialchars($p['productname']); ?></strong></td>
                                                                <td><?php echo htmlspecialchars($p['category']); ?></td>
                                                                <td>&#8358;<?php echo number_format((float)$p['costprice'], 2); ?></td>
                                                                <td><strong>&#8358;<?php echo number_format((float)$p['sellingprice'], 2); ?></strong></td>
                                                                <td class="text-center"><?php echo (int)$p['quantity']; ?></td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown">Action</button>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="view-product?id=<?php echo urlencode($p['product_id']); ?>"><i class="fe fe-eye me-1"></i> View Details</a>
                                                                            <a class="dropdown-item" href="edit-product?id=<?php echo urlencode($p['product_id']); ?>"><i class="fe fe-edit me-1"></i> Edit Product</a>
                                                                            <a class="dropdown-item" href="edit-product-images?id=<?php echo urlencode($p['product_id']); ?>"><i class="fe fe-image me-1"></i> Manage Images</a>
                                                                            <a class="dropdown-item text-danger" href="delete-product?id=<?php echo urlencode($p['product_id']); ?>" onclick="return confirm('Delete this product?')"><i class="fe fe-trash me-1"></i> Delete Product</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <?php else: ?>
                                                    <p class="text-muted text-center py-4 mb-0"><i class="fe fe-info me-1"></i> No product records match your search criteria.</p>
                                                <?php endif; ?>
                                            </div>

                                            <!-- CUSTOMERS TAB -->
                                            <div class="tab-pane fade <?php echo ($active_tab == 'customers') ? 'show active' : ''; ?>" id="customers-pane" role="tabpanel">
                                                <?php if ($count_cust > 0): ?>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered text-nowrap border-bottom file-datatable">
                                                        <thead>
                                                            <tr>
                                                                <th>S/N</th>
                                                                <th>UIN</th>
                                                                <th>Full Name</th>
                                                                <th>Email</th>
                                                                <th>Phone</th>
                                                                <th>Address</th>
                                                                <th>Registered Date</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $sn=1; foreach($customers as $c): ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo $sn++; ?></td>
                                                                <td><?php echo htmlspecialchars($c['customer_uin']); ?></td>
                                                                <td><strong><?php echo htmlspecialchars($c['fullname']); ?></strong></td>
                                                                <td><?php echo htmlspecialchars($c['customer_email']); ?></td>
                                                                <td><?php echo htmlspecialchars($c['phone']); ?></td>
                                                                <td><?php echo htmlspecialchars($c['address']); ?></td>
                                                                <td><?php echo date('jS-F-Y', strtotime($c['date'])); ?></td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown">Action</button>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item text-danger" href="delete-customer?id=<?php echo urlencode($c['customer_id']); ?>" onclick="return confirm('Delete this customer record?')"><i class="fe fe-trash me-1"></i> Delete Customer</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <?php else: ?>
                                                    <p class="text-muted text-center py-4 mb-0"><i class="fe fe-info me-1"></i> No customer records match your search criteria.</p>
                                                <?php endif; ?>
                                            </div>

                                            <!-- PAID ORDERS TAB -->
                                            <div class="tab-pane fade <?php echo ($active_tab == 'paid_orders') ? 'show active' : ''; ?>" id="paid-orders-pane" role="tabpanel">
                                                <?php if ($count_paid > 0): ?>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered text-nowrap border-bottom file-datatable">
                                                        <thead>
                                                            <tr>
                                                                <th>S/N</th>
                                                                <th>Order / Invoice #</th>
                                                                <th>Date</th>
                                                                <th>Customer Name</th>
                                                                <th>Phone</th>
                                                                <th>Amount</th>
                                                                <th>Payment</th>
                                                                <th>Order Status</th>
                                                                <th>Courier Details</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $sn=1; foreach($paid_orders as $po): 
                                                                $ord_disp = !empty($po['order_id']) ? $po['order_id'] : $po['invoicenumber'];
                                                                $status_disp = !empty($po['order_status']) ? $po['order_status'] : 'Payment Confirmed';
                                                                $courier_info = !empty($po['courier_name']) ? htmlspecialchars($po['courier_name']) . ($po['tracking_number'] ? ' ('.$po['tracking_number'].')' : '') : 'N/A';
                                                            ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo $sn++; ?></td>
                                                                <td><strong><?php echo htmlspecialchars($ord_disp); ?></strong></td>
                                                                <td><?php echo date('jS-F-Y', strtotime($po['date'])); ?></td>
                                                                <td><?php echo htmlspecialchars($po['customername']); ?></td>
                                                                <td><?php echo htmlspecialchars($po['customer_phone']); ?></td>
                                                                <td><strong>&#8358;<?php echo number_format((float)$po['amount'], 2); ?></strong></td>
                                                                <td><span class="badge bg-success"><?php echo htmlspecialchars($po['paymentstatus']); ?></span></td>
                                                                <td><span class="badge bg-info"><?php echo htmlspecialchars($status_disp); ?></span></td>
                                                                <td><small><?php echo $courier_info; ?></small></td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown">Action</button>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="update-order-status?invoicenumber=<?php echo urlencode($po['invoicenumber']); ?>"><i class="fe fe-edit me-1"></i> Update Status</a>
                                                                            <a class="dropdown-item text-danger" href="delete-order?invoicenumber=<?php echo urlencode($po['invoicenumber']); ?>" onclick="return confirm('Delete this order record?')"><i class="fe fe-trash me-1"></i> Delete Order</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <?php else: ?>
                                                    <p class="text-muted text-center py-4 mb-0"><i class="fe fe-info me-1"></i> No paid order records match your search criteria.</p>
                                                <?php endif; ?>
                                            </div>

                                            <!-- PENDING ORDERS TAB -->
                                            <div class="tab-pane fade <?php echo ($active_tab == 'pending_orders') ? 'show active' : ''; ?>" id="pending-orders-pane" role="tabpanel">
                                                <?php if ($count_pend > 0): ?>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered text-nowrap border-bottom file-datatable">
                                                        <thead>
                                                            <tr>
                                                                <th>S/N</th>
                                                                <th>Invoice #</th>
                                                                <th>Date</th>
                                                                <th>Product</th>
                                                                <th>Quantity</th>
                                                                <th>Total Amount</th>
                                                                <th>Customer Name</th>
                                                                <th>Customer Contact</th>
                                                                <th>Payment Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $sn=1; foreach($pending_orders as $pdo): ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo $sn++; ?></td>
                                                                <td><strong><?php echo htmlspecialchars($pdo['invoicenumber']); ?></strong></td>
                                                                <td><?php echo date('jS-F-Y', strtotime($pdo['date'])); ?></td>
                                                                <td><strong><?php echo htmlspecialchars($pdo['productname']); ?></strong></td>
                                                                <td class="text-center"><?php echo (int)$pdo['quantity']; ?></td>
                                                                <td><strong>&#8358;<?php echo number_format((float)$pdo['amount'], 2); ?></strong></td>
                                                                <td><?php echo htmlspecialchars($pdo['customername']); ?></td>
                                                                <td>
                                                                    <small>
                                                                        <i class="fe fe-phone me-1"></i><?php echo htmlspecialchars($pdo['customer_phone']); ?><br>
                                                                        <i class="fe fe-mail me-1"></i><?php echo htmlspecialchars($pdo['customer_email']); ?>
                                                                    </small>
                                                                </td>
                                                                <td class="text-center"><span class="badge bg-warning text-dark"><i class="fe fe-clock me-1"></i><?php echo htmlspecialchars($pdo['paymentstatus']); ?></span></td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <?php else: ?>
                                                    <p class="text-muted text-center py-4 mb-0"><i class="fe fe-info me-1"></i> No pending checkout order records match your search criteria.</p>
                                                <?php endif; ?>
                                            </div>

                                            <!-- STAFF TAB -->
                                            <div class="tab-pane fade <?php echo ($active_tab == 'staff') ? 'show active' : ''; ?>" id="staff-pane" role="tabpanel">
                                                <?php if ($count_staff > 0): ?>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered text-nowrap border-bottom file-datatable">
                                                        <thead>
                                                            <tr>
                                                                <th>S/N</th>
                                                                <th>UIN</th>
                                                                <th>Full Name</th>
                                                                <th>Email</th>
                                                                <th>Phone</th>
                                                                <th>Role</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $sn=1; foreach($staff_list as $st): ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo $sn++; ?></td>
                                                                <td><?php echo htmlspecialchars($st['uin']); ?></td>
                                                                <td><strong><?php echo htmlspecialchars($st['fullname']); ?></strong></td>
                                                                <td><?php echo htmlspecialchars($st['email']); ?></td>
                                                                <td><?php echo htmlspecialchars($st['phone']); ?></td>
                                                                <td><span class="badge bg-primary-light text-primary"><?php echo htmlspecialchars($st['role']); ?></span></td>
                                                                <td><span class="badge bg-success-light text-success"><?php echo htmlspecialchars($st['status']); ?></span></td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown">Action</button>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="profile?id=<?php echo urlencode($st['id']); ?>"><i class="fe fe-user me-1"></i> View Profile</a>
                                                                            <a class="dropdown-item" href="edit-staff?id=<?php echo urlencode($st['id']); ?>"><i class="fe fe-edit me-1"></i> Edit Staff</a>
                                                                            <a class="dropdown-item" href="idcard?uin=<?php echo urlencode($st['uin']); ?>" target="_blank"><i class="fe fe-credit-card me-1"></i> ID Card</a>
                                                                            <a class="dropdown-item text-danger" href="delete-staff?id=<?php echo urlencode($st['id']); ?>" onclick="return confirm('Delete this staff user?')"><i class="fe fe-trash me-1"></i> Delete Staff</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <?php else: ?>
                                                    <p class="text-muted text-center py-4 mb-0"><i class="fe fe-info me-1"></i> No staff members match your search criteria.</p>
                                                <?php endif; ?>
                                            </div>

                                            <!-- VENDORS TAB -->
                                            <div class="tab-pane fade <?php echo ($active_tab == 'vendors') ? 'show active' : ''; ?>" id="vendors-pane" role="tabpanel">
                                                <?php if ($count_vendor > 0): ?>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered text-nowrap border-bottom file-datatable">
                                                        <thead>
                                                            <tr>
                                                                <th>S/N</th>
                                                                <th>Store Logo</th>
                                                                <th>Store Name</th>
                                                                <th>Owner Name</th>
                                                                <th>Email / Phone</th>
                                                                <th>Address</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $sn=1; foreach($vendors as $v): ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo $sn++; ?></td>
                                                                <td class="text-center">
                                                                    <?php if (!empty($v['logo']) && file_exists("../vendor/vendorupload/" . $v['logo'])): ?>
                                                                        <img src="../vendor/vendorupload/<?php echo htmlspecialchars($v['logo']); ?>" class="avatar avatar-md brround cover-image">
                                                                    <?php else: ?>
                                                                        <img src="assets/images/users/21.jpg" class="avatar avatar-md brround cover-image">
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <strong><?php echo htmlspecialchars($v['store_name']); ?></strong><br>
                                                                    <small class="text-muted">UIN: <?php echo htmlspecialchars($v['vendor_uin']); ?></small>
                                                                </td>
                                                                <td><?php echo htmlspecialchars($v['vendor_name']); ?></td>
                                                                <td>
                                                                    <small>
                                                                        <i class="fe fe-mail me-1"></i><?php echo htmlspecialchars($v['vendor_email']); ?><br>
                                                                        <i class="fe fe-phone me-1"></i><?php echo htmlspecialchars($v['vendor_phone']); ?>
                                                                    </small>
                                                                </td>
                                                                <td><?php echo htmlspecialchars($v['store_address']); ?></td>
                                                                <td>
                                                                    <?php if ($v['status'] === 'Active'): ?>
                                                                        <span class="badge bg-success-light text-success fw-bold">Active</span>
                                                                    <?php elseif ($v['status'] === 'Pending'): ?>
                                                                        <span class="badge bg-warning-light text-warning fw-bold">Pending Approval</span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-danger-light text-danger fw-bold"><?php echo htmlspecialchars($v['status']); ?></span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown">Action</button>
                                                                        <div class="dropdown-menu">
                                                                            <?php if ($v['status'] === 'Pending'): ?>
                                                                                <a class="dropdown-item text-success" href="accept?id=<?php echo urlencode($v['id']); ?>" onclick="return confirm('Accept vendor account?')"><i class="fe fe-check-circle me-1"></i> Accept Vendor</a>
                                                                                <a class="dropdown-item text-success" href="approve?id=<?php echo urlencode($v['id']); ?>" onclick="return confirm('Approve vendor account?')"><i class="fe fe-check me-1"></i> Approve Vendor</a>
                                                                                <a class="dropdown-item text-danger" href="reject?id=<?php echo urlencode($v['id']); ?>" onclick="return confirm('Reject vendor application?')"><i class="fe fe-x-circle me-1"></i> Reject Vendor</a>
                                                                            <?php elseif ($v['status'] === 'Active'): ?>
                                                                                <a class="dropdown-item text-warning" href="suspend?id=<?php echo urlencode($v['id']); ?>" onclick="return confirm('Suspend vendor account?')"><i class="fe fe-slash me-1"></i> Suspend Vendor</a>
                                                                            <?php elseif ($v['status'] === 'Suspended' || $v['status'] === 'Rejected'): ?>
                                                                                <a class="dropdown-item text-success" href="activate?id=<?php echo urlencode($v['id']); ?>" onclick="return confirm('Activate vendor account?')"><i class="fe fe-check me-1"></i> Activate Vendor</a>
                                                                            <?php endif; ?>
                                                                            <a class="dropdown-item text-info" href="../vendor-store?vendor_uin=<?php echo urlencode($v['vendor_uin']); ?>" target="_blank"><i class="fe fe-eye me-1"></i> View Store</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <?php else: ?>
                                                    <p class="text-muted text-center py-4 mb-0"><i class="fe fe-info me-1"></i> No vendor store records match your search criteria.</p>
                                                <?php endif; ?>
                                            </div>

                                            <!-- BLOGS TAB -->
                                            <div class="tab-pane fade <?php echo ($active_tab == 'blogs') ? 'show active' : ''; ?>" id="blogs-pane" role="tabpanel">
                                                <?php if ($count_blog > 0): ?>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered text-nowrap border-bottom file-datatable">
                                                        <thead>
                                                            <tr>
                                                                <th>S/N</th>
                                                                <th>Cover Image</th>
                                                                <th>Headline</th>
                                                                <th>Category</th>
                                                                <th>Author</th>
                                                                <th>Published Date</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $sn=1; foreach($blogs as $bl): ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo $sn++; ?></td>
                                                                <td class="text-center">
                                                                    <?php if (!empty($bl['blogimage']) && file_exists("blogupload/" . $bl['blogimage'])): ?>
                                                                        <img src="blogupload/<?php echo htmlspecialchars($bl['blogimage']); ?>" style="height:50px; width:70px; object-fit:cover; border-radius:4px;">
                                                                    <?php else: ?>
                                                                        <img src="assets/images/media/1.jpg" style="height:50px; width:70px; object-fit:cover; border-radius:4px;">
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td><strong><?php echo htmlspecialchars($bl['headline']); ?></strong></td>
                                                                <td><?php echo htmlspecialchars($bl['category']); ?></td>
                                                                <td><?php echo htmlspecialchars($bl['staff']); ?></td>
                                                                <td><?php echo date('jS-F-Y', strtotime($bl['date'])); ?></td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown">Action</button>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="edit-blog?id=<?php echo urlencode($bl['id']); ?>"><i class="fe fe-edit me-1"></i> Edit Blog</a>
                                                                            <a class="dropdown-item" href="edit-blog-images?id=<?php echo urlencode($bl['id']); ?>"><i class="fe fe-image me-1"></i> Edit Images</a>
                                                                            <a class="dropdown-item text-danger" href="delete-blog?id=<?php echo urlencode($bl['id']); ?>" onclick="return confirm('Delete this blog post?')"><i class="fe fe-trash me-1"></i> Delete Blog</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <?php else: ?>
                                                    <p class="text-muted text-center py-4 mb-0"><i class="fe fe-info me-1"></i> No blog posts match your search criteria.</p>
                                                <?php endif; ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <!-- DEFAULT STATE WHEN OPENED WITHOUT SEARCH -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card text-center py-5">
                                    <div class="card-body">
                                        <i class="fe fe-search fs-50 text-primary d-block mb-3"></i>
                                        <h3 class="fw-bold text-dark">Enter Search Keywords Above</h3>
                                        <p class="text-muted fs-15">You can search for anything across the admin system including <strong>Products, Customers, Orders (Paid & Pending), Staff Members, Vendor Stores, and Blog Posts</strong>.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div>
                    <!-- CONTAINER CLOSED -->

                </div>
            </div>
            <!--app-content closed-->
        </div>

        <?php include("footer.php"); ?>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- JQUERY JS -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- DATA TABLE JS-->
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
    <script src="assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>

    <!-- PERFECT SCROLLBAR JS-->
    <script src="assets/plugins/p-scroll/perfect-scrollbar.js"></script>
    <script src="assets/plugins/p-scroll/pscroll.js"></script>

    <!-- SIDE-MENU JS -->
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>
    <script src="assets/plugins/sidebar/sidebar.js"></script>

    <!-- Color Theme js -->
    <script src="assets/js/themeColors.js"></script>
    <script src="assets/js/sticky.js"></script>
    <script src="assets/js/custom.js"></script>

    <script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            $('.file-datatable').DataTable({
                responsive: true,
                language: {
                    searchPlaceholder: 'Filter results...',
                    sSearch: ''
                }
            });
        }
    });
    </script>
</body>
</html>