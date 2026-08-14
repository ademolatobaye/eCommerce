<?php
include("db_conn.php");
if(isset($_REQUEST['id'])){
    $cat_id = intval($_REQUEST['id']);
    $sql = "DELETE FROM blog_category WHERE id='$cat_id'";
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Blog Category successfully deleted.'); window.location.href='view-blog-category.php';</script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>