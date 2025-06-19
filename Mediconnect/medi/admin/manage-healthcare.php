<?php
session_start();
error_reporting(0);
include('include/config.php');

// Handle delete request
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $sql = "DELETE FROM healthcare_facilities WHERE id = '$id'";
    if (mysqli_query($con, $sql)) {
        echo '<script>alert("Facility deleted successfully."); window.location.href="manage-healthcare.php";</script>';
        exit();
    } else {
        echo '<script>alert("Error deleting facility."); window.location.href="manage-healthcare.php";</script>';
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | View Healthcare Facilities</title>
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
    <?php include('include/sidebar.php');?>
    <div class="app-content">
        <?php include('include/header.php');?>
        <div class="main-content">
            <div class="wrap-content container" id="container">

                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">Admin | View Healthcare Facilities</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li class="active"><span>Healthcare Facilities</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="add-facility.php" class="btn btn-success" style="margin-bottom:15px;">
                                <i class="fa fa-plus"></i> Add New Facilities
                            </a>
                            <h5 class="over-title margin-bottom-15">View <span class="text-bold">Facilities</span></h5>
                            <table class="table table-hover" id="sample-table-1">
                                <thead>
                                    <tr>
                                        <th class="center">#</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Location</th>
                                        <th>Specialty</th>
                                        <th>Status</th>
                                        <th>Rating</th>
                                        <th>Contact</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = mysqli_query($con, "SELECT * FROM healthcare_facilities");
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($sql)) {
                                    ?>
                                        <tr>
                                            <td class="center"><?php echo $cnt; ?>.</td>
                                            <td><?php echo htmlentities($row['name']); ?></td>
                                            <td><?php echo htmlentities($row['type']); ?></td>
                                            <td><?php echo htmlentities($row['location']); ?></td>
                                            <td><?php echo htmlentities($row['specialty']); ?></td>
                                            <td><?php echo htmlentities($row['operational_status']); ?></td>
                                            <td><?php echo htmlentities($row['rating']); ?></td>
                                            <td><?php echo htmlentities($row['contact']); ?></td>
                                            <td>
                                                <a href="edit-facility.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-xs">Edit</a>
                                                <a href="manage-healthcare.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this facility?');">Delete</a>
                                            </td>
                                        </tr>
                                    <?php 
                                    $cnt++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php include('include/footer.php');?>
    <?php include('include/setting.php');?>
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
