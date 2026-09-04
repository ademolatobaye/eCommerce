<?php
session_start();
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
    header("Location: management/");
    exit();
}
?>

<?php
$product_row = null;
if(isset($_REQUEST['uin']) && !empty($_REQUEST['uin'])){
    $req_uin = mysqli_real_escape_string($conn, $_REQUEST['uin']);
    $sql = "SELECT * FROM product_table WHERE uin='$req_uin' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $product_row = mysqli_fetch_array($result);
        $uin = mysqli_real_escape_string($conn, $product_row['uin']);
        $productname = $product_row['productname'];
        $quantity = $product_row['quantity'];
        $category = mysqli_real_escape_string($conn, $product_row['category']);
        $sellingprice = $product_row['sellingprice'];
        $productimage = $product_row['productimage'];

        // Fetch Vendor Info if product belongs to a vendor
        $vendor_info = "";
        if (!empty($product_row['vendor_uin'])) {
            $v_stmt = mysqli_prepare($conn, "SELECT * FROM vendor_table WHERE vendor_uin = ? LIMIT 1");
            if ($v_stmt) {
                mysqli_stmt_bind_param($v_stmt, "s", $product_row['vendor_uin']);
                mysqli_stmt_execute($v_stmt);
                $v_res = mysqli_stmt_get_result($v_stmt);
                $vendor_info = mysqli_fetch_assoc($v_res);
            }
        }

        // Track recently viewed products in session
        if (!isset($_SESSION['recently_viewed']) || !is_array($_SESSION['recently_viewed'])) {
            $_SESSION['recently_viewed'] = array();
        }
        if (($key = array_search($uin, $_SESSION['recently_viewed'])) !== false) {
            unset($_SESSION['recently_viewed'][$key]);
        }
        array_unshift($_SESSION['recently_viewed'], $uin);
        $_SESSION['recently_viewed'] = array_slice($_SESSION['recently_viewed'], 0, 8);

        // Check if item is in wishlist
        $in_wishlist = false;
        $cust_uin = isset($_SESSION['customer_uin']) ? $_SESSION['customer_uin'] : '';
        if (!empty($cust_uin)) {
            $w_check = mysqli_prepare($conn, "SELECT id FROM wishlist WHERE customer_uin = ? AND product_uin = ?");
            if ($w_check) {
                mysqli_stmt_bind_param($w_check, 'ss', $cust_uin, $uin);
                mysqli_stmt_execute($w_check);
                $w_res = mysqli_stmt_get_result($w_check);
                if (mysqli_num_rows($w_res) > 0) { $in_wishlist = true; }
                mysqli_stmt_close($w_check);
            }
        } else if (isset($_SESSION['wishlist']) && in_array($uin, $_SESSION['wishlist'])) {
            $in_wishlist = true;
        }

        // Fetch customer reviews
        $reviews_stmt = mysqli_prepare($conn, "SELECT * FROM product_reviews WHERE product_uin = ? ORDER BY id DESC");
        $reviews_list = array();
        $total_rating_sum = 0;
        $review_count = 0;
        if ($reviews_stmt) {
            mysqli_stmt_bind_param($reviews_stmt, 's', $uin);
            mysqli_stmt_execute($reviews_stmt);
            $reviews_res = mysqli_stmt_get_result($reviews_stmt);
            while ($r_row = mysqli_fetch_assoc($reviews_res)) {
                $reviews_list[] = $r_row;
                $total_rating_sum += (int)$r_row['rating'];
                $review_count++;
            }
            mysqli_stmt_close($reviews_stmt);
        }
        $avg_rating = $review_count > 0 ? round($total_rating_sum / $review_count, 1) : 0;
        $star_percentage = $review_count > 0 ? ($avg_rating / 5) * 100 : 0;
    }
}

