<?php
include('session-check.php');
include('db_conn.php');
$id = 1;
$sql = "SELECT * FROM blog WHERE id='$id'";
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
    <title>DEE MART – Blog Post</title>

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
                            <h1 class="page-title">Blog Post</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Blog Post</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="javascript:history.back()"> > Go back</a></li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <div class="row">
                            <div class="col-xl-12">

                                <form method="post" enctype="multipart/form-data">
                                    <?php
                                        include("db_conn.php");
                                        date_default_timezone_set("Africa/Lagos");
                                        $rand= rand(1000,9999);
                                        $today= date("dmyhis");
                                        $date= date('jS-F-Y');
                                        $UIN= "BLOG" . $today . $rand;
                                        error_reporting(E_ALL);
                                        if(isset($_REQUEST["post"])){
                                             $headline = mysqli_real_escape_string($conn, trim($_REQUEST["headline"]));
                                             $category = mysqli_real_escape_string($conn, trim($_REQUEST["category"]));
                                             $content = mysqli_real_escape_string($conn, trim($_REQUEST["content"]));
                                             $photocredit = mysqli_real_escape_string($conn, trim($_REQUEST["photocredit"]));
                                             $staff = mysqli_real_escape_string($conn, $session_role);

                                            // FILE UPLOAD - MULTIPLE IMAGES
                                            $files = isset($_FILES['blogimages']) ? $_FILES['blogimages'] : (isset($_FILES['blogimage']) ? $_FILES['blogimage'] : null);
                                            $uploaded_images = array();

                                            if ($files && is_array($files['name'])) {
                                                $total_files = count($files['name']);
                                                for ($i = 0; $i < $total_files; $i++) {
                                                    $filename = basename($files['name'][$i]);
                                                    if (!empty($filename)) {
                                                        $target = "blogupload/" . $filename;
                                                        if (move_uploaded_file($files['tmp_name'][$i], $target)) {
                                                            $uploaded_images[] = $filename;
                                                        }
                                                    }
                                                }
                                            } else if ($files && !empty($files['name'])) {
                                                $filename = basename($files['name']);
                                                $target = "blogupload/" . $filename;
                                                if (move_uploaded_file($files['tmp_name'], $target)) {
                                                    $uploaded_images[] = $filename;
                                                }
                                            }

                                            $primary_image = !empty($uploaded_images) ? $uploaded_images[0] : "";

                                            // CHECKING FOR DUPLICATE BLOGS
                                            $check = mysqli_query($conn, "SELECT * FROM blog WHERE headline = '$headline'");
                                            $checkrows = mysqli_num_rows($check);
                                        
                                            if($checkrows > 0){
                                                echo "<script>alert('Blog headline already exists.')</script>";
                                            } else {
                                                // INSERTING VALUES INTO blog TABLE
                                                $sql="INSERT INTO blog (`date`, staff, headline, content, blogimage, uin, category, photocredit) VALUES ('$date', '$staff', '$headline', '$content', '$primary_image', '$UIN', '$category', '$photocredit')";
                                                mysqli_query($conn, $sql) or die(mysqli_error($conn));
                                                
                                                if(mysqli_affected_rows($conn) == 1){
                                                    // INSERT ALL IMAGES INTO blog_images TABLE (IF TABLE EXISTS)
                                                    foreach ($uploaded_images as $sort_index => $img_name) {
                                                        $sort_order = $sort_index + 1;
                                                        $img_escaped = mysqli_real_escape_string($conn, $img_name);
                                                        $sql_img = "INSERT INTO blog_images (uin, blog_image, sort_order) VALUES ('$UIN', '$img_escaped', '$sort_order')";
                                                        @mysqli_query($conn, $sql_img);
                                                    }

                                                    echo "<script>alert('Blog successfully added.');
                                                        window.location.href='blog.php'</script>";
                                                } else {
                                                    echo "<script>alert('Error inserting record into database.');</script>";
                                                }
                                            }
                                        }
                                        
                                        

                                        ?>
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Add New Blog</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Post Headline :</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="headline" placeholder="Input blog's headline">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Category :</label>
                                            <div class="">
                                                <select name="category" class="form-control form-select select2" data-bs-placeholder="Select Category">
                                                    <option value="">Select Blog Category</option>
                                                    <option value="Technology">Technology</option>
                                                    <option value="Politics">Politics</option>
                                                    <option value="Travel">Travel</option>
                                                    <option value="Food">Food</option>
                                                    <option value="Fashion">Fashion</option>
                                                    <option value="Sports">Sports</option>
                                            </select>
                                            </div>
                                        </div>

                                        <input type="hidden" class="form-control" name="uin" value="<?php echo $UIN;?>">
                                        <input type="hidden" class="form-control" name="date" value="<?php echo $date;?>">

                                        <!-- Row -->
                                        <div class="row">
                                            <label class="col-md-3 form-label mb-4">Blog Content :</label>
                                            <div class="mb-4">
                                                <textarea class="content" name="content"></textarea>
                                            </div>
                                        </div>
                                        <!--End Row-->

                                        <div class="form-group mb-0">
                                            <label class="col-md-3 form-label mb-4">Blog Image :</label>
                                            <input id="demo" type="file" name="blogimages[]" accept=".jpg, .png, image/jpeg, image/png, image/webp" multiple>
                                        </div>

                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Photo Credit :</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="photocredit">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" name="post" class="btn btn-primary btn-block" onclick="return confirm('Are you sure to post blog?')">Post</button>
                                    </div>
                                    </form>

                                </div>
                            </div>

                           

                        </div>
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

    <!-- INPUT MASK JS -->
    <script src="assets/plugins/input-mask/jquery.mask.min.js"></script>

    <!-- INTERNAL WYSIWYG Editor JS -->
    <script src="assets/plugins/wysiwyag/jquery.richtext.js"></script>
    <script src="assets/plugins/wysiwyag/wysiwyag.js"></script>

    <!-- INTERNAL SELECT2 JS -->
    <script src="assets/plugins/select2/select2.full.min.js"></script>
    <script src="assets/js/select2.js"></script>

	<!-- TypeHead js -->
	<script src="assets/plugins/bootstrap5-typehead/autocomplete.js"></script>
    <script src="assets/js/typehead.js"></script>

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