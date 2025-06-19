<?php
session_start();
error_reporting(0);
include('include/config.php');

// if (strlen($_SESSION['id']) == 0) {
//     header('location:logout.php');
//     exit();
// }

// Fetch review details
$review_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$review_data = null;

if ($review_id > 0) {
    $query = mysqli_query($con, "SELECT r.id, r.name AS reviewer_name, r.rating, r.comment, r.submission_date, 
                                       f.name AS facility_name
                                FROM reviews r
                                JOIN healthcare_facilities f ON r.facility_id = f.id
                                WHERE r.id = '$review_id'");
    $review_data = mysqli_fetch_array($query);
}

// Handle form submission
if (isset($_POST['submit'])) {
    $reviewer_name = mysqli_real_escape_string($con, $_POST['reviewer_name']);
    $rating = intval($_POST['rating']);
    $comment = mysqli_real_escape_string($con, $_POST['comment']);
    
    $update_query = mysqli_query($con, "UPDATE reviews 
                                      SET name = '$reviewer_name', 
                                          rating = '$rating', 
                                          comment = '$comment'
                                      WHERE id = '$review_id'");
    
    if ($update_query) {
        echo "<script>alert('Review updated successfully');</script>";
        echo "<script>window.location.href='reviews-feedback.php';</script>";
    } else {
        echo "<script>alert('Error updating review');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Edit Review</title>
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
    <link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/plugins.css">
    <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
</head>
<body>
    <div id="app">
        <?php include('include/sidebar.php'); ?>
        <div class="app-content">
            <?php include('include/header.php'); ?>
            <div class="main-content">
                <div class="wrap-content container" id="container">
                    <section id="page-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h1 class="mainTitle">Admin | Edit Review</h1>
                            </div>
                            <ol class="breadcrumb">
                                <li><span>Admin</span></li>
                                <li><a href="reviews.php">Reviews and Feedback</a></li>
                                <li class="active"><span>Edit Review</span></li>
                            </ol>
                        </div>
                    </section>
                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="over-title margin-bottom-15">Edit <span class="text-bold">Review</span></h5>
                                <?php if ($review_data) { ?>
                                    <form method="post" class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Facility Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="<?php echo $review_data['facility_name']; ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Reviewer Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="reviewer_name" class="form-control" value="<?php echo $review_data['reviewer_name']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Rating</label>
                                            <div class="col-sm-10">
                                                <select name="rating" class="form-control" required>
                                                    <option value="1" <?php echo $review_data['rating'] == 1 ? 'selected' : ''; ?>>1</option>
                                                    <option value="2" <?php echo $review_data['rating'] == 2 ? 'selected' : ''; ?>>2</option>
                                                    <option value="3" <?php echo $review_data['rating'] == 3 ? 'selected' : ''; ?>>3</option>
                                                    <option value="4" <?php echo $review_data['rating'] == 4 ? 'selected' : ''; ?>>4</option>
                                                    <option value="5" <?php echo $review_data['rating'] == 5 ? 'selected' : ''; ?>>5</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Comment</label>
                                            <div class="col-sm-10">
                                                <textarea name="comment" class="form-control" rows="5" required><?php echo $review_data['comment']; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Submission Date</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="<?php echo $review_data['submission_date']; ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" name="submit" class="btn btn-primary">Update Review</button>
                                                <a href="reviews-feedback.php" class="btn btn-default">Cancel</a>
                                            </div>
                                        </div>
                                    </form>
                                <?php } else { ?>
                                    <div class="alert alert-danger">Invalid review ID or review not found.</div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('include/footer.php'); ?>
        <?php include('include/setting.php'); ?>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/modernizr/modernizr.js"></script>
    <script src="vendor/jquery-cookie/jquery.cookie.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="vendor/switchery/switchery.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/form-elements.js"></script>
    <script>
        jQuery(document).ready(function() {
            Main.init();
            FormElements.init();
        });
    </script>
</body>
</html>