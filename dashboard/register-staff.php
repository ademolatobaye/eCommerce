<?php
include('session-check.php');
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
    header("Location: ../management/");
    exit();
}

$id = 1;
$sql = "SELECT * FROM stafftable WHERE id='$id'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$rows = mysqli_fetch_array($result);

?>

<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/brand/favicon.png">

    <!-- TITLE -->
    <title><?php echo $business_name; ?> – STAFF REGISTRATION</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- STYLE CSS -->
     <link href="assets/css/style.css" rel="stylesheet">

	<!-- Plugins CSS -->
    <link href="assets/css/plugins.css" rel="stylesheet">

    <!--- FONT-ICONS CSS -->
    <link href="assets/css/icons.css" rel="stylesheet">

    <!-- INTERNAL Switcher css -->
    <link href="assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="assets/switcher/demo.css" rel="stylesheet">

</head>

<body class="app sidebar-mini ltr light-mode">
    <?php
    include("menu.php");
    ?>

    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src="assets/images/loader.svg" class="loader-img" alt="Loader">
    </div>
    <!-- /GLOBAL-LOADER -->

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">



            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Add New Staff</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">New Staff</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="javascript:history.back()"> < Go back</a></li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- ROW OPEN -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">New Staff Form</h3>
                                    </div>
                                    <div class="card-body">

                                        <form class="needs-validation" novalidate method="post" onsubmit="return check()">
                                        
                                        <?php
                                        include("db_conn.php");
                                        date_default_timezone_set("Africa/Lagos");
                                        $rand =rand(1000,9999);
                                        $today =date("dmyhis");
                                        $UIN = "REG" . $rand. $today;
                                        error_reporting(E_ALL);
                                        if(isset($_REQUEST["submit"])){
                                            $email= trim(addslashes($_REQUEST["email"]));
                                            $fullname= trim(addslashes($_REQUEST["fullname"]));
                                            $phone= trim(addslashes($_REQUEST["phone"]));
                                            $password= trim(addslashes($_REQUEST["password"]));
                                            $role= trim(addslashes($_REQUEST["role"]));

                                             // CHECKING FOR DUPLICATE RECORDS.
                                                 $check = mysqli_query($conn, "SELECT * FROM stafftable WHERE email = '$email' OR phone = '$phone'");
                                                $checkrows = mysqli_num_rows($check);
                                                
                                                if($checkrows > 0){
                                                echo "<script>alert('Staff already exists.')</script>";
                                                }
                                                else{

                                                  // INSERTING VALUES INTO DATABASE.
                                                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                                                $sql="INSERT INTO stafftable (email, uin, fullname, phone, `password`, `role`, `status`) VALUES ('$email', '$UIN', '$fullname', '$phone', '$hashed_password', '$role', 'Verified')";
                                                mysqli_query($conn, $sql) or die(mysqli_error($conn));
                                                $num = mysqli_insert_id($conn);
                                                if(mysqli_affected_rows($conn)!= 1){
                                                $message = "Error inserting record into database.";
                                                }
                                                else{

                                                 echo "<script>alert('Staff successfully registered.');
                                                  window.location.href='view-staff'</script>";
                                            }
                                                }
                                            }
                                        
                                        ?>

                                            <div class="form-row">
                                                <div class="col-xl-6 mb-3">
                                                    <label for="validationCustom01">Fullname</label>
                                                    <input type="text" name="fullname" class="form-control" id="validationCustom01" placeholder="Input staff's fullname" required>
                                                    <div class="valid-feedback">Looks good!</div>
                                                </div>

                                                <div class="col-xl-6 mb-3">
                                                    <label for="validationCustom02">Email Address</label>
                                                    <input type="text" class="form-control" id="validationCustom02" name="email" placeholder="Input staff's email address" required>
                                                    <div class="valid-feedback">Looks good!</div>
                                                </div>

                                                 <input type="hidden" class="form-control" id="validationCustom02" name="uin" required value="<?php echo $UIN; ?>">

                                            </div>
                                            
                                            <div class="form-row">
                                                <div class="col-xl-6 mb-3">
                                                    <label for="validationCustom03">Phone Number</label>
                                                    <input type="number" class="form-control" id="validationCustom03" name="phone" placeholder="Input staff's phone number" required>
                                                    <div class="invalid-feedback">Please provide a valid phone number.</div>
                                                </div>

                                                <div class="col-xl-6 mb-3">
                                                    <label for="validationCustom03">Password</label>
                                                    <input type="password" class="form-control" id="password" name="password" placeholder="Input password" required oninput="return check()">
                                                </div>

                                                 <div class="col-xl-6 mb-3">
                                                    <label for="validationCustom04">Designation</label>
                                                    <select class="form-control select2" id="validationCustom04" name="role" required>
                                                        <option value="">Select Staff's Role</option>
                                                        <option value="Super Admin">Super Admin</option>
                                                        <option value="Admin">Admin</option>
                                                        <option value="Manager">Manager</option>
                                                        <option value="Staff">Supervisor</option>
                                                        <option value="Cashier">Staff</option>
                                                        <option value="Front Desk">Cashier</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please select a role.</div>
                                                </div>

                                                <div class="col-xl-6 mb-3">
                                                    <label for="validationCustom03">Confirm Password</label>
                                                    <input type="number" class="form-control" id="passwordReg" name="passwordReg" placeholder="Confirm password" required oninput="return check()">
                                                    <span id="error"></span>
                                                </div>

                                               </div>

                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="invalidCheck" required>
                                                    <label class="form-check-label" for="invalidCheck">Agree to terms and
                                                        conditions</label>
                                                    <div class="invalid-feedback">You must agree before submitting.</div>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary btn-block" type="submit" name="submit" onclick="return confirm('Are you sure to register new staff?')">Submit form</button>
                                        </form>

                                         <script>
                // CHECKING IF PASSWORD MATCHES.
                function check(){
                    let password = document.getElementById("password").value;
                    let passwordReg = document.getElementById("passwordReg").value;

                    if(passwordReg !== password){
                        document.getElementById("error").textContent = `Password does not match!`;
                        document.getElementById("error").style.color = `red`;
                        return false;
                    }else if(passwordReg == password){
                        document.getElementById("error").textContent = `Password matches correctly!`;
                        document.getElementById("error").style.color = `green`;
                        return true;
                    }
                    // END
                }
                
              </script>

                                    </div>
                                </div>
                            </div>


                        </div>
                        <!-- ROW CLOSED -->
                    </div>
                    <!-- CONTAINER CLOSED -->
                </div>
            </div>
            <!--app-content closed-->
        </div>

        <?php
        include("footer.php");
        ?>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- JQUERY JS -->
    <script src="assets/js/jquery.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- INPUT MASK JS-->
    <script src="assets/plugins/input-mask/jquery.mask.min.js"></script>

	<!-- TypeHead js -->
	<script src="assets/plugins/bootstrap5-typehead/autocomplete.js"></script>
    <script src="assets/js/typehead.js"></script>

    <!-- SELECT2 JS -->
    <script src="assets/plugins/select2/select2.full.min.js"></script>

    <!-- FORMVALIDATION JS -->
    <script src="assets/js/form-validation.js"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="assets/plugins/p-scroll/perfect-scrollbar.js"></script>
    <script src="assets/plugins/p-scroll/pscroll.js"></script>
    <script src="assets/plugins/p-scroll/pscroll-1.js"></script>

    <!-- SIDE-MENU JS -->
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>

    <!-- SIDEBAR JS -->
    <script src="assets/plugins/sidebar/sidebar.js"></script>

    <!-- Color Theme js -->
    <script src="assets/js/themeColors.js"></script>

    <!-- Sticky js -->
    <script src="assets/js/sticky.js"></script>

    <!-- CUSTOM JS -->
    <script src="assets/js/custom.js"></script>

    <!-- Custom-switcher -->
    <script src="assets/js/custom-swicher.js"></script>

    <!-- Switcher js -->
    <script src="assets/switcher/js/switcher.js"></script>

</body>

</html>