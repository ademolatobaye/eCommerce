<?php
include("db_conn.php");

// Fetch variables from session
$invoiceNumber = isset($_SESSION['invoicenumber']) ? $_SESSION['invoicenumber'] : '';
$customerUin   = isset($_SESSION['customer_uin']) ? $_SESSION['customer_uin'] : '';

// FETCH USER'S CART ITEMS
$cartItems = array();
$total = 0;

if ($invoiceNumber != '' && $customerUin != '') {
    $stmt = mysqli_prepare($conn, "SELECT * FROM invoiceorder WHERE invoicenumber = ? AND customer_uin = ? ORDER BY product_id ASC LIMIT 8");
    mysqli_stmt_bind_param($stmt, 'ss', $invoiceNumber, $customerUin);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $total += (float)$row['amount'];
        $cartItems[] = $row;
    }
    mysqli_stmt_close($stmt);
}

// FETCH WISHLIST COUNT
$wishlistCount = 0;
if (!empty($customerUin)) {
    $w_stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM wishlist WHERE customer_uin = ?");
    if ($w_stmt) {
        mysqli_stmt_bind_param($w_stmt, 's', $customerUin);
        mysqli_stmt_execute($w_stmt);
        $w_res = mysqli_stmt_get_result($w_stmt);
        $w_row = mysqli_fetch_assoc($w_res);
        $wishlistCount = (int)$w_row['total'];
        mysqli_stmt_close($w_stmt);
    }
} else {
    if (isset($_SESSION['wishlist']) && is_array($_SESSION['wishlist'])) {
        $wishlistCount = count($_SESSION['wishlist']);
    }
}
?>

