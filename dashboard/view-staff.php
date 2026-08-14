<?php
include('session-check.php');
include('db_conn.php');
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
    <title>DEE MART – VIEW STAFF</title>

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
                            <h1 class="page-title">Staff List</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Staff List</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="javascript:history.back()">< Go back</a></li>
                                </ol>
                            </div>

                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">All Staff</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                                <thead>
                                                    <tr>
                                                        <th class="border-bottom-0">ID</th>
                                                        <th class="border-bottom-0">Email</th>
                                                        <th class="border-bottom-0">UIN</th>
                                                        <th class="border-bottom-0">Fullname</th>
                                                        <th class="border-bottom-0">Phone</th>
                                                        <th class="border-bottom-0">Role</th>
                                                        <th class="border-bottom-0">Status</th>
                                                        <th class="border-bottom-0">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php
                                                include("db_conn.php");
                                                $sql="SELECT * FROM stafftable ORDER BY fullname ASC";
                                                $result= mysqli_query($conn, $sql);
                                                if(mysqli_num_rows($result)>0){
                                                $count=1;
                                                while($row=mysqli_fetch_array($result)){
                                                
                                                ?>
                                                    <tr>
                                                        <td><?php echo $count++?></td>
                                                        <td><?php echo $row['email']?></td>
                                                        <td><?php echo $row['uin']?></td>
                                                        <td><?php echo $row['fullname']?></td>
                                                        <td><?php echo $row['phone']?></td>
                                                        <td><?php echo $row['role']?></td>
                                                        <td><?php echo $row['status']?></td>
                                                         <td>
                                                             <div class="dropdown">
                                                        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                                <i class="fe fe-action me-2"></i>Action
                                                            </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="delete-staff.php?id=<?php echo $row['id']?>" onclick="return confirm('Are you sure to delete this staff?')">Delete Staff</a>

                                                            <a class="dropdown-item" href="profile.php?id=<?php echo $row['id']?>">View Staff</a>

                                                            <a class="dropdown-item" href="edit-staff.php?id=<?php echo $row['id']?>"> Edit Staff</a>

                                                            <a class="dropdown-item" href="idcard.php?uin=<?php echo $row['uin']?>" target="_blank">Generate Staff ID Card</a>
                                                        </div>
                                                    </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                }
                                                    ?>
                                                   
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Row -->

                       
                       
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

    <!-- INTERNAL SELECT2 JS -->
    <script src="assets/plugins/select2/select2.full.min.js"></script>

    <!-- DATA TABLE JS-->
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
    <script src="assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
    <script src="assets/plugins/datatable/js/jszip.min.js"></script>
    <script src="assets/plugins/datatable/pdfmake/pdfmake.min.js"></script>
    <script src="assets/plugins/datatable/pdfmake/vfs_fonts.js"></script>
    <script src="assets/plugins/datatable/js/buttons.html5.min.js"></script>
    <script src="assets/plugins/datatable/js/buttons.print.min.js"></script>
    <script src="assets/plugins/datatable/js/buttons.colVis.min.js"></script>
    <script src="assets/plugins/datatable/dataTables.responsive.min.js"></script>
    <script src="assets/plugins/datatable/responsive.bootstrap5.min.js"></script>
    <script src="assets/js/table-data.js"></script>

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