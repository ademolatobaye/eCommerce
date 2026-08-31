<?php
include("db_conn.php");
if(isset($_REQUEST['id'])){
    $staff_id = intval($_REQUEST['id']);
    $sql = "DELETE FROM stafftable WHERE id='$staff_id'";
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Staff successfully deleted.'); window.location.href='view-staff';</script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>