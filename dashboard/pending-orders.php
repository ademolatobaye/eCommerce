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
    header("Location: ../management/");
    exit();
}

$stmt = mysqli_prepare($conn, "SELECT * FROM invoiceorder WHERE (vendor_uin = ? OR vendor_uin IS NULL OR vendor_uin = '') AND paymentstatus = 'Pending' ORDER BY product_id DESC");
mysqli_stmt_bind_param($stmt, "s", $session_uin);
mysqli_stmt_execute($stmt);
$orders = mysqli_stmt_get_result($stmt);
?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/brand/favicon.png">
    <title><?php echo htmlspecialchars($business_name); ?> – Unpaid / Pending Orders</title>

    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="assets/switcher/demo.css" rel="stylesheet">
</head>
<body class="app sidebar-mini ltr light-mode">

    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src="assets/images/loader.svg" class="loader-img" alt="Loader">
    </div>

    <div class="page">
        <div class="page-main">
            <?php include("menu.php"); ?>

            <div class="main-content app-content mt-0">
                <div class="side-app">
                    <div class="main-container container-fluid">

                        <!-- PAGE HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Unpaid / Pending Checkout Orders</h1>
                            <div>
                                <a href="orders" class="btn btn-primary me-2"><i class="fe fe-check-circle me-1"></i> View Paid Orders</a>
                                <a href="index" class="btn btn-secondary"><i class="fe fe-arrow-left me-1"></i> Dashboard</a>
                            </div>
                        </div>

                        <!-- ORDERS TABLE CARD -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header border-bottom-0">
                                        <h3 class="card-title">Pending Order Lines</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                                <thead>
                                                    <tr>
                                                        <th>S/N</th>
                                                        <th>Invoice #</th>
                                                        <th>Date</th>
                                                        <th>Product</th>
                                                        <th>Quantity</th>
                                                        <th>Total Amount</th>
                                                        <th>Profit</th>
                                                        <th>Customer ID</th>
                                                        <th>Customer Name</th>
                                                        <th>Customer Phone / Email</th>
                                                        <th>Payment Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $count = 1;
                                                    ?>
                                                    <?php if ($orders && mysqli_num_rows($orders) > 0): ?>
                                                        <?php while ($ord = mysqli_fetch_assoc($orders)): ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo $count++; ?></td>
                                                                <td><strong><?php echo htmlspecialchars($ord['invoicenumber']); ?></strong></td>
                                                                <td><?php echo date('jS-F-Y', strtotime($ord['date'])); ?></td>
                                                                <td><strong><?php echo htmlspecialchars($ord['productname']); ?></strong></td>
                                                                <td class="text-center"><?php echo (int)$ord['quantity']; ?></td>
                                                                <td><strong>&#8358;<?php echo number_format((float)$ord['amount'], 2); ?></strong></td>
                                                                <td>&#8358;<?php echo number_format((float)$ord['profit'], 2); ?></td>
                                                                <td><?php echo htmlspecialchars($ord['customer_uin']); ?></td>
                                                                <td><?php echo htmlspecialchars($ord['customername']); ?></td>
                                                                <td>
                                                                    <small>
                                                                        <i class="fe fe-phone me-1"></i><?php echo htmlspecialchars($ord['customer_phone']); ?><br>
                                                                        <i class="fe fe-mail me-1"></i><?php echo htmlspecialchars($ord['customer_email']); ?>
                                                                    </small>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-warning text-dark"><i class="fe fe-clock me-1"></i><?php echo htmlspecialchars($ord['paymentstatus']); ?></span>
                                                                </td>
                                                            </tr>
                                                        <?php endwhile; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="11" class="text-center py-5 text-muted">
                                                                <i class="fe fe-shopping-bag fs-40 d-block mb-2 text-muted"></i>
                                                                No unpaid or pending orders currently found.
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <?php include("footer.php"); ?>
    </div>

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
    <script src="assets/plugins/datatable/js/jszip.min.js"></script>
    <script src="assets/plugins/datatable/pdfmake/pdfmake.min.js"></script>
    <script src="assets/plugins/datatable/pdfmake/vfs_fonts.js"></script>
    <script src="assets/plugins/datatable/js/buttons.html5.min.js"></script>
    <script src="assets/plugins/datatable/js/buttons.print.min.js"></script>
    <script src="assets/plugins/datatable/js/buttons.colVis.min.js"></script>
    <script src="assets/plugins/datatable/dataTables.responsive.min.js"></script>
    <script src="assets/plugins/datatable/responsive.bootstrap5.min.js"></script>
    <script src="assets/js/table-data.js"></script>

    <!-- PERFECT SCROLLBAR JS-->
    <script src="assets/plugins/p-scroll/perfect-scrollbar.js"></script>
    <script src="assets/plugins/p-scroll/pscroll.js"></script>

    <!-- SIDE-MENU JS -->
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>
    <script src="assets/plugins/sidebar/sidebar.js"></script>
    <script src="assets/js/themeColors.js"></script>
    <script src="assets/js/sticky.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
