<?php
include("session-check.php"); 
include("db_conn.php");

if (!isset($_REQUEST['product_id'])) {
    header("Location: product.php");
    exit();
}

$product_id = $_REQUEST['product_id'];

$query = "SELECT * FROM product_table WHERE product_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);


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
    <title>DEE MART – Edit Product</title>

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
                            <h1 class="page-title">Edit Product</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Edit Product</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="javascript:history.back()"> > Go back</a></li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- ROW-1 OPEN -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <form method="post">
                                        <?php
                                        include("db_conn.php");
                                        date_default_timezone_set("Africa/Lagos");
                                        error_reporting(E_ALL);
                                        if(isset($_REQUEST["submit"])){
                                             $productname = mysqli_real_escape_string($conn, trim($_REQUEST["productname"]));
                                             $costprice = floatval($_REQUEST["costprice"]);
                                             $sellingprice = floatval($_REQUEST["sellingprice"]);
                                             $quantity = intval($_REQUEST["quantity"]);
                                             $profit = $sellingprice - $costprice;
                                             $category = isset($_REQUEST["category"]) ? mysqli_real_escape_string($conn, trim($_REQUEST["category"])) : mysqli_real_escape_string($conn, $product['category']);
                                             $description = mysqli_real_escape_string($conn, trim($_REQUEST["description"]));
                                             $staff = isset($session_role) ? mysqli_real_escape_string($conn, $session_role) : 'Admin';

                                            $sql = "UPDATE product_table SET productname='$productname', costprice='$costprice', sellingprice='$sellingprice', quantity='$quantity', profit='$profit', category='$category', `description`='$description', staff='$staff' WHERE product_id='$product_id'";
                                            if(mysqli_query($conn, $sql)){
                                                if (class_exists('CacheManager')) {
                                                    CacheManager::flush();
                                                }
                                                echo "<script>alert('Product details successfully updated.');
                                                window.location.href='product.php';</script>";
                                            } else {
                                                echo "<script>alert('Error updating details.');</script>";
                                            }
                                        }
                                        ?>

                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div class="card-title">Edit Product Details</div>
                                        <a href="edit-product-images.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-info btn-sm">
                                            <i class="fa fa-image me-1"></i> Edit Product Images
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Product Name :</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($product['productname']); ?>" name="productname" required>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Cost Price :</label>
                                            <div class="col-md-9">
                                                <input type="number" step="any" class="form-control" name="costprice" value="<?php echo htmlspecialchars($product['costprice']); ?>" required>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Selling Price :</label>
                                            <div class="col-md-9">
                                                <input type="number" step="any" class="form-control" name="sellingprice" value="<?php echo htmlspecialchars($product['sellingprice']); ?>" required>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Quantity :</label>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Category :</label>
                                            <div class="col-md-9">
                                                <select class="form-control form-select select2" data-bs-placeholder="Select Category" name="category">
                                                    <option value="">Select Category</option>
                                                    <?php
                                                    $categories = array("Food", "Drinks", "Wears", "Shoes", "Wristwatch", "Gadgets", "Electronics", "Fashion", "Deodorant", "Home Decor", "Furniture");
                                                    foreach($categories as $cat) {
                                                        $selected = ($product['category'] == $cat) ? "selected" : "";
                                                        echo "<option value=\"$cat\" $selected>$cat</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Row -->
                                        <div class="row">
                                            <label class="col-md-3 form-label mb-4">Product Description :</label>
                                            <div class="col-md-9 mb-4">
                                                <textarea class="content" name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
                                            </div>
                                        </div>
                                        <!--End Row-->
                                    </div>
                                    <div class="card-footer">
                                        <!--Row-->
                                        <div class="row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-9">
                                                <button type="submit" name="submit" class="btn btn-primary btn-block" onclick="return confirm('Are you sure to update product?')">Update Product</button>
                                                
                                            </div>
                                        </div>
                                        <!--End Row-->
                                    </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <!-- /ROW-1 CLOSED -->
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