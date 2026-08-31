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
    <title>DEE MART || STAFF DASHBOARD</title>

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
                            <h1 class="page-title"><?php date_default_timezone_set("Africa/Lagos"); // Set your timezone
                                    $hour = date("H"); // Get current hour (24-hour format)
                                    if ($hour >= 5 && $hour < 12) {
                                        echo "Good Morning, $session_role $session_fullname.";
                                    } 
                                    elseif ($hour >= 12 && $hour < 16) {
                                        echo "Good Afternoon, $session_role $session_fullname.";
                                    } 
                                    elseif ($hour >= 16 && $hour < 21) {
                                        echo "Good Evening, $session_role $session_fullname.";
                                    } 
                                    else {
                                        echo "Good Night, $session_role $session_fullname.";
                                        
                                        }
                                        ?></h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- ROW-1 -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                                        <div class="card overflow-hidden">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="mt-2">
                                                        <h6 class="">Total Users</h6>
                                                         <?php
                                                        include("db_conn.php");
                                                        $sql="SELECT COUNT(*) AS totalcustomers FROM customertable";
                                                        $result=mysqli_query($conn, $sql);
                                                        while($count= mysqli_fetch_array($result)){
                                                        ?>
                                                        <h2 class="mb-0 number-font"><?php echo $count['totalcustomers']; ?></h2>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="ms-auto">
                                                        <div class="chart-wrapper mt-1">
                                                            <canvas id="saleschart"
                                                                class="h-8 w-9 chart-dropshadow"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                                        <div class="card overflow-hidden">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="mt-2">
                                                        <h6 class="">Total Profit</h6>
                                                         <?php
                                                        include("db_conn.php");
                                                        $sql="SELECT SUM(profit) AS totalprofit FROM product_table";
                                                        $result= mysqli_query($conn, $sql);
                                                        while($count= mysqli_fetch_array($result)){
                                                        ?>
                                                        <h2 class="mb-0 number-font">&#8358;<?php echo number_format($count['totalprofit'], 2); ?></h2>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>

                                                    <div class="ms-auto">
                                                        <div class="chart-wrapper mt-1">
                                                            <canvas id="leadschart"
                                                                class="h-8 w-9 chart-dropshadow"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                               
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                                        <div class="card overflow-hidden">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="mt-6">
                                                        <h2 class="mb-0 number-font"><a href="" data-bs-toggle="modal" data-bs-target="#newcategory">Add New Category</a></h2>
                                                    </div>
                                                    <div class="ms-auto">
                                                        <div class="chart-wrapper mt-1">
                                                            <!-- <canvas id="profitchart"
                                                                class="h-8 w-9 chart-dropshadow"></canvas> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                                        <div class="card overflow-hidden">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="mt-6">
                                                        <h2 class="mb-0 number-font"><a href="" data-bs-toggle="modal" data-bs-target="#newblogcategory">Add New Blog Category</a></h2>
                                                    </div>
                                                    <div class="ms-auto">
                                                        <div class="chart-wrapper mt-1">
                                                            <!-- <canvas id="profitchart"
                                                                class="h-8 w-9 chart-dropshadow"></canvas> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                                        <div class="card overflow-hidden">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="mt-6">
                                                         <h2 class="mb-0 number-font"><a href="add-product">Add New Product</a></h2>
                                                    </div>
                                                    <div class="ms-auto">
                                                        <div class="chart-wrapper mt-1">
                                                            <!-- <canvas id="profitchart"
                                                                class="h-8 w-9 chart-dropshadow"></canvas> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                                        <div class="card overflow-hidden">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="mt-6">
                                                         <h2 class="mb-0 number-font"><a href="product">View All Products</a></h2>
                                                    </div>
                                                    <div class="ms-auto">
                                                        <div class="chart-wrapper mt-1">
                                                            <!-- <canvas id="profitchart"
                                                                class="h-8 w-9 chart-dropshadow"></canvas> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                                        <div class="card overflow-hidden">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="mt-6">
                                                         <h2 class="mb-0 number-font"><a href="view-category">View All Categories</a></h2>
                                                    </div>
                                                    <div class="ms-auto">
                                                        <div class="chart-wrapper mt-1">
                                                            <!-- <canvas id="profitchart"
                                                                class="h-8 w-9 chart-dropshadow"></canvas> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                                        <div class="card overflow-hidden">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="mt-6">
                                                         <h2 class="mb-0 number-font"><a href="orders">View All Orders</a></h2>
                                                    </div>
                                                    <div class="ms-auto">
                                                        <div class="chart-wrapper mt-1">
                                                            <!-- <canvas id="profitchart"
                                                                class="h-8 w-9 chart-dropshadow"></canvas> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                   

                                </div>
                            </div>
                        </div>
                        <!-- ROW-1 END -->

                    <?php
            // Get last 7 days labels
            $labels = array();
            $sales_data = array();
            $orders_data = array();
            
            for ($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $label = date('D d', strtotime("-$i days"));
                array_push($labels, $label);
            
                // Total Sales (amount) from invoicesales for that day
                $sales_sql = "SELECT SUM(amount) as totalsales FROM invoicesales WHERE DATE(`date`) = '$date'";
                $sales_result = mysqli_query($conn, $sales_sql);
                $sales_row = mysqli_fetch_array($sales_result);
                array_push($sales_data, $sales_row['totalsales'] ? $sales_row['totalsales'] : 0);
            
                // Total Orders (count) from invoiceorder for that day
                $orders_sql = "SELECT COUNT(*) as totalorders FROM invoiceorder WHERE DATE(`date`) = '$date'";
                $orders_result = mysqli_query($conn, $orders_sql);
                $orders_row = mysqli_fetch_array($orders_result);
                array_push($orders_data, $orders_row['totalorders'] ? $orders_row['totalorders'] : 0);
            }
            
            // Convert to JSON for Chart.js
            $labels_json = json_encode($labels);
            $sales_json = json_encode($sales_data);
            $orders_json = json_encode($orders_data);
            ?>
                       
                       
                        <!-- ROW-2 -->
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Sales Analytics (7 days)</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex mx-auto text-center justify-content-center mb-4">
                                            <div class="d-flex text-center justify-content-center me-3"><span
                                                    class="dot-label bg-primary my-auto"></span>Total Sales</div>
                                            <div class="d-flex text-center justify-content-center"><span
                                                    class="dot-label bg-secondary my-auto"></span>Total Orders</div>
                                        </div>
                                        <div class="chartjs-wrapper-demo">
                                            <canvas id="transactions" class="chart-dropshadow"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             
                        </div>
                        <!-- ROW-2 END -->

                <script>
                    var labels = <?php echo $labels_json; ?>;
                    var salesData = <?php echo $sales_json; ?>;
                    var ordersData = <?php echo $orders_json; ?>;
                
                    var ctx = document.getElementById('transactions').getContext('2d');
                    var transactionsChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Total Sales (₦)',
                                    data: salesData,
                                    borderColor: '#6259ca',
                                    backgroundColor: 'rgba(98, 89, 202, 0.1)',
                                    borderWidth: 2,
                                    fill: true,
                                    tension: 0.4,
                                    pointBackgroundColor: '#6259ca',
                                    pointRadius: 4
                                },
                                {
                                    label: 'Total Orders',
                                    data: ordersData,
                                    borderColor: '#f1388b',
                                    backgroundColor: 'rgba(241, 56, 139, 0.1)',
                                    borderWidth: 2,
                                    fill: true,
                                    tension: 0.4,
                                    pointBackgroundColor: '#f1388b',
                                    pointRadius: 4
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            if (context.datasetIndex === 0) {
                                                return ' ₦' + context.raw.toLocaleString();
                                            }
                                            return ' ' + context.raw + ' Orders';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(255,255,255,0.1)' }
                                },
                                x: {
                                    grid: { color: 'rgba(255,255,255,0.1)' }
                                }
                            }
                        }
                    });
                </script>


                       
                    </div>
                    <!-- CONTAINER END -->
                </div>
            </div>
            <!--app-content close-->

        </div>

       
    <?php
    include("footer.php");
    ?>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>
    
    <script>
        var transactionLabels = <?php echo $labels_json; ?>;
        var transactionSales = <?php echo $sales_json; ?>;
        var transactionOrders = <?php echo $orders_json; ?>;
    </script>

    <!-- JQUERY JS -->
    <script src="assets/js/jquery.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- SPARKLINE JS-->
    <script src="assets/js/jquery.sparkline.min.js"></script>

    <!-- Sticky js -->
    <script src="assets/js/sticky.js"></script>

    <!-- CHART-CIRCLE JS-->
    <script src="assets/js/circle-progress.min.js"></script>

    <!-- PIETY CHART JS-->
    <script src="assets/plugins/peitychart/jquery.peity.min.js"></script>
    <script src="assets/plugins/peitychart/peitychart.init.js"></script>

    <!-- SIDEBAR JS -->
    <script src="assets/plugins/sidebar/sidebar.js"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="assets/plugins/p-scroll/perfect-scrollbar.js"></script>
    <script src="assets/plugins/p-scroll/pscroll.js"></script>
    <script src="assets/plugins/p-scroll/pscroll-1.js"></script>

    <!-- INTERNAL CHARTJS CHART JS-->
    <script src="assets/plugins/chart/Chart.bundle.js"></script>
    <script src="assets/plugins/chart/utils.js"></script>

    <!-- INTERNAL SELECT2 JS -->
    <script src="assets/plugins/select2/select2.full.min.js"></script>

    <!-- INTERNAL Data tables js-->
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
    <script src="assets/plugins/datatable/dataTables.responsive.min.js"></script>

    <!-- INTERNAL APEXCHART JS -->
    <script src="assets/js/apexcharts.js"></script>
    <script src="assets/plugins/apexchart/irregular-data-series.js"></script>

    <!-- INTERNAL Flot JS -->
    <script src="assets/plugins/flot/jquery.flot.js"></script>
    <script src="assets/plugins/flot/jquery.flot.fillbetween.js"></script>
    <script src="assets/plugins/flot/chart.flot.sampledata.js"></script>
    <script src="assets/plugins/flot/dashboard.sampledata.js"></script>

    <!-- INTERNAL Vector js -->
    <script src="assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

    <!-- SIDE-MENU JS-->
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>

	<!-- TypeHead js -->
	<script src="assets/plugins/bootstrap5-typehead/autocomplete.js"></script>
    <script src="assets/js/typehead.js"></script>

    <!-- INTERNAL INDEX JS -->
    <script src="assets/js/index1.js"></script>

    <!-- Color Theme js -->
    <script src="assets/js/themeColors.js"></script>

    <!-- CUSTOM JS -->
    <script src="assets/js/custom.js"></script>

    <!-- Custom-switcher -->
    <script src="assets/js/custom-swicher.js"></script>

    <!-- Switcher js -->
    <script src="assets/switcher/js/switcher.js"></script>

</body>

</html>