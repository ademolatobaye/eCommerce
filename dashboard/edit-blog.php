<?php
include("session-check.php"); 
include("db_conn.php");

$sql = "SELECT * FROM system_setting LIMIT 1";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$setting_row = mysqli_fetch_assoc($result);
$phone = $setting_row['phone'];
$business_name = $setting_row['business_name'];
$address = $setting_row['address'];
$email = $setting_row['email'];

// Check if business_name is NULL or empty
if (empty($setting_row['business_name'])) {
    header("Location: ../management/");
    exit();
}

if (!isset($_REQUEST['id'])) {
    header("Location: blog");
    exit();
}

$blog_id = intval($_REQUEST['id']);

$query = "SELECT * FROM blog WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $blog_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$blog = mysqli_fetch_assoc($result);

if (!$blog) {
    echo "<script>alert('Blog post not found.'); window.location.href='blog';</script>";
    exit();
}
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $business_name; ?> – Edit Blog</title>

    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="assets/switcher/demo.css" rel="stylesheet">
</head>

<body class="app sidebar-mini ltr light-mode">
    <?php include("menu.php"); ?>

    <div class="page">
        <div class="page-main">
            <div class="main-content app-content mt-0">
                <div class="side-app">
                    <div class="main-container container-fluid">

                        <div class="page-header">
                            <h1 class="page-title">Edit Blog</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="blog">Blogs</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Edit Blog</li>
                                </ol>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <form method="post">
                                        <?php
                                        date_default_timezone_set("Africa/Lagos");
                                        error_reporting(E_ALL);

                                        if (isset($_POST["update_blog"])) {
                                            $headline = mysqli_real_escape_string($conn, trim($_POST["headline"]));
                                            $category = mysqli_real_escape_string($conn, trim($_POST["category"]));
                                            $content = mysqli_real_escape_string($conn, trim($_POST["content"]));
                                            $photocredit = mysqli_real_escape_string($conn, trim($_POST["photocredit"]));
                                            $staff = isset($session_role) ? mysqli_real_escape_string($conn, $session_role) : mysqli_real_escape_string($conn, $blog['staff']);

                                            $update_sql = "UPDATE blog SET headline='$headline', category='$category', content='$content', photocredit='$photocredit', staff='$staff' WHERE id='$blog_id'";
                                            if (mysqli_query($conn, $update_sql)) {
                                                echo "<script>alert('Blog details updated successfully.'); window.location.href='blog';</script>";
                                            } else {
                                                echo "<script>alert('Error updating blog post.');</script>";
                                            }
                                        }
                                        ?>

                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <div class="card-title">Edit Blog Details</div>
                                            <a href="edit-blog-images?id=<?php echo $blog['id']; ?>" class="btn btn-info btn-sm">
                                                <i class="fa fa-image me-1"></i> Edit Blog Images
                                            </a>
                                        </div>

                                        <div class="card-body">
                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Post Headline :</label>
                                                <div>
                                                    <input type="text" class="form-control" name="headline" value="<?php echo htmlspecialchars($blog['headline']); ?>" required>
                                                </div>
                                            </div>

                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Category :</label>
                                                <div>
                                                    <select name="category" class="form-control form-select select2" data-bs-placeholder="Select Category">
                                                        <option value="">Select Blog Category</option>
                                                        <?php
                                                        $cat_query = mysqli_query($conn, "SELECT * FROM blog_category");
                                                        if ($cat_query && mysqli_num_rows($cat_query) > 0) {
                                                            while ($cat_row = mysqli_fetch_assoc($cat_query)) {
                                                                $cat_name = $cat_row['blogcategoryname'];
                                                                $selected = ($blog['category'] == $cat_name) ? 'selected' : '';
                                                                echo "<option value=\"$cat_name\" $selected>$cat_name</option>";
                                                            }
                                                        } else {
                                                            $default_cats = array("Technology", "Politics", "Travel", "Food", "Fashion", "Sports");
                                                            foreach ($default_cats as $cat_name) {
                                                                $selected = ($blog['category'] == $cat_name) ? 'selected' : '';
                                                                echo "<option value=\"$cat_name\" $selected>$cat_name</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label class="col-md-3 form-label mb-4">Blog Content :</label>
                                                <div class="mb-4">
                                                    <textarea class="content" name="content"><?php echo htmlspecialchars($blog['content']); ?></textarea>
                                                </div>
                                            </div>

                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Photo Credit :</label>
                                                <div>
                                                    <input type="text" class="form-control" name="photocredit" value="<?php echo htmlspecialchars($blog['photocredit']); ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-footer">
                                            <button type="submit" name="update_blog" class="btn btn-primary btn-block" onclick="return confirm('Are you sure to update this blog?')">Update Blog</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?php include("footer.php"); ?>
    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/plugins/select2/select2.full.min.js"></script>
    <script src="assets/js/select2.js"></script>
    <script src="assets/plugins/wysiwyag/jquery.richtext.js"></script>
    <script src="assets/plugins/wysiwyag/wysiwyag.js"></script>
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>
    <script src="assets/plugins/sidebar/sidebar.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
