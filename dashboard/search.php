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

$product_id = 1;
$sql = "SELECT * FROM product_table WHERE product_id='$product_id'";
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
    <title><?php echo $business_name; ?> – PRODUCT SEARCH</title>

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
                            <h1 class="page-title">Product Search Result</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Product Search</a></li>
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
                                        <h3 class="card-title">Search Result</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                                <thead>
                                                    <tr>
                                                        <th class="border-bottom-0">Product ID</th>
                                                        <th class="border-bottom-0">UIN</th>
                                                        <th class="border-bottom-0">Product Name</th>
                                                        <th class="border-bottom-0">Date</th>
                                                        <th class="border-bottom-0">Cost Price</th>
                                                        <th class="border-bottom-0">Selling Price</th>
                                                        <th class="border-bottom-0">Quantity</th>
                                                        <th class="border-bottom-0">Low Level</th>
                                                        <th class="border-bottom-0" style="width:90px;">Product Image</th>
                                                        <th class="border-bottom-0">Profit</th>
                                                        <th class="border-bottom-0">Category</th>
                                                        <th class="border-bottom-0">Description</th>
                                                        <th class="border-bottom-0">Staff</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                     <?php
                                                include("db_conn.php");
                                                if($_POST){
                                                    $count = 1;

                                                     if(isset($_POST["check"])){
                                                         $startdate = mysqli_real_escape_string($conn, $_POST["startdate"]);
                                                         $enddate   = mysqli_real_escape_string($conn, $_POST["enddate"]);
                                                         $sql = "SELECT * FROM product_table WHERE DATE(`date`) BETWEEN '$startdate' AND '$enddate'";

                                                     } else if (isset($_REQUEST["search"])) {
                                                         $search = mysqli_real_escape_string($conn, $_REQUEST["search"]);
                                                         $sql = "SELECT * FROM product_table WHERE productname LIKE '%$search%' OR category='$search' OR `date`='$search'";
                                                     }

                                                    $result = mysqli_query($conn, $sql);

                                                    if(mysqli_num_rows($result) > 0){
                                                        while($row = mysqli_fetch_array($result)){ ?>
                                                            <tr>
                                                                <td><?php echo $count++?></td>
                                                                <td><?php echo $row['uin']?></td>
                                                                <td><?php echo $row['productname']?></td>
                                                                <td><?php echo $row['date']?></td>
                                                                <td>&#8358;<?php echo number_format($row['costprice'], 2); ?></td>
                                                                <td>&#8358;<?php echo number_format($row['sellingprice'], 2); ?></td>
                                                                <td><?php echo $row['quantity']?></td>
                                                                <td><?php echo $row['lowlevel']?></td>
                                                                <td><img src="productupload/<?php echo $row['productimage']?>" style="height:70px; width:150px; object-fit:cover; border-radius:5px;"></td>
                                                                <td>&#8358;<?php echo number_format($row['profit'], 2); ?></td>
                                                                <td><?php echo $row['category']?></td>
                                                                <td><?php echo $row['description']?></td>
                                                                <td><?php echo $row['staff']?></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    } else {
                                                        if(isset($_POST["check"])){
                                                            echo "<script>alert('Oops! No products found for that date range.'); window.location.href='index'</script>";
                                                        } else {
                                                            echo "<script>alert('Oops! Product not found.'); window.location.href='index'</script>";
                                                        }
                                                        exit();
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