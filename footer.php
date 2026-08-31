<!-- Start of Footer -->
        <footer class="footer appear-animate" data-animation-options="{
            'name': 'fadeIn'
        }">
            <div class="footer-newsletter bg-primary">
                <div class="container">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-xl-5 col-lg-6">
                            <div class="icon-box icon-box-side text-white">
                                <div class="icon-box-icon d-inline-flex">
                                    <i class="w-icon-envelop3"></i>
                                </div>
                                <div class="icon-box-content">
                                    <h4 class="icon-box-title text-white text-uppercase font-weight-bold">Subscribe To
                                        Our Newsletter</h4>
                                    <p class="text-white">Get all the latest information on Sales and Offers.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-7 col-lg-6 col-md-9 mt-4 mt-lg-0 ">
                            <form action="#" method="get"
                                class="input-wrapper input-wrapper-inline input-wrapper-rounded">
                                <input type="email" class="form-control mr-2 bg-white" name="email" id="email"
                                    placeholder="Your E-mail Address" required>
                                <button class="btn btn-dark btn-rounded" type="submit">Subscribe Today<i
                                        class="w-icon-long-arrow-right"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="footer-top">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6">
                            <div class="widget widget-about">
                                <a href="index" class="logo-footer">
                                    <img src="assets/images/logo_footer.png" alt="logo-footer" width="144"
                                        height="45" />
                                </a>
                                <div class="widget-body">
                                    <p class="widget-about-title">Got Questions? We are available 24/7</p>
                                    <a href="tel:+2348160161379 " class="widget-about-call"><?php echo $phone; ?></a>
                                    

                                    <div class="social-icons social-icons-colored">
                                        <a href="#" class="social-icon social-twitter w-icon-twitter"></a>
                                        <a href="#" class="social-icon social-instagram w-icon-instagram"></a>
                                            <a href="#" class="social-icon social-linkedin fab fa-linkedin-in"></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="widget">
                                <h3 class="widget-title">Company</h3>
                                <ul class="widget-body">
                                    <li><a href="about-us">About Us</a></li>
                                    <li><a href="contact-us">Contact Us</a></li>
                                    <li><a href="#">Affiliate</a></li>
                                    <li><a href="#">Order History</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="widget">
                                <h4 class="widget-title">My Account</h4>
                                <ul class="widget-body">
                                    <li><a href="track-order">Track My Order</a></li>
                                    <li><a href="cart">View Cart</a></li>
                                    <?php if(isset($_SESSION['customer_email'])){ ?>
                                    <li><a href="signout" onclick="return confirm('Are you sure to sign out?')">Sign Out</a></li>
                                    <?php } else{ ?>
                                    <a href="reg/user-login" class="d-lg-show"><i></i>Sign In</a>
                                    <span class="delimiter d-lg-show">/</span>
                                    <a href="reg/index" class="ml-0 d-lg-show">Register</a>
                                    <?php } ?>
                                    <li><a href="#">Help</a></li>
                                    <li><a href="#">Privacy Policy</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="widget">
                                <h4 class="widget-title">Customer Service</h4>
                                <ul class="widget-body">
                                    <li><a href="#">Payment Methods</a></li>
                                    <li><a href="#">Support Center</a></li>
                                    <li><a href="#">Shipping</a></li>
                                    <li><a href="#">Terms and Conditions</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="footer-middle">
                    <?php
                        $sql = "SELECT * FROM `product_table` ORDER BY category, product_id ASC";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            $category = '';
                            while ($row = mysqli_fetch_array($result)) {
                                if ($row['category'] !== $category) {
                                    if ($category !== '') {
                                        echo '</div></div>'; 
                                    }
                                    $category = $row['category'];
                    ?>
                    <div class="widget widget-category">
                        <div class="category-box">
                            <h6 class="category-name"><?php echo $row['category']; ?>:</h6>
                    <?php } ?>
                            <a href="product?uin=<?php echo $row['uin']; ?>"><?php echo $row['productname']; ?></a>
                    <?php }} ?>
                            <a href="shop">View All</a>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <div class="footer-left">
                        <p class="copyright">Copyright © <script>document.write(new Date().getFullYear())</script> <?php echo $business_name; ?>. All Rights Reserved.</p>
                    </div>
                    <div class="footer-right">
                        <span class="payment-label mr-lg-8">We're using safe payment</span>
                        <figure class="payment">
                            <img src="assets/images/payment.png" alt="payment" width="159" height="25" />
                        </figure>
                    </div>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->
