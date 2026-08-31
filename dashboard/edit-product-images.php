<?php
include("session-check.php"); 
include("db_conn.php");

if (!isset($_REQUEST['product_id'])) {
    header("Location: product");
    exit();
}

$product_id = intval($_REQUEST['product_id']);

$query = "SELECT * FROM product_table WHERE product_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "<script>alert('Product not found.'); window.location.href='product';</script>";
    exit();
}

$uin = $product['uin'];

// HANDLE ACTIONS: DELETE IMAGE
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['img_id'])) {
    $img_id = intval($_GET['img_id']);
    $get_img = mysqli_query($conn, "SELECT * FROM product_images WHERE id = '$img_id' AND uin = '$uin'");
    if (mysqli_num_rows($get_img) > 0) {
        $img_data = mysqli_fetch_assoc($get_img);
        $file_name = $img_data['product_image'];
        
        // Delete row from product_images
        mysqli_query($conn, "DELETE FROM product_images WHERE id = '$img_id'");

        // If file is cover image in product_table, update product_table
        if ($product['productimage'] == $file_name) {
            $next_img_query = mysqli_query($conn, "SELECT product_image FROM product_images WHERE uin = '$uin' ORDER BY sort_order ASC LIMIT 1");
            $new_cover = "";
            if ($next_img_query && mysqli_num_rows($next_img_query) > 0) {
                $next_img = mysqli_fetch_assoc($next_img_query);
                $new_cover = $next_img['product_image'];
            }
            mysqli_query($conn, "UPDATE product_table SET productimage = '$new_cover' WHERE product_id = '$product_id'");
        }

        // Delete physical file if exists
        $file_path = "productupload/" . $file_name;
        if (file_exists($file_path) && !empty($file_name)) {
            @unlink($file_path);
        }

        if (class_exists('CacheManager')) {
            CacheManager::flush();
        }

        echo "<script>alert('Image deleted successfully.'); window.location.href='edit-product-images?product_id=$product_id';</script>";
        exit();
    }
}

// HANDLE ACTIONS: SET AS COVER IMAGE
if (isset($_GET['action']) && $_GET['action'] == 'set_cover' && isset($_GET['img_name'])) {
    $cover_name = mysqli_real_escape_string($conn, $_GET['img_name']);
    mysqli_query($conn, "UPDATE product_table SET productimage = '$cover_name' WHERE product_id = '$product_id'");
    if (class_exists('CacheManager')) {
        CacheManager::flush();
    }
    echo "<script>alert('Cover image updated.'); window.location.href='edit-product-images?product_id=$product_id';</script>";
    exit();
}

// HANDLE UPLOAD NEW IMAGES
if (isset($_POST['upload_images'])) {
    $files = isset($_FILES['productimages']) ? $_FILES['productimages'] : null;
    if ($files && is_array($files['name'])) {
        // Get current max sort order
        $max_sort_q = mysqli_query($conn, "SELECT MAX(sort_order) as max_sort FROM product_images WHERE uin = '$uin'");
        $max_row = mysqli_fetch_assoc($max_sort_q);
        $current_sort = intval($max_row['max_sort']);

        $total_files = count($files['name']);
        $uploaded_count = 0;
        $first_new_img = "";

        for ($i = 0; $i < $total_files; $i++) {
            $filename = basename($files['name'][$i]);
            if (!empty($filename)) {
                $target = "productupload/" . $filename;
                if (move_uploaded_file($files['tmp_name'][$i], $target)) {
                    $current_sort++;
                    $filename_escaped = mysqli_real_escape_string($conn, $filename);
                    mysqli_query($conn, "INSERT INTO product_images (uin, product_image, sort_order) VALUES ('$uin', '$filename_escaped', '$current_sort')");
                    $uploaded_count++;
                    if (empty($first_new_img)) {
                        $first_new_img = $filename;
                    }
                }
            }
        }

        // If product cover image is empty, set first uploaded image as cover
        if (empty($product['productimage']) && !empty($first_new_img)) {
            mysqli_query($conn, "UPDATE product_table SET productimage = '$first_new_img' WHERE product_id = '$product_id'");
        }

        if (class_exists('CacheManager')) {
            CacheManager::flush();
        }

        echo "<script>alert('$uploaded_count image(s) uploaded successfully.'); window.location.href='edit-product-images?product_id=$product_id';</script>";
        exit();
    }
}

// Fetch all images for product
$images_query = mysqli_query($conn, "SELECT * FROM product_images WHERE uin = '$uin' ORDER BY sort_order ASC");
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DEE MART – Manage Product Images</title>

    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
