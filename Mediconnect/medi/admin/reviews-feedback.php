<?php
session_start();
error_reporting(0);
include('include/config.php');

// if (strlen($_SESSION['id']) == 0) {
//     header('location:logout.php');
//     exit();
// } else {
// Handle delete action
if(isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = mysqli_query($con, "DELETE FROM reviews WHERE id = '$delete_id'");
    if($delete_query) {
        echo "<script>alert('Review deleted successfully');</script>";
        echo "<script>window.location.href='reviews.php';</script>";
    } else {
        echo "<script>alert('Error deleting review');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Reviews and Feedback</title>
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
                                <h1 class="mainTitle">Admin | Reviews and Feedback</h1>
                            </div>
                            <ol class="breadcrumb">
                                <li><span>Admin</span></li>
                                <li class="active"><span>Reviews and Feedback</span></li>
                            </ol>
                        </div>
                    </section>
                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="over-title margin-bottom-15">Manage <span class="text-bold">Reviews</span></h5>
                                <table class="table table-hover" id="sample-table-1">
                                    <thead>
                                        <tr>
                                            <th class="center">#</th>
                                            <th>Facility Name</th>
                                            <th>Reviewer Name</th>
                                            <th>Rating</th>
                                            <th>Comment</th>
                                            <th>Submission Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = mysqli_query($con, "SELECT r.id AS review_id, r.name AS reviewer_name, r.rating, r.comment, r.submission_date, 
                                                                  f.name AS facility_name
                                                           FROM reviews r
                                                           JOIN healthcare_facilities f ON r.facility_id = f.id");
                                        $cnt = 1;
                                        while ($row = mysqli_fetch_array($sql)) {
                                        ?>
                                            <tr>
                                                <td class="center"><?php echo $cnt; ?>.</td>
                                                <td><?php echo $row['facility_name']; ?></td>
                                                <td><?php echo $row['reviewer_name']; ?></td>
                                                <td><?php echo $row['rating']; ?></td>
                                                <td><?php echo $row['comment']; ?></td>
                                                <td><?php echo $row['submission_date']; ?></td>
                                                <td>
                                                <a href="edit-review.php?id=<?php echo $row['review_id']; ?>" class="btn btn-success btn-xs">Edit</a>
                                                <a href="reviews-feedback.php?delete_id=<?php echo $row['review_id']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this review?');">Delete</a>
                                            </td>
                                            </tr>
                                        <?php
                                        $cnt = $cnt + 1;
                                        } ?>
                                    </tbody>
                                </table>
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