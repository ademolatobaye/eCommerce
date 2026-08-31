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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title><?php echo $business_name;?> || ABOUT US</title>

    <meta name="keywords" content="<?php echo $business_name;?>, ecommerce, online shopping, Nigeria">
    <meta name="description" content="Learn more about <?php echo $business_name;?>, our mission, and how we make online shopping simple for customers.">
    <meta name="author" content="<?php echo $business_name;?>">

    <link rel="icon" type="image/png" href="assets/images/icons/favicon.png">

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

    <link rel="preload" href="assets/vendor/fontawesome-free/webfonts/fa-regular-400.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="assets/vendor/fontawesome-free/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="assets/vendor/fontawesome-free/webfonts/fa-brands-400.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="assets/fonts/wolmart.woff?png09e" as="font" type="font/woff" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/animate/animate.min.css">
    <link rel="stylesheet" href="assets/vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/magnific-popup/magnific-popup.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css">

    <style>
        .about-card {
            height: 100%;
            padding: 2.5rem 2rem;
            border: 1px solid #eee;
            border-radius: 1rem;
            background-color: #fff;
        }

        .about-card .icon-box-icon {
            margin-bottom: 1.2rem;
            font-size: 2.4rem;
            color: #336699;
        }

        .product-media img {
            width: 100%;
            height: 240px;
            object-fit: cover;
        }

        .product-wrap {
            margin-bottom: 2rem;
        }

        .category-pill {
            display: inline-block;
            margin: 0 .8rem .8rem 0;
            padding: .8rem 1.4rem;
            border: 1px solid #ddd;
            border-radius: 999px;
            color: #333;
            transition: all .2s ease;
        }

        .category-pill:hover {
            border-color: #4B0082;
            color: #4B0082;
        }
    </style>
</head>

