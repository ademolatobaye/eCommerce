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

if (!isset($_REQUEST['product_id']) && !isset($_REQUEST['id'])) {
    header("Location: product");
    exit();
}

$product_id = isset($_REQUEST['product_id']) ? intval($_REQUEST['product_id']) : intval($_REQUEST['id']);

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

// Helper function to resolve image path for display
function getAdminImgPath($img_name) {
    if (!empty($img_name) && file_exists("../vendor/vendorupload/" . $img_name)) {
        return "../vendor/vendorupload/" . htmlspecialchars($img_name);
    }
    if (!empty($img_name) && file_exists("productupload/" . $img_name)) {
        return "productupload/" . htmlspecialchars($img_name);
    }
    return "../vendor/vendorupload/" . htmlspecialchars($img_name);
}

// HANDLE ACTIONS: DELETE SECONDARY IMAGE
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['img_id'])) {
    $img_id = intval($_GET['img_id']);
    $get_img = mysqli_query($conn, "SELECT * FROM product_images WHERE id = '$img_id' AND uin = '$uin'");
    if ($get_img && mysqli_num_rows($get_img) > 0) {
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
        $paths_to_check = array("../vendor/vendorupload/" . $file_name, "productupload/" . $file_name);
        foreach ($paths_to_check as $fp) {
            if (file_exists($fp) && !empty($file_name)) {
                @unlink($fp);
            }
        }

        if (class_exists('CacheManager')) {
            CacheManager::flush();
        }

        echo "<script>alert('Image deleted successfully.'); window.location.href='edit-product-images?product_id=$product_id';</script>";
        exit();
    }
}

// HANDLE ACTIONS: SET AS PRIMARY COVER IMAGE
if (isset($_GET['action']) && $_GET['action'] == 'set_cover' && isset($_GET['img_name'])) {
    $cover_name = mysqli_real_escape_string($conn, $_GET['img_name']);
    mysqli_query($conn, "UPDATE product_table SET productimage = '$cover_name' WHERE product_id = '$product_id'");
    if (class_exists('CacheManager')) {
        CacheManager::flush();
    }
    echo "<script>alert('Primary cover image updated successfully.'); window.location.href='edit-product-images?product_id=$product_id';</script>";
    exit();
}