</head>

<body class="app sidebar-mini ltr light-mode">
    <?php include("menu.php"); ?>

    <div class="page">
        <div class="page-main">
            <div class="main-content app-content mt-0">
                <div class="side-app">
                    <div class="main-container container-fluid">

                        <div class="page-header">
                            <h1 class="page-title">Manage Product Images</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="product.php">Products</a></li>
                                    <li class="breadcrumb-item"><a href="edit-product.php?product_id=<?php echo $product_id; ?>">Edit Details</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Manage Images</li>
                                </ol>
                            </div>
                        </div>

                        <!-- PRODUCT SUMMARY CARD -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h3 class="card-title">Images for: <strong><?php echo htmlspecialchars($product['productname']); ?></strong> (SKU: <?php echo htmlspecialchars($product['uin']); ?>)</h3>
                                        <a href="edit-product.php?product_id=<?php echo $product_id; ?>" class="btn btn-secondary btn-sm">
                                            <i class="fa fa-arrow-left me-1"></i> Edit Product Details
                                        </a>
                                    </div>

                                    <!-- UPLOAD NEW IMAGES FORM -->
                                    <div class="card-body border-bottom">
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="row align-items-center">
                                                <label class="col-md-3 form-label fw-bold">Upload Additional Image(s):</label>
                                                <div class="col-md-6">
                                                    <input type="file" name="productimages[]" class="form-control" accept=".jpg, .png, .jpeg, .jfif, .webp" multiple required>
                                                </div>
                                                <div class="col-md-3 mt-2 mt-md-0">
                                                    <button type="submit" name="upload_images" class="btn btn-success btn-block">
                                                        <i class="fa fa-upload me-1"></i> Upload Images
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- EXISTING IMAGES GRID -->
                                    <div class="card-body">
                                        <h4 class="mb-4">Current Uploaded Images</h4>
                                        <div class="row">
                                            <?php
                                            if (mysqli_num_rows($images_query) > 0) {
                                                while ($img = mysqli_fetch_assoc($images_query)) {
                                                    $is_cover = ($product['productimage'] == $img['product_image']);
                                            ?>
                                                    <div class="col-md-3 col-sm-6 mb-4">
                                                        <div class="card border p-2 text-center h-100 <?php echo $is_cover ? 'border-primary' : ''; ?>" style="position: relative;">
                                                            <?php if ($is_cover) { ?>
                                                                <span class="badge bg-primary text-white mb-2" style="position: absolute; top: 10px; left: 10px;">Primary Cover</span>
                                                            <?php } ?>
                                                            <div style="height: 180px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #f8f9fa; border-radius: 4px;">
                                                                <img src="productupload/<?php echo htmlspecialchars($img['product_image']); ?>" alt="Product Image" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                                            </div>
                                                            <div class="mt-3">
                                                                <p class="text-muted small text-truncate" title="<?php echo htmlspecialchars($img['product_image']); ?>">
                                                                    <?php echo htmlspecialchars($img['product_image']); ?>
                                                                </p>
                                                                <div class="btn-group btn-group-sm w-100" role="group">
                                                                    <?php if (!$is_cover) { ?>
                                                                        <a href="edit-product-images.php?product_id=<?php echo $product_id; ?>&action=set_cover&img_name=<?php echo urlencode($img['product_image']); ?>" class="btn btn-outline-primary" title="Make Primary Cover">
                                                                            Make Cover
                                                                        </a>
                                                                    <?php } ?>
                                                                    <a href="edit-product-images.php?product_id=<?php echo $product_id; ?>&action=delete&img_id=<?php echo $img['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Delete this image?');" title="Delete Image">
                                                                        <i class="fa fa-trash"></i> Delete
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            } else {
                                                if (!empty($product['productimage'])) {
                                            ?>
                                                    <div class="col-md-3 col-sm-6 mb-4">
                                                        <div class="card border border-primary p-2 text-center h-100">
                                                            <span class="badge bg-primary text-white mb-2">Main Image</span>
                                                            <div style="height: 180px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #f8f9fa;">
                                                                <img src="productupload/<?php echo htmlspecialchars($product['productimage']); ?>" alt="Product Image" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                                            </div>
                                                            <div class="mt-3">
                                                                <p class="text-muted small text-truncate"><?php echo htmlspecialchars($product['productimage']); ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php
                                                } else {
                                                    echo "<div class='col-12'><div class='alert alert-warning'>No images uploaded for this product yet. Use the upload box above to add images.</div></div>";
                                                }
                                            }
                                            ?>
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

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>
    <script src="assets/plugins/sidebar/sidebar.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
