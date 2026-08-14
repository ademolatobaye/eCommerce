<?php
include("db_conn.php");
if(isset($_REQUEST['id'])){
    $blog_id = intval($_REQUEST['id']);
    
    // Also cleanup blog images
    $get_uin_q = mysqli_query($conn, "SELECT uin FROM blog WHERE id = '$blog_id'");
    if ($get_uin_q && mysqli_num_rows($get_uin_q) > 0) {
        $uin_row = mysqli_fetch_assoc($get_uin_q);
        $uin_escaped = mysqli_real_escape_string($conn, $uin_row['uin']);
        mysqli_query($conn, "DELETE FROM blog_images WHERE uin = '$uin_escaped'");
    }

    $sql = "DELETE FROM blog WHERE id='$blog_id'";
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Blog successfully deleted.'); window.location.href='blog.php';</script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>