// HANDLE UPLOAD NEW IMAGES
if (isset($_POST['upload_images'])) {
    $files = isset($_FILES['productimages']) ? $_FILES['productimages'] : null;
    if ($files && is_array($files['name'])) {
        $uploadDir = "../vendor/vendorupload/";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Get current max sort order
        $max_sort_q = mysqli_query($conn, "SELECT MAX(sort_order) as max_sort FROM product_images WHERE uin = '$uin'");
        $max_row = mysqli_fetch_assoc($max_sort_q);
        $current_sort = intval($max_row['max_sort']);

        $total_files = count($files['name']);
        $uploaded_count = 0;
        $first_new_img = "";

        for ($i = 0; $i < $total_files; $i++) {
            $orig_name = $files['name'][$i];
            if (!empty($orig_name)) {
                $ext = pathinfo($orig_name, PATHINFO_EXTENSION);
                $filename = "prod_" . time() . "_" . $i . "_" . rand(1000, 9999) . "." . $ext;
                $target = $uploadDir . $filename;

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
            $first_escaped = mysqli_real_escape_string($conn, $first_new_img);
            mysqli_query($conn, "UPDATE product_table SET productimage = '$first_escaped' WHERE product_id = '$product_id'");
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
    <title><?php echo $business_name; ?> – Manage Product Images</title>

    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">

    <style>
        .drop-zone {
            border: 2px dashed #4b0082;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            background: #fdfaff;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .drop-zone:hover, .drop-zone.dragover {
            background: #f0e6ff;
            border-color: #310056;
        }
        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }
        .preview-card {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid #ddd;
            background: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }
        .preview-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .preview-card .remove-btn {
            position: absolute;
            top: 4px;
            right: 4px;
            background: rgba(220, 53, 69, 0.9);
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .img-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .img-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1) !important;
        }
        .img-card .zoom-trigger {
            cursor: pointer;
        }

        /* DARK MODE PURPLE TEXT & BUTTON OVERRIDES */
        body.dark-mode .text-primary,
        body.dark-mode .drop-zone h5,
        body.dark-mode .drop-zone i,
        body.dark-mode .drop-zone p,
        body.dark-mode h4.text-primary,
        body.dark-mode h3.card-title strong,
        body.dark-theme .text-primary,
        body.dark-theme .drop-zone h5,
        body.dark-theme .drop-zone i,
        body.dark-theme .drop-zone p,
        body.dark-theme h4.text-primary,
        .dark-mode .text-primary,
        .dark-mode .drop-zone h5,
        .dark-mode .drop-zone i,
        .dark-mode .drop-zone p,
        .dark-mode h4.text-primary {
            color: #ffffff !important;
        }

        body.dark-mode .btn-outline-primary,
        body.dark-theme .btn-outline-primary,
        .dark-mode .btn-outline-primary {
            color: #ffffff !important;
            border-color: #a855f7 !important;
            background-color: transparent !important;
        }

        body.dark-mode .btn-outline-primary:hover,
        body.dark-theme .btn-outline-primary:hover,
        .dark-mode .btn-outline-primary:hover {
            color: #ffffff !important;
            border-color: #8b5cf6 !important;
            background-color: #8b5cf6 !important;
        }

        body.dark-mode .drop-zone,
        body.dark-theme .drop-zone,
        .dark-mode .drop-zone {
            background: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.4) !important;
        }
        body.dark-mode .drop-zone:hover,
        body.dark-theme .drop-zone:hover,
        .dark-mode .drop-zone:hover {
            background: rgba(255, 255, 255, 0.12) !important;
            border-color: #ffffff !important;
        }
    </style>
</head>

<body class="app sidebar-mini ltr light-mode">
    <?php include("menu.php"); ?>

    <div class="page">
        <div class="page-main">
            <div class="main-content app-content mt-0">
                <div class="side-app">
                    <div class="main-container container-fluid">

                        <!-- PAGE HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Manage Product Images</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="product">Products</a></li>
                                    <li class="breadcrumb-item"><a href="edit-product?product_id=<?php echo $product_id; ?>">Edit Details</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Manage Images</li>
                                </ol>
                            </div>
                        </div>

                        <!-- PRODUCT SUMMARY CARD -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                                        <h3 class="card-title">Product: <strong><?php echo htmlspecialchars($product['productname']); ?></strong> (SKU: <?php echo htmlspecialchars($product['uin']); ?>)</h3>
                                        <a href="edit-product?product_id=<?php echo $product_id; ?>" class="btn btn-secondary btn-sm">
                                            <i class="fa fa-arrow-left me-1"></i> Edit Product Details
                                        </a>
                                    </div>

                                    <!-- INTERACTIVE DRAG & DROP UPLOAD FORM -->
                                    <div class="card-body border-bottom">
                                        <h4 class="card-title mb-3"><i class="fe fe-upload-cloud me-2 text-primary"></i>Upload Additional Image(s)</h4>
                                        <form method="post" enctype="multipart/form-data" id="uploadForm">
                                            <div class="drop-zone" id="dropZone">
                                                <i class="fe fe-upload-cloud fs-40 text-primary mb-2"></i>
                                                <h5 class="fw-bold mb-1">Drag & Drop product images here or click to browse</h5>
                                                <p class="text-muted small mb-0">Supports JPG, PNG, WEBP. You can select multiple files at once.</p>
                                                <input type="file" name="productimages[]" id="fileInput" class="d-none" accept=".jpg, .png, .jpeg, .jfif, .webp, image/*" multiple required>
                                            </div>

                                            <div class="preview-container" id="previewContainer"></div>

                                            <div class="mt-3 text-end" id="uploadSubmitRow" style="display: none;">
                                                <button type="submit" name="upload_images" class="btn btn-success btn-lg" onclick="return confirm('Upload selected image(s)?')">
                                                    <i class="fe fe-check-circle me-1"></i> Upload Selected Images
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- PRIMARY COVER IMAGE SECTION -->
                                    <div class="card-body border-bottom">
                                        <h4 class="mb-3 font-weight-bold text-primary"><i class="fe fe-star me-1"></i> Primary Cover Image (Main Product Display)</h4>
                                        <div class="row">
                                            <div class="col-md-3 col-sm-6 mb-3">
                                                <div class="card border border-primary p-2 text-center h-100 img-card" style="position: relative; background: #f0f4ff;">
                                                    <span class="badge bg-primary text-white mb-2" style="position: absolute; top: 10px; left: 10px; z-index: 2;">Primary Cover</span>
                                                    <?php
                                                    $primary_src = getAdminImgPath($product['productimage']);
                                                    ?>
                                                    <div class="zoom-trigger" style="height: 180px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #fff; border-radius: 4px;" <?php if (!empty($product['productimage'])) echo 'onclick="openLightbox(\'' . htmlspecialchars($primary_src) . '\', \'Primary Cover Image\')"'; ?>>
                                                        <?php if (!empty($product['productimage'])): ?>
                                                            <img src="<?php echo htmlspecialchars($primary_src); ?>" alt="Primary Cover" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                                        <?php else: ?>
                                                            <span class="text-muted">No primary cover image set</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="mt-3">
                                                        <p class="text-muted small text-truncate mb-0"><?php echo htmlspecialchars($product['productimage']); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SECONDARY GALLERY IMAGES GRID -->
                                    <div class="card-body">
                                        <h4 class="mb-3 font-weight-bold"><i class="fe fe-image me-1"></i> Secondary Gallery Images</h4>
                                        <div class="row">
                                            <?php if ($images_query && mysqli_num_rows($images_query) > 0): ?>
                                                <?php while ($img = mysqli_fetch_assoc($images_query)): ?>
                                                    <?php
                                                    $is_cover = ($product['productimage'] == $img['product_image']);
                                                    $img_src = getAdminImgPath($img['product_image']);
                                                    ?>
                                                    <div class="col-md-3 col-sm-6 mb-4">
                                                        <div class="card border p-2 text-center h-100 img-card <?php echo $is_cover ? 'border-primary' : ''; ?>" style="position: relative;">
                                                            <?php if ($is_cover): ?>
                                                                <span class="badge bg-primary text-white mb-2" style="position: absolute; top: 10px; left: 10px; z-index: 2;">Current Primary</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary text-white mb-2" style="position: absolute; top: 10px; left: 10px; z-index: 2;">Secondary</span>
                                                            <?php endif; ?>
                                                            <div class="zoom-trigger" style="height: 180px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #f8f9fa; border-radius: 4px;" onclick="openLightbox('<?php echo htmlspecialchars($img_src); ?>', '<?php echo htmlspecialchars($img['product_image']); ?>')">
                                                                <img src="<?php echo htmlspecialchars($img_src); ?>" alt="Secondary Image" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                                            </div>
                                                            <div class="mt-3">
                                                                <p class="text-muted small text-truncate mb-2" title="<?php echo htmlspecialchars($img['product_image']); ?>">
                                                                    <?php echo htmlspecialchars($img['product_image']); ?>
                                                                </p>
                                                                <div class="btn-group btn-group-sm w-100">
                                                                    <?php if (!$is_cover): ?>
                                                                        <a href="edit-product-images?product_id=<?php echo $product_id; ?>&action=set_cover&img_name=<?php echo urlencode($img['product_image']); ?>" class="btn btn-outline-primary" title="Make Primary Cover">
                                                                            Make Cover
                                                                        </a>
                                                                    <?php endif; ?>
                                                                    <a href="edit-product-images?product_id=<?php echo $product_id; ?>&action=delete&img_id=<?php echo $img['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure to delete this secondary image?');" title="Delete Image">
                                                                        <i class="fe fe-trash"></i> Delete
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <div class="col-12">
                                                    <div class="alert alert-info">No secondary gallery images uploaded yet for this product. Use the drag & drop box above to add gallery images.</div>
                                                </div>
                                            <?php endif; ?>
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

    <!-- LIGHTBOX PREVIEW MODAL -->
    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lightboxTitle">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0" style="background: #000;">
                    <img src="" id="lightboxImage" class="img-fluid" style="max-height: 80vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>

    <!-- JQUERY JS -->
    <script src="assets/js/jquery.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- PERFECT SCROLLBAR JS-->
    <script src="assets/plugins/p-scroll/perfect-scrollbar.js"></script>
    <script src="assets/plugins/p-scroll/pscroll.js"></script>

    <!-- SIDE-MENU JS -->
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>

    <!-- SIDEBAR JS -->
    <script src="assets/plugins/sidebar/sidebar.js"></script>

    <!-- COLOR THEME JS -->
    <script src="assets/js/themeColors.js"></script>

    <!-- STICKY JS -->
    <script src="assets/js/sticky.js"></script>

    <!-- CUSTOM JS -->
    <script src="assets/js/custom.js"></script>

    <!-- DYNAMIC UPLOADER & LIGHTBOX JS LOGIC -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('fileInput');
            const previewContainer = document.getElementById('previewContainer');
            const uploadSubmitRow = document.getElementById('uploadSubmitRow');

            if (dropZone && fileInput) {
                dropZone.addEventListener('click', () => fileInput.click());

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropZone.addEventListener(eventName, (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        dropZone.classList.add('dragover');
                    }, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        dropZone.classList.remove('dragover');
                    }, false);
                });

                dropZone.addEventListener('drop', (e) => {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    fileInput.files = files;
                    handleFiles(files);
                });

                fileInput.addEventListener('change', function() {
                    handleFiles(this.files);
                });

                function handleFiles(files) {
                    previewContainer.innerHTML = '';
                    if (files.length > 0) {
                        uploadSubmitRow.style.display = 'block';
                        Array.from(files).forEach((file, index) => {
                            if (file.type.startsWith('image/')) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const card = document.createElement('div');
                                    card.className = 'preview-card';
                                    card.innerHTML = `
                                        <img src="${e.target.result}" alt="Preview">
                                        <span class="remove-btn" title="Remove" data-index="${index}">&times;</span>
                                    `;
                                    previewContainer.appendChild(card);
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    } else {
                        uploadSubmitRow.style.display = 'none';
                    }
                }

                previewContainer.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-btn')) {
                        const indexToRemove = parseInt(e.target.getAttribute('data-index'));
                        const dt = new DataTransfer();
                        Array.from(fileInput.files).forEach((file, i) => {
                            if (i !== indexToRemove) {
                                dt.items.add(file);
                            }
                        });
                        fileInput.files = dt.files;
                        handleFiles(fileInput.files);
                    }
                });
            }
        });

        function openLightbox(imageSrc, title) {
            document.getElementById('lightboxImage').src = imageSrc;
            document.getElementById('lightboxTitle').textContent = title || 'Image Preview';
            const modal = new bootstrap.Modal(document.getElementById('lightboxModal'));
            modal.show();
        }
    </script>
</body>
</html>