<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="header-left">
                <h1 class="welcome-msg"><?php if(isset($_SESSION['customer_email'])){ ?>
                <?php
                 date_default_timezone_set("Africa/Lagos"); // Set your timezone
                                    $hour = date("H"); // Get current hour (24-hour format)
                                    if ($hour >= 5 && $hour < 12) {
                                        echo "Good Morning, " . $_SESSION['fullname'];
                                    } 
                                    elseif ($hour >= 12 && $hour < 16) {
                                        echo "Good Afternoon, " . $_SESSION['fullname'];
                                    } 
                                    elseif ($hour >= 16 && $hour < 21) {
                                        echo "Good Evening, " . $_SESSION['fullname'];
                                    } 
                                    else {
                                        echo "Good Night, " . $_SESSION['fullname'];
                                        
                                        }
                                        ?></h1>
                                        <?php } else{ ?>
                                       <h1 class="welcome-msg">Welcome to <?php echo $business_name; ?>.</h1>
                                       <?php } ?>

            </div>

            <div class="header-right">
                <div class="dropdown">
                    <a href="#currency">NGN</a>
                    <div class="dropdown-box">
                        <a href="">NGN</a>
                    </div>
                </div>

                <span class="divider d-lg-show"></span>
                <a href="blog" class="d-lg-show">Blog</a>
                <a href="contact-us" class="d-lg-show">Contact Us</a>
                <a href="dashboard" class="d-lg-show" target="_blank">Admin</a>
                <?php if(isset($_SESSION['customer_email'])){ ?>
                <a href="track-order" class="d-lg-show">Track Order</a>
                <a href="my-account" class="d-lg-show"><?php echo $_SESSION['fullname'];; ?></a>
                <?php } else{ ?>
                <a href="my-account" class="d-lg-show">My Account</a>
                <?php } ?>
               <?php if (isset($_SESSION['customer_email'])) { ?>
                <a href="signout" class="d-lg-show" onclick="return confirm('Are you certain to sign out?')"><i class="w-icon-account"></i>Sign Out</a>
            <?php } else { ?>
                <a href="reg/user-login" class="d-lg-show"><i class="w-icon-account"></i>Sign In</a>
                <span class="delimiter d-lg-show">/</span>
                <a href="reg/index" class="ml-0 d-lg-show">Register</a>
            <?php } ?>
            </div>
        </div>
    </div>

    <div class="header-middle">
        <div class="container">
            <div class="header-left mr-md-4">
                <a href="#" class="mobile-menu-toggle w-icon-hamburger" aria-label="menu-toggle"></a>
                <a href="index" class="logo ml-lg-0">
                    <img src="assets/images/logo.png" alt="logo" width="144" height="45" />
                </a>

                <form method="post" action="search" class="header-search hs-expanded hs-round d-none d-md-flex input-wrapper">
                    <div class="select-box">
                        <select id="category" name="category">
                            <option value="">All Categories</option>
                            <?php
                            $sql = "SELECT * FROM `category` ORDER BY id ASC";
                            $catResult = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($catResult) > 0) {
                                while ($catRow = mysqli_fetch_array($catResult)) {
                                    echo '<option value="'.$catRow['id'].'">'.$catRow['categoryname'].'</option>';
                                }
                            } 
                            ?>
                        </select>
                    </div>
                    <input type="text" class="form-control" name="search" id="search" placeholder="Search for products..." required />
                    <button class="btn btn-search" type="submit">
                        <i class="w-icon-search"></i>
                    </button>
                </form>
            </div>

            <div class="header-right ml-4">
                <div class="header-call d-xs-show d-lg-flex align-items-center">
                    <a href="tel:#" class="w-icon-call"></a>
                    <div class="call-info d-lg-show">
                        <h4 class="chat font-weight-normal font-size-md text-normal ls-normal text-light mb-0">
                            <a href="#" class="text-capitalize">Live Chat</a> or Call:
                        </h4>
                        <a href="tel:#" class="phone-number font-weight-bolder ls-50"><?php echo $phone; ?></a>
                    </div>
                </div>

                <a href="wishlist" class="wishlist label-down link d-xs-show" title="Wishlist" style="position: relative; margin-right: 2.3rem;">
                    <i class="w-icon-heart" style="position: relative; display: inline-block;">
                        <span class="wishlist-count" id="header-wishlist-count" style="position: absolute; right: -8px; top: -5px; width: 1.9rem; height: 1.9rem; border-radius: 50%; font-style: normal; z-index: 1; font-family: Poppins, sans-serif; font-size: 1.1rem; font-weight: 400; line-height: 1.9rem; background: #333; color: #fff; text-align: center; display: inline-block;"><?php echo $wishlistCount; ?></span>
                    </i>
                    <span class="wishlist-label">Wishlist</span>
                </a>

                <div class="dropdown cart-dropdown cart-offcanvas mr-0 mr-lg-2">
                    <div class="cart-overlay"></div>
                    <a href="#" class="cart-toggle label-down link">
                        <i class="w-icon-cart">
                            <span class="cart-count"><?php echo count($cartItems); ?></span>
                        </i>
                        <span class="cart-label">Cart</span>
                    </a>

                    <div class="dropdown-box">
                        <div class="cart-header">
                            <span>Shopping Cart</span>
                            <a href="#" class="btn-close">Close<i class="w-icon-long-arrow-right"></i></a>
                        </div>

                        <div class="products" style="max-height: 500px; overflow-y: auto;">
                            <?php if (count($cartItems) > 0) {
                                foreach ($cartItems as $row) { ?>
                            <div class="product product-cart">
                                <div class="product-detail">
                                    <a href="product?uin=<?php echo $row['uin']; ?>" class="product-name">
                                        <?php echo $row['productname']; ?>
                                    </a>
                                    <div class="price-box">
                                        <span class="product-quantity"><?php echo $row['quantity']; ?></span>
                                        <span class="product-price">&#8358;<?php echo number_format($row['amount'], 2); ?></span>
                                    </div>
                                </div>
                                <figure class="product-media">
                                    <a href="product?uin=<?php echo $row['uin']; ?>">
                                        <img src="vendor/vendorupload/<?php echo $row['productimage']; ?>"
                                            alt="product" height="84" width="94" />
                                    </a>
                                </figure>
                                <button class="btn btn-link btn-close" aria-label="button">
                                    <i class="fas fa-times"></i>
                                </button>

                                
                            </div>
                            <?php }} else { ?>
                                <p class="text-center mt-5">Your cart is empty.</p>
                            <?php } ?>
                        </div>

                        <div class="cart-total">
                            <label>Subtotal:</label>
                            <span class="price">&#8358;<?php echo number_format($total, 2); ?></span>
                        </div>

                        <div class="cart-action">
                            <a href="cart" class="btn btn-dark btn-outline btn-rounded">View Cart</a>
                            <a href="checkout" class="btn btn-primary btn-rounded">Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="header-bottom sticky-content fix-top sticky-header has-dropdown">
        <div class="container">
            <div class="inner-wrap">
                <div class="header-left">
                    <div class="dropdown category-dropdown has-border" data-visible="true">
                        <a href="#" class="category-toggle text-dark" role="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="true" data-display="static"
                            title="Browse Categories">
                            <i class="w-icon-category"></i>
                            <span>Browse Categories</span>
                        </a>

                        <div class="dropdown-box">
                            <ul class="menu vertical-menu category-menu">
                                <?php
                                $sql = "SELECT * FROM `category` ORDER BY id ASC";
                                $catResult2 = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($catResult2) > 0) {
                                    while ($catRow2 = mysqli_fetch_array($catResult2)) {
                                ?>
                                <li>
                                    <a href="cat?cat=<?php echo $catRow2['id']; ?>"><?php echo $catRow2["categoryname"]; ?></a>
                                </li>
                                <?php }} ?>
                            </ul>
                        </div>
                    </div>

                    <nav class="main-nav">
                        <ul class="menu active-underline">
                            <li class="active"><a href="index">Home</a></li>
                            <li><a href="shop">Shop</a></li>
                            <li><a href="wishlist">Wishlist</a></li>
                            <li><a href="blog">Blog</a></li>
                            <li><a href="about-us">About Us</a></li>
                            <li><a href="track-order">Track Order</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>