<?php
include('session-check.php');
include('db_conn.php');
$product_id = 1;
$sql = "SELECT * FROM invoiceorder WHERE product_id='$product_id'";
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
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/brand/favicon.png">

    <!-- TITLE -->
    <title>DEE MART – ORDERS </title>

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

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Order List</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Order List</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="javascript:history.back()">< Go back</a></li>
                                </ol>
                            </div>

                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">All Confirmed Sales & Orders</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                                <thead>
                                                    <tr>
                                                        <th class="border-bottom-0">S/N</th>
                                                        <th class="border-bottom-0">Order ID</th>
                                                        <th class="border-bottom-0">Invoice Number</th>
                                                        <th class="border-bottom-0">Date</th>
                                                        <th class="border-bottom-0">Customer Name</th>
                                                        <th class="border-bottom-0">Phone</th>
                                                        <th class="border-bottom-0">Amount</th>
                                                        <th class="border-bottom-0">Payment</th>
                                                        <th class="border-bottom-0">Order Status</th>
                                                        <th class="border-bottom-0">Courier Details</th>
                                                        <th class="border-bottom-0">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php
                                                    include("db_conn.php");
                                                    $sql = "SELECT * FROM invoicesales ORDER BY id DESC";
                                                    $result = mysqli_query($conn, $sql);
                                                    if ($result && mysqli_num_rows($result) > 0) {
                                                        $count = 1;
                                                        while ($row = mysqli_fetch_array($result)) {
                                                            $order_id_disp = !empty($row['order_id']) ? $row['order_id'] : $row['invoicenumber'];
                                                            $current_ord_status = !empty($row['order_status']) ? $row['order_status'] : 'Payment Confirmed';
                                                            $courier_disp = !empty($row['courier_name']) ? htmlspecialchars($row['courier_name']) . ($row['tracking_number'] ? ' ('.$row['tracking_number'].')' : '') : 'N/A';
                                                    ?>
                                                            <tr>
                                                                <td style="text-align:center;"><?php echo $count++; ?></td>
                                                                <td><strong><?php echo htmlspecialchars($order_id_disp); ?></strong></td>
                                                                <td><?php echo htmlspecialchars($row['invoicenumber']); ?></td>
                                                                <td><?php echo date('jS-F-Y', strtotime($row['date'])); ?></td>
                                                                <td><?php echo htmlspecialchars($row['customername']); ?></td>
                                                                <td><?php echo htmlspecialchars($row['customer_phone']); ?></td>
                                                                <td style="text-align:center;">&#8358;<?php echo number_format($row['amount'], 2); ?></td>
                                                                <td style="text-align:center;"><span class="badge bg-success"><?php echo htmlspecialchars($row['paymentstatus']); ?></span></td>
                                                                <td style="text-align:center;"><span class="badge bg-info"><?php echo htmlspecialchars($current_ord_status); ?></span></td>
                                                                <td><small><?php echo $courier_disp; ?></small></td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button type="button" class="btn btn-info dropdown-toggle btn-sm" data-bs-toggle="dropdown">
                                                                            <i class="fe fe-action me-1"></i>Action
                                                                        </button>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="update-order-status.php?invoicenumber=<?php echo urlencode($row['invoicenumber']); ?>">Update Status</a>
                                                                            <a class="dropdown-item" href="delete-order.php?invoicenumber=<?php echo urlencode($row['invoicenumber']); ?>" onclick="return confirm('Are you sure to delete this order record?')">Delete Order</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                    <?php
                                                        }
                                                    }
                                                    ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Row -->
                       
                    </div>
                    <!-- CONTAINER CLOSED -->

                </div>
            </div>
            <!--app-content closed-->
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

	<!-- TypeHead js -->
	<script src="assets/plugins/bootstrap5-typehead/autocomplete.js"></script>
    <script src="assets/js/typehead.js"></script>

    <!-- INTERNAL SELECT2 JS -->
    <script src="assets/plugins/select2/select2.full.min.js"></script>

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