// If product is not found or uin is missing/invalid, show alert and redirect
if (!$product_row) {
    echo "<script>alert('Product not available.');
     window.location.href='shop';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title><?php echo $business_name;?> || PRODUCTS</title>

    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/icons/favicon.png">

    <!-- WebFont.js -->
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

    <link rel="preload" href="assets/vendor/fontawesome-free/webfonts/fa-regular-400.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="assets/vendor/fontawesome-free/webfonts/fa-solid-900.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="assets/vendor/fontawesome-free/webfonts/fa-brands-400.woff2" as="font" type="font/woff2"
            crossorigin="anonymous">
    <link rel="preload" href="assets/fonts/wolmart.woff?png09e" as="font" type="font/woff" crossorigin="anonymous">

    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/animate/animate.min.css">

    <!-- Plugin CSS -->
    <link rel="stylesheet" type="text/css" href="assets/vendor/magnific-popup/magnific-popup.min.css">
    <link rel="stylesheet" href="assets/vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/photoswipe/photoswipe.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/photoswipe/default-skin/default-skin.min.css">
    <!-- Swiper's CSS -->
    <link rel="stylesheet" href="assets/vendor/swiper/swiper-bundle.min.css">

    <!-- Default CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css">
</head>

<style>
    .product-single .product-image img {
        max-height: 500px; 
        width: auto;
        margin: 0 auto;
        object-fit: contain;
    }
    
    .product-thumb img {
        height: 100px;
        object-fit: cover;
    }
</style>

<body>
    <div class="page-wrapper">
        <!-- Start of Header -->
        <?php
        include("header.php");
        ?>
        <!-- End of Header -->


        <!-- Start of Main -->
        <main class="main mb-10 pb-1">
            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav container">
                <ul class="breadcrumb bb-no">
                    <li><a href="javascript:history.back()">Home</a></li>
                    <li>Products</li>
                    <li><?php echo $product_row['productname']; ?></li>
                </ul>

            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of Page Content -->
            <div class="page-content">
                <div class="container">
                    <div class="row gutter-lg">
                        <div class="main-content">
                            <div class="product product-single row">
                                <div class="col-md-6 mb-6">
                                    <div class="product-gallery product-gallery-sticky">
                                        <div class="swiper-container product-single-swiper swiper-theme nav-inner" data-swiper-options="{
                                            'navigation': {
                                                'nextEl': '.swiper-button-next',
                                                'prevEl': '.swiper-button-prev'
                                            }
                                        }">
                                            <div class="swiper-wrapper row cols-1 gutter-no">
                                                 <?php
                                                 if (!function_exists('getProductImgUrl')) {
                                                     function getProductImgUrl($fileName) {
                                                         if (!empty($fileName) && file_exists("vendor/vendorupload/" . $fileName)) {
                                                             return "vendor/vendorupload/" . htmlspecialchars($fileName);
                                                         }
                                                         if (!empty($fileName) && file_exists("vendor/vendorupload/" . $fileName)) {
                                                             return "vendor/vendorupload/" . htmlspecialchars($fileName);
                                                         }
                                                         return "vendor/vendorupload/" . htmlspecialchars($fileName);
                                                     }
                                                 }
                                                 $req_uin = mysqli_real_escape_string($conn, $_REQUEST['uin']);
                                                 $all_product_images = array();
                                                 if (!empty($product_row['productimage'])) {
                                                     $all_product_images[] = $product_row['productimage'];
                                                 }
                                                 $imgs_result = mysqli_query($conn, "SELECT * FROM `product_images` WHERE uin='$req_uin' ORDER BY sort_order ASC");
                                                 if ($imgs_result && mysqli_num_rows($imgs_result) > 0) {
                                                     while ($img_row = mysqli_fetch_array($imgs_result)) {
                                                         if ($img_row['product_image'] !== $product_row['productimage']) {
                                                             $all_product_images[] = $img_row['product_image'];
                                                         }
                                                     }
                                                 }

                                                 foreach ($all_product_images as $img_file) {
                                                     $imgPath = getProductImgUrl($img_file);
                                                 ?>
                                                     <div class="swiper-slide">
                                                         <figure class="product-image">
                                                             <img src="<?php echo $imgPath; ?>"
                                                                 data-zoom-image="<?php echo $imgPath; ?>"
                                                                 width="800" height="900">
                                                         </figure>
                                                     </div>
                                                 <?php } ?>
                                             </div>
                                             <button class="swiper-button-next"></button>
                                             <button class="swiper-button-prev"></button>
                                             <a href="#" class="product-gallery-btn product-image-full"><i class="w-icon-zoom"></i></a>
                                         </div>
                                         
                                          <div class="product-thumbs-wrap swiper-container" data-swiper-options="{
                                              'navigation': {
                                                  'nextEl': '.swiper-button-next',
                                                  'prevEl': '.swiper-button-prev'
                                              }
                                          }">
                                              <div class="product-thumbs swiper-wrapper row cols-4 gutter-sm">
                                                  <?php
                                                  foreach ($all_product_images as $img_file) {
                                                      $thumbPath = getProductImgUrl($img_file);
                                                  ?>
                                                      <div class="product-thumb swiper-slide">
                                                          <img src="<?php echo $thumbPath; ?>"
                                                              alt="Product Thumb" width="800" height="900">
                                                      </div>
                                                  <?php } ?>
                                              </div>
                                              <button class="swiper-button-next"></button>
                                              <button class="swiper-button-prev"></button>
                                          </div>
                                      </div>
                                  </div>
                                  
                                  <div class="col-md-6 mb-4 mb-md-6">
                                      <div class="product-details" data-sticky-options="{'minWidth': 767}">
                                          <h1 class="product-title"><?php echo htmlspecialchars($product_row['productname']); ?></h1>
                                          <div class="product-bm-wrapper">
                                              <div class="product-meta">
                                                  <div class="product-categories">
                                                      Category:
                                                      <span class="product-category"><a href="cat?category=<?php echo urlencode($product_row['category']); ?>"><?php echo htmlspecialchars($product_row['category']); ?></a></span>
                                                  </div>

                                                  <div class="product-sku">
                                                      SKU: <span><?php echo htmlspecialchars($product_row['uin']); ?></span>
                                                  </div>
                                              </div>
                                          </div>

                                          <!-- Vendor Sold By Bar -->
                                          <div class="vendor-sold-by-bar" style="display: flex; align-items: center; justify-content: space-between; background: #f7f8fa; border: 1px solid #e1e4e8; border-radius: 6px; padding: 8px 14px; margin: 12px 0 16px 0;">
                                              <div style="display: flex; align-items: center; gap: 8px;">
                                                  <span style="font-size: 13px; color: #666; font-weight: 500;">Sold by:</span>
                                                  <?php if (!empty($vendor_info)): ?>
                                                      <?php if (!empty($vendor_info['logo']) && file_exists("vendor/vendorupload/" . $vendor_info['logo'])): ?>
                                                          <img src="vendor/vendorupload/<?php echo htmlspecialchars($vendor_info['logo']); ?>" alt="logo" style="width: 24px; height: 24px; object-fit: cover; border-radius: 50%;">
                                                      <?php else: ?>
                                                          <i class="fas fa-store text-primary" style="font-size: 14px;"></i>
                                                      <?php endif; ?>
                                                      <a href="vendor-store?vendor_uin=<?php echo urlencode($vendor_info['vendor_uin']); ?>" style="font-weight: 700; color: #336699; font-size: 14px;">
                                                          <?php echo htmlspecialchars($vendor_info['store_name']); ?>
                                                      </a>
                                                  <?php else: ?>
                                                      <i class="fas fa-shield-alt text-success" style="font-size: 14px;"></i>
                                                      <span style="font-weight: 700; color: #333; font-size: 14px;"><?php echo $business_name;?> Official Store</span>
                                                  <?php endif; ?>
                                              </div>
                                              <?php if (!empty($vendor_info)): ?>
                                                  <a href="vendor-store?vendor_uin=<?php echo urlencode($vendor_info['vendor_uin']); ?>" style="font-size: 12px; font-weight: 600; color: #336699; text-decoration: underline;">
                                                      View Vendor &rarr;
                                                  </a>
                                              <?php endif; ?>
                                          </div>

                                        <hr class="product-divider">

                                        <div class="product-price"><ins class="new-price">
                                            &#8358;
                                            <?php
        
            echo number_format($product_row['sellingprice'], 2);
        
        ?>
                                        </ins></div>

                                        <div class="ratings-container">
                                            <div class="ratings-full">
                                                <span class="ratings" style="width: <?php echo $star_percentage; ?>%;"></span>
                                                <span class="tooltiptext tooltip-top"><?php echo $avg_rating; ?> / 5.0</span>
                                            </div>
                                            <a href="#product-tab-reviews" class="rating-reviews scroll-to">(<?php echo $review_count; ?> <?php echo $review_count === 1 ? 'Review' : 'Reviews'; ?>)</a>
                                        </div>

                                        <div class="product-short-desc">
                                            <ul class="list-type-check list-style-none">
                                                <?php 
