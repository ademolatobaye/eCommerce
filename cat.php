<?php 
session_start();
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
    header("Location: management/");
    exit();
}

$categoryName = '';
$categoryNameDisplay = 'Category';

if (isset($_GET['category']) && !empty($_GET['category'])) {
    $categoryName = $_GET['category'];
    $categoryNameDisplay = htmlspecialchars($categoryName);
} elseif (isset($_GET['cat']) && !empty($_GET['cat'])) {
    $cat_id = (int)$_GET['cat'];
    $cat_sql = "SELECT categoryname FROM category WHERE id = '$cat_id'";
    $cat_res = mysqli_query($conn, $cat_sql);
    if($cat_res && mysqli_num_rows($cat_res) > 0){
        $c_row = mysqli_fetch_assoc($cat_res);
        $categoryName = $c_row['categoryname'];
        $categoryNameDisplay = htmlspecialchars($categoryName);
    }
}

if (empty($categoryName)) {
    // If no category is found or provided, redirect back to shop
    header("Location: shop");
    exit();
}

$categoryNameEscaped = mysqli_real_escape_string($conn, $categoryName);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title><?php echo $business_name;?> || <?php echo strtoupper($categoryNameDisplay); ?> PRODUCTS</title>

    <link rel="icon" type="image/png" href="assets/images/icons/favicon.png">

    <style>
        .product-media img {
            width: 100%;
            height: 200px; 
            object-fit: cover; 
        }
        .product-wrap {
            margin-bottom: 20px;
        }
    </style>

    <script>
        WebFontConfig = {
            google: { families: ['Poppins:400,500,600,700'] }
        };
        (function (d) {
            var wf = d.createElement('script'), s = d.scripts[0];
            wf.src = 'assets/js/webfont.js';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>

    <link rel="stylesheet" type="text/css" href="assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/animate/animate.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css">
</head>

<body>
    <div class="page-wrapper">

        <?php 
        include("header.php"); 
        ?>

        <main class="main">
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb bb-no">
                        <li><a href="index">Home</a></li>
                        <li><a href="shop">Shop</a></li>
                        <li><?php echo $categoryNameDisplay; ?></li>
                    </ul>
                </div>
            </nav>

            <div class="page-content mb-10">
                <div class="container">
                    <div class="shop-content">
                        <div class="main-content">
                            <?php
                            // PAGINATION LOGIC
                            $limit = 30; 
                            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            if ($page < 1) { $page = 1; }
                            $offset = ($page - 1) * $limit;

                            $sql = "SELECT * FROM `product_table` WHERE category='$categoryNameEscaped' AND approval_status='Approved' ORDER BY `product_id` ASC LIMIT $limit OFFSET $offset";
                            $result = mysqli_query($conn, $sql);
                            ?>

                            <div class="product-wrapper row cols-lg-4 cols-md-3 cols-sm-2 cols-2">
                                <?php
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                     <div class="product-wrap">
                                         <div class="product text-center">
                                             <figure class="product-media">
                                                 <a href="product?uin=<?php echo $row['uin']; ?>">
                                                     <img src="vendor/vendorupload/<?php echo htmlspecialchars($row['productimage']);?>" alt="Product" />
                                                 </a>
                                                 <div class="product-action-vertical">
                                                     <a href="addtowishlist?uin=<?php echo $row['uin']; ?>" class="btn-product-icon btn-wishlist w-icon-heart btn-add-wishlist-ajax" title="Add to Wishlist" data-uin="<?php echo $row['uin']; ?>"></a>
                                                 </div>
                                                 <div class="product-action">
                                                     
                                                 </div>
                                             </figure>

                                             <div class="product-details">
                                                 <div class="product-cat">
                                                     <a href="cat?category=<?php echo urlencode($row['category']); ?>"><?php echo htmlspecialchars($row['category']); ?></a>
                                                 </div>
                                                 <h3 class="product-name">
                                                     <a href="product?uin=<?php echo $row['uin']; ?>">
                                                         <?php echo htmlspecialchars($row['productname']); ?>
                                                     </a>
                                                 </h3>
                                                 <!-- Vendor Badge on Card -->
                                                 <div class="product-vendor-mini mt-1 mb-1" style="font-size: 12px; color: #666;">
                                                     <?php if (!empty($row['vendor_uin'])): ?>
                                                         <i class="fas fa-store text-primary me-1"></i>
                                                         <a href="vendor-store?vendor_uin=<?php echo $row['vendor_uin']; ?>" class="text-primary font-weight-bold" style="text-decoration: underline;">
                                                             <?php echo $row['vendor_storename']; ?>
                                                         </a>
                                                     <?php else: ?>
                                                         <span class="text-muted"><i class="fas fa-shield-alt text-success me-1"></i> <?php echo $business_name;?></span>
                                                     <?php endif; ?>
                                                 </div>
                                                 <?php
                                                 $p_uin = $row['uin'];
                                                 $r_stmt = mysqli_prepare($conn, "SELECT AVG(rating) as avg_r, COUNT(*) as cnt FROM product_reviews WHERE product_uin = ?");
                                                 $card_rating = 0; $card_count = 0;
                                                 if ($r_stmt) {
                                                     mysqli_stmt_bind_param($r_stmt, 's', $p_uin);
                                                     mysqli_stmt_execute($r_stmt);
                                                     $r_res = mysqli_stmt_get_result($r_stmt);
                                                     if ($r_row = mysqli_fetch_assoc($r_res)) {
                                                         $card_rating = round((float)$r_row['avg_r'], 1);
                                                         $card_count = (int)$r_row['cnt'];
                                                     }
                                                     mysqli_stmt_close($r_stmt);
                                                 }
                                                 $card_stars = ($card_rating / 5) * 100;
                                                 ?>
                                                 <div class="ratings-container">
                                                     <div class="ratings-full">
                                                         <span class="ratings" style="width: <?php echo $card_stars; ?>%;"></span>
                                                     </div>
                                                     <a href="product.php?uin=<?php echo $row['uin']; ?>#product-tab-reviews" class="rating-reviews">(<?php echo $card_count; ?>)</a>
                                                 </div>
                                                 <div class="product-price">
                                                     &#8358; <?php echo number_format($row['sellingprice'], 2); ?>   
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                <?php
                                    }
                                } else {
                                    echo "<p>No products found in this category.</p>";
                                }
                                ?>
                            </div>

                            <?php
                            // PAGINATION LINKS
                            $count_sql = "SELECT COUNT(*) AS total FROM `product_table` WHERE category='$categoryNameEscaped'";
                            $count_result = mysqli_query($conn, $count_sql);
                            $count_row = mysqli_fetch_assoc($count_result);
                            $total_products = $count_row['total'];
                            $total_pages = ceil($total_products / $limit);

                            if ($total_pages > 1) {
                                echo '<div class="pagination" style="margin-top:30px; text-align:center;">';
                                
                                // Preserve URL parameters for pagination
                                $queryParams = $_GET;
                                unset($queryParams['page']);
                                $queryString = http_build_query($queryParams);
                                $baseUrl = "?" . ($queryString ? $queryString . "&" : "");
                                
                                if ($page > 1) {
                                    echo "<a href='".$baseUrl."page=".($page - 1)."' style='margin-right:10px;'>« Prev</a>";
                                }
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    if ($i == $page) {
                                        echo "<strong style='margin:0 5px;'>$i</strong>";
                                    } else {
                                        echo "<a href='".$baseUrl."page=$i' style='margin:0 5px;'>$i</a>";
                                    }
                                }
                                if ($page < $total_pages) {
                                    echo "<a href='".$baseUrl."page=".($page + 1)."' style='margin-left:10px;'>Next »</a>";
                                }
                                echo '</div>';
                            }
                            ?>               
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?php include("footer.php"); ?>
    </div> 
    <!-- End of Page Wrapper -->

    <?php
    include("sticky-footer.php");
    ?>
   
    <!-- Start of Scroll Top -->
    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button"> <i class="w-icon-angle-up"></i> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70"> <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34" style="stroke-dasharray: 16.4198, 400;"></circle> </svg> </a>
    <!-- End of Scroll Top -->

    <?php
    include("mobile-menu.php");
    ?>

    <!-- Plugin JS File -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/sticky/sticky.js"></script>
    <script src="assets/vendor//jquery.plugin/jquery.plugin.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/nouislider/nouislider.min.js"></script>
    <script src="assets/vendor/jquery.countdown/jquery.countdown.min.js"></script>
    <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="assets/vendor/zoom/jquery.zoom.js"></script>
    <script src="assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.min.js"></script>
    <script>
    $(document).ready(function() {
        $(document).on('click', '.btn-add-wishlist-ajax', function(e) {
            e.preventDefault();
            var $btn = $(this);
            var url = $btn.attr('href');
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                data: { ajax: 1 },
                success: function(res) {
                    if (res.success) {
                        if (res.action === 'added') {
                            $btn.addClass('added').attr('title', 'Remove from Wishlist').css('color', '#e3342f');
                        } else {
                            $btn.removeClass('added').attr('title', 'Add to Wishlist').css('color', '');
                        }
                        if ($('#header-wishlist-count').length) {
                            $('#header-wishlist-count').text(res.count);
                        }
                        alert(res.message);
                    }
                },
                error: function() {
                    window.location.href = url;
                }
            });
        });

        $(document).on('click', '.btn-add-cart-ajax', function(e) {
            e.preventDefault();
            var $btn = $(this);
            var url = $btn.attr('href');
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                data: { ajax: 1 },
                success: function(res) {
                    if (res.success) {
                        if ($('.cart-count').length) {
                            $('.cart-count').text(res.cartCount);
                        }
                        alert(res.message);
                    } else {
                        if (res.redirect) {
                            window.location.href = res.redirect;
                        } else {
                            alert(res.message);
                        }
                    }
                },
                error: function() {
                    window.location.href = url;
                }
            });
        });
    });
    </script>
</body>
</html>
