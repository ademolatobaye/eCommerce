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

$customer_uin = isset($_SESSION['customer_uin']) ? $_SESSION['customer_uin'] : '';

$wishlist_products = array();

if (!empty($customer_uin)) {
    // Logged in user: fetch from database joined with product_table for stock status
    $stmt = mysqli_query($conn, "SELECT w.*, p.quantity FROM wishlist w LEFT JOIN product_table p ON w.product_uin = p.uin WHERE w.customer_uin='$customer_uin' ORDER BY w.id DESC");
    if ($stmt) {
        while ($row = mysqli_fetch_assoc($stmt)) {
            $wishlist_products[] = $row;
        }
    }
} else {
    // Guest user: fetch from session array
    if (isset($_SESSION['wishlist']) && is_array($_SESSION['wishlist']) && count($_SESSION['wishlist']) > 0) {
        $uins = array();
        foreach ($_SESSION['wishlist'] as $item) {
            $uins[] = "'" . mysqli_real_escape_string($conn, $item) . "'";
        }
        $in_clause = implode(',', $uins);
        $sql = "SELECT * FROM product_table WHERE uin IN ($in_clause) ORDER BY product_id DESC";
        $res = mysqli_query($conn, $sql);
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $wishlist_products[] = $row;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title><?php echo $business_name;?> || MY WISHLIST</title>

    <link rel="icon" type="image/png" href="assets/images/icons/favicon.png">

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
    
    <style>
        .wishlist-table img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
        }
        .wishlist-table .product-thumbnail {
            text-align: center;
        }
        .wishlist-table .product-thumbnail > div,
        .wishlist-table .product-thumbnail .p-relative {
            display: inline-block !important;
            width: 80px !important;
            max-width: 80px !important;
            position: relative !important;
            margin: 0 auto !important;
        }
        .wishlist-table .btn-close {
            top: -8px !important;
            right: -8px !important;
        }
        .stock-status.in-stock {
            color: #28a745;
            font-weight: 600;
        }
        .stock-status.out-stock {
            color: #dc3545;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">

        <?php 
        include("header.php");
         ?>

        <main class="main wishlist-page">
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb bb-no">
                        <li><a href="index">Home</a></li>
                        <li><a href="shop">Shop</a></li>
                        <li>Wishlist</li>
                    </ul>
                </div>
            </nav>

            <div class="page-content mb-10 pb-2">
                <div class="container">
                    <h2 class="title text-left mb-5"><i class="w-icon-heart mr-2"></i> My Wishlist</h2>
                    
                    <?php if (count($wishlist_products) > 0) { ?>
                        <div class="table-responsive">
                            <table class="shop-table wishlist-table">
                                <thead>
                                    <tr>
                                        <th class="product-name"><span>Product</span></th>
                                        <th></th>
                                        <th class="product-price"><span>Price</span></th>
                                        <th class="product-stock-status"><span>Stock Status</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($wishlist_products as $item) { 
                                        $in_stock = isset($item['quantity']) && (int)$item['quantity'] > 0;
                                    ?>
                                        <tr>
                                            <td class="product-thumbnail">
                                                <div class="p-relative">
                                                    <a href="product?uin=<?php echo $item['product_uin']; ?>">
                                                        <figure>
                                                            <img src="vendor/vendorupload/<?php echo $item['product_image']; ?>" 
                                                                 alt="<?php echo $item['productname']; ?>">
                                                        </figure>
                                                    </a>
                                                    <a href="remove-wishlist?product_uin=<?php echo $item['product_uin']; ?>" 
                                                       class="btn btn-close" 
                                                       title="Remove item" 
                                                       onclick="return confirm('Remove this item from your wishlist?')">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="product-name">
                                                <a href="product?uin=<?php echo $item['product_uin']; ?>">
                                                    <?php echo $item['productname']; ?>
                                                </a>
                                                <br>
                                                <small class="text-muted">Category: <?php echo $item['category']; ?></small>
                                            </td>
                                            <td class="product-price">
                                                <ins class="new-price">&#8358;<?php echo number_format((float)($item['sellingprice'] ?? 0), 2); ?></ins>
                                            </td>
                                            <td class="product-stock-status">
                                                <?php if ($in_stock) { ?>
                                                    <span class="stock-status in-stock"><i class="fa fa-check-circle mr-1"></i>In Stock</span>
                                                <?php } else { ?>
                                                    <span class="stock-status out-stock"><i class="fa fa-times-circle mr-1"></i>Out of Stock</span>
                                                <?php } ?>
                                            </td>
                                            
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        <div class="text-center pt-8 pb-8">
                            <i class="w-icon-heart" style="font-size: 64px; color: #ccc;"></i>
                            <h3 class="mt-4">Your Wishlist is Empty</h3>
                            <p class="mb-6">Explore our catalog and add items you love to your wishlist!</p>
                            <a href="shop" class="btn btn-primary btn-rounded"><i class="w-icon-bag mr-2"></i> Continue Shopping</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </main>

        <?php include("footer.php"); ?>
    </div>

    <?php include("sticky-footer.php"); ?>

    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button">
        <i class="w-icon-angle-up"></i>
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70">
            <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34" style="stroke-dasharray: 16.4198, 400;"></circle>
        </svg>
    </a>

    <?php include("mobile-menu.php"); ?>

    <!-- Plugin JS Files -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/sticky/sticky.js"></script>
    <script src="assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/main.min.js"></script>
</body>
</html>
