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

$blog_row = null;
if (isset($_REQUEST['uin'])) {
    $uin = mysqli_real_escape_string($conn, $_REQUEST['uin']);
    $sql = "SELECT * FROM `blog` WHERE uin='$uin'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $blog_row = mysqli_fetch_array($result);
    }
} else if (isset($_REQUEST['id'])) {
    $id = intval($_REQUEST['id']);
    $sql = "SELECT * FROM `blog` WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $blog_row = mysqli_fetch_array($result);
    }
}

if (!$blog_row) {
    header("Location: blog");
    exit();
}

$blog_uin = $blog_row['uin'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title><?php echo htmlspecialchars($blog_row['headline']); ?> - <?php echo $business_name;?> BLOG</title>

    <meta name="keywords" content="<?php echo htmlspecialchars($blog_row['category']); ?>" />
    <meta name="description" content="<?php echo htmlspecialchars(substr(strip_tags($blog_row['content']), 0, 160)); ?>">
    <meta name="author" content="">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/icons/favicon.png">

    <!-- WebFont.js -->
    <script>
        WebFontConfig = {
            google: { families: ['Poppins:400,500,600,700'] }
        };
        ( function ( d ) {
            var wf = d.createElement( 'script' ), s = d.scripts[0];
            wf.src = 'assets/js/webfont.js';
            wf.async = true;
            s.parentNode.insertBefore( wf, s );
        } )( document );
    </script>

    <link rel="preload" href="assets/vendor/fontawesome-free/webfonts/fa-regular-400.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="assets/vendor/fontawesome-free/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="assets/vendor/fontawesome-free/webfonts/fa-brands-400.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="assets/fonts/wolmart.woff?png09e" as="font" type="font/woff" crossorigin="anonymous">

    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/magnific-popup/magnific-popup.min.css">

    <!-- Default CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css">
</head>

<body>
    <div class="page-wrapper">

    <?php include("header.php"); ?>

        <!-- Start of Main -->
        <main class="main">
            <!-- Start of Page Header -->
            <div class="page-header">
                <div class="container">
                    <h1 class="page-title mb-0">BLOG DETAILS</h1>
                </div>
            </div>
            <!-- End of Page Header -->

            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav mb-6">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="blog.php">Blog</a></li>
                        <li><?php echo htmlspecialchars($blog_row['headline']); ?></li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of Page Content -->
            <div class="page-content mb-10">
                <div class="container">
                    <div class="row gutter-lg">
                        <div class="main-content">
                            <article class="post post-single">
                                
                                <!-- BLOG IMAGES DISPLAY -->
                                <?php
                                $b_imgs_q = mysqli_query($conn, "SELECT * FROM `blog_images` WHERE uin='$blog_uin' ORDER BY sort_order ASC");
                                $b_imgs = array();
                                if ($b_imgs_q && mysqli_num_rows($b_imgs_q) > 0) {
                                    while ($img_r = mysqli_fetch_assoc($b_imgs_q)) {
                                        $b_imgs[] = $img_r['blog_image'];
                                    }
                                } else if (!empty($blog_row['blogimage'])) {
                                    $b_imgs[] = $blog_row['blogimage'];
                                }

                                if (count($b_imgs) > 1) {
                                ?>
                                    <!-- MULTIPLE IMAGES SLIDER -->
                                    <figure class="post-media br-sm mb-6">
                                        <div class="swiper-container swiper-theme nav-inner" data-swiper-options="{
                                            'navigation': {
                                                'nextEl': '.swiper-button-next',
                                                'prevEl': '.swiper-button-prev'
                                            }
                                        }">
                                            <div class="swiper-wrapper row cols-1 gutter-no">
                                                <?php foreach ($b_imgs as $b_img) { ?>
                                                    <div class="swiper-slide">
                                                        <img src="dashboard/blogupload/<?php echo htmlspecialchars($b_img); ?>" width="930" height="500" alt="blog image" style="width:100%; height: auto; max-height: 550px; object-fit: cover; border-radius: 8px;">
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <button class="swiper-button-next"></button>
                                            <button class="swiper-button-prev"></button>
                                        </div>
                                    </figure>
                                <?php } else if (!empty($b_imgs)) { ?>
                                    <!-- SINGLE MAIN IMAGE -->
                                    <figure class="post-media br-sm mb-6">
                                        <img src="dashboard/blogupload/<?php echo htmlspecialchars($b_imgs[0]); ?>" width="930" height="500" alt="blog image" style="width:100%; height: auto; max-height: 550px; object-fit: cover; border-radius: 8px;">
                                    </figure>
                                <?php } ?>

                                <div class="post-details">
                                    <div class="post-meta">
                                        by <span class="post-author"><?php echo htmlspecialchars($blog_row['staff'] ? $blog_row['staff'] : 'Admin'); ?></span>
                                        - <span class="post-date"><?php echo htmlspecialchars($blog_row['date']); ?></span>
                                        <?php if (!empty($blog_row['category'])) { ?>
                                            in <span class="post-category text-primary fw-bold"><?php echo htmlspecialchars($blog_row['category']); ?></span>
                                        <?php } ?>
                                    </div>

                                    <h1 class="post-title mt-2 mb-4"><?php echo htmlspecialchars($blog_row['headline']); ?></h1>

                                    <div class="post-body fs-6" style="line-height: 1.8;">
                                        <?php echo $blog_row['content']; ?>
                                    </div>

                                    <?php if (!empty($blog_row['photocredit'])) { ?>
                                        <div class="post-photo-credit mt-6 text-muted font-italic">
                                            <strong>Photo Credit:</strong> <?php echo htmlspecialchars($blog_row['photocredit']); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </article>

                            <!-- NAV POSTS -->
                            <div class="post-navigation d-flex justify-content-between align-items-center border-top border-bottom pt-4 pb-4 mt-8">
                                <a href="blog.php" class="btn btn-dark btn-rounded"><i class="w-icon-long-arrow-left"></i> Back to Blog</a>
                            </div>
                        </div>

                        <!-- SIDEBAR -->
                        <aside class="sidebar right-sidebar blog-sidebar sidebar-fixed sticky-sidebar-wrapper">
                            <div class="sidebar-overlay">
                                <a href="#" class="sidebar-close"><i class="close-icon"></i></a>
                            </div>
                            <a href="#" class="sidebar-toggle"><i class="fas fa-chevron-left"></i></a>
                            <div class="sidebar-content">
                                <div class="sticky-sidebar">

                                    <!-- RECENT POSTS -->
                                    <div class="widget widget-posts mb-6">
                                        <h3 class="widget-title bb-no mb-4">Recent Posts</h3>
                                        <div class="widget-body">
                                            <?php
                                            $recent_sql = "SELECT * FROM `blog` WHERE uin != '$blog_uin' ORDER BY id DESC LIMIT 4";
                                            $recent_res = mysqli_query($conn, $recent_sql);
                                            if ($recent_res && mysqli_num_rows($recent_res) > 0) {
                                                while ($rec = mysqli_fetch_assoc($recent_res)) {
                                            ?>
                                                    <div class="post post-widget d-flex align-items-center mb-3">
                                                        <figure class="post-media br-sm mr-3" style="width:70px; height:70px; flex-shrink:0;">
                                                            <a href="blog-detail.php?uin=<?php echo $rec['uin']; ?>">
                                                                <img src="dashboard/blogupload/<?php echo htmlspecialchars($rec['blogimage']); ?>" width="70" height="70" alt="post" style="width:70px; height:70px; object-fit:cover; border-radius:4px;">
                                                            </a>
                                                        </figure>
                                                        <div class="post-details">
                                                            <div class="post-meta">
                                                                <span class="post-date"><?php echo htmlspecialchars($rec['date']); ?></span>
                                                            </div>
                                                            <h4 class="post-title">
                                                                <a href="blog-detail.php?uin=<?php echo $rec['uin']; ?>"><?php echo htmlspecialchars($rec['headline']); ?></a>
                                                            </h4>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
            <!-- End of Page Content -->
        </main>
        <!-- End of Main -->

        <?php include("footer.php"); ?>
    </div>

    <?php include("sticky-footer.php"); ?>

    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button">
        <i class="w-icon-angle-up"></i>
    </a>

    <?php include("mobile-menu.php"); ?>

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/sticky/sticky.js"></script>
    <script src="assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/main.min.js"></script>
</body>

</html>
