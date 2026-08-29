<?php 
session_start();
include('db_conn.php'); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>DEE MART || SHOP NOW</title>

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
                        <li><a href="index.php">Home</a></li>
                        <li>Shop Now</li>
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

                            $sql = "SELECT * FROM product_table WHERE approval_status = 'Approved' ORDER BY product_id DESC LIMIT $limit OFFSET $offset";
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
                                                  <a href="product.php?uin=<?php echo $row['uin']; ?>">
                                                      <img src="vendor/vendorupload/<?php echo htmlspecialchars($row['productimage']);?>" alt="Product" />
                                                  </a>
                                                  <div class="product-action-vertical">
                                                      <a href="addtowishlist.php?uin=<?php echo $row['uin']; ?>" class="btn-product-icon btn-wishlist w-icon-heart btn-add-wishlist-ajax" title="Add to Wishlist" data-uin="<?php echo $row['uin']; ?>"></a>
                                                  </div>
                                                  <div class="product-action">
                                                      
                                                  </div>
                                              </figure>

                                             <div class="product-details">
                                                 <div class="product-cat">
                                                     <a href="cat.php?category=<?php echo urlencode($row['category']); ?>"><?php echo htmlspecialchars($row['category']); ?></a>
                                                 </div>
                                                 <h3 class="product-name">
                                                     <a href="product.php?uin=<?php echo $row['uin']; ?>">
                                                         <?php echo htmlspecialchars($row['productname']); ?>
                                                     </a>
                                                 </h3>
                                                 <!-- Vendor Badge on Card -->
                                                 <div class="product-vendor-mini mt-1 mb-1" style="font-size: 12px; color: #666;">
                                                     <?php if (!empty($row['vendor_uin'])): ?>
                                                         <i class="fas fa-store text-primary me-1"></i>
                                                         <a href="vendor-store.php?vendor_uin=<?php echo $row['vendor_uin']; ?>" class="text-primary font-weight-bold" style="text-decoration: underline;">
                                                             <?php echo htmlspecialchars($row['vendor_storename']); ?>
                                                         </a>
                                                     <?php else: ?>
                                                         <span class="text-muted"><i class="fas fa-shield-alt text-success me-1"></i> DEE MART</span>
                                                     <?php endif; ?>
                                                 </div>
                                                 <?php
                                                 $p_uin = $row['uin'];
                                                 $r_stmt = mysqli_prepare($conn, "SELECT AVG(rating) as avg_r, COUNT(*) as cnt FROM product_reviews WHERE product_uin = ?");
                                                 $card_rating = 0;
                                                 $card_count = 0;
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
                                    echo "<p>No products found.</p>";
                                }
                                ?>
                            </div>

                            <?php
                            // PAGINATION LINKS
                            $count_sql = "SELECT COUNT(*) AS total FROM `product_table` WHERE approval_status = 'Approved'";
                            $count_result = mysqli_query($conn, $count_sql);
                            $count_row = mysqli_fetch_assoc($count_result);
                            $total_products = $count_row['total'];
                            $total_pages = ceil($total_products / $limit);

                            if ($total_pages > 1) {
                                echo '<div class="pagination" style="margin-top:30px; text-align:center;">';
                                if ($page > 1) {
                                    echo "<a href='?page=".($page - 1)."' style='margin-right:10px;'>« Prev</a>";
                                }
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    if ($i == $page) {
                                        echo "<strong style='margin:0 5px;'>$i</strong>";
                                    } else {
                                        echo "<a href='?page=$i' style='margin:0 5px;'>$i</a>";
                                    }
                                }
                                if ($page < $total_pages) {
                                    echo "<a href='?page=".($page + 1)."' style='margin-left:10px;'>Next »</a>";
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
</body>
</html>
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
   
    <!-- Start of Quick View -->
    <div class="product product-single product-popup">
        <div class="row gutter-lg">
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="product-gallery product-gallery-sticky">
                    <div class="swiper-container product-single-swiper swiper-theme nav-inner">
                        <div class="swiper-wrapper row cols-1 gutter-no">
                            <div class="swiper-slide">
                                <figure class="product-image">
                                    <img src="assets/images/products/popup/1-440x494.jpg"
                                        data-zoom-image="assets/images/products/popup/1-800x900.jpg"
                                        alt="Water Boil Black Utensil" width="800" height="900">
                                </figure>
                            </div>
                            <div class="swiper-slide">
                                <figure class="product-image">
                                    <img src="assets/images/products/popup/2-440x494.jpg"
                                        data-zoom-image="assets/images/products/popup/2-800x900.jpg"
                                        alt="Water Boil Black Utensil" width="800" height="900">
                                </figure>
                            </div>
                            <div class="swiper-slide">
                                <figure class="product-image">
                                    <img src="assets/images/products/popup/3-440x494.jpg"
                                        data-zoom-image="assets/images/products/popup/3-800x900.jpg"
                                        alt="Water Boil Black Utensil" width="800" height="900">
                                </figure>
                            </div>
                            <div class="swiper-slide">
                                <figure class="product-image">
                                    <img src="assets/images/products/popup/4-440x494.jpg"
                                        data-zoom-image="assets/images/products/popup/4-800x900.jpg"
                                        alt="Water Boil Black Utensil" width="800" height="900">
                                </figure>
                            </div>
                        </div>
                        <button class="swiper-button-next"></button>
                        <button class="swiper-button-prev"></button>
                    </div>
                    <div class="product-thumbs-wrap swiper-container" data-swiper-options="{
                        'navigation': {
                            'nextEl': '.swiper-button-next',
                            'prevEl': '.swiper-button-prev'
                        }
                    }">
                        <div class="product-thumbs swiper-wrapper row cols-4 gutter-sm">
                            <div class="product-thumb swiper-slide">
                                <img src="assets/images/products/popup/1-103x116.jpg" alt="Product Thumb" width="103"
                                    height="116">
                            </div>
                            <div class="product-thumb swiper-slide">
                                <img src="assets/images/products/popup/2-103x116.jpg" alt="Product Thumb" width="103"
                                    height="116">
                            </div>
                            <div class="product-thumb swiper-slide">
                                <img src="assets/images/products/popup/3-103x116.jpg" alt="Product Thumb" width="103"
                                    height="116">
                            </div>
                            <div class="product-thumb swiper-slide">
                                <img src="assets/images/products/popup/4-103x116.jpg" alt="Product Thumb" width="103"
                                    height="116">
                            </div>
                        </div>
                        <button class="swiper-button-next"></button>
                        <button class="swiper-button-prev"></button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 overflow-hidden p-relative">
                <div class="product-details scrollable pl-0">
                    <h2 class="product-title">Electronics Black Wrist Watch</h2>
                    <div class="product-bm-wrapper">
                        <figure class="brand">
                            <img src="assets/images/products/brand/brand-1.jpg" alt="Brand" width="102" height="48" />
                        </figure>
                        <div class="product-meta">
                            <div class="product-categories">
                                Category:
                                <span class="product-category"><a href="#">Electronics</a></span>
                            </div>
                            <div class="product-sku">
                                SKU: <span>MS46891340</span>
                            </div>
                        </div>
                    </div>

                    <hr class="product-divider">

                    <div class="product-price">$40.00</div>

                    <div class="ratings-container">
                        <div class="ratings-full">
                            <span class="ratings" style="width: 80%;"></span>
                            <span class="tooltiptext tooltip-top"></span>
                        </div>
                        <a href="#" class="rating-reviews">(3 Reviews)</a>
                    </div>

                    <div class="product-short-desc">
                        <ul class="list-type-check list-style-none">
                            <li>Ultrices eros in cursus turpis massa cursus mattis.</li>
                            <li>Volutpat ac tincidunt vitae semper quis lectus.</li>
                            <li>Aliquam id diam maecenas ultricies mi eget mauris.</li>
                        </ul>
                    </div>

                    <hr class="product-divider">

                    <div class="product-form product-variation-form product-color-swatch">
                        <label>Color:</label>
                        <div class="d-flex align-items-center product-variations">
                            <a href="#" class="color" style="background-color: #ffcc01"></a>
                            <a href="#" class="color" style="background-color: #ca6d00;"></a>
                            <a href="#" class="color" style="background-color: #1c93cb;"></a>
                            <a href="#" class="color" style="background-color: #ccc;"></a>
                            <a href="#" class="color" style="background-color: #333;"></a>
                        </div>
                    </div>
                    <div class="product-form product-variation-form product-size-swatch">
                        <label class="mb-1">Size:</label>
                        <div class="flex-wrap d-flex align-items-center product-variations">
                            <a href="#" class="size">Small</a>
                            <a href="#" class="size">Medium</a>
                            <a href="#" class="size">Large</a>
                            <a href="#" class="size">Extra Large</a>
                        </div>
                        <a href="#" class="product-variation-clean">Clean All</a>
                    </div>

                    <div class="product-variation-price">
                        <span></span>
                    </div>

                    <div class="product-form">
                        <div class="product-qty-form">
                            <div class="input-group">
                                <input class="quantity form-control" type="number" min="1" max="10000000">
                                <button class="quantity-plus w-icon-plus"></button>
                                <button class="quantity-minus w-icon-minus"></button>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-cart">
                            <i class="w-icon-cart"></i>
                            <!-- <span>Add to Cart</span> -->
                        </button>
                    </div>

                    <div class="social-links-wrapper">
                        <div class="social-links">
                            <div class="social-icons social-no-color border-thin">
                                <a href="#" class="social-icon social-facebook w-icon-facebook"></a>
                                <a href="#" class="social-icon social-twitter w-icon-twitter"></a>
                                <a href="#" class="social-icon social-pinterest fab fa-pinterest-p"></a>
                                <a href="#" class="social-icon social-whatsapp fab fa-whatsapp"></a>
                                <a href="#" class="social-icon social-youtube fab fa-linkedin-in"></a>
                            </div>
                        </div>
                        <span class="divider d-xs-show"></span>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Quick view -->

    <!-- Plugin JS File -->
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="assets/vendor/jquery/jquery.min.js"></script>
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
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9da9ebcbff3c62f4',t:'MTc3MzIyNTQxNw=='};var a=document.createElement('script');a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script><script defer src="https://static.cloudflareinsights.com/beacon.min.js/v8c78df7c7c0f484497ecbca7046644da1771523124516" integrity="sha512-8DS7rgIrAmghBFwoOTujcf6D9rXvH8xm8JQ1Ja01h9QX8EzXldiszufYa4IFfKdLUKTTrnSFXLDkUEOTrZQ8Qg==" data-cf-beacon='{"version":"2024.11.0","token":"ecd4920e43e14654b78e65dbf8311922","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}' crossorigin="anonymous"></script>
</body>

</html>
