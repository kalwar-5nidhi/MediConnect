<?php
session_start();
error_reporting(0);
include('include/config.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch form data
    $name = $_POST['name'];
    $type = $_POST['type'];
    $location = $_POST['location'];
    $specialty = $_POST['specialty'];
    $status = $_POST['status'];
    $rating = $_POST['rating'];
    $contact = $_POST['contact'];

    // Insert query to add the new healthcare facility
    $insert_sql = "INSERT INTO healthcare_facilities (name, type, location, specialty, operational_status, rating, contact)
                   VALUES ('$name', '$type', '$location', '$specialty', '$status', '$rating', '$contact')";

    // Execute the query and check if the insertion is successful
    if (mysqli_query($con, $insert_sql)) {
        // Redirect with a success message
        echo '<script>alert("Facility added successfully."); window.location.href="manage-healthcare.php";</script>';
    } else {
        // Display error message if insertion fails
        echo '<script>alert("Error adding facility."); window.location.href="add-facility.php";</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Add Healthcare Facility</title>
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
                            <h1 class="mainTitle">Admin | Add Healthcare Facility</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li><span>Add Healthcare Facility</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="over-title margin-bottom-15">Add <span class="text-bold">New Facility</span></h5>
                            <!-- Form to Add Facility -->
                            <form name="add_facility" method="POST">
                                <div class="form-group">
                                    <label for="name">Facility Name</label>
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="type">Facility Type</label>
                                    <input type="text" class="form-control" name="type" id="type" required>
                                </div>
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <input type="text" class="form-control" name="location" id="location" required>
                                </div>
                                <div class="form-group">
                                    <label for="specialty">Specialty</label>
                                    <input type="text" class="form-control" name="specialty" id="specialty" required>
                                </div>
                                <div class="form-group">
                                    <label for="status">Operational Status</label>
                                    <input type="text" class="form-control" name="status" id="status" required>
                                </div>
                                <div class="form-group">
                                    <label for="rating">Rating</label>
                                    <input type="text" class="form-control" name="rating" id="rating" required>
                                </div>
                                <div class="form-group">
                                    <label for="contact">Contact</label>
                                    <input type="text" class="form-control" name="contact" id="contact" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Facility</button>
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
