<?php
include('session-check.php');
include('db_conn.php');

// Handle product approval/rejection by Admin
if (isset($_GET['action']) && isset($_GET['product_id'])) {
    $action_p = $_GET['action'];
    $pid = intval($_GET['product_id']);
    
    $status_col = "approval_status";

    if ($action_p === 'approve_product') {
        @mysqli_query($conn, "UPDATE product_table SET `$status_col` = 'Approved' WHERE product_id = '$pid'");
        echo "<script>alert('Product APPROVED successfully!'); window.location.href='product';</script>";
        exit();
    } else if ($action_p === 'reject_product') {
        @mysqli_query($conn, "UPDATE product_table SET `$status_col` = 'Rejected' WHERE product_id = '$pid'");
        echo "<script>alert('Product REJECTED.'); window.location.href='product';</script>";
        exit();
    }
}
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
    <title>DEE MART – PRODUCTS</title>

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
                            <h1 class="page-title">Product List</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Product List</a></li>
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
                                        <h3 class="card-title">All Products</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                                <thead>
                                                    <tr>
                                                        <th class="border-bottom-0">S/N</th>
                                                        <th class="border-bottom-0">Product ID</th>
                                                        <th class="border-bottom-0">Product Name</th>
                                                        <th class="border-bottom-0">Date</th>
                                                        <!-- <th class="border-bottom-0">Cost Price</th> -->
                                                        <th class="border-bottom-0">Selling Price</th>
                                                        <th class="border-bottom-0">Quantity</th>
                                                        <th class="border-bottom-0">Low Level</th>
                                                        <th class="border-bottom-0" style="width:90px;">Product Image</th>
                                                        <!-- <th class="border-bottom-0">Profit</th> -->
                                                        <th class="border-bottom-0">Category</th>
                                                        <th class="border-bottom-0">Description</th>
                                                        <th class="border-bottom-0">Vendor</th>
                                                        <th class="border-bottom-0">Approval Status</th>
                                                        <th class="border-bottom-0">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php
                                                include("db_conn.php");
                                                $sql = "SELECT * FROM product_table ORDER BY product_id DESC";
                                                $result = mysqli_query($conn, $sql);
                                                if ($result && mysqli_num_rows($result) > 0) {
                                                $count = 1;
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $vendor_name = !empty($row['vendor_storename']) ? $row['vendor_storename'] . " (Vendor)" : 'DEE MART';
                                                    $p_stat      = !empty($row['approval_status']) ? $row['approval_status'] : (!empty($row['product_status']) ? $row['product_status'] : 'Approved');
                                                    if (empty($p_stat)) { $p_stat = 'Approved'; }
                                                ?>
                                                    <tr>
                                                        <td style="text-align:center;"><?php echo $count++?></td>
                                                        <td><?php echo $row['uin']?></td>
                                                        <td style="text-align:center;"><?php echo $row['productname']?></td>
                                                        <td><?php echo date('jS-F-Y', strtotime($row['date']) )?></td>
                                                        <!-- <td style="text-align:center;">&#8358;<?php //echo number_format($row['costprice'], 2); ?></td> -->
                                                        <td style="text-align:center;">&#8358;<?php echo number_format($row['sellingprice'], 2); ?></td>
                                                        <td style="text-align:center;"><?php echo $row['quantity']?></td>
                                                        <td><?php echo $row['lowlevel']?></td>
                                                         <td><img src="../vendor/vendorupload/<?php echo $row['productimage']?>" style="height:70px; width:150px; object-fit:cover; border-radius:5px;"></td>
                                                        <!-- <td style="text-align:center;">&#8358;<?php //echo number_format($row['profit'], 2); ?></td> -->
                                                        <td style="text-align:center;"><?php echo $row['category']?></td>
                                                        <td><?php echo $row['description']?></td>
                                                        <td><strong><?php echo htmlspecialchars($vendor_name); ?></strong></td>
                                                        <td>
                                                            <?php if ($p_stat === 'Approved'): ?>
                                                                <span class="badge bg-success">Approved & Live</span>
                                                            <?php elseif ($p_stat === 'Pending'): ?>
                                                                <span class="badge bg-warning">Pending Approval</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-danger"><?php echo htmlspecialchars($p_stat); ?></span>
                                                            <?php endif; ?>
                                                        </td>
                                                         <td>
                                                             <div class="dropdown">
                                                        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                                <i class="fe fe-action me-2"></i>Action
                                                            </button>
                                                        <div class="dropdown-menu">
                                                            <?php if ($p_stat === 'Pending' || $p_stat === 'Rejected'): ?>
                                                                <a class="dropdown-item text-success" href="product?action=approve_product&product_id=<?php echo $row['product_id']?>" onclick="return confirm('Approve this product for storefront display?')"><i class="fa fa-check me-1"></i> Approve Product</a>
                                                            <?php endif; ?>
                                                            <?php if ($p_stat === 'Pending' || $p_stat === 'Approved'): ?>
                                                                <a class="dropdown-item text-warning" href="product?action=reject_product&product_id=<?php echo $row['product_id']?>" onclick="return confirm('Reject this product?')"><i class="fa fa-times me-1"></i> Reject Product</a>
                                                            <?php endif; ?>
                                                            <a class="dropdown-item" href="delete-product?product_id=<?php echo $row['product_id']?>" onclick="return confirm('Are you sure to delete this product?')">Delete Product</a>

                                                            <a class="dropdown-item" href="view-product?product_id=<?php echo $row['product_id']?>">View Product</a>

                                                            <!-- <a class="dropdown-item" href="edit-product.php?product_id=<?php echo $row['product_id']?>"><i class="fa fa-edit me-1"></i> Edit Product</a>

                                                            <a class="dropdown-item" href="edit-product-images.php?product_id=<?php echo $row['product_id']?>"><i class="fa fa-image me-1"></i> Edit Images</a> -->
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