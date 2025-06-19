<?php
session_start();
error_reporting(0);
include('include/config.php');

// Check if 'id' is set in the URL
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($con, "SELECT * FROM healthcare_facilities WHERE id = '$id'");
    $row = mysqli_fetch_array($result);

    // Handle form submission for updating facility details
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $type = $_POST['type'];
        $location = $_POST['location'];
        $specialty = $_POST['specialty'];
        $status = $_POST['status'];
        $rating = $_POST['rating'];
        $contact = $_POST['contact'];

        // Update query
        $update_sql = "UPDATE healthcare_facilities SET 
                        name='$name', 
                        type='$type', 
                        location='$location', 
                        specialty='$specialty', 
                        operational_status='$status', 
                        rating='$rating', 
                        contact='$contact' 
                        WHERE id='$id'";

        // Check if the update was successful
        if (mysqli_query($con, $update_sql)) {
            echo '<script>alert("Facility updated successfully."); window.location.href="manage-healthcare.php";</script>';
        } else {
            echo '<script>alert("Error updating facility."); window.location.href="edit-facility.php?id='.$id.'";</script>';
        }
    }
} else {
    // If 'id' is not set in the URL, redirect to view page
    header("Location: manage-healthcare.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Edit Healthcare Facility</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
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
                            <h1 class="mainTitle">Admin | Edit Healthcare Facility</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li><span>Edit Healthcare Facility</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="over-title margin-bottom-15">Edit <span class="text-bold">Facility</span></h5>
                            <form name="edit_facility" method="POST">
                                <div class="form-group">
                                    <label for="name">Facility Name</label>
                                    <input type="text" class="form-control" name="name" value="<?php echo htmlentities($row['name']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <input type="text" class="form-control" name="type" value="<?php echo htmlentities($row['type']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <input type="text" class="form-control" name="location" value="<?php echo htmlentities($row['location']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="specialty">Specialty</label>
                                    <input type="text" class="form-control" name="specialty" value="<?php echo htmlentities($row['specialty']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="status">Operational Status</label>
                                    <input type="text" class="form-control" name="status" value="<?php echo htmlentities($row['operational_status']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="rating">Rating</label>
                                    <input type="text" class="form-control" name="rating" value="<?php echo htmlentities($row['rating']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="contact">Contact</label>
                                    <input type="text" class="form-control" name="contact" value="<?php echo htmlentities($row['contact']); ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Facility</button>
                            </form>
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
<script src="assets/js/main.js"></script>
</body>
</html>
