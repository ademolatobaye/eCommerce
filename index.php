<?php
session_start();
include('db_conn.php');

date_default_timezone_set("Africa/Lagos");

// Only run logic for logged-in customers
if (isset($_SESSION['customer_email'])) {
    $customer_email = $_SESSION['customer_email'];

    // Fetch the customer_id (UIN) and store it in the session
    if (!isset($_SESSION['customer_uin'])) {
        $sql = "SELECT * FROM `customertable` WHERE customer_email='$customer_email' LIMIT 1";
        $user_res = mysqli_query($conn, $sql);
        $user_data = mysqli_fetch_array($user_res);
        
        if ($user_data) {
            $_SESSION['customer_uin'] = $user_data['customer_id'];
        }
    }

    // Restore or Generate Invoice
    if (!isset($_SESSION['invoicenumber'])) {
        $customer_uin = $_SESSION['customer_uin'];

        // Check if this customer already has a pending invoice in the database
        $sql = "SELECT * FROM `invoiceorder` 
            WHERE customer_uin = '$customer_uin' 
            AND paymentstatus = 'Pending' 
            LIMIT 1";
        $res = mysqli_query($conn, $sql);
        $existingInvoice = mysqli_fetch_array($res);

        if ($existingInvoice) {
            // Restore existing
            $_SESSION['invoicenumber'] = $existingInvoice['invoicenumber'];
        } else {
            // Generate brand new unique number
            $rand = rand(1000, 9999);
            $randTime = date("his");
            $randToday = date("dmy");
            $_SESSION['invoicenumber'] = "DEE-" . $randToday . $randTime . $rand;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title>DEE MART - Multipurpose E-Commerce Web Application</title>

    <meta name="keywords" content="" />
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/icons/favicon.png">

    <!-- WebFont.js -->
    <script>
        WebFontConfig = {
            google: { families: ['Poppins:400,500,600,700,800'] }
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

    <!-- Plugins CSS -->
    <!-- <link rel="stylesheet" href="assets/vendor/swiper/swiper-bundle.min.css"> -->
    <link rel="stylesheet" type="text/css" href="assets/vendor/animate/animate.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/magnific-popup/magnific-popup.min.css">
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="assets/vendor/swiper/swiper-bundle.min.css">

    <!-- Default CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/demo1.min.css">

    <style>
        .single-product .product-image img {
            width: 100%;
            height: 420px;
            object-fit: cover;
        }

        .single-product .product-thumb img {
            width: 100%;
            height: 68px;
            object-fit: cover;
        }

        .product-wrap .product-media {
            overflow: hidden;
        }

        .product-wrap .product-media img {
            width: 100%;
            height: 243px;
            object-fit: cover;
        }

        .product-widget .product-media img {
            width: 105px;
            height: 118px;
            object-fit: cover;
        }

        .category-media img {
            width: 100%;
            height: 140px;
            object-fit: cover;
        }

        .top-category-card {
            background-color: #fff;
            border-radius: .5rem;
            overflow: hidden;
            text-align: center;
            height: 100%;
        }

        .top-category-card .category-media {
            display: block;
        }

        .top-category-card .category-content {
            padding: 1.5rem 1rem 1.2rem;
        }

        .top-category-card .category-name {
            margin-bottom: .7rem;
        }

        .post-media img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
    </style>

</head>

<body class="home">
    <div class="page-wrapper">
        <h1 class="d-none">DEE MART - Multipurpose E-Commerce Web Application</h1>

        <?php
        include("header.php");
        ?>


        <!-- Start of Main-->
        <main class="main">
            <section class="intro-section">
                <div class="swiper-container swiper-theme nav-inner pg-inner swiper-nav-lg animation-slider pg-xxl-hide nav-xxl-show nav-hide"
                    data-swiper-options="{
                    'slidesPerView': 1,
                    'autoplay': {
                        'delay': 8000,
                        'disableOnInteraction': false
                    }
                }">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide banner banner-fixed intro-slide intro-slide1"
                            style="background-color: #ebeef2;">
                            <div class="container">
                                <figure class="slide-image skrollable slide-animate">
                                    <img src="assets/images/demos/demo1/sliders/shoes.png" alt="Banner"
                                        data-bottom-top="transform: translateY(10vh);"
                                        data-top-bottom="transform: translateY(-10vh);" width="474" height="397">
                                </figure>
                                <div class="banner-content y-50 text-right">
                                    <h5 class="banner-subtitle font-weight-normal text-default ls-50 lh-1 mb-2 slide-animate"
                                        data-animation-options="{
                                    'name': 'fadeInRightShorter',
                                    'duration': '1s',
                                    'delay': '.2s'
                                }">
                                        Men’s <span class="p-relative d-inline-block">Custom</span>
                                    </h5>
                                    <h3 class="banner-title font-weight-bolder ls-25 lh-1 slide-animate"
                                        data-animation-options="{
                                    'name': 'fadeInRightShorter',
                                    'duration': '1s',
                                    'delay': '.4s'
                                }">
                                         SHOES
                                    </h3>
                                    <p class="font-weight-normal text-default slide-animate" data-animation-options="{
                                    'name': 'fadeInRightShorter',
                                    'duration': '1s',
                                    'delay': '.6s'
                                }">
                                    </p>

                                    <a href="shop.php"
                                        class="btn btn-dark btn-outline btn-rounded btn-icon-right slide-animate"
                                        data-animation-options="{
                                    'name': 'fadeInRightShorter',
                                    'duration': '1s',
                                    'delay': '.8s'
                                }">SHOP NOW<i class="w-icon-long-arrow-right"></i></a>

                                </div>
                                <!-- End of .banner-content -->
                            </div>
                            <!-- End of .container -->
                        </div>
                        <!-- End of .intro-slide1 -->

                        <div class="swiper-slide banner banner-fixed intro-slide intro-slide2"
                            style="background-color: #ebeef2;">
                            <div class="container">
                                <figure class="slide-image skrollable slide-animate" data-animation-options="{
                                    'name': 'fadeInUpShorter',
                                    'duration': '1s'
                                }">
                                    <img src="assets/images/demos/demo1/sliders/men.png" alt="Banner"
                                        data-bottom-top="transform: translateX(10vh);"
                                        data-top-bottom="transform: translateX(-10vh);" width="480" height="633">
                                </figure>
                                <div class="banner-content d-inline-block y-50">
                                    <h5 class="banner-subtitle font-weight-normal text-default ls-50 slide-animate"
                                        data-animation-options="{
                                        'name': 'fadeInUpShorter',
                                        'duration': '1s',
                                        'delay': '.2s'
                                    }">
                                        Limited<span class="text-secondary"> Collection</span>
                                    </h5>
                                    <h3 class="banner-title font-weight-bolder text-dark mb-0 ls-25 slide-animate"
                                        data-animation-options="{
                                        'name': 'fadeInUpShorter',
                                        'duration': '1s',
                                        'delay': '.4s'
                                    }">
                                       School Bag
                                    </h3>
                                    <p class="font-weight-normal text-default slide-animate" data-animation-options="{
                                        'name': 'fadeInUpShorter',
                                        'duration': '1s',
                                        'delay': '.8s'
                                    }">

                                    </p>
                                    <a href="shop.php"
                                        class="btn btn-dark btn-outline btn-rounded btn-icon-right slide-animate"
                                        data-animation-options="{
                                        'name': 'fadeInUpShorter',
                                        'duration': '1s',
                                        'delay': '1s'
                                    }">
                                        SHOP NOW<i class="w-icon-long-arrow-right"></i>
                                    </a>
                                </div>
                                <!-- End of .banner-content -->
                            </div>
                            <!-- End of .container -->
                        </div>
                        <!-- End of .intro-slide2 -->

                        <div class="swiper-slide banner banner-fixed intro-slide intro-slide3"
                            style="background-color: #f0f1f2;">
                            <div class="container">
                                <figure class="slide-image skrollable slide-animate" data-animation-options="{
                                    'name': 'fadeInDownShorter',
                                    'duration': '1s'
                                }">
                                    <img src="assets/images/demos/demo1/sliders/skate.png" alt="Banner"
                                        data-bottom-top="transform: translateY(10vh);"
                                        data-top-bottom="transform: translateY(-10vh);" width="310" height="444">
                                </figure>
                                <div class="banner-content text-right y-50">
                                    <p class="font-weight-normal text-default text-uppercase mb-0 slide-animate"
                                        data-animation-options="{
                                        'name': 'fadeInLeftShorter',
                                        'duration': '1s',
                                        'delay': '.6s'
                                    }">

                                    </p>
                                    <h5 class="banner-subtitle font-weight-normal text-default ls-25 slide-animate"
                                        data-animation-options="{
                                        'name': 'fadeInLeftShorter',
                                        'duration': '1s',
                                        'delay': '.4s'
                                    }">
                                        Trending Collection
                                    </h5>
                                    <h3 class="banner-title p-relative font-weight-bolder ls-50 slide-animate"
                                        data-animation-options="{
                                        'name': 'fadeInLeftShorter',
                                        'duration': '1s',
                                        'delay': '.2s'
                                    }"><span class="text-white mr-4">Original Head</span>-set
                                    </h3>
                                    <div class="btn-group slide-animate" data-animation-options="{
                                        'name': 'fadeInLeftShorter',
                                        'duration': '1s',
                                        'delay': '.8s'
                                    }">
                                        <a href=""
                                            class="btn btn-dark btn-outline btn-rounded btn-icon-right">SHOP
                                            NOW<i class="w-icon-long-arrow-right"></i></a>
                                    </div>
                                    <!-- End of .banner-content -->
                                </div>
                                <!-- End of .container -->
                            </div>
                        </div>
                        <!-- End of .intro-slide3 -->
                    </div>
                    <div class="swiper-pagination"></div>
                    <button class="swiper-button-next"></button>
                    <button class="swiper-button-prev"></button>
                </div>
                <!-- End of .swiper-container -->
            </section>
            <!-- End of .intro-section -->

            <div class="container">
                <div class="swiper-container appear-animate icon-box-wrapper br-sm mt-6 mb-6" data-swiper-options="{
                    'slidesPerView': 1,
                    'loop': false,
                    'breakpoints': {
                        '576': {
                            'slidesPerView': 2
                        },
                        '768': {
                            'slidesPerView': 3
                        },
                        '1200': {
                            'slidesPerView': 4
                        }
                    }
                }">
                    <div class="swiper-wrapper row cols-md-4 cols-sm-3 cols-1">
                        <div class="swiper-slide icon-box icon-box-side icon-box-primary">
                            <span class="icon-box-icon icon-shipping">
                                <i class="w-icon-truck"></i>
                            </span>
                            <div class="icon-box-content">
                                <h4 class="icon-box-title font-weight-bold mb-1">Shipping & Delivery</h4>
                                <p class="text-default">Delivery anywhere in the country.</p>
                            </div>
                        </div>

                        <div class="swiper-slide icon-box icon-box-side icon-box-primary">
                            <span class="icon-box-icon icon-payment">
                                <i class="w-icon-bag"></i>
                            </span>
                            <div class="icon-box-content">
                                <h4 class="icon-box-title font-weight-bold mb-1">Secure Payment</h4>
                                <p class="text-default">We ensure secure payment always.</p>
                            </div>
                        </div>

                        <div class="swiper-slide icon-box icon-box-side icon-box-primary icon-box-money">
                            <span class="icon-box-icon icon-money">
                                <i class="w-icon-money"></i>
                            </span>

                            <div class="icon-box-content">
                                <h4 class="icon-box-title font-weight-bold mb-1">Quick Payment Confirmation</h4>
                                <p class="text-default">All payments gets confirmed in seconds.</p>
                            </div>   
                        </div>

                        <div class="swiper-slide icon-box icon-box-side icon-box-primary icon-box-chat">
                            <span class="icon-box-icon icon-chat">
                                <i class="w-icon-chat"></i>
                            </span>
                            <div class="icon-box-content">
                                <h4 class="icon-box-title font-weight-bold mb-1">Customer Support</h4>
                                <p class="text-default">Customer support available 24/7.

                                </p>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- End of Iocn Box Wrapper -->

                <div class="row deals-wrapper appear-animate mb-8">
                    <div class="col-lg-9 mb-4">
                        <div class="single-product h-100 br-sm">
                            <h4 class="title-sm title-underline font-weight-bolder ls-normal">
                                Newly Uploaded Products
                            </h4>
                            <div class="swiper">
                                <div class="swiper-container swiper-theme nav-top swiper-nav-lg" data-swiper-options="{
                                    'spaceBetween': 20,
                                    'slidesPerView': 1
                                }">

                                    <div class="swiper-wrapper row cols-1 gutter-no">
                                        <?php
                                $recent_products_list = CacheManager::get('index_recent_products_limit5');
                                if ($recent_products_list === null) {
                                    $recent_products_list = array();
                                    $sql = "SELECT * FROM `product_table` ORDER BY product_id DESC LIMIT 5";
                                    $result = mysqli_query($conn, $sql);
                                    if ($result && mysqli_num_rows($result) > 0){
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $prod_uin = mysqli_real_escape_string($conn, $row['uin']);
                                            $imgs_query = mysqli_query($conn, "SELECT * FROM `product_images` WHERE uin='$prod_uin' ORDER BY sort_order ASC");
                                            $product_images_list = array();
                                            if ($imgs_query && mysqli_num_rows($imgs_query) > 0) {
                                                while ($img_row = mysqli_fetch_assoc($imgs_query)) {
                                                    $product_images_list[] = $img_row['product_image'];
                                                }
                                            } else if (!empty($row['productimage'])) {
                                                $product_images_list[] = $row['productimage'];
                                            }
                                            $row['images_list'] = $product_images_list;
                                            $recent_products_list[] = $row;
                                        }
                                    }
                                    CacheManager::set('index_recent_products_limit5', $recent_products_list, 600);
                                }

                                if (!empty($recent_products_list)){
                                    foreach ($recent_products_list as $row) {
                                        $product_images_list = $row['images_list'];
                                ?>
                                        <div class="swiper-slide">
                                            <div class="product product-single row">
                                                <div class="col-md-6">
                                                    <div class="product-gallery product-gallery-sticky product-gallery-vertical">
                                                        <div class="swiper-container product-single-swiper swiper-theme nav-inner">
                                                            <div class="swiper-wrapper row cols-1 gutter-no">
                                                                <?php foreach ($product_images_list as $img_name) { ?>
                                                                <div class="swiper-slide">
                                                                    <figure class="product-image">
                                                                        <img src="dashboard/productupload/<?php echo htmlspecialchars($img_name); ?>"
                                                                            data-zoom-image="dashboard/productupload/<?php echo htmlspecialchars($img_name); ?>"
                                                                            alt="<?php echo htmlspecialchars($row['productname']); ?>" width="800"
                                                                            height="900">
                                                                    </figure>
                                                                </div>
                                                                <?php } ?>
                                                            </div>
                                                            <button class="swiper-button-next"></button>
                                                            <button class="swiper-button-prev"></button>
                                                            <div class="product-label-group">
                                                                
                                                            </div>
                                                        </div>

                                                        <div class="product-thumbs-wrap swiper-container"
                                                            data-swiper-options="{
                                                            'direction': 'vertical',
                                                            'breakpoints': {
                                                                '0': {
                                                                    'direction': 'horizontal',
                                                                    'slidesPerView': 4
                                                                },
                                                                '992': {
                                                                    'direction': 'vertical',
                                                                    'slidesPerView': 'auto'
                                                                }
                                                            }
                                                        }">

                                                            <div class="product-thumbs swiper-wrapper row cols-lg-1 cols-4 gutter-sm">
                                                                <?php foreach ($product_images_list as $img_name) { ?>
                                                                <div class="product-thumb swiper-slide">
                                                                    <img src="dashboard/productupload/<?php echo htmlspecialchars($img_name); ?>"
                                                                        alt="Product thumb" width="60" height="68" />
                                                                </div>
                                                                <?php } ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="product-details scrollable">
                                                        <h2 class="product-title mb-1"><a
                                                                href="product.php?uin=<?php echo $row['uin']; ?>"><?php echo $row['productname']; ?></a>
                                                            </h2>
                                                            

                                                        <hr class="product-divider">

                                                        <div class="product-price"><ins class="new-price ls-50">
                                                            &#8358;<?php
                                                            echo number_format($row['sellingprice'], 2);
                                                            ?>
                                                        </ins></div>


                                                        <div class="ratings-container">
                                                            <div class="ratings-full">
                                                                <span class="ratings" style="width: 80%;"></span>
                                                                <span class="tooltiptext tooltip-top"></span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php }} ?>

                                    </div>
                                    <button class="swiper-button-prev"></button>
                                    <button class="swiper-button-next"></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 mb-4">
                        <div class="widget widget-products widget-products-bordered h-100">
                            <div class="widget-body br-sm h-100">
                                <h4 class="title-sm title-underline font-weight-bolder ls-normal mb-2">
                                    Top Products
                                </h4>

                                <div class="swiper">
                                    <div class="swiper-container swiper-theme nav-top" data-swiper-options="{
                                        'slidesPerView': 1,
                                        'spaceBetween': 20,
                                        'breakpoints': {
                                            '576': {
                                                'slidesPerView': 2
                                            },
                                            '768': {
                                                'slidesPerView': 3
                                            },
                                            '992': {
                                                'slidesPerView': 1
                                            }
                                        }
                                    }">

                                      <div class="swiper-wrapper row cols-lg-1 cols-md-3">
         <?php
        $sql = "SELECT * FROM `product_table` ORDER BY product_id ASC";
        $result = mysqli_query($conn, $sql);
        $total_products = mysqli_num_rows($result);

        for ($offset = 0; $offset < $total_products; $offset += 4) {
    ?>
    <div class="swiper-slide product-widget-wrap">
        <?php
            $sql = "SELECT * FROM `product_table` ORDER BY product_id ASC LIMIT $offset, 4";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                $item_count = 0;
                $total_items_in_slide = mysqli_num_rows($result);
                while ($row = mysqli_fetch_array($result)) {
                    $item_count++;
        ?>
        <div class="product product-widget <?php if ($item_count != $total_items_in_slide) { echo 'bb-no'; } ?>">
            <figure class="product-media">
                <a href="product.php?uin=<?php echo $row['uin']; ?>">
                    <img src="dashboard/productupload/<?php echo $row['productimage']; ?>"
                        alt="Product" width="105" height="118" />
                </a>
            </figure>
            
            <div class="product-details">
                <h4 class="product-name">
                    <a href="product.php?uin=<?php echo $row['uin']; ?>"><?php echo $row['productname']; ?></a>
                </h4>
                <div class="ratings-container">
                    <div class="ratings-full">
                        <span class="ratings" style="width: 60%;"></span>
                        <span class="tooltiptext tooltip-top"></span>
                    </div>
                </div>
                <div class="product-price">
                    <ins class="new-price">&#8358;<?php echo number_format($row['sellingprice'], 2); ?></ins>
                </div>
            </div>
        </div>
        <?php
                }
            }
        ?>
    </div>
    <?php } ?>
