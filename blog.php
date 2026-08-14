<?php
session_start();
include('db_conn.php'); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title>DEE MART || BLOG</title>

    <meta name="keywords" content="" />
    <meta name="description" content="">
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

    <link rel="preload" href="assets/vendor/fontawesome-free/webfonts/fa-regular-400.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="assets/vendor/fontawesome-free/webfonts/fa-solid-900.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="assets/vendor/fontawesome-free/webfonts/fa-brands-400.woff2" as="font" type="font/woff2"
            crossorigin="anonymous">
    <link rel="preload" href="assets/fonts/wolmart.woff?png09e" as="font" type="font/woff" crossorigin="anonymous">

    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/swiper/swiper-bundle.min.css">

    <!-- Plugin CSS -->
    <link rel="stylesheet" type="text/css" href="assets/vendor/magnific-popup/magnific-popup.min.css">

    <!-- Default CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css">
</head>

<body>
    <div class="page-wrapper">

    <?php
    include("header.php");
    ?>

        <!-- Start of Main -->
        <main class="main">
            <!-- Start of Page Header -->
            <div class="page-header">
                <div class="container">
                    <h1 class="page-title mb-0">BLOG</h1>
                </div>
            </div>
            <!-- End of Page Header -->

            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav mb-6">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="javascript:history.back()">Home</a></li>
                        <li>Blog</li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of Page Content -->
            <div class="page-content">
                <div class="container">
                    <div class="row gutter-lg mb-10">
                        <div class="main-content">
                            <?php
                            $sql = "SELECT * FROM `blog` ORDER BY id DESC";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    $detail_url = "blog-detail.php?uin=" . urlencode($row['uin']);
                                    $excerpt = strip_tags($row['content']);
                                    if (strlen($excerpt) > 180) {
                                        $excerpt = substr($excerpt, 0, 180) . '...';
                                    }
                            ?>
                                    <article class="post post-classic overlay-zoom mb-6 border-bottom pb-6">
                                        <figure class="post-media br-sm">
                                            <a href="<?php echo $detail_url; ?>">
                                                <img src="dashboard/blogupload/<?php echo htmlspecialchars($row['blogimage']); ?>" width="930" height="500" alt="blog" style="width:100%; max-height:450px; object-fit:cover;">
                                            </a>
                                        </figure>

                                        <div class="post-details">
                                            <div class="post-cats text-primary fw-bold">
                                                <a href="<?php echo $detail_url; ?>"><?php echo htmlspecialchars($row['category']); ?></a>
                                            </div>

                                            <h3 class="post-title">
                                                <a href="<?php echo $detail_url; ?>"><?php echo htmlspecialchars($row['headline']); ?></a>
                                            </h3>

                                            <div class="post-content">
                                                <p><?php echo htmlspecialchars($excerpt); ?></p>
                                                <a href="<?php echo $detail_url; ?>" class="btn btn-link btn-primary fw-bold">(read more)</a>
                                            </div>

                                            <div class="post-meta">
                                                by <a href="<?php echo $detail_url; ?>" class="post-author"><?php echo htmlspecialchars($row['staff'] ? $row['staff'] : 'Admin'); ?></a>
                                                - <span class="post-date"><?php echo htmlspecialchars($row['date']); ?></span>
                                            </div>
                                        </div>
                                    </article>
                            <?php 
                                }
                            } else {
                                echo "<p>No blog posts found.</p>";
                            }
                            ?>

                            <ul class="pagination justify-content-center pb-2">
                                <li class="prev disabled">
                                    <a href="#" aria-label="Previous" tabindex="-1" aria-disabled="true">
                                        <i class="w-icon-long-arrow-left"></i>Prev
                                    </a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="next">
                                    <a href="#" aria-label="Next">
                                        Next<i class="w-icon-long-arrow-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <aside class="sidebar right-sidebar blog-sidebar sidebar-fixed sticky-sidebar-wrapper">
                            <div class="sidebar-overlay">
                                <a href="#" class="sidebar-close">
                                    <i class="close-icon"></i>
                                </a>
                            </div>
                            <a href="#" class="sidebar-toggle">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <div class="sidebar-content">
                                <div class="sticky-sidebar">
                                    <div class="widget widget-search-form">
                                        <div class="widget-body">
                                            <form action="#" method="GET" class="input-wrapper input-wrapper-inline">
                                                <input type="text" class="form-control"
                                                    placeholder="Search in Blog" autocomplete="off" required>
                                                <button class="btn btn-search"><i
                                                        class="w-icon-search"></i></button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- End of Widget search form -->
                                    <div class="widget widget-categories">
                                        <h3 class="widget-title bb-no mb-0">Blog Categories</h3>
                                         
                                        <ul class="widget-body filter-items search-ul">
                                            <?php
                                        $sql = "SELECT * FROM `blog_category` ORDER BY id ";
                                        $result = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                            <li><a href=""></a><?php echo $row['blogcategoryname']; ?></li>
                                            <?php }} ?>
                                        </ul>
                                        
                                    </div>

                                    <!-- End of Widget categories -->
                                    <div class="widget widget-posts">
                                        <h3 class="widget-title bb-no">Trending Blogs</h3>
                                        <div class="widget-body">
                                            <div class="swiper">
                                                <div class="swiper-container swiper-theme nav-top" data-swiper-options="{
                                                    'spaceBetween': 20,
                                                    'slidesPerView': 1
                                                }">
                                                    <div class="swiper-wrapper row cols-1">
                                                        

                                                        <div class="swiper-slide widget-col">
                                                            <?php
                                        $sql = "SELECT * FROM `blog` ORDER BY id DESC";
                                        $result = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                                            <div class="post-widget mb-4">
                                                                <figure class="post-media br-sm">
                                                                    <img src="dashboard/blogupload/<?php echo $row['blogimage']; ?>" alt="150" height="150" />
                                                                </figure>
                                                                <div class="post-details">
                                                                    <div class="post-meta">
                                                                        <a href="#" class="post-date"><?php echo $row['date']; ?></a>
                                                                    </div>
                                                                    <h4 class="post-title">
                                                                        <a href=""><?php echo $row["headline"]; ?></a>
                                                                    </h4>
                                                                </div>
                                                            </div>
                                                            <?php }} ?>
                                                        </div>

                                                        <div class="swiper-slide widget-col">
                                                             <?php
                                        $sql = "SELECT * FROM `blog` ORDER BY id ASC";
                                        $result = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                                            <div class="post-widget mb-4">
                                                                <figure class="post-media br-sm">
                                                                    <img src="dashboard/blogupload/<?php echo $row['blogimage']; ?>" alt="150" height="150" />
                                                                </figure>
                                                                <div class="post-details">
                                                                    <div class="post-meta">
                                                                        <a href="#" class="post-date"><?php echo $row['date']; ?></a>
                                                                    </div>
                                                                    <h4 class="post-title">
                                                                        <a href=""><?php echo $row["headline"]; ?></a>
                                                                    </h4>
                                                                </div>
                                                            </div>
                                                            <?php }} ?>

                                                        </div>
                                                    </div>
                                                    <div class="swiper-button-next"></div>
                                                    <div class="swiper-button-prev"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of Widget posts -->
                                   
                                    <div class="widget widget-tags">
                                        <h3 class="widget-title bb-no">Browse Blogs</h3>
                                        
                                        <div class="widget-body tags">
                                            <?php
                                        $sql = "SELECT * FROM `blog_category` ORDER BY id ";
                                        $result = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                            <a href="#" class="tag"><?php echo $row['blogcategoryname']; ?></a>
                                            <?php }} ?>
                                        </div>
                                        
                                    </div>

                                    <div class="widget widget-calendar">
                                        <h3 class="widget-title bb-no">Calendar</h3>
                                        <div class="widget-body">
                                            <div class="calendar-container" data-calendar-options="{
                                                'dayExcerpt': 1
                                            }"></div>
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
   
    <!-- Plugin JS File -->
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/sticky/sticky.js"></script>
    <script src="assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/main.min.js"></script>
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/v8c78df7c7c0f484497ecbca7046644da1771523124516" integrity="sha512-8DS7rgIrAmghBFwoOTujcf6D9rXvH8xm8JQ1Ja01h9QX8EzXldiszufYa4IFfKdLUKTTrnSFXLDkUEOTrZQ8Qg==" data-cf-beacon='{"version":"2024.11.0","token":"ecd4920e43e14654b78e65dbf8311922","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}' crossorigin="anonymous"></script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9da9eab27c9b62f4',t:'MTc3MzIyNTM3Mg=='};var a=document.createElement('script');a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>

</html>