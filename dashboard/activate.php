<?php
include("db_conn.php");
if(isset($_REQUEST['id'])){
    $vendor_id = intval($_REQUEST['id']);
    $sql = "UPDATE vendor_table SET `status` = 'Active' WHERE id='$vendor_id'";
    if(mysqli_query($conn, $sql)){
        echo "<script>alert(`Vendor's account successfully activated.`); 
        window.location.href='vendors';
        </script>";
    } else {
        echo "Error activating record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>