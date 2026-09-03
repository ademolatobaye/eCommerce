<?php
session_start();

ini_set('display_errors', '1');
	require 'includes/PHPMailer.php';
	require 'includes/SMTP.php';
	require 'includes/Exception.php';
//Define name spaces
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title>MANAGEMENT REGISTRATION</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/icons/favicon.png">

    <!-- WebFont.js -->
    <script>
        WebFontConfig = {
            google: { families: ['Poppins:400,500,600,700'] }
        };
        ( function ( d ) {
            var wf = d.createElement( 'script' ), s = d.scripts[0];
            wf.src = '../assets/js/webfont.js';
            wf.async = true;
            s.parentNode.insertBefore( wf, s );
        } )( document );
    </script>

    <link rel="preload" href="../assets/vendor/fontawesome-free/webfonts/fa-regular-400.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="../assets/vendor/fontawesome-free/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="../assets/vendor/fontawesome-free/webfonts/fa-brands-400.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="../assets/fonts/wolmart.woff?png09e" as="font" type="font/woff" crossorigin="anonymous">

    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="../assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../assets/vendor/swiper/swiper-bundle.min.css">

    <!-- Plugin CSS -->
    <link rel="stylesheet" type="text/css" href="../assets/vendor/magnific-popup/magnific-popup.min.css">

    <!-- Default CSS -->
    <link rel="stylesheet" type="text/css" href="../assets/css/style.min.css">
</head>

