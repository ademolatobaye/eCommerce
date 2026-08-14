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
    <title>DEE MART – STAFF'S PROFILE</title>

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
                            <h1 class="page-title">Staff's Profile</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Profile</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="javascript:history.back()"> < Go back</a></li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- ROW-1 OPEN -->
                        <div class="row" id="user-profile">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="wideget-user mb-2">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="row">
                                                        <div class="panel profile-cover">
                                                            <div class="profile-cover__action bg-img"></div>
                                                            <div class="profile-cover__img">
                                                                <div class="profile-img-1">
                                                                    <img src="assets/images/users/21.jpg" alt="img">
                                                                </div>
                                                                <div class="profile-img-content text-dark text-start">
                                                                    <div class="text-dark">
                                                                        <h3 class="h3 mb-2"><?php echo $staff['fullname'];?></h3>
                                                                        <h5 class="text-muted"><?php echo $staff['role'];?></h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                         
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="px-0 px-sm-4">
                                                            <div class="social social-profile-buttons mt-5 float-end">
                                                                <div class="mt-3">
                                                                   
                                                                    <a class="social-icon text-primary" href="https://myaccount.google.com/" target="_blank"><i class="fa fa-google-plus"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                       

                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">About Staff</div>
                                            </div>
                                            <div class="card-body">
                                               
                                               
                                              
                                                <div class="d-flex align-items-center mb-3 mt-3">
                                                    <div class="me-4 text-center text-primary">
                                                        <span><i class="fe fe-phone fs-20"></i></span>
                                                    </div>
                                                    <div>
                                                        <strong><?php echo $staff['phone'];?></strong>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center mb-3 mt-3">
                                                    <div class="me-4 text-center text-primary">
                                                        <span><i class="fe fe-mail fs-20"></i></span>
                                                    </div>
                                                    <div>
                                                        <strong><?php echo $staff['email'];?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <!-- COL-END -->
                        </div>
                        <!-- ROW-1 CLOSED -->

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