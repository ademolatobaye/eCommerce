<?php
include('customer-session-check.php');
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

if (!isset($_SESSION['invoicenumber'])) {
    header("Location: index");
    exit();
}
?>

<?php
$invoicenumber = $_SESSION['invoicenumber'];
$sql = "SELECT * FROM invoiceorder WHERE invoicenumber='$invoicenumber' AND paymentstatus='Pending'";
$result = mysqli_query($conn, $sql);

$product_row = "";
$total = 0;
$cartItems = array();

while ($row = mysqli_fetch_assoc($result)) {
    if ($product_row === "") $product_row = $row; // grab first row for customer details
    $total += (float)$row['amount'];
    $cartItems[] = $row;
}

// Checking if User's cart is empty
if (empty($cartItems)) {
    echo "<script>alert('Your cart is empty. Add items before checking out.'); window.location.href='index';</script>";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title><?php echo $business_name;?> || CHECKOUT</title>

    <meta name="keywords" content="">
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

    <!-- Plugin CSS -->
    <link rel="stylesheet" type="text/css" href="assets/vendor/magnific-popup/magnific-popup.min.css">

    <!-- Default CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css">
</head>

<body>
    <div class="page-wrapper">
        <h1 class="d-none"><?php echo $business_name;?> || CHECKOUT</h1>

        <?php
        include("header.php");
        ?>


        <!-- Start of Main -->
        <main class="main checkout">
            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb shop-breadcrumb bb-no">
                        <li class="passed"><a href="cart">Shopping Cart</a></li>
                        <li class="active"><a href="checkout">Checkout</a></li>
                        <li>Complete Order</li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->


            <!-- Start of PageContent -->
            <div class="page-content">
                <div class="container">
                    
                   
                    <form class="form checkout-form" action="addpayment" method="post">

                    <?php
                    include("db_conn.php");
                    date_default_timezone_set("Africa/Lagos");
                    $date =date("Y-m-d");

                        // GENERATING ORDER ID
                        $order_id = 'ORD-' . strtoupper(uniqid());

                        //TOTAL QUANTITY
                        $totalQty = 0;
                        foreach ($cartItems as $item) {
                            $totalQty += (int)$item['quantity'];
                        }

                        
                    ?>

                        <div class="row mb-9">
                            <div class="col-lg-7 pr-lg-4 mb-4">
                                <h3 class="title billing-title text-uppercase ls-10 pt-1 pb-3 mb-0">
                                    Billing Details
                                </h3>

                                <div class="row gutter-sm">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label>Fullname *</label>
                                            <input type="text" class="form-control form-control-md" name="customername" value="<?php echo $product_row['customername']; ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                 <input type="hidden" class="form-control form-control-md" name="order_id" value="<?php echo $order_id; ?>">
                                 
                                 <input type="hidden" class="form-control form-control-md" name="date" value="<?php echo $date; ?>">
                                 
                                 <input type="hidden" class="form-control form-control-md" name="invoiceorder" value="<?php echo $product_row['invoicenumber'];; ?>">
                                 
                                 <input type="hidden" class="form-control form-control-md" name="customer_uin" value="<?php echo $product_row['customer_uin'];; ?>">
                                 
                                 <input type="hidden" class="form-control form-control-md" name="quantity" value="<?php echo $product_row['quantity'];; ?>">
                                 
                                 <input type="hidden" class="form-control form-control-md" name="productname" value="<?php echo $product_row['productname'];; ?>">

                                 <input type="hidden" class="form-control form-control-md" name="profit" value="<?php echo $product_row['profit'];; ?>">

                                 <input type="hidden" class="form-control form-control-md" name="vendor_uin" value="<?php echo $product_row['vendor_uin'];; ?>">

                                <div class="form-group">
                                    <label>Home Address *</label>
                                    <input type="text" value="<?php echo $session_address; ?>"
                                        class="form-control form-control-md mb-2" name="customer_address" required>
                                </div>

                                <div class="row gutter-sm">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>State *</label>
                                            <div class="select-box">
                                                <select name="state" class="form-control form-control-md">
                                                    <option value="default" selected="selected">Select State</option>
                                                <?php
                                                
                                                $statelist = "Abia, Adamawa, Akwa-Ibom, Anambra, Bauchi, Bayelsa, Benue, Borno, Cross River, Delta, Ebonyi, Edo, Ekiti, Enugu, Gombe, Imo, Jigawa, Kaduna, Kano, Katsina, Kebbi, Kogi, Kwara, Lagos, Nasarawa, Niger, Ogun, Ondo, Osun, Oyo, Plateau, Rivers, Sokoto, Taraba, Yobe, Zamfara, Abuja";
                                                $arrStates = explode("," , $statelist);
                                                $countState = count($arrStates);
                                                $maincount = $countState - 1;
                                
                                                for($x = 0; $x <= $maincount; $x = $x + 1){
                                                    echo "<option value= '$arrStates[$x]'>$arrStates[$x]</option>";
                                                }
                                
                                                ?>
                                                    
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Town/ City *</label>
                                            <div class="select-box">
                                                <select name="city" class="form-control form-control-md">
                                                <option value="">Select City</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Phone Number *</label>
                                            <input type="text" class="form-control form-control-md" name="customer_phone" value="<?php echo $product_row['customer_phone']; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                

                                <div class="form-group mb-7">
                                    <label>Email Address *</label>
                                    <input type="email" class="form-control form-control-md" name="customer_email" value="<?php echo $product_row['customer_email']; ?>" required>
                                </div>
                                 
                                

                                <div class="form-group mt-3">
                                    <label for="order-notes">Order note (optional)</label>
                                    <textarea class="form-control mb-0" id="order-notes" name="ordernote" cols="30"
                                        rows="4"
                                        placeholder="Notes about your order, e.g Special notes for delivery."></textarea>
                                </div>
                            </div>
                            
                            <div class="col-lg-5 mb-4 sticky-sidebar-wrapper">
                                <div class="order-summary-wrapper sticky-sidebar">
                                    <h3 class="title text-uppercase ls-10">Order Details</h3>
                                    <div class="order-summary">
                                        <table class="order-table">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">
                                                        <b>Order List</b>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM `invoiceorder` WHERE invoicenumber='$_SESSION[invoicenumber]' ORDER BY product_id ASC ";
                                                $result = mysqli_query($conn, $sql);
                                                if (mysqli_num_rows($result) > 0) {
                                                    while ($row = mysqli_fetch_array($result)) {
                                                ?>
                                                <tr class="bb-no">
                                                     
                                                    <td class="product-name" name="productname"><?php echo $row['productname']; ?><i
                                                            class="fas fa-times"></i> <span
                                                            class="product-quantity"><?php echo $row['quantity']; ?></span></td>

                                                    <td class="product-total" name="amount"> 
                                            <?php echo number_format($row['amount'], 2);?></td>
                                                </tr>
                                                <?php }} ?>

                                                <tr class="cart-subtotal bb-no">
                                                    <td>
                                                        <b>Subtotal</b>
                                                    </td>
                                                    <td name="subtotal">
                                                        <b>&#8358;<?php echo number_format($total, 2); ?></b>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr class="shipping-methods">
                                                    <td colspan="2" class="text-left">
                                                        <h4 class="title title-simple bb-no mb-1 pb-0 pt-3">Shipping
                                                        </h4>
                                                        <ul id="shipping-method" class="mb-4">
                                                            <li>
                                                                <div class="custom-radio">
                                                                    <input type="radio" id="free-shipping"
                                                                        class="custom-control-input" name="delivery[]" value="Self Delivery">
                                                                    <label for="free-shipping"
                                                                        class="custom-control-label color-dark">Self 
                                                                        Delivery</label>
                                                                </div>
                                                            </li>

                                                            <li>
                                                                <div class="custom-radio">
                                                                    <input type="radio" id="local-pickup"
                                                                        class="custom-control-input" name="delivery[]" value="Local Pickup">
                                                                    <label for="local-pickup"
                                                                        class="custom-control-label color-dark">Local
                                                                        Pickup</label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-radio">
                                                                    <input type="radio" id="flat-rate"
                                                                        class="custom-control-input" name="shipping">
                                                                    <!-- <label for="flat-rate"
                                                                        class="custom-control-label color-dark">Flat
                                                                        rate: $5.00</label> -->
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                <tr class="order-total">
                                                    <th>
                                                        <b>Total</b>
                                                    </th>
                                                    <td name="total">
                                                        <b>&#8358;<?php echo number_format($total, 2); ?></b>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>

                                        <div class="payment-methods" id="payment_method">
                                            <h4 class="title font-weight-bold ls-25 pb-0 mb-1">Payment Methods</h4>
                                            <div class="accordion payment-accordion">

                                                <div class="form-group">
                                                    <div class="select-box">
                                                    <select name="paymentmethod" class="form-control form-control-md">
                                                        <option value="">Select Payment Method</option>
                                                        <option value="Paystack">Paystack</option>
                                                    </select>
                                                </div>
                                                </div>
                                                
                                            </div>
                                        </div>

                                        <div class="form-group place-order pt-6">
                                            <button type="submit" class="btn btn-dark btn-block btn-rounded"name="submit" onclick="return confirm('Are you certain to proceed?')">Place Order</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End of PageContent -->
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
    <script src="assets/vendor/sticky/sticky.js"></script>
    <script src="assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/main.min.js"></script>
    
    <script>
const stateCities = {
  "Abia": ["Aba","Umuahia","Ohafia","Arochukwu","Bende","Isuikwuato","Umunneochi"],
  "Adamawa": ["Yola","Mubi","Jimeta","Ngurore","Numan","Ganye","Gombi"],
  "Akwa-Ibom": ["Uyo","Eket","Ikot Ekpene","Abak","Oron","Etinan","Essien Udim"],
  "Anambra": ["Awka","Onitsha","Nnewi","Ekwulobia","Ogidi","Agulu","Ihiala"],
  "Bauchi": ["Bauchi","Azare","Misau","Katagum","Ningi","Jama'are","Alkaleri"],
  "Bayelsa": ["Yenagoa","Brass","Ogbia","Sagbama","Southern Ijaw","Ekeremor","Nembe"],
  "Benue": ["Makurdi","Gboko","Otukpo","Katsina-Ala","Vandeikya","Zaki Biam"],
  "Borno": ["Maiduguri","Biu","Bama","Dikwa","Gwoza","Chibok","Monguno"],
  "Cross River": ["Calabar","Ikom","Ogoja","Obudu","Ugep","Akamkpa","Biase"],
  "Delta": ["Asaba","Warri","Sapele","Agbor","Ughelli","Ozoro","Kwale"],
  "Ebonyi": ["Abakaliki","Afikpo","Onueke","Ezza","Ishielu","Ikwo","Onicha"],
  "Edo": ["Benin City","Auchi","Ekpoma","Uromi","Igarra","Okpella","Sabongida-Ora"],
  "Ekiti": ["Ado Ekiti","Ikere Ekiti","Ikole Ekiti","Efon Alaaye","Ijero Ekiti","Iyin Ekiti"],
  "Enugu": ["Enugu","Nsukka","Agbani","Oji River","Udi","Awgu","Obollo-Afor"],
  "Gombe": ["Gombe","Kaltungo","Bajoga","Deba","Nafada","Billiri","Kumo"],
  "Imo": ["Owerri","Orlu","Okigwe","Oguta","Mbaise","Ikeduru","Isiala Mbano"],
  "Jigawa": ["Dutse","Hadejia","Gumel","Kazaure","Birnin Kudu","Ringim","Babura"],
  "Kaduna": ["Kaduna","Zaria","Kafanchan","Saminaka","Kagoro","Lere","Makarfi"],
  "Kano": ["Kano","Wudil","Gwarzo","Rano","Bichi","Dawakin Tofa","Bebeji"],
  "Katsina": ["Katsina","Daura","Funtua","Malumfashi","Mani","Musawa","Rimi"],
  "Kebbi": ["Birnin Kebbi","Argungu","Yauri","Zuru","Koko","Bagudo","Kalgo"],
  "Kogi": ["Lokoja","Okene","Kabba","Anyigba","Idah","Ankpa","Bassa"],
  "Kwara": ["Ilorin","Offa","Omu-Aran","Patigi","Lafiagi","Kaiama","Jebba"],
  "Lagos": ["Lagos Island","Lagos Mainland","Ikeja","Badagry","Epe","Ikorodu","Surulere","Alimosho","Kosofe","Mushin","Ojo","Oshodi-Isolo","Apapa","Eti-Osa","Ibeju-Lekki"],
  "Nasarawa": ["Lafia","Keffi","Akwanga","Nasarawa","Doma","Keana","Awe","Wamba"],
  "Niger": ["Minna","Bida","Kontagora","Suleja","New Bussa","Mokwa","Lapai"],
  "Ogun": ["Abeokuta","Sagamu","Ijebu Ode","Ilaro","Ota","Shagamu","Ayetoro","Ifo"],
  "Ondo": ["Akure","Ondo","Owo","Ikare","Ore","Ilaje","Odigbo","Irele","Idanre"],
  "Osun": ["Osogbo","Ile-Ife","Ilesa","Ede","Iwo","Inisa","Ejigbo","Modakeke"],
  "Oyo": ["Ibadan","Ogbomosho","Oyo","Iseyin","Saki","Igboho","Eruwa","Kisi"],
  "Plateau": ["Jos","Barkin Ladi","Shendam","Pankshin","Riyom","Wase","Bokkos"],
  "Rivers": ["Port Harcourt","Obio-Akpor","Eleme","Bonny","Okrika","Ahoada","Degema"],
  "Sokoto": ["Sokoto","Wamako","Tambuwal","Wurno","Binji","Dange-Shuni","Gada"],
  "Taraba": ["Jalingo","Wukari","Bali","Zing","Sardauna","Gassol","Donga","Ibi"],
  "Yobe": ["Damaturu","Potiskum","Nguru","Gashua","Geidam","Buni Yadi","Fika"],
  "Zamfara": ["Gusau","Kaura Namoda","Anka","Bakura","Birnin Magaji","Bungudu","Gummi"],
  "Abuja": ["Abuja Municipal","Gwagwalada","Kuje","Abaji","Kwali","Bwari"]
};

document.querySelector('select[name="state"]').addEventListener('change', function () {
    var citySelect = document.querySelector('select[name="city"]');
    var selectedState = this.value.trim();

    citySelect.innerHTML = '<option value="">Select City</option>';

    if (selectedState && stateCities[selectedState]) {
        stateCities[selectedState].forEach(function (city) {
            var option = document.createElement('option');
            option.value = city;
            option.textContent = city;
            citySelect.appendChild(option);
        });
    }
});
</script>
    
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9da9eb762c9562f4',t:'MTc3MzIyNTQwMw=='};var a=document.createElement('script');a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script><script defer src="https://static.cloudflareinsights.com/beacon.min.js/v8c78df7c7c0f484497ecbca7046644da1771523124516" integrity="sha512-8DS7rgIrAmghBFwoOTujcf6D9rXvH8xm8JQ1Ja01h9QX8EzXldiszufYa4IFfKdLUKTTrnSFXLDkUEOTrZQ8Qg==" data-cf-beacon='{"version":"2024.11.0","token":"ecd4920e43e14654b78e65dbf8311922","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}' crossorigin="anonymous"></script>
</body>

</html>