<body class="about-us">
    <div class="page-wrapper">
        <?php 
        include("header.php");
         ?>

        <main class="main">
            <div class="page-header">
                <div class="container">
                    <h1 class="page-title mb-0">About Us</h1>
                </div>
            </div>

            <nav class="breadcrumb-nav mb-10 pb-2">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li>About Us</li>
                    </ul>
                </div>
            </nav>

            <div class="page-content">
                <div class="container">
                    <section class="introduce mb-10 pb-10">
                        <h2 class="title title-center">
                            <?php echo $business_name;?> makes shopping easier,
                            faster, and more reliable
                        </h2>
                        <p class="mx-auto text-center">
                            We built <?php echo $business_name;?> to connect customers with quality products in one place, with simple browsing,
                            secure checkout, and support that stays helpful from first click to final delivery.
                        </p>
                        <figure class="br-lg">
                            <img src="assets/images/pages/about_us/1.jpg" alt="<?php echo $business_name;?> banner"
                                width="1240" height="540" style="background-color: #D0C1AE;" />
                        </figure>
                    </section>

                    <section class="customer-service mb-10">
                        <div class="row align-items-center">
                            <div class="col-md-6 pr-lg-8 mb-8">
                                <h2 class="title text-left">What <?php echo $business_name;?> is built around</h2>
                                <div class="accordion accordion-simple accordion-plus">
                                    <div class="card border-no">
                                        <div class="card-header">
                                            <a href="#collapse3-1" class="collapse">A smooth shopping experience</a>
                                        </div>
                                        <div class="card-body expanded" id="collapse3-1">
                                            <p class="mb-0">
                                                From the homepage to product pages and checkout, <?php echo $business_name;?> is designed to help
                                                shoppers find what they need quickly and place orders without confusion.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <a href="#collapse3-2" class="expand">Useful product discovery</a>
                                        </div>
                                        <div class="card-body collapsed" id="collapse3-2">
                                            <p class="mb-0">
                                                Customers can browse by category, search for products, compare options, and
                                                explore fresh arrivals in a clean, store-focused experience.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <a href="#collapse3-3" class="expand">Real support and trust</a>
                                        </div>
                                        <div class="card-body collapsed" id="collapse3-3">
                                            <p class="mb-0">
                                                We want every purchase to feel dependable, with clear pricing, secure payments,
                                                helpful communication, and customer care that stays available when needed.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-8">
                                <figure class="br-lg">
                                    <img src="assets/images/pages/about_us/2.jpg" alt="<?php echo $business_name;?> service"
                                        width="610" height="500" style="background-color: #CECECC;" />
                                </figure>
                            </div>
                        </div>
                    </section>

                    <section class="count-section mb-10 pb-5">
                        <div class="swiper-container swiper-theme" data-swiper-options="{
                            'slidesPerView': 1,
                            'breakpoints': {
                                '576': { 'slidesPerView': 2 },
                                '992': { 'slidesPerView': 4 }
                            }
                        }">
                            <div class="swiper-wrapper row cols-lg-4 cols-sm-2 cols-1">
                                <div class="swiper-slide counter-wrap">
                                    <div class="counter text-center">
                                        <?php
                                        $sql = "SELECT * FROM `product_table` WHERE approval_status = 'Approved'";
                                        $result = mysqli_query($conn, $sql);
                                        $productCount = mysqli_num_rows($result);
                                        ?>
                                        <span class="count-to" data-to="<?php echo $productCount; ?>">0</span>
                                        <h4 class="title title-center">Products Listed</h4>
                                        <p>Items available for customers to discover and order.</p>
                                    </div>
                                </div>
                                <div class="swiper-slide counter-wrap">
                                    <div class="counter text-center">
                                        <?php
                                        $sql = "SELECT * FROM `category`";
                                        $result = mysqli_query($conn, $sql);
                                        $categoryCount = mysqli_num_rows($result);
                                        ?>
                                        <span class="count-to" data-to="<?php echo $categoryCount; ?>">0</span>
                                        <h4 class="title title-center">Store Categories</h4>
                                        <p>Organized departments that make browsing simple.</p>
                                    </div>
                                </div>
                                <div class="swiper-slide counter-wrap">
                                    <div class="counter text-center">
                                        <?php
                                        $sql = "SELECT * FROM `customertable`";
                                        $result = mysqli_query($conn, $sql);
                                        $customerCount = mysqli_num_rows($result);
                                        ?>
                                        <span class="count-to" data-to="<?php echo $customerCount; ?>">0</span>
                                        <h4 class="title title-center">Registered Shoppers</h4>
                                        <p>A growing customer base choosing <?php echo $business_name;?>.</p>
                                    </div>
                                </div>
                                <div class="swiper-slide counter-wrap">
                                    <div class="counter text-center">
                                        <?php
                                        $sql = "SELECT * FROM `blog`";
                                        $result = mysqli_query($conn, $sql);
                                        $blogCount = mysqli_num_rows($result);
                                        ?>
                                        <span class="count-to" data-to="<?php echo $blogCount; ?>">0</span>
                                        <h4 class="title title-center">Blog Updates</h4>
                                        <p>Helpful posts, updates, and store news for visitors.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </section>
                </div>

                <section class="boost-section pt-10 pb-10">
                    <div class="container mt-10 mb-9">
                        <div class="row align-items-center mb-10">
                            <div class="col-md-6 mb-8">
                                <figure class="br-lg">
                                    <img src="assets/images/pages/about_us/3.jpg" alt="<?php echo $business_name;?> mission"
                                        width="610" height="450" style="background-color: #9E9DA2;" />
                                </figure>
                            </div>
                            <div class="col-md-6 pl-lg-8 mb-8">
                                <h4 class="title text-left">Our mission is simple: make online shopping feel dependable</h4>
                                <p class="mb-3">
                                    <?php echo $business_name;?> is more than a product catalog. It is a practical e-commerce platform created
                                    to help people browse confidently, shop conveniently, and return whenever they need
                                    trusted products and a familiar experience.
                                </p>
                                <p class="mb-6">
                                    Whether someone is shopping for everyday needs, fashion, accessories, gadgets, or new arrivals,
                                    we want the journey to stay clear, fast, and customer-friendly.
                                </p>
                                <a href="shop.php" class="btn btn-dark btn-rounded mr-2">Visit Our Store</a>
                                <a href="contact-us.php" class="btn btn-outline btn-dark btn-rounded">Contact Us</a>
                            </div>
                        </div>

                        <div class="awards-wrapper">
                            <h4 class="title title-center font-weight-bold mb-10 pb-1 ls-25">Why Customers Choose <?php echo $business_name;?>?</h4>
                            <div class="swiper-container swiper-theme" data-swiper-options="{
                                'spaceBetween': 20,
                                'slidesPerView': 1,
                                'breakpoints': {
                                    '576': { 'slidesPerView': 2 },
                                    '992': { 'slidesPerView': 4 }
                                }
                            }">
                                <div class="swiper-wrapper row cols-xl-4 cols-sm-2 cols-1">
                                    <div class="swiper-slide image-box-wrapper">
                                        <div class="image-box text-center">
                                            <figure>
                                                <img src="assets/images/pages/about_us/1.png" alt="Shopping made easy" width="109" height="105" />
                                            </figure>
                                            <p>Simple browsing<br>across the store</p>
                                        </div>
                                    </div>
                                    <div class="swiper-slide image-box-wrapper">
                                        <div class="image-box text-center">
                                            <figure>
                                                <img src="assets/images/pages/about_us/2.png" alt="Secure checkout" width="109" height="105" />
                                            </figure>
                                            <p>Secure checkout<br>and cart flow</p>
                                        </div>
                                    </div>
                                    <div class="swiper-slide image-box-wrapper">
                                        <div class="image-box text-center">
                                            <figure>
                                                <img src="assets/images/pages/about_us/3.png" alt="Fresh products" width="109" height="105" />
                                            </figure>
                                            <p>Fresh products and<br>new arrivals</p>
                                        </div>
                                    </div>
                                    <div class="swiper-slide image-box-wrapper">
                                        <div class="image-box text-center">
                                            <figure>
                                                <img src="assets/images/pages/about_us/4.png" alt="Customer care" width="109" height="105" />
                                            </figure>
                                            <p>Customer-first<br>support mindset</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="icon-box-section mt-10 mb-10 pb-2">
                    <div class="container">
                        <div class="row cols-xl-3 cols-md-2 cols-1">
                            <div class="icon-box-wrapper mb-4">
                                <div class="about-card text-center">
                                    <div class="icon-box-icon">
                                        <i class="w-icon-truck"></i>
                                    </div>
                                    <h4 class="title mb-2">Delivery-minded operations</h4>
                                    <p class="mb-0">
                                        Every part of the store experience is shaped to help orders move from product page
                                        to delivery with less friction.
                                    </p>
                                </div>
                            </div>
                            <div class="icon-box-wrapper mb-4">
                                <div class="about-card text-center">
                                    <div class="icon-box-icon">
                                        <i class="w-icon-verification"></i>
                                    </div>
                                    <h4 class="title mb-2">Trust and transparency</h4>
                                    <p class="mb-0">
                                        Clear pricing, visible product information, and reliable account tools help build
                                        confidence for every shopper.
                                    </p>
                                </div>
                            </div>
                            <div class="icon-box-wrapper mb-4">
                                <div class="about-card text-center">
                                    <div class="icon-box-icon">
                                        <i class="w-icon-service"></i>
                                    </div>
                                    <h4 class="title mb-2">Support that stays available</h4>
                                    <p class="mb-0">
                                        <?php echo $business_name;?> is built to feel approachable, with clear contact points and a customer
                                        service mindset throughout the app.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="member-section mt-10 pt-2 mb-10 pb-4">
                    <div class="container">
                        <div class="row align-items-start">
                            <div class="col-lg-7 mb-8">
                                <h4 class="title mb-3">Popular departments on <?php echo $business_name;?></h4>
                                <p class="mb-5">
                                    Customers shop across multiple categories, and these sections help them discover products
                                    faster without losing track of what they need.
                                </p>
                                <?php
                                $categoryResult = mysqli_query($conn, "SELECT * FROM `category` ORDER BY id ASC LIMIT 6");
                                if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
                                ?>
                                    <div>
                                        <?php while ($row = mysqli_fetch_array($categoryResult)) { ?>
                                            <a class="category-pill" href="cat.php?cat=<?php echo $row['id']; ?>">
                                                <?php echo $row['categoryname']; ?>
                                            </a>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <p class="mb-0">Categories will appear here as they are added to the store.</p>
                                <?php } ?>
                            </div>

                           
                        </div>

                    </div>
                </section>

                <section class="mb-10 pb-4">
                    <div class="container">
                        <div class="title-link-wrapper mb-4">
                            <h4 class="title">Recently Added Products</h4>
                            <a href="shop.php" class="btn btn-dark btn-link btn-slide-right btn-icon-right">View All Products<i class="w-icon-long-arrow-right"></i></a>
                        </div>

                        <div class="product-wrapper row cols-lg-4 cols-md-3 cols-sm-2 cols-2">
                            <?php
                            $sql = "SELECT * FROM `product_table` WHERE approval_status = 'Approved' ORDER BY product_id DESC LIMIT 8";
                            $result = mysqli_query($conn, $sql);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                            ?>
                            <div class="product-wrap">
                                <div class="product text-center">
                                    <figure class="product-media">
                                        <a href="product.php?uin=<?php echo $row['uin']; ?>">
                                            <img src="vendor/vendorupload/<?php echo $row['productimage']; ?>" alt="Product" />
                                        </a>
                                    </figure>

                                    <div class="product-details">
                                        <div class="product-cat">
                                            <a href="shop.php"><?php echo $row['category']; ?></a>
                                        </div>
                                        <h3 class="product-name">
                                            <a href="product.php?uin=<?php echo $row['uin']; ?>">
                                                <?php echo $row['productname']; ?>
                                            </a>
                                        </h3>
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
                    </div>
                </section>
            </div>
        </main>

        <?php include("footer.php"); ?>
    </div>
    
    <?php
    include("sticky-footer.php");
    ?>

    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button">
        <i class="w-icon-angle-up"></i>
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70">
            <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35"
                r="34" style="stroke-dasharray: 16.4198, 400;"></circle>
        </svg>
    </a>

    <?php
    include("mobile-menu.php");
    ?>

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/jquery.count-to/jquery.count-to.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/main.min.js"></script>
</body>

</html>
