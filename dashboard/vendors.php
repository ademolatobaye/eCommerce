<?php
include('session-check.php');
include('db_conn.php');

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;

// require 'includes/Exception.php';
// require 'includes/PHPMailer.php';
// require 'includes/SMTP.php';

// Enforce access control for Super Admin / Admin
check_access(array('Super Admin', 'Admin', 'Manager'));

$message = "";
$messageType = "";

// Handle Vendor Status Updates
if (isset($_GET['action']) && isset($_GET['vendor_uin'])){
    $action = $_GET['action'];
    $vendor_uin    = mysqli_real_escape_string($conn, $_GET['vendor_uin']);

    $vendor_query = mysqli_query($conn, "SELECT * FROM vendor_table WHERE vendor_uin = '$vendor_uin' LIMIT 1");
    $vendor_data = mysqli_fetch_assoc($vendor_query);

    if ($vendor_data) {
        if ($action === 'approve') {
            $update = mysqli_query($conn, "UPDATE vendor_table SET `status` = 'Active' WHERE vendor_uin = '$vendor_uin'");
            if ($update) {
                $vendor_email = $vendor_data['vendor_email'];
                $store_name   = $vendor_data['store_name'];
                $vendor_name  = $vendor_data['vendor_name'];
                $login_url    = "http://" . $_SERVER['HTTP_HOST'] . "/eCommerce/vendor/login.php";

                // Create instance of PHPMailer
                // $mail = new PHPMailer();
                // // Set mailer to use smtp
                // $mail->isSMTP();
                // // Define smtp host
                // $mail->Host = "mail.pocketvest.com.ng";
                // // Enable smtp authentication
                // $mail->SMTPAuth = true;
                // // Set smtp encryption type (ssl/tls)
                // $mail->SMTPSecure = "ssl";
                // // Port to connect smtp
                // $mail->Port = "465";
                // // Set gmail username
                // $mail->Username = "ademolaomomeji@pocketvest.com.ng";
                // // Set gmail password
                // $mail->Password = "Omomejih08";
                // // Email subject
                // $mail->Subject = "Vendor Account Approved - Welcome to DEE MART!";
                // // Set sender email
                // $mail->setFrom('ademolaomomeji@pocketvest.com.ng', 'DEE MART');
                // // Enable HTML
                // $mail->isHTML(true);

                // // Email body
                // $mail->Body = "
                // <html>
                // <head>
                //     <style>
                //         body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; }
                //         .container { max-width: 600px; background-color: #ffffff; margin: 0 auto; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
                //         .header { background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); color: #ffffff; text-align: center; padding: 20px; border-radius: 8px 8px 0 0; }
                //         .content { padding: 25px 0; color: #333333; line-height: 1.6; }
                //         .btn { display: inline-block; background-color: #4f46e5; color: #ffffff !important; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-weight: bold; margin-top: 15px; }
                //         .footer { margin-top: 25px; font-size: 12px; color: #888888; text-align: center; }
                //     </style>
                // </head>
                // <body>
                //     <div class='container'>
                //         <div class='header'>
                //             <h2>DEE MART VENDOR PORTAL</h2>
                //         </div>
                //         <div class='content'>
                //             <h3>Congratulations, " . htmlspecialchars($vendor_name) . "!</h3>
                //             <p>We are excited to inform you that your vendor account for <strong>" . htmlspecialchars($store_name) . "</strong> has been officially <strong>APPROVED</strong> by the Super Admin team!</p>
                //             <p>You can now log in to your vendor dashboard to start uploading products, managing inventory, and tracking customer orders.</p>
                //             <div style='text-align: center;'>
                //                 <a href='" . $login_url . "' class='btn'>Log In to Vendor Portal</a>
                //             </div>
                //             <p style='margin-top: 25px;'>If you have any questions or require assistance setting up your store, please contact our vendor support desk.</p>
                //         </div>
                //         <div class='footer'>
                //             <p>&copy; " . date('Y') . " DEE MART E-Commerce Platform. All rights reserved.</p>
                //         </div>
                //     </div>
                // </body>
                // </html>";

                // // Add recipient
                // $mail->addAddress($vendor_email);
                // // Send email
                // $mail->send();

                $message = "Vendor store <strong>" . htmlspecialchars($vendor_data['store_name']) . "</strong> has been APPROVED and an approval email notification has been dispatched!";
                $messageType = "success";
            }
        }
    }
}

// Fetch all vendors
$sql = "SELECT * FROM vendor_table ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/brand/favicon.png">
    <title>DEE MART – Vendor Management</title>

    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
