<?php
session_start();
include('include/config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>MediConnect | User Dashboard</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- Theme -->
    <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
</head>
<body>
    <div id="app">
        <?php include('include/sidebar.php'); ?>
        <div class="app-content">
            <?php include('include/header.php'); ?>

            <!-- Main Content -->
            <div class="main-content">
                <div class="wrap-content container" id="container">
                    <!-- Page Title -->
                    <section id="page-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h1 class="mainTitle">Welcome to MediConnect, <?php echo htmlentities($_SESSION['user_name']); ?>!</h1>
                            </div>
                            <ol class="breadcrumb">
                                <li><span>User</span></li>
                                <li class="active"><span>Dashboard</span></li>
                            </ol>
                        </div>
                    </section>

                    <!-- Dashboard Options -->
                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <!-- Profile -->
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="fa fa-user-circle fa-3x text-primary"></i>
                                        <h4 class="mt-3">My Profile</h4>
                                        <a href="edit-profile.php" class="btn btn-sm btn-outline-primary mt-2">Edit Profile</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Appointments -->
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="fa fa-calendar-check-o fa-3x text-success"></i>
                                        <h4 class="mt-3">My Appointments</h4>
                                        <a href="appointment-history.php" class="btn btn-sm btn-outline-success mt-2">View History</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Book Appointment -->
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="fa fa-plus-circle fa-3x text-info"></i>
                                        <h4 class="mt-3">Book Appointment</h4>
                                        <a href="book-appointment.php" class="btn btn-sm btn-outline-info mt-2">Book Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Options -->
                        <div class="row mt-4">
                            <!-- Medicine Orders -->
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="fa fa-medkit fa-3x text-danger"></i>
                                        <h4 class="mt-3">My Medicine Orders</h4>
                                        <a href="medicine-orders.php" class="btn btn-sm btn-outline-danger mt-2">View Orders</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Emergency Access -->
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="fa fa-ambulance fa-3x text-warning"></i>
                                        <h4 class="mt-3">Emergency Services</h4>
                                        <a href="emergency.php" class="btn btn-sm btn-outline-warning mt-2">Access Now</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Search Facilities -->
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="fa fa-hospital-o fa-3x text-secondary"></i>
                                        <h4 class="mt-3">Find Healthcare Facilities</h4>
                                        <a href="search-facilities.php" class="btn btn-sm btn-outline-secondary mt-2">Search Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /container-fullw -->
                </div> <!-- /wrap-content -->
            </div> <!-- /main-content -->

            <?php include('include/footer.php'); ?>
        </div> <!-- /app-content -->

        <?php include('include/setting.php'); ?>
    </div> <!-- /app -->

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        $(document).ready(function () {
            Main.init();
        });
    </script>
</body>
</html>
