    <!-- Start of Sticky Footer -->
    <div class="sticky-footer sticky-content fix-bottom">
    <a href="index" class="sticky-link">
        <i class="w-icon-home"></i>
        <p>Home</p>
    </a>
    <a href="shop" class="sticky-link">
        <i class="w-icon-category"></i>
        <p>Shop</p>
    </a>
    <a href="my-account" class="sticky-link">
        <i class="w-icon-account"></i>
        <p>Account</p>
    </a>

    <div class="cart-dropdown dir-up">
        <a href="cart" class="sticky-link">
            <i class="w-icon-cart"></i>
            <?php if (count($cartItems) > 0): ?>
                <span class="cart-count"><?php echo count($cartItems); ?></span>
            <?php endif; ?>
            <p>Cart</p>
        </a>
        <div class="dropdown-box">
            <div class="products">
                <?php if (count($cartItems) > 0): ?>
                    <?php foreach ($cartItems as $row): ?>
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
                                    <img src="dashboard/productupload/<?php echo $row['productimage']; ?>"
                                        alt="product" height="84" width="94" />
                                </a>
                            </figure>
                            <button class="btn btn-link btn-close" aria-label="button">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center mt-5">Your cart is empty.</p>
                <?php endif; ?>
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

    <div class="header-search hs-toggle dir-up">
        <a href="#" class="search-toggle sticky-link">
            <i class="w-icon-search"></i>
            <p>Search</p>
        </a>
        <form action="search" method="POST" class="input-wrapper">
            <input type="text" class="form-control" name="search" autocomplete="off"
                placeholder="Search" required />
            <button class="btn btn-search" type="submit">
                <i class="w-icon-search"></i>
            </button>
        </form>
    </div>
</div>
    <!-- End of Sticky Footer -->