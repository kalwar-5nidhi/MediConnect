<?php
session_start();
error_reporting(0);
include('include/config.php');

// Delete staff record
if(isset($_GET['del'])) {
    $staffid = $_GET['id'];
    mysqli_query($con, "DELETE FROM staff WHERE staff_id ='$staffid'");
    $_SESSION['msg'] = "Staff record deleted!";
    header('location:manage-staffs.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Manage Staffs</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/fontawesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
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
                            <h1 class="mainTitle">Admin | Manage Staffs</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li class="active"><span>Manage Staffs</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">

                            <!-- Add Staff Button -->
                            <a href="add-staff.php" class="btn btn-success" style="margin-bottom:15px;">
                                    <i class="fa fa-plus"></i> Add New Staff
                                </a>

                            <!-- Session Message -->
                            <p style="color:green;"><?php echo htmlentities($_SESSION['msg']); $_SESSION['msg']=""; ?></p>

                            <!-- Staff Table -->
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Phone Number</th>
                                        <th>Status</th>
                                        <th>Joined Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
$sql = mysqli_query($con, "SELECT * FROM staff");
$cnt = 1;
while($row = mysqli_fetch_array($sql)) {
?>
<tr>
    <td><?php echo $cnt;?>.</td>
    <td><?php echo htmlentities($row['full_name']);?></td>
    <td><?php echo htmlentities(ucfirst($row['role']));?></td>
    <td><?php echo htmlentities($row['phone_number']);?></td>
    <td><?php echo htmlentities(ucfirst($row['status']));?></td>
    <td><?php echo htmlentities($row['joined_date']);?></td>
    <td>
        <a href="edit-staff.php?id=<?php echo $row['staff_id'];?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="manage-staffs.php?id=<?php echo $row['staff_id'];?>&del=delete" 
           onClick="return confirm('Are you sure you want to delete this staff?');" 
           class="btn btn-sm btn-danger">Delete</a>
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
<script src="assets/js/main.js"></script>

<script>
    jQuery(document).ready(function() {
        Main.init();
    });
</script>
</body>
</html>
