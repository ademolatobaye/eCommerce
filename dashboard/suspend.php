<?php
include("db_conn.php");
if(isset($_REQUEST['id'])){
    $vendor_id = intval($_REQUEST['id']);
    $sql = "UPDATE vendor_table SET `status` = 'Suspended' WHERE id='$vendor_id'";
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Vendor successfully suspended.'); 
        window.location.href='vendors.php';
        </script>";
    } else {
        echo "Error suspending record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>