</div>
                                        
                                        <button class="swiper-button-next"></button>
                                        <button class="swiper-button-prev"></button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Deals Wrapper -->
            </div>

            <section class="category-section top-category bg-grey pt-10 pb-10 appear-animate">
                <div class="container pb-2">
                    <h2 class="title justify-content-center pt-1 ls-normal mb-5">Top Categories</h2>
                    <div class="swiper">
                        <div class="swiper-container swiper-theme pg-show" data-swiper-options="{
                            'spaceBetween': 20,
                            'slidesPerView': 2,
                            'breakpoints': {
                                '576': {
                                    'slidesPerView': 3
                                },
                                '768': {
                                    'slidesPerView': 5
                                },
                                '992': {
                                    'slidesPerView': 6
                                }
                            }
                        }">
                            <div class="swiper-wrapper row cols-lg-6 cols-md-5 cols-sm-3 cols-2">
                                <?php
     $sql = "SELECT * FROM `product_table` WHERE category NOT IN ('Electronics') GROUP BY category ORDER BY product_id DESC";
     $result = mysqli_query($conn, $sql);
     if (mysqli_num_rows($result) > 0) {
         while ($row = mysqli_fetch_array($result)) {
     ?>

                                <div class="swiper-slide">
                                    <div class="top-category-card">
                                        <a href="cat.php?category=<?php echo urlencode($row['category']); ?>" class="category-media">
                                            <img src="dashboard/productupload/<?php echo $row['productimage']; ?>" alt="Category"
                                                width="130" height="80">
                                        </a>
                                        <div class="category-content">
                                            <h4 class="category-name"><?php echo $row['category']; ?></h4>
                                            <a href="cat.php?category=<?php echo urlencode($row['category']); ?>" class="btn btn-primary btn-link btn-underline">Shop Now</a>
                                        </div>
                                    </div>
                                </div>

                                <?php
         }}
                    ?>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End of .category-section top-category -->

            <div class="container">


                <div class="product-wrapper-1 appear-animate mb-5">
                <div class="title-link-wrapper pb-1 mb-4">
                    <?php
                    $sql = "SELECT * FROM `product_table` WHERE category IN ('Fashion') ORDER BY product_id DESC LIMIT 0, 8";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_array($result);
                    ?>
                    <h2 class="title ls-normal mb-0"><?php echo $row['category']; ?></h2>
                    <a href="shop.php" class="font-size-normal font-weight-bold ls-25 mb-0">More Products
                        <i class="w-icon-long-arrow-right"></i>
                    </a>
                    <?php } ?>
                </div>

                    <div class="row">
                        <div class="col-lg-3 col-sm-4 mb-4">
                            <?php
                    $sql = "SELECT * FROM `product_table` WHERE category IN ('Fashion') ORDER BY product_id ASC LIMIT 0, 8";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_array($result);
                    ?>
                            <div class="banner h-100 br-sm" style="background-image: url(dashboard/productupload/<?php echo $row['productimage']; ?>); 
                                background-color: #ebeced;">
                                <div class="banner-content content-top">
                                    <h5 class="banner-subtitle font-weight-normal mb-2">Cool Stuff</h5>
                                    <hr class="banner-divider bg-dark mb-2">
                                    <h3 class="banner-title font-weight-bolder ls-25 text-uppercase">
                                        New Arrival<br>
                                    </h3>
                                    <a href="shop.php"
                                        class="btn btn-dark btn-outline btn-rounded btn-sm">Shop Now</a>
                                </div>
                            </div>
                            <?php
                    }
                            ?>
                         
                        </div>
                        <!-- End of Banner -->

                        <div class="col-lg-9 col-sm-8">
                            <div class="swiper-container swiper-theme" data-swiper-options="{
                                'spaceBetween': 20,
                                'slidesPerView': 2,
                                'breakpoints': {
                                    '992': {
                                        'slidesPerView': 3
                                    },
                                    '1200': {
                                        'slidesPerView': 4
                                    }
                                }
                            }">
                                <div class="swiper-wrapper row cols-xl-4 cols-lg-3 cols-2">
                                     <?php
     $sql = "SELECT * FROM `product_table` WHERE category IN ('Fashion') ORDER BY product_id DESC LIMIT 0, 5";
     $result = mysqli_query($conn, $sql);
     if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
        ?>

                                    <div class="swiper-slide product-col">
                                        <div class="product-wrap product text-center">
                                            <figure class="product-media">
                                                <a href="product.php?uin=<?php echo $row['uin']; ?>">
                                                    <img src="dashboard/productupload/<?php echo $row['productimage']; ?>" alt="Product"
                                                        width="216" height="243">
                                                </a>

                                                <div class="product-action-vertical">
                                                    <a href="addtowishlist.php?uin=<?php echo $row['uin']; ?>" class="btn-product-icon btn-wishlist w-icon-heart btn-add-wishlist-ajax" title="Add to Wishlist" data-uin="<?php echo $row['uin']; ?>"></a>
                                                    <a href="product.php?uin=<?php echo $row['uin']; ?>" class="btn-product-icon btn-quickview w-icon-search" title="Quickview"></a>
                                                </div>
                                                <div class="product-action">
                                                    <a href="addtocart.php?uin=<?php echo $row['uin']; ?>&product_id=<?php echo $row['product_id']; ?>&quantity=1" class="btn-product btn-cart btn-add-cart-ajax" title="Add to Cart">
                                                        <i class="w-icon-cart"></i> Add To Cart
                                                    </a>
                                                </div>
                                            </figure>

                                            <div class="product-details">
                                                <h4 class="product-name"><a href="product.php?uin=<?php echo $row['uin']; ?>">
                                                    <?php echo $row['productname']; ?>
                                                </a>
                                                </h4>


                                                <div class="product-price">
                                                    <ins class="new-price">
                                                        &#8358;<?php
                                                            echo number_format($row['sellingprice'], 2); ?>
                                                    </ins>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>

                    <?php
         
                    ?>

                </div>
                <!-- End of Product Wrapper 1 -->

                <div class="product-wrapper-1 appear-animate mb-8">
                    <div class="title-link-wrapper pb-1 mb-4">
                        <?php
     $sql = "SELECT * FROM `product_table` WHERE category IN ('Gadgets')";
     $result = mysqli_query($conn, $sql);
     if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        ?>
                        <h2 class="title ls-normal mb-0"><?php echo $row['category']; ?></h2>
                        <a href="shop.php" class="font-size-normal font-weight-bold ls-25 mb-0">More
                            Products<i class="w-icon-long-arrow-right"></i></a>
                            <?php 
                            }
                            ?>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-sm-4 mb-4">
                            <div class="banner h-100 br-sm" style="background-image: url(assets/images/demos/demo1/banners/3.jpg); 
                            background-color: #252525;">
                                <div class="banner-content content-top">
                                    <h5 class="banner-subtitle text-white font-weight-normal mb-2">Sleek Gadgets</h5>
                                    <hr class="banner-divider bg-white mb-2">
                                    <h3 class="banner-title text-white font-weight-bolder text-uppercase ls-25">
                                        Laptops, iPhones, Headsets <br>
                                    </h3>
                                    <a href="shop.php"
                                        class="btn btn-white btn-outline btn-rounded btn-sm">Shop now</a>
                                </div>
                            </div>
                        </div>
                        <!-- End of Banner -->

                        <div class="col-lg-9 col-sm-8">
                            <div class="swiper-container swiper-theme" data-swiper-options="{
                                'spaceBetween': 20,
                                'slidesPerView': 2,
                                'breakpoints': {
                                    '992': {
                                        'slidesPerView': 3
                                    },
                                    '1200': {
                                        'slidesPerView': 4
                                    }
                                }
                            }">

                                <div class="swiper-wrapper row cols-xl-4 cols-lg-3 cols-2">
                                    <?php
     $sql = "SELECT * FROM `product_table` WHERE category IN ('Gadgets') ORDER BY product_id DESC LIMIT 0, 5";
     $result = mysqli_query($conn, $sql);
     if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
        ?>
                                    
                                    <div class="swiper-slide product-col">
                                        <div class="product-wrap product text-center">
                                            <figure class="product-media">
                                                <a href="product.php?uin=<?php echo $row['uin']; ?>">
                                                    <img src="dashboard/productupload/<?php echo $row['productimage']; ?>"
                                                        alt="Product" width="216" height="243" />
                                                </a>

                                                <div class="product-action-vertical">
                                                    <a href="addtowishlist.php?uin=<?php echo $row['uin']; ?>" class="btn-product-icon btn-wishlist w-icon-heart btn-add-wishlist-ajax" title="Add to Wishlist" data-uin="<?php echo $row['uin']; ?>"></a>
                                                    <a href="product.php?uin=<?php echo $row['uin']; ?>" class="btn-product-icon btn-quickview w-icon-search" title="Quickview"></a>
                                                </div>
                                                <div class="product-action">
                                                    <a href="addtocart.php?uin=<?php echo $row['uin']; ?>&product_id=<?php echo $row['product_id']; ?>&quantity=1" class="btn-product btn-cart btn-add-cart-ajax" title="Add to Cart">
                                                        <i class="w-icon-cart"></i> Add To Cart
                                                    </a>
                                                </div>
                                            </figure>
                                            
                                            <div class="product-details">
                                                <h4 class="product-name"><a href="product.php?uin=<?php echo $row['uin']; ?>">
                                                    <?php echo $row['productname']; ?>
                                                </a></h4>
                                               
                                                <div class="product-price">
                                                    <ins class="new-price">
                                                        &#8358;<?php
                                                            echo number_format($row['sellingprice'], 2);?>
                                                    </ins>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <?php
                                         }}
                                        ?>

                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                            <!-- End of Produts -->
                        </div>
                    </div>
                </div>
                <!-- End of Product Wrapper 1 -->

              
                <div class="product-wrapper-1 appear-animate mb-7">

                    <div class="title-link-wrapper pb-1 mb-4">
                        <?php
     $sql = "SELECT * FROM `product_table` WHERE category IN ('Shoes')";
     $result = mysqli_query($conn, $sql);
     if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        ?>
                        <h2 class="title ls-normal mb-0"><?php echo $row['category']; ?></h2>
                        <a href="shop.php" class="font-size-normal font-weight-bold ls-25 mb-0">More
                            Products<i class="w-icon-long-arrow-right"></i></a>
                            <?php } ?>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-3 col-sm-4 mb-4">
                            <?php
     $sql = "SELECT * FROM `product_table` WHERE category IN ('Shoes') ORDER BY product_id ASC LIMIT 0, 8";
     $result = mysqli_query($conn, $sql);
     if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        ?>
                            <div class="banner h-100 br-sm" style="background-image: url(dashboard/productupload/<?php echo $row['productimage']; ?>); 
                            background-color: #EAEFF3;">
                                <div class="banner-content content-top">
                                    <h5 class="banner-subtitle font-weight-normal mb-2 text-white">New Kicks</h5>
                                    <hr class="banner-divider bg-dark mb-2">
                                    <h3 class="banner-title font-weight-bolder text-uppercase ls-25 text-white">
                                    Latest Kicks<br>
                                    </h3>
                                    <a href="shop.php"
                                        class="btn btn-dark btn-outline btn-rounded btn-sm text-white">Shop now</a>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <!-- End of Banner -->

                        <div class="col-lg-9 col-sm-8">
                            <div class="swiper-container swiper-theme" data-swiper-options="{
                                'spaceBetween': 20,
                                'slidesPerView': 2,
                                'breakpoints': {
                                    '992': {
                                        'slidesPerView': 3
                                    },
                                    '1200': {
                                        'slidesPerView': 4
                                    }
                                }
                            }">
                                <div class="swiper-wrapper row cols-xl-4 cols-lg-3 cols-2">
                            <?php
                            $sql = "SELECT * FROM `product_table` WHERE category IN ('Shoes') ORDER BY product_id DESC";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                            ?>
                            <div class="swiper-slide product-col">
                                <div class="product-wrap product text-center">
                                    <figure class="product-media">
                                        <a href="product.php?uin=<?php echo $row['uin']; ?>">
                                            <img src="dashboard/productupload/<?php echo $row['productimage']; ?>" alt="Product"
                                                width="216" height="243" />
                                        </a>

                                        <div class="product-action-vertical">
                                            <a href="addtowishlist.php?uin=<?php echo $row['uin']; ?>" class="btn-product-icon btn-wishlist w-icon-heart btn-add-wishlist-ajax" title="Add to Wishlist" data-uin="<?php echo $row['uin']; ?>"></a>
                                            <a href="product.php?uin=<?php echo $row['uin']; ?>" class="btn-product-icon btn-quickview w-icon-search" title="Quickview"></a>
                                        </div>
                                        <div class="product-action">
                                            <a href="addtocart.php?uin=<?php echo $row['uin']; ?>&product_id=<?php echo $row['product_id']; ?>&quantity=1" class="btn-product btn-cart btn-add-cart-ajax" title="Add to Cart">
                                                <i class="w-icon-cart"></i> Add To Cart
                                            </a>
                                        </div>
                                    </figure>

                                    <div class="product-details">
                                        <h4 class="product-name">
                                            <a href="product.php?uin=<?php echo $row['uin']; ?>">
                                                <?php echo $row['productname']; ?>
                                            </a>
                                        </h4>
                                        <div class="product-price">
                                            <ins class="new-price">
                                                &#8358;<?php echo number_format($row['sellingprice'], 2); ?>
                                            </ins>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                                <div class="swiper-pagination"></div>
                            </div>
                            <!-- End of Produts -->
                        </div>
                    </div>
                </div>
                <!-- End of Product Wrapper 1 -->

                <div class="post-wrapper appear-animate mb-4">
                    <div class="title-link-wrapper pb-1 mb-4">
                        <h2 class="title ls-normal mb-0">Our Blog</h2>
                        <a href="blog.php" class="font-weight-bold font-size-normal">View All Articles</a>
                    </div>
                    <div class="swiper">
                        <div class="swiper-container swiper-theme" data-swiper-options="{
                            'slidesPerView': 1,
                            'spaceBetween': 20,
                            'breakpoints': {
                                '576': {
                                    'slidesPerView': 2
                                },
                                '768': {
                                    'slidesPerView': 3
                                },
                                '992': {
                                    'slidesPerView': 4
                                }
                            }
                        }">
                            <div class="swiper-wrapper row cols-lg-4 cols-md-3 cols-sm-2 cols-1">
                                <?php
                            $sql = "SELECT * FROM `blog` ORDER BY id ASC";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                            ?>
                                <div class="swiper-slide post text-center overlay-zoom">
                                    <figure class="post-media br-sm">
                                        <a href="blog.php">
                                            <img src="dashboard/blogupload/<?php echo $row['blogimage']; ?>" alt="Post" width="280"
                                                height="180" style="background-color: #4b6e91;" />
                                        </a>
                                    </figure>
                                    <div class="post-details">
                                        <div class="post-meta">
                                            by <a href="blog.php" class="post-author"><?php echo $row['photocredit']; ?></a>
                                            - <a href="blog.php" class="post-date mr-0"><?php echo $row['date']; ?></a>
                                        </div>
                                        <h4 class="post-title"><a href="">
                                            <?php echo $row['headline']; ?>
                                        </a>
                                        </h4>
                                        <a href="blog.php" class="btn btn-link btn-dark btn-underline">Read
                                            More<i class="w-icon-long-arrow-right"></i></a>
                                    </div>
                                </div>

                                <?php }} ?>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                </div>
                <!-- Post Wrapper -->

               
            </div>
            <!--End of Catainer -->
        </main>
        <!-- End of Main -->

        <?php
        include("footer.php");
        ?>

    </div>
    <!-- End of Page-wrapper-->

       <?php
       include("sticky-footer.php");
       ?>

   
    <!-- Start of Scroll Top -->
    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button"> <i class="w-icon-angle-up"></i> <svg
            version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70">
            <circle id="progress-indicator" fill="transparent" stroke="#4B0082" stroke-miterlimit="10" cx="35" cy="35"
                r="34" style="stroke-dasharray: 16.4198, 400;"></circle>
        </svg> </a>
    <!-- End of Scroll Top -->

    <?php
    include("mobile-menu.php");
    ?>
   
    <!-- Start of Newsletter popup -->
    
    <!-- End of Newsletter popup -->

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
    <!--                            <span class="product-category"><a href="shop.php">Electronics</a></span>-->
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
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/jquery.plugin/jquery.plugin.min.js"></script>
    <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="assets/vendor/zoom/jquery.zoom.js"></script>
    <script src="assets/vendor/jquery.countdown/jquery.countdown.min.js"></script>
    <!-- <script src="assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script> -->
    <script src="assets/vendor/skrollr/skrollr.min.js"></script>

    <!-- Swiper JS -->
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

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
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/v8c78df7c7c0f484497ecbca7046644da1771523124516" integrity="sha512-8DS7rgIrAmghBFwoOTujcf6D9rXvH8xm8JQ1Ja01h9QX8EzXldiszufYa4IFfKdLUKTTrnSFXLDkUEOTrZQ8Qg==" data-cf-beacon='{"version":"2024.11.0","token":"ecd4920e43e14654b78e65dbf8311922","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}' crossorigin="anonymous"></script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9daa04e618a24813',t:'MTc3MzIyNjQ0NQ=='};var a=document.createElement('script');a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>

</html>
