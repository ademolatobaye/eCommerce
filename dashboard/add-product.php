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
    <title><?php echo $business_name; ?> – STAFF DASHBOARD</title>

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

    <style>
        .drop-zone {
            border: 2px dashed #4b0082;
            border-radius: 8px;
            padding: 25px;
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
            width: 110px;
            height: 110px;
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
    </style>
</head>

<body class="app sidebar-mini ltr light-mode">
    <?php
    include("menu.php");
    ?>

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
                            <h1 class="page-title">New Product</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">New Product</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="javascript:history.back()"> > Go back</a></li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- ROW-1 OPEN -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">

                                    <form method="post" enctype="multipart/form-data">
                                        <?php
                                        include("db_conn.php");
                                        date_default_timezone_set("Africa/Lagos");
                                        $date= date("Y-m-d");
                                        function generateCustomerID($productname, $business_name, $length = 4){
                                        $productname = strtoupper(substr(preg_replace('/\s+/', '', $productname), 0, 4));
                                        $businessname = strtoupper(substr(preg_replace('/\s+/', '', $business_name), 0, 5));
                                        $crandom_part = strtoupper(substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length));
                                        return $businessname . $productname . $crandom_part;
                                    }
                                        error_reporting(E_ALL);
                                        if(isset($_REQUEST["submit"])){
                                             $productname = mysqli_real_escape_string($conn, trim($_REQUEST["productname"]));
                                             $costprice = floatval($_REQUEST["costprice"]);
                                             $sellingprice = floatval($_REQUEST["sellingprice"]);
                                             $quantity = intval($_REQUEST["quantity"]);
                                             $lowlevel = isset($_REQUEST["lowlevel"]) ? intval($_REQUEST["lowlevel"]) : 5;
                                             $profit = $sellingprice - $costprice;
                                             $category = mysqli_real_escape_string($conn, trim($_REQUEST["category"]));
                                             $description = mysqli_real_escape_string($conn, trim($_REQUEST["description"]));
                                             $staff = mysqli_real_escape_string($conn, $session_role);
                                             $product_uin = mysqli_real_escape_string($conn, generateCustomerID($productname, $business_name));

                                            // FILE UPLOAD - MULTIPLE IMAGES
                                            $files = isset($_FILES['productimages']) ? $_FILES['productimages'] : (isset($_FILES['productimage']) ? $_FILES['productimage'] : null);
                                            $uploaded_images = array();

                                            if ($files && is_array($files['name'])) {
                                                $total_files = count($files['name']);
                                                for ($i = 0; $i < $total_files; $i++) {
                                                    $filename = basename($files['name'][$i]);
                                                    if (!empty($filename)) {
                                                        $target = "../vendor/vendorupload/" . $filename;
                                                        if (move_uploaded_file($files['tmp_name'][$i], $target)) {
                                                            $uploaded_images[] = $filename;
                                                        }
                                                    }
                                                }
                                            } else if ($files && !empty($files['name'])) {
                                                $filename = basename($files['name']);
                                                $target = "../vendor/vendorupload/" . $filename;
                                                if (move_uploaded_file($files['tmp_name'], $target)) {
                                                    $uploaded_images[] = $filename;
                                                }
                                            }

                                            $primary_image = !empty($uploaded_images) ? $uploaded_images[0] : "";

                                            // CHECKING FOR DUPLICATE PRODUCTS
                                            $check = mysqli_query($conn, "SELECT * FROM product_table WHERE productname = '$productname'");
                                            $checkrows = mysqli_num_rows($check);
                                        
                                            if($checkrows > 0){
                                                echo "<script>alert('Product with this name already exists.')</script>";
                                            } else {
                                                // INSERTING VALUES INTO product_table
                                                $sql="INSERT INTO product_table (uin, productname, `date`, costprice, sellingprice, quantity, lowlevel, productimage, profit, category, `description`, vendor_uin, approval_status)
                                                 VALUES ('$product_uin', '$productname', '$date', '$costprice', '$sellingprice', '$quantity', '$lowlevel', '$primary_image', '$profit', '$category', '$description','$session_uin', 'Approved')";
                                                mysqli_query($conn, $sql) or die(mysqli_error($conn));
                                                
                                                if(mysqli_affected_rows($conn) == 1){
                                                    // INSERT SECONDARY IMAGES INTO product_images TABLE
                                                    if (count($uploaded_images) > 1) {
                                                        for ($i = 1; $i < count($uploaded_images); $i++) {
                                                            $sort_order = $i;
                                                            $img_escaped = mysqli_real_escape_string($conn, $uploaded_images[$i]);
                                                            $sql_img = "INSERT INTO product_images (uin, product_image, sort_order) VALUES ('$product_uin', '$img_escaped', '$sort_order')";
                                                            mysqli_query($conn, $sql_img);
                                                        }
                                                    }

                                                     // Flush product catalog caches
                                                     CacheManager::flush();

                                                     echo "<script>alert('Product successfully added.');
                                                         window.location.href='product'</script>";
                                                } else {
                                                    echo "<script>alert('Error inserting record.');</script>";
                                                }
                                            }
                                        }
                                        
                                        

                                        ?>

                                    <div class="card-header">
                                        <div class="card-title">Add New Product</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-4">

                                        <input type="hidden" class="form-control" name="date" value="<?php echo $date;?>">

                                            <label class="col-md-3 form-label">Product Name :</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" placeholder="Input product's name" name="productname">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Cost Price :</label>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control" name="costprice" placeholder="Input product's cost price">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Selling Price :</label>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control" name="sellingprice" placeholder="Input product's selling price">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Quantity :</label>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control" name="quantity" placeholder="Input product's quantity">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Low Level Stock Alert :</label>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control" name="lowlevel" placeholder="Input low level stock threshold (e.g. 5)" value="5">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-9">
                                                <input type="hidden" class="form-control" name="profit" placeholder="Input product's profit" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Category :</label>
                                            <div class="col-md-9">
                                                <select class="form-control form-select select2" data-bs-placeholder="Select Category" name="category" required>
                                                    <option value="">Select Category</option>
                                                    <?php
                                                    $cat_query = mysqli_query($conn, "SELECT * FROM category ORDER BY categoryname ASC");
                                                    if ($cat_query && mysqli_num_rows($cat_query) > 0) {
                                                        while ($c = mysqli_fetch_assoc($cat_query)) {
                                                            echo '<option value="' . htmlspecialchars($c['categoryname']) . '">' . htmlspecialchars($c['categoryname']) . '</option>';
                                                        }
                                                    } else {
                                                        $default_cats = array("Food", "Drinks", "Wears", "Shoes", "Wristwatch", "Gadgets", "Electronics", "Fashion", "Deodorant", "Home Decor", "Furniture");
                                                        foreach ($default_cats as $cat) {
                                                            echo '<option value="' . $cat . '">' . $cat . '</option>';
                                                        }
                                                    }
                                                    ?>
                                            </select>
                                            </div>
                                        </div>

                                        <!-- Row -->
                                        <div class="row">
                                            <label class="col-md-3 form-label mb-4">Product Description :</label>
                                            <div class="col-md-9 mb-4">
                                                <textarea class="content" name="description" placeholder="Write a description..."></textarea>
                                            </div>
                                        </div>
                                        <!--End Row-->

                                        <!--Row-->
                                        <div class="row">
                                            <label class="col-md-3 form-label mb-4">Product Upload :</label>
                                            <div class="col-md-9">
                                                
                                                <div class="drop-zone" id="dropZone">
                                                    <i class="fe fe-upload-cloud fs-35 text-primary mb-2"></i>
                                                    <h5 class="fw-bold mb-1">Drag & Drop product images here or click to browse</h5>
                                                    <p class="text-muted small mb-0">Select multiple image files (JPG, PNG, WEBP). First image will be set as primary cover.</p>
                                                    <input type="file" name="productimages[]" id="fileInput" class="d-none" accept=".jpg, .png, .jpeg, .jfif, .webp, image/jpeg, image/png, image/webp" multiple required>
                                                </div>
                                                <div class="preview-container" id="previewContainer"></div>
                                            </div>
                                        </div>
                                        <!--End Row-->
                                    </div>
                                    <div class="card-footer">
                                        <!--Row-->
                                        <div class="row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-9">
                                                <button type="submit" name="submit" class="btn btn-primary btn-block" onclick="return confirm('Add product?')">Add Product</button>
                                                
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

    <!-- DYNAMIC DRAG & DROP UPLOADER LOGIC -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('fileInput');
            const previewContainer = document.getElementById('previewContainer');

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
    </script>
</body>

</html>