$lowLevel = $product_row['lowlevel'];
$quantity = $product_row['quantity'];
?>
                                                <li>
                                                 <?php if ($quantity > $lowLevel) : ?>
        <span style="color: green;"><b>Available In Stock</b></span>
    <?php elseif ($quantity > 0) : ?>
        <span style="color: red;"><b>Only <?php echo $quantity; ?> left in stock</b></span>
    <?php else : ?>
        <span style="color: red;"><b>Out of Stock</b></span>
    <?php endif; ?>   
                                                </li>
                                                
                                            </ul>
                                        </div>

                                        <hr class="product-divider">

                                        <form method="post" action="addtocart">
                                            <input type="hidden" name="uin" value="<?php echo $uin; ?>">
                                            <input type="hidden" name="product_id" value="<?php echo $product_row['product_id']; ?>">
                                            <div class="product-form product-qty">
                                                <label>Qty:</label>
                                                <div class="input-group">
                                                    <input class="quantity form-control" name="quantity" type="number" min="1" max="10000000" value="1">
                                                    <button type="button" class="quantity-plus w-icon-plus"></button>
                                                    <button type="button" class="quantity-minus w-icon-minus"></button>
                                                </div>
                                            </div>
                                            
                                            <?php if ($product_row['quantity'] > 0): ?>
                                                <button type="submit" class="btn btn-primary" name="add">
                                                    <i class="w-icon-cart"></i>
                                                    <span>Add to Cart</span>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-disabled" disabled>
                                                    <span>Out of Stock</span>
                                                </button>
                                            <?php endif; ?>
                                            
                                            <a href="addtowishlist?uin=<?php echo $uin; ?>" 
                                               id="btn-wishlist-toggle"
                                               class="btn btn-outline btn-dark btn-rounded ml-3 <?php echo $in_wishlist ? 'added' : ''; ?>" 
                                               title="<?php echo $in_wishlist ? 'Remove from Wishlist' : 'Add to Wishlist'; ?>"
                                               style="display: inline-flex; align-items: center; justify-content: center; height: 46px; padding: 0 20px; font-weight: 600; <?php echo $in_wishlist ? 'color:#e3342f; border-color:#e3342f; background-color:#fff0f0;' : ''; ?>">
                                               <i class="w-icon-heart mr-2" id="wishlist-btn-icon"></i>
                                               <span id="wishlist-btn-text"><?php echo $in_wishlist ? 'Wishlisted' : 'Add to Wishlist'; ?></span>
                                            </a>
                                        </form>

                                    </div>
                                </div>
                            </div>
                            
                           
                             <div class="tab tab-nav-boxed tab-nav-underline product-tabs">
                                 <ul class="nav nav-tabs" role="tablist">
                                     <li class="nav-item">
                                         <a href="#product-tab-description" class="nav-link active">Product Description</a>
                                     </li>  
                                     <li class="nav-item">
                                         <a href="#product-tab-vendor" class="nav-link">Vendor Info</a>
                                     </li>
                                     <li class="nav-item">
                                         <a href="#product-tab-reviews" class="nav-link">Customer Reviews (<?php echo $review_count; ?>)</a>
                                     </li>
                                 </ul>

                                 <div class="tab-content">
                                     <div class="tab-pane active" id="product-tab-description">
                                         <div class="row mb-4">
                                             <div class="col-md-12 mb-5">
                                                 <p class="mb-4">
                                                     <?php echo $product_row['description']; ?>
                                                 </p>
                                             </div>
                                         </div>
                                     </div>

                                     <div class="tab-pane" id="product-tab-vendor">
                                         <div class="row mb-4">
                                             <div class="col-md-12">
                                                 <?php if (!empty($vendor_info)): ?>
                                                     <div class="vendor-info-tab" style="border: 1px solid #e1e4e8; border-radius: 10px; background: #ffffff; padding: 28px 32px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                                                         <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #f0f0f0;">
                                                             <?php if (!empty($vendor_info['logo']) && file_exists("vendor/vendorupload/" . $vendor_info['logo'])): ?>
                                                                 <img src="vendor/vendorupload/<?php echo htmlspecialchars($vendor_info['logo']); ?>" alt="Vendor Logo" style="width: 75px; height: 75px; object-fit: cover; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 3px 10px rgba(0,0,0,0.12);">
                                                             <?php else: ?>
                                                                 <div style="width: 75px; height: 75px; border-radius: 50%; background: #336699; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 30px; box-shadow: 0 3px 10px rgba(0,0,0,0.12);">
                                                                     <i class="fas fa-store"></i>
                                                                 </div>
                                                             <?php endif; ?>
                                                             <div>
                                                                 <h3 style="font-size: 1.35rem; font-weight: 700; color: #222; margin: 0 0 6px 0;"><?php echo htmlspecialchars($vendor_info['store_name']); ?></h3>
                                                                 <p style="font-size: 0.92rem; color: #666; margin: 0;"><i class="fas fa-user-circle text-primary" style="margin-right: 6px;"></i>Owner: <strong><?php echo htmlspecialchars($vendor_info['vendor_name']); ?></strong></p>
                                                             </div>
                                                         </div>

                                                         <?php if (!empty($vendor_info['description'])): ?>
                                                             <div style="margin-bottom: 22px;">
                                                                 <h5 style="font-size: 0.95rem; font-weight: 700; color: #444; margin-bottom: 8px;">About Store:</h5>
                                                                 <p style="font-size: 0.92rem; line-height: 1.7; color: #555; margin: 0; background: #fafafa; padding: 14px 18px; border-radius: 6px; border-left: 3px solid #336699;"><?php echo nl2br(htmlspecialchars($vendor_info['description'])); ?></p>
                                                             </div>
                                                         <?php endif; ?>

                                                         <div style="margin-bottom: 24px;">
                                                             <ul style="list-style: none; padding: 0; margin: 0;">
                                                                 <?php if (!empty($vendor_info['store_address'])): ?>
                                                                     <li style="margin-bottom: 12px; font-size: 0.93rem; color: #444; display: flex; align-items: flex-start; gap: 10px;">
                                                                         <i class="fas fa-map-marker-alt text-primary" style="font-size: 16px; margin-top: 3px; min-width: 18px;"></i>
                                                                         <span><strong>Address:</strong> <?php echo htmlspecialchars($vendor_info['store_address']); ?></span>
                                                                     </li>
                                                                 <?php endif; ?>
                                                                 <?php if (!empty($vendor_info['vendor_phone'])): ?>
                                                                     <li style="margin-bottom: 12px; font-size: 0.93rem; color: #444; display: flex; align-items: center; gap: 10px;">
                                                                         <i class="fas fa-phone text-primary" style="font-size: 15px; min-width: 18px;"></i>
                                                                         <span><strong>Phone:</strong> <?php echo htmlspecialchars($vendor_info['vendor_phone']); ?></span>
                                                                     </li>
                                                                 <?php endif; ?>
                                                             </ul>
                                                         </div>

                                                         <div style="padding-top: 10px;">
                                                             <a href="vendor-store?vendor_uin=<?php echo urlencode($vendor_info['vendor_uin']); ?>" class="btn btn-primary btn-rounded" style="padding: 12px 28px; font-weight: 600;">
                                                                 <i class="fas fa-store" style="margin-right: 8px;"></i> View Vendor
                                                             </a>
                                                         </div>
                                                     </div>
                                                 <?php else: ?>
                                                     <div class="vendor-info-tab" style="border: 1px solid #e1e4e8; border-radius: 10px; background: #ffffff; padding: 28px 32px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                                                         <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 16px;">
                                                             <i class="fas fa-shield-alt text-success" style="font-size: 32px;"></i>
                                                             <div>
                                                                 <h3 style="font-size: 1.25rem; font-weight: 700; color: #222; margin: 0;"><?php echo $business_name;?> Official Store</h3>
                                                                 <p style="font-size: 0.88rem; color: #666; margin: 4px 0 0 0;">Verified Official Partner</p>
                                                             </div>
                                                         </div>
                                                         <p style="font-size: 0.93rem; color: #555; line-height: 1.6; margin-bottom: 0;">
                                                             This product is sold and fulfilled directly by <?php echo $business_name;?>. Quality guaranteed and fast delivery across Nigeria.
                                                         </p>
                                                     </div>
                                                 <?php endif; ?>
                                             </div>
                                         </div>
                                     </div>

                                    <div class="tab-pane" id="product-tab-reviews">
                                        <div class="row mb-4">
                                            <div class="col-xl-4 col-lg-5 mb-4">
                                                <div class="ratings-wrapper">
                                                    <div class="avg-rating-container text-center p-4" style="background: #f8f9fa; border-radius: 8px;">
                                                        <h4 class="avg-mark font-weight-bolder mb-1" style="font-size: 2.5rem; color: #333;"><?php echo $avg_rating; ?></h4>
                                                        <div class="ratings-full d-inline-block">
                                                            <span class="ratings" style="width: <?php echo $star_percentage; ?>%;"></span>
                                                        </div>
                                                        <p class="mb-0 text-muted">Average Rating based on <?php echo $review_count; ?> <?php echo $review_count === 1 ? 'review' : 'reviews'; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-8 col-lg-7 mb-4">
                                                <div class="review-form-wrapper p-4" style="border: 1px solid #eee; border-radius: 8px;">
                                                    <h4 class="title font-weight-bold">Write a Review</h4>

                                                    
                                                    <form method="post" action="add-review" class="form review-form">
                                                        <input type="hidden" name="product_uin" value="<?php echo $uin; ?>">
                                                        <div class="form-group mb-3">
                                                            <label class="d-block mb-1">Your Rating *</label>
                                                            <select name="rating" class="form-control" required style="max-width: 200px;">
                                                                <option value="5">5 Stars - Excellent</option>
                                                                <option value="4">4 Stars - Very Good</option>
                                                                <option value="3">3 Stars - Average</option>
                                                                <option value="2">2 Stars - Poor</option>
                                                                <option value="1">1 Star - Very Poor</option>
                                                            </select>
                                                        </div>
                                                        <?php if (!isset($_SESSION['customer_email'])) { ?>
                                                            <div class="form-group mb-3">
                                                                <label>Your Name *</label>
                                                                <input type="text" name="customer_name" class="form-control" placeholder="Enter your name" required>
                                                            </div>
                                                        <?php } ?>
                                                        <div class="form-group mb-3">
                                                            <label>Review Title</label>
                                                            <input type="text" name="review_title" class="form-control" placeholder="e.g. Great product quality!">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label>Your Review *</label>
                                                            <textarea name="review_text" class="form-control" rows="4" placeholder="Share your experience with this product..." required></textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary btn-rounded">Submit Review</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="mt-2 mb-6">

                                        <div class="comments list-type-none pl-0">
                                            <h4 class="title font-weight-bold mb-4">Customer Reviews</h4>
                                            <?php if (count($reviews_list) > 0) { ?>
                                                <ul class="comments-list" style="list-style: none; padding-left: 0;">
                                                    <?php foreach ($reviews_list as $rev) { 
                                                        $rev_stars = ((int)$rev['rating'] / 5) * 100;
                                                    ?>
                                                        <li class="comment mb-4 p-3" style="border-bottom: 1px solid #f1f1f1;">
                                                            <div class="comment-body">
                                                                <div class="comment-rating ratings-container mb-1">
                                                                    <div class="ratings-full">
                                                                        <span class="ratings" style="width: <?php echo $rev_stars; ?>%;"></span>
                                                                    </div>
                                                                </div>
                                                                <div class="comment-meta mb-2">
                                                                    <span class="comment-author font-weight-bold text-dark"><?php echo htmlspecialchars($rev['customer_name']); ?></span>
                                                                    <span class="comment-date text-muted ml-2" style="font-size: 0.85rem;"><?php echo date('M d, Y', strtotime($rev['timestamp'])); ?></span>
                                                                </div>
                                                                <?php if (!empty($rev['review_title'])) { ?>
                                                                    <h5 class="comment-title font-weight-semi-bold mb-1"><?php echo htmlspecialchars($rev['review_title']); ?></h5>
                                                                <?php } ?>
                                                                <div class="comment-content">
                                                                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($rev['review_text'])); ?></p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            <?php } else { ?>
                                                <p class="text-muted">There are no reviews for this product yet. Be the first to write a review!</p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <section class="related-product-section">
                                <div class="title-link-wrapper mb-4">
                                    <h4 class="title">Related Products</h4>
                                    <a href="shop" class="btn btn-dark btn-link btn-slide-right btn-icon-right">More
                                        Products<i class="w-icon-long-arrow-right"></i></a>
                                </div>
                                <div class="swiper-container swiper-theme" data-swiper-options="{
                                    'spaceBetween': 20,
                                    'slidesPerView': 2,
                                    'breakpoints': {
                                        '576': {
                                            'slidesPerView': 3
                                        },
                                        '768': {
                                            'slidesPerView': 4
                                        },
                                        '992': {
                                            'slidesPerView': 3
                                        }
                                    }
                                }">
                                    <div class="swiper-wrapper row cols-lg-3 cols-md-4 cols-sm-3 cols-2">

                                    <?php
                                    $sql = "SELECT * FROM `product_table` WHERE category='$category' AND uin !='$uin' ORDER BY `product_id` ASC";
     $result = mysqli_query($conn, $sql);
     if (mysqli_num_rows($result) > 0) {
         while ($row = mysqli_fetch_array($result)) {
                                    ?>

                                        <div class="swiper-slide product">
                                            <figure class="product-media">
                                                <a href="product?uin=<?php echo $row['uin']; ?>">
                                                    <?php if (!empty($row['productimage']) && file_exists("vendor/vendorupload/" . $row['productimage'])): ?>
                                                        <img src="vendor/vendorupload/<?php echo htmlspecialchars($row['productimage']); ?>" alt="Product" width="300" height="300" />
                                                    <?php else: ?>
                                                        <img src="assets/images/products/1.jpg" alt="Product" width="300" height="300" />
                                                    <?php endif; ?>
                                                </a>
                                                <div class="product-action-vertical">
                                                    <a href="product?uin=<?php echo $row['uin']; ?>" class="btn-product btn-quickview" title="Quick View">Quick
                                                        View</a>
                                                </div>
                                            </figure>

                                            <div class="product-details">
                                                <h4 class="product-name"><a href="product?uin=<?php echo $row['uin']; ?>"><?php echo htmlspecialchars($row['productname']); ?></a></h4>

                                                <div class="ratings-container">
                                                    <div class="ratings-full">
                                                        <span class="ratings" style="width: <?php echo $star_percentage; ?>%;"></span>
                                                        <span class="tooltiptext tooltip-top"><?php echo $avg_rating; ?> / 5.0</span>
                                                    </div>

                                                    <a href="product-default" class="rating-reviews"></a>
                                                </div>

                                                <div class="product-pa-wrapper">
                                                    <div class="product-price">
                                                        &#8358;
        <?php
            echo number_format($row['sellingprice'], 2);
        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php }} ?>

                                        

                                    </div>
                                </div>
                            </section>

                            <?php
                            // RECENTLY VIEWED PRODUCTS SECTION
                            if (isset($_SESSION['recently_viewed']) && count($_SESSION['recently_viewed']) > 0) {
                                $recent_uins = array();
                                foreach ($_SESSION['recently_viewed'] as $rv_uin) {
                                    if ($rv_uin !== $uin) {
                                        $recent_uins[] = $rv_uin;
                                    }
                                }

                                if (count($recent_uins) > 0) {
                                    $escaped_uins = array();
                                    foreach ($recent_uins as $u) {
                                        $escaped_uins[] = "'" . mysqli_real_escape_string($conn, $u) . "'";
                                    }
                                    $in_sql = implode(',', $escaped_uins);
                                    $rv_query = mysqli_query($conn, "SELECT * FROM `product_table` WHERE uin IN ($in_sql) LIMIT 4");

                                    if ($rv_query && mysqli_num_rows($rv_query) > 0) {
                            ?>
                                <section class="recently-viewed-section mt-8">
                                    <div class="title-link-wrapper mb-4">
                                        <h4 class="title"><i class="fas fa-history mr-2"></i>Recently Viewed Items</h4>
                                    </div>
                                    <div class="row cols-lg-4 cols-md-3 cols-2 gutter-sm">
                                        <?php while ($rv_row = mysqli_fetch_assoc($rv_query)) { ?>
                                            <div class="product-wrap mb-4">
                                                <div class="product text-center border p-2 rounded" style="border-radius: 6px;">
                                                    <figure class="product-media mb-2">
                                                        <a href="product?uin=<?php echo $rv_row['uin']; ?>">
                                                            <img src="vendor/vendorupload/<?php echo htmlspecialchars($rv_row['productimage']); ?>" alt="Product" width="300" height="300" />
                                                        </a>
                                                    </figure>
                                                    <div class="product-details">
                                                        <h4 class="product-name font-size-sm mb-1">
                                                            <a href="product?uin=<?php echo $rv_row['uin']; ?>"><?php echo htmlspecialchars($rv_row['productname']); ?></a>
                                                        </h4>
                                                        <div class="product-price text-primary font-weight-bold">
                                                            &#8358;<?php echo number_format($rv_row['sellingprice'], 2); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </section>
                            <?php 
                                    }
                                }
                            }
                            ?>
                        </div>
                        <!-- End of Main Content -->

                        <aside class="sidebar product-sidebar sidebar-fixed right-sidebar sticky-sidebar-wrapper">
                            <div class="sidebar-overlay"></div>
                            <a class="sidebar-close" href="#"><i class="close-icon"></i></a>
                            <a href="#" class="sidebar-toggle d-flex d-lg-none"><i class="fas fa-chevron-left"></i></a>
                            <div class="sidebar-content scrollable">
                                <div class="sticky-sidebar">
                                    <div class="widget widget-icon-box mb-6">
                                        <div class="icon-box icon-box-side">
                                            <span class="icon-box-icon text-dark">
                                                <i class="w-icon-truck"></i>
                                            </span>
                                            <div class="icon-box-content">
                                                <h4 class="icon-box-title">Shipping & Delivery</h4>
                                                <p>Delivery anywhere in the country.</p>
                                            </div>
                                        </div>
                                        <div class="icon-box icon-box-side">
                                            <span class="icon-box-icon text-dark">
                                                <i class="w-icon-bag"></i>
                                            </span>
                                            <div class="icon-box-content">
                                                <h4 class="icon-box-title">Secure Payment</h4>
                                                <p>We ensure secure payment always.</p>
                                            </div>
                                        </div>
                                        <div class="icon-box icon-box-side">
                                            <span class="icon-box-icon text-dark">
                                                <i class="w-icon-money"></i>
                                            </span>
                                            <div class="icon-box-content">
                                                <h4 class="icon-box-title">Quick Payment Confirmation</h4>
                                                <p>All payments get confirmed in seconds.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of Widget Icon Box -->


                                    <div class="widget widget-products">
                                        <div class="title-link-wrapper mb-2">
                                            <h4 class="title title-link font-weight-bold">More Products</h4>
                                        </div>

                                        <div class="swiper nav-top">
                                            <div class="swiper-container swiper-theme nav-top" data-swiper-options = "{
                                                'slidesPerView': 1,
                                                'spaceBetween': 20,
                                                'navigation': {
                                                    'prevEl': '.swiper-button-prev',
                                                    'nextEl': '.swiper-button-next'
                                                }
                                            }">
                                                <div class="swiper-wrapper">
                                                    <div class="widget-col swiper-slide">

                                                        <?php
     $sql = "SELECT * FROM `product_table` WHERE uin !='$uin' ORDER BY RAND() LIMIT 4";
     $result = mysqli_query($conn, $sql);
     if (mysqli_num_rows($result) > 0) {
         while ($row = mysqli_fetch_array($result)) {
     ?>
                                                        <div class="product product-widget">
                                                            <figure class="product-media">
                                                                <a href="product?uin=<?php echo $row['uin']; ?>">
                                                                    <?php if (!empty($row['productimage']) && file_exists("vendor/vendorupload/" . $row['productimage'])): ?>
                                                                        <img src="vendor/vendorupload/<?php echo htmlspecialchars($row['productimage']); ?>" alt="Product" width="300" height="300" />
                                                                    <?php else: ?>
                                                                        <img src="assets/images/products/1.jpg" alt="Product" width="300" height="300" />
                                                                    <?php endif; ?>
                                                                </a>
                                                            </figure>
                                                            
                                                            <div class="product-details">
                                                                <h4 class="product-name">
                                                                    <a href="product?uin=<?php echo $row['uin']; ?>"><?php echo $row['productname']; ?></a>
                                                                </h4>
                                                                
                                                                <div class="ratings-container">
                                                                    <div class="ratings-full">
                                                                        <span class="" style="width: <?php echo $star_percentage; ?>%;"></span>
                                                                        <span class="tooltiptext tooltip-top"><?php echo $avg_rating; ?> / 5.0</span>
                                                                    </div>
                                                                </div>
                                                                <div class="product-price">
                                                                    &#8358;<?php
            echo number_format($row['sellingprice'], 2);
        ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php }} ?>

                                                    </div>
                                                    
                                                </div>

                                                <button class="swiper-button-next"></button>
                                                <button class="swiper-button-prev"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </aside>
                        <!-- End of Sidebar -->
                    </div>
                </div>
            </div>
            <!-- End of Page Content -->
        </main>
        <!-- End of Main -->

        <?php
        include("footer.php");
        ?>
        
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

    <!-- Root element of PhotoSwipe. Must have class pswp -->
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

        <!-- Background of PhotoSwipe. It's a separate element as animating opacity is faster than rgba(). -->
        <div class="pswp__bg"></div>

        <!-- Slides wrapper with overflow:hidden. -->
        <div class="pswp__scroll-wrap">

            <!-- Container that holds slides.
			PhotoSwipe keeps only 3 of them in the DOM to save memory.
			Don't modify these 3 pswp__item elements, data is added later on. -->
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
            <div class="pswp__ui pswp__ui--hidden">

                <div class="pswp__top-bar">

                    <!--  Controls are self-explanatory. Order can be changed. -->

                    <div class="pswp__counter"></div>

                    <button class="pswp__button pswp__button--close" aria-label="Close (Esc)"></button>
                    <button class="pswp__button pswp__button--zoom" aria-label="Zoom in/out"></button>

                    <div class="pswp__preloader">
                        <div class="loading-spin"></div>
                    </div>
                </div>

                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>

                <button class="pswp__button--arrow--left" aria-label="Previous (arrow left)"></button>
                <button class="pswp__button--arrow--right" aria-label="Next (arrow right)"></button>

                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- End of PhotoSwipe -->

    <!-- Start of Quick View -->
    <!--<div class="product product-single product-popup">-->
    <!--    <div class="row gutter-lg">-->
    <!--        <div class="col-md-6 mb-4 mb-md-0">-->
    <!--            <div class="product-gallery product-gallery-sticky">-->
    <!--                <div class="swiper-container product-single-swiper swiper-theme nav-inner">-->
    <!--                    <div class="swiper-wrapper row cols-1 gutter-no">-->
    <!--                        <div class="swiper-slide">-->
    <!--                            <figure class="product-image">-->
    <!--                                <img src="assets/images/products/popup/1-440x494.jpg"-->
    <!--                                    data-zoom-image="assets/images/products/popup/1-800x900.jpg"-->
    <!--                                    alt="Water Boil Black Utensil" width="800" height="900">-->
    <!--                            </figure>-->
    <!--                        </div>-->
    <!--                        <div class="swiper-slide">-->
    <!--                            <figure class="product-image">-->
    <!--                                <img src="assets/images/products/popup/2-440x494.jpg"-->
    <!--                                    data-zoom-image="assets/images/products/popup/2-800x900.jpg"-->
    <!--                                    alt="Water Boil Black Utensil" width="800" height="900">-->
    <!--                            </figure>-->
    <!--                        </div>-->
    <!--                        <div class="swiper-slide">-->
    <!--                            <figure class="product-image">-->
    <!--                                <img src="assets/images/products/popup/3-440x494.jpg"-->
    <!--                                    data-zoom-image="assets/images/products/popup/3-800x900.jpg"-->
    <!--                                    alt="Water Boil Black Utensil" width="800" height="900">-->
    <!--                            </figure>-->
    <!--                        </div>-->
    <!--                        <div class="swiper-slide">-->
    <!--                            <figure class="product-image">-->
    <!--                                <img src="assets/images/products/popup/4-440x494.jpg"-->
    <!--                                    data-zoom-image="assets/images/products/popup/4-800x900.jpg"-->
    <!--                                    alt="Water Boil Black Utensil" width="800" height="900">-->
    <!--                            </figure>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <button class="swiper-button-next"></button>-->
    <!--                    <button class="swiper-button-prev"></button>-->
    <!--                </div>-->
    <!--                <div class="product-thumbs-wrap swiper-container" data-swiper-options="{-->
    <!--                    'navigation': {-->
    <!--                        'nextEl': '.swiper-button-next',-->
    <!--                        'prevEl': '.swiper-button-prev'-->
    <!--                    }-->
    <!--                }">-->
    <!--                    <div class="product-thumbs swiper-wrapper row cols-4 gutter-sm">-->
    <!--                        <div class="product-thumb swiper-slide">-->
    <!--                            <img src="assets/images/products/popup/1-103x116.jpg" alt="Product Thumb" width="103"-->
    <!--                                height="116">-->
    <!--                        </div>-->
    <!--                        <div class="product-thumb swiper-slide">-->
    <!--                            <img src="assets/images/products/popup/2-103x116.jpg" alt="Product Thumb" width="103"-->
    <!--                                height="116">-->
    <!--                        </div>-->
    <!--                        <div class="product-thumb swiper-slide">-->
    <!--                            <img src="assets/images/products/popup/3-103x116.jpg" alt="Product Thumb" width="103"-->
    <!--                                height="116">-->
    <!--                        </div>-->
    <!--                        <div class="product-thumb swiper-slide">-->
    <!--                            <img src="assets/images/products/popup/4-103x116.jpg" alt="Product Thumb" width="103"-->
    <!--                                height="116">-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <button class="swiper-button-next"></button>-->
    <!--                    <button class="swiper-button-prev"></button>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="col-md-6 overflow-hidden p-relative">-->
    <!--            <div class="product-details scrollable pl-0">-->
    <!--                <h2 class="product-title">Electronics Black Wrist Watch</h2>-->
    <!--                <div class="product-bm-wrapper">-->
    <!--                    <figure class="brand">-->
    <!--                        <img src="assets/images/products/brand/brand-1.jpg" alt="Brand" width="102" height="48" />-->
    <!--                    </figure>-->
    <!--                    <div class="product-meta">-->
    <!--                        <div class="product-categories">-->
    <!--                            Category:-->
    <!--                            <span class="product-category"><a href="#">Electronics</a></span>-->
    <!--                        </div>-->
    <!--                        <div class="product-sku">-->
    <!--                            SKU: <span>MS46891340</span>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                </div>-->

    <!--                <hr class="product-divider">-->

    <!--                <div class="product-price">$40.00</div>-->

    <!--                <div class="ratings-container">-->
    <!--                    <div class="ratings-full">-->
    <!--                        <span class="ratings" style="width: 80%;"></span>-->
    <!--                        <span class="tooltiptext tooltip-top"></span>-->
    <!--                    </div>-->
    <!--                    <a href="#" class="rating-reviews">(3 Reviews)</a>-->
    <!--                </div>-->

    <!--                <div class="product-short-desc">-->
    <!--                    <ul class="list-type-check list-style-none">-->
    <!--                        <li>Ultrices eros in cursus turpis massa cursus mattis.</li>-->
    <!--                        <li>Volutpat ac tincidunt vitae semper quis lectus.</li>-->
    <!--                        <li>Aliquam id diam maecenas ultricies mi eget mauris.</li>-->
    <!--                    </ul>-->
    <!--                </div>-->

    <!--                <hr class="product-divider">-->

    <!--                <div class="product-form product-variation-form product-color-swatch">-->
    <!--                    <label>Color:</label>-->
    <!--                    <div class="d-flex align-items-center product-variations">-->
    <!--                        <a href="#" class="color" style="background-color: #ffcc01"></a>-->
    <!--                        <a href="#" class="color" style="background-color: #ca6d00;"></a>-->
    <!--                        <a href="#" class="color" style="background-color: #1c93cb;"></a>-->
    <!--                        <a href="#" class="color" style="background-color: #ccc;"></a>-->
    <!--                        <a href="#" class="color" style="background-color: #333;"></a>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--                <div class="product-form product-variation-form product-size-swatch">-->
    <!--                    <label class="mb-1">Size:</label>-->
    <!--                    <div class="flex-wrap d-flex align-items-center product-variations">-->
    <!--                        <a href="#" class="size">Small</a>-->
    <!--                        <a href="#" class="size">Medium</a>-->
    <!--                        <a href="#" class="size">Large</a>-->
    <!--                        <a href="#" class="size">Extra Large</a>-->
    <!--                    </div>-->
    <!--                    <a href="#" class="product-variation-clean">Clean All</a>-->
    <!--                </div>-->

    <!--                <div class="product-variation-price">-->
    <!--                    <span></span>-->
    <!--                </div>-->

    <!--                <div class="product-form">-->
    <!--                    <div class="product-qty-form">-->
    <!--                        <div class="input-group">-->
    <!--                            <input class="quantity form-control" type="number" min="1" max="10000000">-->
    <!--                            <button class="quantity-plus w-icon-plus"></button>-->
    <!--                            <button class="quantity-minus w-icon-minus"></button>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <button class="btn btn-primary btn-cart">-->
    <!--                        <i class="w-icon-cart"></i>-->
    <!--                        <span>Add to Cart</span>-->
    <!--                    </button>-->
    <!--                </div>-->

    <!--                <div class="social-links-wrapper">-->
    <!--                    <div class="social-links">-->
    <!--                        <div class="social-icons social-no-color border-thin">-->
    <!--                            <a href="#" class="social-icon social-facebook w-icon-facebook"></a>-->
    <!--                            <a href="#" class="social-icon social-twitter w-icon-twitter"></a>-->
    <!--                            <a href="#" class="social-icon social-pinterest fab fa-pinterest-p"></a>-->
    <!--                            <a href="#" class="social-icon social-whatsapp fab fa-whatsapp"></a>-->
    <!--                            <a href="#" class="social-icon social-youtube fab fa-linkedin-in"></a>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <span class="divider d-xs-show"></span>-->
                        
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <!-- End of Quick view -->

    <!-- Plugin JS File -->
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/sticky/sticky.js"></script>
    <script src="assets/vendor/jquery.plugin/jquery.plugin.min.js"></script>
    <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/zoom/jquery.zoom.js"></script>
    <script src="assets/vendor/photoswipe/photoswipe.js"></script>
    <script src="assets/vendor/photoswipe/photoswipe-ui-default.js"></script>

    <!-- Swiper JS -->
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.min.js"></script>
    <script>
    $(document).ready(function() {
        // Clear quantity input value on load so it stays empty by default
        var $qtyInput = $('#product-quantity-input');
        $qtyInput.val('');
        setTimeout(function() {
            $qtyInput.val('');
        }, 50);

        // Validate quantity on form submission
        $('#add-to-cart-form').on('submit', function(e) {
            var qtyVal = $.trim($qtyInput.val());
            if (qtyVal === '' || isNaN(qtyVal) || parseInt(qtyVal, 10) <= 0) {
                e.preventDefault();
                alert('Please enter a valid quantity before adding to cart.');
                $qtyInput.focus();
                return false;
            }
        });

        $('#btn-wishlist-toggle').on('click', function(e) {
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
                            $btn.addClass('added').attr('title', 'Remove from Wishlist')
                                .css({'color': '#e3342f', 'border-color': '#e3342f', 'background-color': '#fff0f0'});
                            $('#wishlist-btn-text').text('Wishlisted');
                        } else {
                            $btn.removeClass('added').attr('title', 'Add to Wishlist')
                                .css({'color': '', 'border-color': '', 'background-color': ''});
                            $('#wishlist-btn-text').text('Add to Wishlist');
                        }
                        if ($('#header-wishlist-count').length) {
                            $('#header-wishlist-count').text(res.count);
                        }
                        if (typeof alertify !== 'undefined') {
                            alertify.success(res.message);
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
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9da9eb234f7662f4',t:'MTc3MzIyNTM5MA=='};var a=document.createElement('script');a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script><script defer src="https://static.cloudflareinsights.com/beacon.min.js/v8c78df7c7c0f484497ecbca7046644da1771523124516" integrity="sha512-8DS7rgIrAmghBFwoOTujcf6D9rXvH8xm8JQ1Ja01h9QX8EzXldiszufYa4IFfKdLUKTTrnSFXLDkUEOTrZQ8Qg==" data-cf-beacon='{"version":"2024.11.0","token":"ecd4920e43e14654b78e65dbf8311922","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}' crossorigin="anonymous"></script>
</body>

</html>