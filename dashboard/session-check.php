<?php
session_start();
include('db_conn.php');
date_default_timezone_set('Africa/Lagos');

// 🔐 Session Timeout: 20 minutes (1200 seconds)
$timeout_duration = 1200;

// Capture the current page URL for redirect after login
$current_url = $_SERVER['REQUEST_URI'];

// ⏰ Handle session timeout
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    $_SESSION['redirect_after_login'] = $current_url;
    session_unset();
    session_destroy();
    echo "<script>
        alert('Session expired due to inactivity. Please log in again.');
        window.location.href = '../reg/staff-login.php';
    </script>";
    exit();
}

// ⏳ Update last activity time
$_SESSION['LAST_ACTIVITY'] = time();

// 🚫 Redirect if not logged in
if (!isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    $_SESSION['redirect_after_login'] = $current_url;
    header("Location: ../reg/staff-login.php");
    exit();
}

// 🔄 Assign session variables
$staff_email = $_SESSION['email'];
$staff_role  = $_SESSION['role'];

// ✅ Fetch user details from the database
$query = "SELECT * FROM stafftable WHERE email = ? AND status = 'Verified'";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $staff_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Check if the role is valid
        if ($row['role'] !== $staff_role) {
            echo "<script>
                alert('Invalid role. Please contact administrator.');
                window.location.href = '../reg/staff-login.php';
            </script>";
            exit();
        }

        // ✅ Assign session data
        $session_uin              = $row['uin'];
        $session_fullname         = $row['fullname'];
        $session_email            = $row['email'];
        $session_phone            = $row['phone'];
        $session_role             = $row['role'];
        $session_photo            = $row['photo'];
        $session_status           = $row['status'];
    } else {
        echo "<script>
            alert('Unauthorized access or inactive account.');
            window.location.href = '../reg/staff-login.php';
        </script>";
        exit();
    }
} else {
    echo "Error preparing query.";
    exit();
}



function check_access($allowed_roles) {
    global $staff_role;
    if (!in_array($staff_role, $allowed_roles)) {
        echo "<script>
            alert('You do not have access to this page.');
            window.location.href = '../reg/staff-login.php';
        </script>";
        exit();
    }
}
?>