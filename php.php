<?php
include("db_conn.php");

if (!isset($_SESSION['userId'])) {
    header("Location: index.php");
    exit();
}

// Fetch this user's cart items
$invoiceNumber = $_SESSION['userId'];
$stmt = mysqli_prepare($conn, "SELECT * FROM invoiceorder WHERE invoicenumber = ? ORDER BY product_id DESC");
mysqli_stmt_bind_param($stmt, 's', $invoiceNumber);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$total = 0;
$cartItems  = array();
while ($row = mysqli_fetch_assoc($result)) {
    $total += (float)$row['amount'];
    $cartItems[] = $row;
}
?>
        
        <!-- Start of Header -->
        <header class="header">
            <div class="header-top">
                <div class="container">
                    <div class="header-left">
                        <p class="welcome-msg">Welcome to DEE MART.</p>
                    </div>

                    <div class="header-right">
                        <div class="dropdown">
                            <a href="#currency">NGN</a>
                            <div class="dropdown-box">
                                <a href="#USD">NGN</a>
                                <a href="#EUR">USD</a>
                            </div>
                        </div>
                        <!-- End of DropDown Menu -->

                        
                        <!-- End of Dropdown Menu -->
                        <span class="divider d-lg-show"></span>
                        <a href="blog.html" class="d-lg-show">Blog</a>
                        <a href="contact-us.html" class="d-lg-show">Contact Us</a>
                        <a href="my-account.html" class="d-lg-show">My Account</a>
                        <a href="assets/ajax/login.html" class="d-lg-show login sign-in"><i
                                class="w-icon-account"></i>Sign In</a>
                        <span class="delimiter d-lg-show">/</span>
                        <a href="assets/ajax/login.html" class="ml-0 d-lg-show login register">Register</a>
                    </div>
                </div>
            </div>
            <!-- End of Header Top -->

            <div class="header-middle">
                <div class="container">
                    <div class="header-left mr-md-4">
                        <a href="#" class="mobile-menu-toggle  w-icon-hamburger" aria-label="menu-toggle">
                        </a>
                        <a href="index.php" class="logo ml-lg-0">
                            <img src="assets/images/logo.png" alt="logo" width="144" height="45" />
                        </a>
                        
                        <form method="post" action="#"
                            class="header-search hs-expanded hs-round d-none d-md-flex input-wrapper">
                            <div class="select-box">
                                <select id="category" name="category">
                                    <option value="">All Categories</option>
                                    <option value="4">Fashion</option>
                                    <option value="5">Furniture</option>
                                    <option value="6">Shoes</option>
                                    <option value="7">Sports</option>
                                    <option value="8">Games</option>
                                    <option value="9">Computers</option>
                                    <option value="10">Electronics</option>
                                    <option value="11">Kitchen</option>
                                    <option value="12">Clothing</option>
                                </select>
                            </div>
                            <input type="text" class="form-control" name="search" id="search" placeholder="Search in..."
                                required />
                            <button class="btn btn-search" type="submit"><i class="w-icon-search"></i>
                            </button>
                        </form>

                    </div>
                    <div class="header-right ml-4">
                        <div class="header-call d-xs-show d-lg-flex align-items-center">
                            <a href="tel:#" class="w-icon-call"></a>
                            <div class="call-info d-lg-show">
                                <h4 class="chat font-weight-normal font-size-md text-normal ls-normal text-light mb-0">
                                    <a href="/cdn-cgi/l/email-protection#b192" class="text-capitalize">Live Chat</a> or :</h4>
                                <a href="tel:#" class="phone-number font-weight-bolder ls-50">+234 816 016 1379</a>
                            </div>
                        </div>

                        <a class="wishlist label-down link d-xs-show" href="wishlist.html">
                            <i class="w-icon-heart"></i>
                            <span class="wishlist-label d-lg-show">Wishlist</span>
                        </a>

                        <!-- START CART -->
                        <div class="dropdown cart-dropdown cart-offcanvas mr-0 mr-lg-2">
                            <div class="cart-overlay"></div>
                            <a href="#" class="cart-toggle label-down link">
                                <?php
                    $sql = "SELECT * FROM `invoiceorder` ORDER BY product_id ASC LIMIT 0, 8";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                    ?>
                                <i class="w-icon-cart">
                                    <span class="cart-count"><?php echo $row["product_id"]; ?></span>
                                </i>
                                <span class="cart-label">Cart</span>
                                <?php } ?>
                            </a>
                            
                            <div class="dropdown-box">
                                <div class="cart-header">
                                    <span>Shopping Cart</span>
                                    <a href="#" class="btn-close">Close<i class="w-icon-long-arrow-right"></i></a>
                                </div>

                                <div class="products">
                                    <?php
     $sql = "SELECT * FROM `invoiceorder` ORDER BY product_id ASC LIMIT 0, 8";
     $result = mysqli_query($conn, $sql);
     if (mysqli_num_rows($result) > 0) {
         while ($row = mysqli_fetch_array($result)) {
     ?>
                                    <div class="product product-cart">
                                        <div class="product-detail">
                                            <a href="product.php?uin=<?php echo $row['uin']; ?>" class="product-name">
                                            <?php echo $row['productname']; ?>
                                            </a>
                                            <div class="price-box">
                                                <span class="product-quantity"><?php echo $row['quantity']; ?></span>
                                                <span class="product-price">&#8358;<?php echo number_format($row['amount'], 2);?></span>
                                            </div>
                                        </div>
                                        <figure class="product-media">
                                            <a href="product.php?uin=<?php echo $row['uin']; ?>">
                                                <img src="../dashboard/productupload/<?php echo $row['productimage']; ?>" alt="product" height="84"
                                                    width="94" />
                                            </a>
                                        </figure>
                                        <button class="btn btn-link btn-close" aria-label="button">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>

                                    <?php }} ?>
 
                                </div> <br><br><br><br>

                                <div class="cart-total">
                                    <label>Subtotal:</label>
                                    <span class="price">&#8358;<?php echo number_format($total, 2); ?></span>
                                    
                                </div>

                                <div class="cart-action">
                                    <a href="cart.php" class="btn btn-dark btn-outline btn-rounded">View Cart</a>
                                    <a href="checkout.html" class="btn btn-primary  btn-rounded">Checkout</a>
                                </div>
                            </div>
                            <!-- End of Dropdown Box -->
                        </div>
                        <!-- END OF CART -->
                        
                    </div>
                </div>
            </div>
            <!-- End of Header Middle -->


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
                                    <?php
     $sql = "SELECT * FROM `category` ORDER BY id ASC";
     $result = mysqli_query($conn, $sql);
     if (mysqli_num_rows($result) > 0) {
         while ($row = mysqli_fetch_array($result)) {
     ?>
                                    <ul class="menu vertical-menu category-menu">
                                        <li>
                                            <a href="shop.php">
                                                <!-- <i class="w-icon-tshirt2"></i> -->
                                                <?php echo $row["categoryname"]; ?>
                                            </a>
                                        </li>

                                        <!-- <li>
                                            <a href="shop-banner-sidebar.html"
                                                class="font-weight-bold text-primary text-uppercase ls-25">
                                                View All Categories<i class="w-icon-angle-right"></i>
                                            </a>
                                        </li> -->

                                    </ul>
                                    <?php
         }}
                                ?>
                                </div>
                            </div>

                            <nav class="main-nav">
                                <ul class="menu active-underline">
                                    <li class="active">
                                        <a href="index.php">Home</a>
                                    </li>

                                    <li>
                                        <a href="shop.php">Shop</a>

                                    </li>

                                    <li>
                                        <a href="blog.html">Blog</a>

                                    </li>

                                    <li>
                                        <a href="about-us.html">About Us</a>
                                       
                                    </li>

                                    
                                </ul>
                            </nav>
                        </div>
                        
                    </div>
                </div>
            </div>

            
        </header>
        <!-- End of Header -->