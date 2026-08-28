<?php
session_start();
include("db_conn.php");

if (!isset($_GET['vendor_uin']) || empty($_GET['vendor_uin'])) {
    header("Location: shop.php");
    exit();
}

$vendor_uin = mysqli_real_escape_string($conn, trim($_GET['vendor_uin']));

// Fetch Vendor details
$v_query = mysqli_prepare($conn, "SELECT * FROM vendor_table WHERE vendor_uin = ? AND `status` = 'Active' LIMIT 1");
mysqli_stmt_bind_param($v_query, "s", $vendor_uin);
mysqli_stmt_execute($v_query);
$v_res = mysqli_stmt_get_result($v_query);
$vendor = mysqli_fetch_assoc($v_res);

if (!$vendor) {
    echo "<script>alert('Vendor store not found or account is inactive.'); window.location.href='shop.php';</script>";
    exit();
}

// Fetch Approved Vendor Products
$p_query = mysqli_prepare($conn, "SELECT * FROM product_table WHERE vendor_uin = ? AND (approval_status = 'Approved') ORDER BY product_id DESC");
mysqli_stmt_bind_param($p_query, "s", $vendor_uin);
mysqli_stmt_execute($p_query);
$products = mysqli_stmt_get_result($p_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title><?php echo htmlspecialchars($vendor['store_name']); ?> - Storefront | DEE MART</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/icons/favicon.png">

    <link rel="stylesheet" type="text/css" href="assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css">
    <style>
        .vendor-banner-container {
            position: relative;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 30px;
        }
        .vendor-banner-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            opacity: 0.6;
        }
        .vendor-profile-overlay {
            position: relative;
            margin-top: -60px;
            padding: 0 30px 25px;
            display: flex;
            align-items: flex-end;
            gap: 20px;
            flex-wrap: wrap;
        }
        .vendor-logo-img {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #ffffff;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            background: #fff;
        }
        .vendor-info-box {
            color: #ffffff;
            flex: 1;
        }
        .vendor-info-box h2 {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .vendor-meta-badge {
            background: rgba(255,255,255,0.15);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            display: inline-block;
            margin-right: 10px;
            margin-bottom: 6px;
        }
        .product-media img {
            height: 240px;
            object-fit: cover;
        }
    </style>
</head>
<body class="home">
    <div class="page-wrapper">
        <?php 
        include("header.php");
         ?>

        <main class="main">
            <!-- Breadcrumb -->
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb bb-no">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="shop.php">Shop</a></li>
                        <li><?php echo htmlspecialchars($vendor['store_name']); ?></li>
                    </ul>
                </div>
            </nav>

            <div class="page-content mb-10 pb-2">
                <div class="container">
                    
                    <!-- Vendor Banner & Profile Header -->
                    <div class="vendor-banner-container">
                        <?php if (!empty($vendor['banner']) && file_exists("vendor/vendorupload/" . $vendor['banner'])): ?>
                            <img src="vendor/vendorupload/<?php echo htmlspecialchars($vendor['banner']); ?>" alt="banner" class="vendor-banner-img">
                        <?php else: ?>
                            <div style="height: 180px; background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);"></div>
                        <?php endif; ?>

                        <div class="vendor-profile-overlay">
                            <?php if (!empty($vendor['logo']) && file_exists("vendor/vendorupload/" . $vendor['logo'])): ?>
                                <img src="vendor/vendorupload/<?php echo htmlspecialchars($vendor['logo']); ?>" alt="logo" class="vendor-logo-img">
                            <?php else: ?>
                                <img src="dashboard/assets/images/users/21.jpg" alt="logo" class="vendor-logo-img">
                            <?php endif; ?>

                            <div class="vendor-info-box">
                                <h2><?php echo htmlspecialchars($vendor['store_name']); ?></h2>
                                <div>
                                    <span class="vendor-meta-badge"><i class="fas fa-user me-1"></i> Owner: <?php echo htmlspecialchars($vendor['vendor_name']); ?></span>
                                    <span class="vendor-meta-badge"><i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($vendor['store_address']); ?></span>
                                    <?php if (!empty($vendor['vendor_phone'])): ?>
                                        <span class="vendor-meta-badge"><i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($vendor['vendor_phone']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($vendor['description'])): ?>
                                    <p class="mt-2 text-white-50 small mb-0"><?php echo htmlspecialchars($vendor['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="shop-content text-left">
                        <div class="title-link-wrapper mb-4">
                            <h3 class="title title-link">Products from <?php echo htmlspecialchars($vendor['store_name']); ?></h3>
                        </div>

                        <div class="product-wrapper row cols-md-4 cols-sm-3 cols-2 g-3">
                            <?php if ($products && mysqli_num_rows($products) > 0): ?>
                                <?php while ($p = mysqli_fetch_assoc($products)): ?>
                                    <div class="product-wrap">
                                        <div class="product text-center border-rounded">
                                            <figure class="product-media">
                                                <a href="product.php?uin=<?php echo urlencode($p['uin']); ?>">
                                                    <?php if (!empty($p['productimage']) && file_exists("vendor/vendorupload/" . $p['productimage'])): ?>
                                                        <img src="vendor/vendorupload/<?php echo htmlspecialchars($p['productimage']); ?>" alt="product" width="300" height="300" />
                                                    <?php else: ?>
                                                        <img src="assets/images/products/1.jpg" alt="product" width="300" height="300" />
                                                    <?php endif; ?>
                                                </a>
                                            </figure>
                                            <div class="product-details p-3">
                                                <div class="product-cat">
                                                    <a href="cat.php?category=<?php echo urlencode($p['category']); ?>"><?php echo htmlspecialchars($p['category']); ?></a>
                                                </div>
                                                <h4 class="product-name">
                                                    <a href="product.php?uin=<?php echo urlencode($p['uin']); ?>"><?php echo htmlspecialchars($p['productname']); ?></a>
                                                </h4>
                                                <div class="product-price">
                                                    <ins class="new-price">₦<?php echo number_format((float)$p['sellingprice'], 2); ?></ins>
                                                </div>
                                                <div class="mt-2">
                                                    <a href="product.php?uin=<?php echo urlencode($p['uin']); ?>" class="btn btn-sm btn-dark btn-rounded">View Product</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="col-12 text-center py-5">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <h4>No products currently listed by this vendor.</h4>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </main>

        <?php include("footer.php"); ?>
    </div>

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/js/main.min.js"></script>
</body>
</html>
