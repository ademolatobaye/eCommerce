<?php
include("session-check.php"); 
include("db_conn.php");

if (!isset($_REQUEST['id'])) {
    header("Location: view-staff.php");
    exit();
}

$staff_id = $_REQUEST['id'];

$query = "SELECT * FROM stafftable WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $staff_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$staff = mysqli_fetch_assoc($result);


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
    <title>DEE MART – Edit Staff</title>

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
                            <h1 class="page-title">Edit Staff's Profile</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="javascript:history.back()"> < Go back</a></li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- ROW-1 OPEN -->
                        <div class="row">
                          

                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Edit Staff's Profile</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-12">

                                                <form action="" method="post" onsubmit="return check()">
                                                    <?php
                                                    include("db_conn.php");
                                                    date_default_timezone_set("Africa/Lagos");
                                                    error_reporting(E_ALL);
                                                    if(isset($_REQUEST['update'])){
                                                        $email=trim(addslashes($_REQUEST['email']));
                                                        $fullname=trim(addslashes($_REQUEST['fullname']));
                                                        $phone=$_REQUEST['phone'];
                                                        $password=$_REQUEST['password'];
                                                        $role=$_REQUEST['role'];

                                                        $sql="UPDATE stafftable SET email='$email', fullname='$fullname', phone='$phone', `password`=PASSWORD('$password'), `role`='$role' WHERE id='$_REQUEST[id]'";
                                                        if(mysqli_query($conn, $sql)){
                                                        echo "<script>alert(`Staff's details successfully updated.`);
                                                        window.location.href='view-staff.php'</script>";
                                                    }else{
                                                        echo "<script>alert(`Error updating details.`)</script>";
                                                    }
                                                    }
                                                    ?>

                                                <div class="form-group">
                                                    <label for="exampleInputname">Fullname</label>
                                                    <input type="text" class="form-control" id="exampleInputname" name="fullname" value="<?php echo $staff['fullname']; ?>">
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-12">
                                                <div class="form-group">
                                                    <label for="exampleInputname1">Email Address</label>
                                                    <input type="text" class="form-control" id="exampleInputname1" name="email" value="<?php echo $staff['email']; ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="exampleInputnumber">Phone Number</label>
                                            <input type="number" class="form-control" id="exampleInputnumber" name="phone" value="<?php echo $staff['phone']; ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">New Password</label>
                                            <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                                    <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                                </a>
                                                <input class="input100 form-control" type="password" name="password" autocomplete="current-password" id="password" oninput="return check()" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Retype New Password</label>
                                            <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                                    <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                                </a>
                                                <input class="input100 form-control" type="password" name="passwordReg" autocomplete="current-password" id="passwordReg" oninput="return check()" required>
                                            </div>
                                            <span id="error"></span>
                                        </div>

                                         <div class="form-group">
                                            <label for="exampleInputnumber">Designation</label>
                                            <select name="role" id="exampleInputEmail1" class="form-control select2 form-select" required>
                                                <option value="">Select staff's role</option>
                                                <option value="Super Admin">Super Admin</option>
                                                <option value="Admin">Admin</option>
                                                <option value="Manager">Manager</option>
                                                <option value="Staff">Supervisor</option>
                                                <option value="Cashier">Staff</option>
                                                <option value="Front Desk">Cashier</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="card-footer text-end">
                                        <button class="btn btn-success my-1 btn-block" type="submit" name="update" onclick="return confirm(`Are you sure to update staff's profile?`)">Update</button>
                                    </div>

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
                        <!-- ROW-1 CLOSED -->

                    </div>
                    <!--CONTAINER CLOSED -->

                </div>
            </div>
            <!--app-content open-->
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

    <!-- SHOW PASSWORD JS -->
    <script src="assets/js/show-password.min.js"></script>

    <!-- INTERNAL SELECT2 JS -->
    <script src="assets/plugins/select2/select2.full.min.js"></script>
    <script src="assets/js/select2.js"></script>

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