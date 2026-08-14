<?php
include("db_conn.php");

$sql = "DELETE FROM invoiceorder";

if(mysqli_query($conn, $sql)){
    echo "<script>
            alert('Cart successfully cleared.');
            window.location.href='cart.php';
          </script>";
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>