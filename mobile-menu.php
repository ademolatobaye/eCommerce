    <!-- Start of Mobile Menu -->
    <div class="mobile-menu-wrapper">
        <div class="mobile-menu-overlay"></div>
        <!-- End of .mobile-menu-overlay -->

        <a href="#" class="mobile-menu-close"><i class="close-icon"></i></a>
        <!-- End of .mobile-menu-close -->

        <div class="mobile-menu-container scrollable">
            <form action="search" method="get" class="input-wrapper">
                <input type="text" class="form-control" name="search" autocomplete="off" placeholder="Search"
                    required />
                <button class="btn btn-search" type="submit">
                    <i class="w-icon-search"></i>
                </button>
            </form>
            <!-- End of Search Form -->
            <div class="tab">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a href="#main-menu" class="nav-link active">Main Menu</a>
                    </li>
                    <li class="nav-item">
                        <a href="#categories" class="nav-link">Categories</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="main-menu">
                    <ul class="mobile-menu">
                        <li><a href="index">Home</a></li>
                        <li><a href="shop">Shop</a></li>
                        <li><a href="about-us">About Us</a></li>
                        <li><a href="blog">Blog</a></li>
                        <li><a href="contact-us">Contact Us</a></li>
                        <li><a href="cart">Cart</a></li>
                        <li><a href="track-order">Track Order</a></li>
                        <li><a href="checkout">Checkout</a></li>
                        <?php if (isset($_SESSION['customer_email'])) { ?>
                            <li><a href="my-account"><?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'My Account'; ?></a></li>
                                <a href="signout" onclick="return confirm('Are you certain to sign out?')">
                                    <i class="w-icon-account"></i> Sign Out
                                </a>
                            </li>
                        <?php } else { ?>
                            <li><a href="my-account">My Account</a></li>
                            <li><a href="reg/user-login">Sign In</a></li>
                            <li><a href="reg/index">Register</a></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="tab-pane" id="categories">
                    <ul class="mobile-menu">
                        <?php
                        $sql = "SELECT * FROM `category` ORDER BY id ASC";
                        $mobileCatResult = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($mobileCatResult) > 0) {
                            while ($row = mysqli_fetch_array($mobileCatResult)) {
                        ?>
                        <li>
                            <a href="cat?cat=<?php echo $row['id']; ?>"><?php echo $row['categoryname']; ?></a>
                        </li>
                        <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Mobile Menu -->