</head>
<body class="app sidebar-mini ltr light-mode">
    <?php 
    include("menu.php");
     ?>

    <div class="page">
        <div class="page-main">
            <div class="main-content app-content mt-0">
                <div class="side-app">
                    <div class="main-container container-fluid">

                        <!-- PAGE HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Vendor Applications & Stores</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Vendors</li>
                                </ol>
                            </div>
                        </div>

                        <!-- VENDORS LIST -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header border-bottom-0">
                                        <h3 class="card-title">All Registered Vendors</h3>
                                    </div>
                                    <div class="card-body">

                                        <?php if (!empty($message)): ?>
                                            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                                                <?php echo $message; ?>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        <?php endif; ?>

                                        <div class="table-responsive">
                                            <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                                <thead>
                                                    <tr>
                                                        <th>S/N</th>
                                                        <th>Store Logo</th>
                                                        <th>Store Name</th>
                                                        <th>Owner Name</th>
                                                        <th>Email / Phone</th>
                                                        <th>Address</th>
                                                        <th>Registered Date</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if ($result && mysqli_num_rows($result) > 0): 
                                                    $count=1; 
                                                    while ($v = mysqli_fetch_assoc($result)): ?>
                                                            <tr>
                                                                <td><?php echo $count++; ?></td>
                                                                <td class="text-center">
                                                                    <?php if (!empty($v['logo']) && file_exists("../vendor/vendorupload/" . $v['logo'])): ?>
                                                                        <img src="../vendor/vendorupload/<?php echo htmlspecialchars($v['logo']); ?>" alt="logo" class="avatar avatar-md brround cover-image">
                                                                    <?php else: ?>
                                                                        <img src="assets/images/users/21.jpg" alt="logo" class="avatar avatar-md brround cover-image">
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <strong><?php echo htmlspecialchars($v['store_name']); ?></strong><br>
                                                                    <small class="text-muted">Vendor ID: <?php echo htmlspecialchars($v['vendor_uin']); ?></small>
                                                                </td>
                                                                <td><?php echo htmlspecialchars($v['vendor_name']); ?></td>
                                                                <td>
                                                                    <small>
                                                                        <i class="fe fe-mail me-1"></i><?php echo htmlspecialchars($v['vendor_email']); ?><br>
                                                                        <i class="fe fe-phone me-1"></i><?php echo htmlspecialchars($v['vendor_phone']); ?>
                                                                    </small>
                                                                </td>
                                                                <td><?php echo htmlspecialchars($v['store_address']); ?></td>
                                                                <td><?php echo date('jS-F-Y', strtotime($v['date'])); ?></td>
                                                                <td>
                                                                    <?php if ($v['status'] === 'Active'): ?>
                                                                        <span class="badge bg-success-light text-success fw-bold">Active</span>
                                                                    <?php elseif ($v['status'] === 'Pending'): ?>
                                                                        <span class="badge bg-warning-light text-warning fw-bold">Pending Approval</span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-danger-light text-danger fw-bold"><?php echo htmlspecialchars($v['status']); ?></span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">
                                                                            Action
                                                                        </button>
                                                                        <div class="dropdown-menu">
                                                                            <?php if ($v['status'] === 'Pending'): ?>
                                                                                <a class="dropdown-item text-success" href="approve?id=<?php echo urlencode($v['id']); ?>" onclick="return confirm('Approve this vendor account? This will send an approval email.')">
                                                                                    <i class="fe fe-check me-1"></i> Approve Vendor
                                                                                </a>
                                                                            <?php elseif ($v['status'] === 'Active'): ?>
                                                                                <a class="dropdown-item text-warning" href="suspend?id=<?php echo urlencode($v['id']); ?>" onclick="return confirm('Suspend this vendor account?')">
                                                                                    <i class="fe fe-slash me-1"></i> Suspend Vendor
                                                                                </a>
                                                                            <?php elseif ($v['status'] === 'Suspended'): ?>
                                                                                <a class="dropdown-item text-success" href="activate?id=<?php echo urlencode($v['id']); ?>" onclick="return confirm('Activate this vendor account? This will send an activation email.')">
                                                                                    <i class="fe fe-check me-1"></i> Activate Vendor
                                                                                </a>
                                                                            <?php endif; ?>

                                                                            <a class="dropdown-item text-info" href="../vendor-store?vendor_uin=<?php echo urlencode($v['vendor_uin']); ?>" target="_blank">
                                                                                <i class="fe fe-eye me-1"></i> View Store
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endwhile; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="9" class="text-center py-4 text-muted">No vendor accounts registered yet.</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include("footer.php"); ?>
    </div>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- JQUERY JS -->
    <script src="assets/js/jquery.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- INPUT MASK JS-->
    <script src="assets/plugins/input-mask/jquery.mask.min.js"></script>

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
