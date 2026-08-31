<?php
include("session-check.php"); 
include("db_conn.php");

if (!isset($_REQUEST['product_id'])) {
    header("Location: product");
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
    <title>DEE MART – VIEW PRODUCT</title>

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
                            <h1 class="page-title">Product Details</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Product Details</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="javascript:history.back()"> > Go back</a></li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- ROW-1 OPEN -->
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row row-sm">
                                            <div class="col-xl-5 col-lg-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-xl-12">                                          
                                                                       <div class="product-carousel">
                                                             <div id="Slider" class="carousel slide border" data-bs-ride="false">
                                                                 <div class="carousel-inner">
                                                                     <?php
                                                                     $product_uin = mysqli_real_escape_string($conn, $product['uin']);
                                                                     $v_img_q = mysqli_query($conn, "SELECT * FROM product_images WHERE uin = '$product_uin' ORDER BY sort_order ASC");
                                                                     $v_imgs = array();
                                                                     if ($v_img_q && mysqli_num_rows($v_img_q) > 0) {
                                                                         while ($row = mysqli_fetch_assoc($v_img_q)) {
                                                                             $v_imgs[] = $row['product_image'];
                                                                         }
                                                                     } else if (!empty($product['productimage'])) {
                                                                         $v_imgs[] = $product['productimage'];
                                                                     }

                                                                     foreach ($v_imgs as $idx => $v_img_name) {
                                                                         $active_class = ($idx === 0) ? 'active' : '';
                                                                     ?>
                                                                         <div class="carousel-item <?php echo $active_class; ?>">
                                                                             <img src="productupload/<?php echo htmlspecialchars($v_img_name);?>" alt="img" class="img-fluid mx-auto d-block" style="max-height:350px; object-fit:contain;">
                                                                         </div>
                                                                     <?php } ?>
                                                                 </div>
                                                             </div>
                                                         </div>

                                                         <div class="clearfix carousel-slider mt-2">
                                                             <div id="thumbcarousel" class="carousel slide" data-bs-interval="false">
                                                                 <div class="carousel-inner">
                                                                     <ul class="carousel-item active d-flex flex-wrap gap-2">
                                                                         <?php foreach ($v_imgs as $idx => $v_img_name) { ?>
                                                                             <li data-bs-target="#Slider" data-bs-slide-to="<?php echo $idx; ?>" class="thumb <?php echo ($idx === 0) ? 'active' : ''; ?> m-1" style="cursor:pointer; width:60px; height:60px; overflow:hidden; border-radius:4px; border:1px solid #ccc;">
                                                                                 <img src="productupload/<?php echo htmlspecialchars($v_img_name);?>" alt="img" style="width:100%; height:100%; object-fit:cover;">
                                                                             </li>
                                                                         <?php } ?>
                                                                     </ul>
                                                                 </div>
                                                             </div>
                                                         </div>  </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="details col-xl-7 col-lg-12 col-md-12 mt-4 mt-xl-0">
                                                <div class="mt-2 mb-4">
                                                    <h3 class="mb-3 fw-bold"><?php echo $product['productname'];?></h3>

                                                    <!-- <p class="text-muted float-start me-3">
                                                        <span class="fa fa-star text-warning"></span>
                                                        <span class="fa fa-star text-warning"></span>
                                                        <span class="fa fa-star text-warning"></span>
                                                        <span class="fa fa-star-half-o text-warning"></span>
                                                        <span class="fa fa-star-o text-warning"></span>
                                                    </p> -->

                                                    <h4 class="mt-4"><b> Description</b></h4>
                                                    <p><?php echo $product['description'];?></p>
                                                    <h3 class="mb-4"><span class="me-2 fw-bold fs-25 d-inline-flex">&#8358;<?php echo number_format($product['sellingprice'], 2);?></h3>
                                                    
                                                    <div class=" mt-4 mb-5"><span class="fw-bold me-2">Availability :</span><span class="fw-bold text-success">In-stock</span></div>
                                                    <div class="colors d-flex me-3 mt-4 mb-5">
                                                        <span class="mt-2 fw-bold">Colors:</span>
                                                        <div class="row gutters-xs ms-4">
                                                            <div class="col-3">
                                                                <label class="colorinput">
                                                                <input name="color" type="radio" value="azure" class="colorinput-input" checked="">
                                                                <span class="colorinput-color bg-danger rounded-10"></span>
                                                                </label>
                                                            </div>

                                                            <div class="col-3">
                                                                <label class="colorinput">
                                                                <input name="color" type="radio" value="indigo" class="colorinput-input">
                                                                <span class="colorinput-color bg-dark rounded-10"></span>
                                                                </label>
                                                            </div>

                                                            <div class="col-3">
                                                                <label class="colorinput">
                                                                <input name="color" type="radio" value="purple" class="colorinput-input">
                                                                <span class="colorinput-color bg-info rounded-10"></span>
                                                                </label>
                                                            </div>
                                                            
                                                            <div class="col-3">
                                                                <label class="colorinput">
                                                                <input name="color" type="radio" value="pink" class="colorinput-input">
                                                                <span class="colorinput-color bg-success rounded-10"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row row-sm">
                                                        <div class="col">
                                                            <div class="mb-2 me-2 sizes">
                                                                <span class="fw-bold me-4">Quantity: <?php echo $product['quantity'];?></span>
                                                     
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12 col-md-12">
                                <div class="card productdesc">
                                    <div class="card-body">
                                        <div class="panel panel-primary">
                                            <div class=" tab-menu-heading">
                                                <div class="tabs-menu1">
                                                    <!-- Tabs -->
                                                    <ul class="nav panel-tabs">
                                                        <li><a href="#tab5" class="active" data-bs-toggle="tab">Specifications</a></li>
                                                        <li><a href="#tab6" data-bs-toggle="tab">Dimensions</a></li>
                                                        <li><a href="#tab7" data-bs-toggle="tab">Features</a></li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="panel-body tabs-menu-body">
                                                <div class="tab-content">
                                                    <div class="tab-pane active" id="tab5">
                                                        <h4 class="mb-5 mt-3 fw-bold">Description :</h4>
                                                        <p class="mb-3 fs-15"><?php echo $product['description'];?></p>
                                                        
                                                        <h4 class="mb-5 mt-3 fw-bold">Specifications :</h4>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <tr>
                                                                    <td class="fw-bold">Package Dimensions</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="fw-bold">Manufacturer</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="fw-bold">Item part number </td>
                                                                    <td></td>
                                                                </tr>
                                                          
                                                            </table>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="tab-pane pt-5" id="tab6">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <tr>
                                                                    <td class="fw-bold">Width</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="fw-bold">Height</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="fw-bold">Depth</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="fw-bold">Other Dimensions</td>
                                                                    <td></td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane" id="tab7">
                                                        <ul class="p-5">
                                                            <li><i class="fa fa-check me-3 text-success mb-5"></i></li>
                                                            <li><i class="fa fa-check me-3 text-success mb-5"></i></li>
                                                            <li><i class="fa fa-check me-3 text-success mb-5"></i></li>
                                                            <li><i class="fa fa-check me-3 text-success mb-5"></i></li>
                                                            <li><i class="fa fa-check me-3 text-success mb-5"></i></li>
                                                            <li><i class="fa fa-check me-3 text-success"></i></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                         
                        </div>
                        <!-- ROW-1 CLOSED -->
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

	<!-- TypeHead js -->
	<script src="assets/plugins/bootstrap5-typehead/autocomplete.js"></script>
    <script src="assets/js/typehead.js"></script>

    <!-- INTERNAL SELECT2 JS -->
    <script src="assets/plugins/select2/select2.full.min.js"></script>
    <script src="assets/js/select2.js"></script>

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