<body>
    <div class="page-wrapper">

        <!-- Start of Main -->
        <main class="main login-page">

            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav mb-10 pb-1">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="index">Home</a></li>
                        <li>Create an Account</li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of PageContent -->
            <div class="page-content">
                <div class="container">
                    <div class="login-popup" style="margin: 0 auto; max-width: 1500px; padding: 30px;">
                        <div class="tab tab-nav-boxed tab-nav-center tab-nav-underline">
                            <ul class="nav nav-tabs text-uppercase" role="tablist">
                                <li class="nav-item">
                                    <a href="#sign-up" class="nav-link active">Create an Account</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="sign-up">
                                    <form id="registerForm" method="post">

                                        <?php
                                        include("db_conn.php");
                                        date_default_timezone_set("Africa/Lagos");
                                        $year =date("Y");
                                        error_reporting(E_ALL);
                                        if(isset($_REQUEST["submit"])){
                                            $business_name = mysqli_real_escape_string($conn, trim($_REQUEST["business_name"]));
                                            $phone = mysqli_real_escape_string($conn, trim($_REQUEST["phone"]));
                                            $email = mysqli_real_escape_string($conn, trim($_REQUEST["email"]));
                                            $address = mysqli_real_escape_string($conn, trim($_REQUEST["address"]));
                                            $business_type = mysqli_real_escape_string($conn, trim($_REQUEST["business_type"]));
                                            $website = mysqli_real_escape_string($conn, trim($_REQUEST["website"]));
                                            $country = mysqli_real_escape_string($conn, trim($_REQUEST["country"]));
                                            $state = mysqli_real_escape_string($conn, trim($_REQUEST["state"]));
                                            $city = mysqli_real_escape_string($conn, trim($_REQUEST["city"]));

                                            //CHECKING FOR DUPLICATE BUSINESS
                                            $check=mysqli_query($conn, "SELECT * FROM system_setting WHERE business_name='$business_name' AND phone='$phone' AND email='$email'");
                                            $checkrows=mysqli_num_rows($check);

                                            if($checkrows>0) {
                                            echo "<script>alert('Invalid account. You already have an account.')
                                            location.href='../'</script>";
                                            } else {
                                            

                                                $sql = "INSERT INTO system_setting(business_name, `address`, phone, email, country, `state`, city, business_type, `status`, website) 
                                                VALUES ('$business_name', '$address', '$phone', '$email', '$country', '$state', '$city', '$business_type', 'Active', '$website')";
                                                mysqli_query($conn, $sql) or die(mysqli_error($conn));
                                            if(mysqli_affected_rows($conn)!= 1){
                                                $message = "Error inserting record.";
                                            } else{

                                            
                                                
// Create instance of PHPMailer
	$mail = new PHPMailer();
//Set mailer to use smtp
	$mail->isSMTP();
//Define smtp host
	$mail->Host = "mail.pocketvest.com.ng";
//Enable smtp authentication
	$mail->SMTPAuth = true;
//Set smtp encryption type (ssl/tls)
	$mail->SMTPSecure = "ssl";
//Port to connect smtp
	$mail->Port = "465";
//Set gmail username
	$mail->Username = "noreply@pocketvest.com.ng";
//Set gmail password
	$mail->Password = "ecommerce@2026";
//Email subject
	$mail->Subject = "REGISTRATON COMPLETION";
//Set sender email
	$mail->setFrom('noreply@pocketvest.com.ng', "$business_name");
//Enable HTML
	$mail->isHTML(true);
                $mail->Body    = "
    <style>
        html, body { margin: 0 auto !important; padding: 0 !important; height: 100% !important; width: 100% !important; font-family: 'Roboto', sans-serif !important; font-size: 14px; margin-bottom: 10px; line-height: 24px; color: #4B0082; font-weight: 400; }
        * { -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; margin: 0; padding: 0; }
        table, td { mso-table-lspace: 0pt !important; mso-table-rspace: 0pt !important; }
        table { border-spacing: 0 !important; border-collapse: collapse !important; table-layout: fixed !important; margin: 0 auto !important; }
        a { text-decoration: none; }
        img { -ms-interpolation-mode: bicubic; }
    </style>

    <center style='width: 100%; background-color: #f5f6fa;'>
        <table width='100%' border='0' cellpadding='0' cellspacing='0' bgcolor='#f5f6fa'>
            <tr>
                <td style='padding: 40px 0;'>
                    <table style='width:100%;max-width:620px;margin:0 auto;'>
                        <tbody align='center'>
                            <a href='https://ademolathedev.name.ng/e-commerce' target='_blank'>
                                <img style='height: 60px' src='https://ademolathedev.name.ng/e-commerce/assets/images/logo.png' alt='DEE MART'>
                            </a>
                        </tbody>
                    </table>
                    <table style='width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;'>
                        <tbody align='left'>
                            <tr>
                                <td style='padding: 0 30px 20px;'>
                                    <p></p><br>
                                    <p style='margin-bottom: 10px;'>Dear <b>$fullname,</b></p>
                                    <p style='margin-bottom: 10px;'>Your account has been successfully registered. Kindly log in to your account to explore our products.</p>
                                    
                                    <hr>
                                    <p style='margin-bottom: 10px;'>If you have any questions or need help, feel free to contact us. Do well to enjoy <strong>DEE MART</strong>.</p>
                                    <hr>
                                    <p style='margin-bottom: 10px;'><em>Warm regards,</em><br><b>DEE MART.</b></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style='width:100%;max-width:620px;margin:0 auto;'>
                        <tbody>
                            <tr>
                                <td style='text-align: center; padding:25px 20px 0;'>
                                    <p style='font-size: 13px;'>Copyright &copy; $year <strong>DEE MART</strong>. All Rights Reserved.</p>
                                    <p style='padding-top: 15px; font-size: 12px;'>This email was sent to you as a registered member on <a style='color: #4B0082; text-decoration:none;' href='#'><strong>DEE MART</strong></a>.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </center>";
    //Add recipient
	$mail->addAddress("$email");
//Finally send email
	if ($mail->send()) {
    echo "<script>alert('Dear $business_name, your account has been successfully created.');
     window.location.href='thank-you'</script>";
                                            }
} else {
    error_log("Mailer Error: " . $mail->ErrorInfo);
    echo "<script>alert('Account created but confirmation email failed.'); 
    window.location.href='thank-you'</script>";
}
            }
        }
                                    
                                        ?>

                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email" class="form-control" id="emailAddress" required name="email" placeholder="Enter your email address">
                                        </div>

                                        <div class="form-group">
                                            <label>Business Name *</label>
                                            <input type="text" name="business_name" class="form-control" required placeholder="Enter your Business name">
                                        </div>

                                        <div class="form-group">
                                            <label>Phone Number *</label>
                                            <input type="number" name="phone" class="form-control" required placeholder="Enter your phone number">
                                        </div>

                                        <div class="form-group">
                                            <label>Business Type *</label>
                                            <select name="business_type" class="form-control" required>
                                                <option value="">Select Business Type</option>
                                                <option value="E-Commerce">E-Commerce</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Business Address *</label>
                                            <input type="text" name="address" class="form-control" required placeholder="Enter your business address">
                                        </div>

                                        <div class="form-group">
                                            <label>Website (optional)</label>
                                            <input type="text" name="website" class="form-control" placeholder="e.g. https://example.com">
                                        </div>

                                        <div class="form-group">
                                            <label>Country *</label>
                                            <input type="text" name="country" class="form-control" required placeholder="Enter your country">
                                        </div>

                                        <div class="form-group">
                                            <label>State *</label>
                                            <select name="state" id="state" class="form-control" required>
                                                <option value="" selected disabled>Select State</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>City *</label>
                                            <select name="city" id="city" class="form-control" required>
                                                <option value="" selected disabled>Select City</option>
                                            </select>
                                        </div>

                                        <div class="form-checkbox d-flex align-items-center justify-content-between mt-4">
                                            <input type="checkbox" class="custom-checkbox" id="agree" name="agree" required>
                                            <label for="agree">I agree to the <a href="" class="text-primary">Terms</a> and <a href="" class="text-primary">Privacy</a>.</label>
                                        </div>

                                        <button type="submit" name="submit" class="btn btn-primary w-100 mt-4" onclick="return confirm('Create this account?')">Create Account</button>

                                    </form>

                                    <script type="text/javascript">
                                        
								// Block form submission if password fails validation
								document.querySelector('form').addEventListener('submit', function(e){
									if(!check()){
										e.preventDefault();
									}
								});


                                        // Dynamic State and City/Town Selector for Nigeria
                                        const stateCityData = {
                                            "Abia": ["Aba", "Umuahia", "Ohafia", "Arochukwu", "Abiriba", "Nkporo", "Akwete", "Omoba", "Bende", "Iperu"],
                                            "Abuja": ["Garki", "Wuse", "Maitama", "Asokoro", "Gwarinpa", "Kubwa", "Lugbe", "Kuje", "Karu", "Jabi", "Utako", "Apo", "Life Camp", "Dawaki", "Nyanya", "Abaji", "Bwari"],
                                            "Adamawa": ["Yola", "Jimeta", "Mubi", "Numan", "Ganye", "Michika", "Girei", "Song", "Hong", "Mayo-Belwa", "Toungo"],
                                            "Akwa Ibom": ["Uyo", "Eket", "Ikot Ekpene", "Oron", "Abak", "Ikot Abasi", "Etinan", "Ibiono-Ibom", "Ibeno", "Ukanafun", "Oruk Anam"],
                                            "Anambra": ["Awka", "Onitsha", "Nnewi", "Ekwulobia", "Ihiala", "Agulu", "Abagana", "Obosi", "Nkpor", "Umunze", "Atani", "Ozubulu", "Ojoto"],
                                            "Bauchi": ["Bauchi", "Azare", "Misau", "Jama'are", "Ningi", "Dass", "Alkaleri", "Toro", "Katagum"],
                                            "Bayelsa": ["Yenagoa", "Ogbia", "Sagbama", "Brass", "Amassoma", "Kaiama", "Nembe", "Twon-Brass"],
                                            "Benue": ["Makurdi", "Gboko", "Otukpo", "Katsina-Ala", "Vandeikya", "Zaki Biam", "Oju", "Okpoga", "Ugbokolo"],
                                            "Borno": ["Maiduguri", "Biu", "Bama", "Monguno", "Gwoza", "Dikwa", "Askira", "Damboa", "Chibok"],
                                            "Cross River": ["Calabar", "Ikom", "Ogoja", "Ugep", "Obudu", "Akamkpa", "Odukpani", "Bakassi", "Obubra"],
                                            "Delta": ["Warri", "Asaba", "Sapele", "Ughelli", "Agbor", "Abraka", "Kwale", "Ogwashi-Uku", "Ozoro", "Oleh", "Koko", "Udu", "Effurun", "Burutu", "Bomadi"],
                                            "Ebonyi": ["Abakaliki", "Afikpo", "Onueke", "Ishiagu", "Uburu", "Nkalagu", "Okposi"],
                                            "Edo": ["Benin City", "Uromi", "Auchi", "Ekpoma", "Igarra", "Sabongida-Ora", "Okada", "Ibillo", "Ubiaja"],
                                            "Ekiti": ["Ado Ekiti", "Ikere Ekiti", "Ijero Ekiti", "Ikole Ekiti", "Ilawe Ekiti", "Oye Ekiti", "Aramoko Ekiti", "Efon-Alaaye", "Otun Ekiti", "Ise Ekiti"],
                                            "Enugu": ["Enugu", "Nsukka", "Oji River", "Agbani", "Udi", "Awgu", "Nkalagu", "Ninth Mile (9th Mile)", "Obollo-Afor"],
                                            "Gombe": ["Gombe", "Kaltungo", "Dukku", "Bajoga", "Billiri", "Deba", "Nafada"],
                                            "Imo": ["Owerri", "Orlu", "Okigwe", "Mbaise", "Oguta", "Nkwerre", "Akokwa", "Mbieri", "Orodo"],
                                            "Jigawa": ["Dutse", "Hadejia", "Birnin Kudu", "Gumel", "Kazaure", "Ringim", "Babura"],
                                            "Kaduna": ["Kaduna", "Zaria", "Kafanchan", "Kagoro", "Saminaka", "Kachia", "Birnin Gwari", "Zangon Kataf", "Ikara"],
                                            "Kano": ["Kano", "Wudil", "Gaya", "Karaye", "Bichi", "Rano", "Dawakin Kudu", "Dambatta"],
                                            "Katsina": ["Katsina", "Daura", "Funtua", "Malumfashi", "Dutsin-Ma", "Kankia", "Jibia", "Bakori"],
                                            "Kebbi": ["Birnin Kebbi", "Argungu", "Yauri", "Zuru", "Jega", "Koko", "Bagudo"],
                                            "Kogi": ["Lokoja", "Okene", "Idah", "Kabba", "Anyigba", "Ankpa", "Ajaokuta", "Koton-Karfe", "Egbe"],
                                            "Kwara": ["Ilorin", "Offa", "Omu-Aran", "Jebba", "Lafiagi", "Patigi", "Oro", "Kaiama"],
                                            "Lagos": ["Ikeja", "Lekki", "Victoria Island", "Yaba", "Surulere", "Ikorodu", "Epe", "Badagry", "Ajah", "Festac Town", "Oshodi", "Agege", "Alimosho", "Maryland", "Ikoyi", "Mushin", "Ebute Metta", "Ojota", "Ogba", "Egbeda", "Sangotedo"],
                                            "Nasarawa": ["Lafia", "Keffi", "Akwanga", "Karu", "Doma", "Nasarawa", "Wamba", "Awe"],
                                            "Niger": ["Minna", "Bida", "Suleja", "Kontagora", "Lapai", "Mokwa", "Agaie", "Borgu"],
                                            "Ogun": ["Abeokuta", "Ijebu Ode", "Sagamu", "Sango Ota", "Ilaro", "Ago-Iwoye", "Odeda", "Ifo", "Ewekoro", "Owode", "Ijebu-Igbo"],
                                            "Ondo": ["Akure", "Ondo Town", "Owo", "Ikare Akoko", "Okitipupa", "Idanre", "Ore", "Igbokoda", "Ile-Oluji"],
                                            "Osun": ["Osogbo", "Ile-Ife", "Ilesa", "Ede", "Ejigbo", "Ikirun", "Ila Orangun", "Iwo", "Gbongan", "Okuku"],
                                            "Oyo": ["Ibadan", "Ogbomoso", "Oyo Town", "Iseyin", "Saki", "Eruwa", "Igboora", "Kisi", "Igboho"],
                                            "Plateau": ["Jos", "Pankshin", "Shendam", "Barkin Ladi", "Bukuru", "Mangu", "Vom", "Langtang"],
                                            "Rivers": ["Port Harcourt", "Obio-Akpor", "Bonny", "Eleme", "Degema", "Omoku", "Bori", "Ahoada", "Buguma", "Opobo", "Okrika"],
                                            "Sokoto": ["Sokoto", "Tambuwal", "Goronyo", "Gwadabawa", "Illela", "Wurno", "Bodinga"],
                                            "Taraba": ["Jalingo", "Wukari", "Bali", "Takum", "Gembu", "Ibi", "Sardauna"],
                                            "Yobe": ["Damaturu", "Potiskum", "Gashua", "Nguru", "Geidam", "Fika"],
                                            "Zamfara": ["Gusau", "Kaura Namoda", "Talata Mafara", "Anka", "Bungudu", "Tsafe"]
                                        };

                                        document.addEventListener("DOMContentLoaded", function() {
                                            const stateSelect = document.getElementById("state");
                                            const citySelect = document.getElementById("city");

                                            if (stateSelect && citySelect) {
                                                // Populate states dropdown
                                                Object.keys(stateCityData).forEach(function(state) {
                                                    const option = document.createElement("option");
                                                    option.value = state;
                                                    option.textContent = state;
                                                    stateSelect.appendChild(option);
                                                });

                                                // Listen for change on state dropdown
                                                stateSelect.addEventListener("change", function() {
                                                    const selectedState = this.value;
                                                    
                                                    // Clear existing city options
                                                    citySelect.innerHTML = '<option value="" selected disabled>Select City</option>';

                                                    if (selectedState && stateCityData[selectedState]) {
                                                        stateCityData[selectedState].forEach(function(city) {
                                                            const option = document.createElement("option");
                                                            option.value = city;
                                                            option.textContent = city;
                                                            citySelect.appendChild(option);
                                                        });
                                                    }
                                                });
                                            }
                                        });
                                    </script>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of PageContent -->
        </main>
        <!-- End of Main -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Start of Scroll Top -->
    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button"> <i class="w-icon-angle-up"></i> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70"> <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34" style="stroke-dasharray: 16.4198, 400;"></circle> </svg> </a>
    <!-- End of Scroll Top -->

    <?php include("../mobile-menu.php"); ?>

    <!-- Plugin JS File -->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="../assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="../assets/js/main.min.js"></script>
</body>
</html>