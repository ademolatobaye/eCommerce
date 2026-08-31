<?php
include_once("session-check.php");

if (!isset($_GET['id'])) {
    header("Location: products");
    exit();
}

$product_id = intval($_GET['id']);

// Fetch product owned by vendor
$stmt = mysqli_prepare($conn, "SELECT * FROM product_table WHERE product_id = ? AND vendor_uin = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "is", $product_id, $session_vendor_uin);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($res);

if (!$product) {
    echo "<script>alert('Product not found or access denied.'); window.location.href='products';</script>";
    exit();
}

$cat_query = mysqli_query($conn, "SELECT * FROM category ORDER BY categoryname ASC");

if (isset($_POST['update_product'])) {
    $productname  = mysqli_real_escape_string($conn, trim($_POST['productname']));
    $costprice    = floatval($_POST['costprice']);
    $sellingprice = floatval($_POST['sellingprice']);
    $quantity     = intval($_POST['quantity']);
    $lowlevel     = isset($_POST['lowlevel']) ? intval($_POST['lowlevel']) : 5;
    $profit       = $sellingprice - $costprice;
    $category     = mysqli_real_escape_string($conn, trim($_POST['category']));
    $description  = mysqli_real_escape_string($conn, trim($_POST['description']));

    $update_stmt = mysqli_prepare($conn, "UPDATE product_table SET productname = ?, costprice = ?, sellingprice = ?, quantity = ?, lowlevel = ?, profit = ?, category = ?, `description` = ?
     WHERE product_id = ? AND vendor_uin = ?");
    mysqli_stmt_bind_param($update_stmt, "sddiidssis", $productname, $costprice, $sellingprice, $quantity, $lowlevel, $profit, $category, $description, $product_id, $session_vendor_uin);

    if (mysqli_stmt_execute($update_stmt)) {
        if (class_exists('CacheManager')) {
            CacheManager::flush();
        }
        echo "<script>alert('Product details updated successfully!');
         window.location.href='products';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating product details.');</script>";
    }
}
?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/brand/favicon.png">
    <title>Edit Product - <?php echo htmlspecialchars($session_vendor_storename); ?></title>

    <link id="style" href="../dashboard/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../dashboard/assets/css/style.css" rel="stylesheet">
    <link href="../dashboard/assets/css/plugins.css" rel="stylesheet">
    <link href="../dashboard/assets/css/icons.css" rel="stylesheet">
</head>
<body class="app sidebar-mini ltr light-mode">

    <div class="page">
        <div class="page-main">
            <?php 
            include("menu.php");
             ?>

            <div class="main-content app-content mt-0">
                <div class="side-app">
                    <div class="main-container container-fluid">

                        <!-- PAGE HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Edit Product Details</h1>
                            <div>
                                <a href="products" class="btn btn-secondary"><i class="fe fe-arrow-left me-1"></i> Back to Products</a>
                            </div>
                        </div>

                        <!-- EDIT FORM CARD -->
                        <div class="row">
                            <div class="col-lg-10 offset-lg-1">
                                <div class="card">
                                    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                                        <h3 class="card-title">Update Product Details</h3>
                                    </div>
                                    <div class="card-body">
                                        <form method="post">
                                            <div class="row">
                                                <div class="col-md-8 mb-3">
                                                    <label class="form-label font-weight-bold">Product Name *</label>
                                                    <input type="text" name="productname" class="form-control" value="<?php echo htmlspecialchars($product['productname']); ?>" required>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label font-weight-bold">Category *</label>
                                                    <select name="category" class="form-select" required>
                                                        <?php if ($cat_query): ?>
                                                            <?php while ($c = mysqli_fetch_assoc($cat_query)): ?>
                                                                <option value="<?php echo htmlspecialchars($c['categoryname']); ?>" <?php if ($c['categoryname'] == $product['category']) echo 'selected'; ?>>
                                                                    <?php echo htmlspecialchars($c['categoryname']); ?>
                                                                </option>
                                                            <?php endwhile; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label font-weight-bold">Cost Price (₦) *</label>
                                                    <input type="number" step="0.01" name="costprice" class="form-control" value="<?php echo htmlspecialchars($product['costprice']); ?>" required>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label font-weight-bold">Selling Price (₦) *</label>
                                                    <input type="number" step="0.01" name="sellingprice" class="form-control" value="<?php echo htmlspecialchars($product['sellingprice']); ?>" required>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label font-weight-bold">Stock Quantity *</label>
                                                    <input type="number" name="quantity" class="form-control" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label font-weight-bold">Low Level Alert</label>
                                                    <input type="number" name="lowlevel" class="form-control" min="0" value="<?php echo htmlspecialchars($product['lowlevel']); ?>">
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <label class="form-label font-weight-bold">Description</label>
                                                <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
                                            </div>

                                            <div class="text-end">
                                                <button type="submit" name="update_product" class="btn btn-primary btn-lg" onclick="return confirm('Save changes?')">
                                                    <i class="fe fe-check me-1"></i> Save Changes
                                                </button>
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

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- JQUERY JS -->
    <script src="../dashboard/assets/js/jquery.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="../dashboard/assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="../dashboard/assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="../dashboard/assets/plugins/p-scroll/perfect-scrollbar.js"></script>
    <script src="../dashboard/assets/plugins/p-scroll/pscroll.js"></script>
    <script src="../dashboard/assets/plugins/p-scroll/pscroll-1.js"></script>

    <!-- SIDE-MENU JS -->
    <script src="../dashboard/assets/plugins/sidemenu/sidemenu.js"></script>

    <!-- SIDEBAR JS -->
    <script src="../dashboard/assets/plugins/sidebar/sidebar.js"></script>

    <!-- Color Theme js -->
    <script src="../dashboard/assets/js/themeColors.js"></script>

    <!-- Sticky js -->
    <script src="../dashboard/assets/js/sticky.js"></script>

    <!-- CUSTOM JS -->
    <script src="../dashboard/assets/js/custom.js"></script>
</body>
</html>
