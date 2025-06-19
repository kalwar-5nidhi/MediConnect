<?php
session_start();
error_reporting(0);
include('include/config.php');

// Delete medicine record
if(isset($_GET['del'])) {
    $medid = $_GET['id'];
    mysqli_query($con, "DELETE FROM medicines WHERE id ='$medid'");
    $_SESSION['msg'] = "Medicine record deleted!";
    header('location:medicine-management.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Manage Medicines</title>
    <!-- CSS files -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
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
                            <h1 class="mainTitle">Admin | Manage Medicines</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li class="active"><span>Medicines</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">

                            <!-- Add Medicine Button -->
                            <a href="add-medicine.php" class="btn btn-success" style="margin-bottom:15px;">
                                    <i class="fa fa-plus"></i> Add New Medicine
                                </a>

                            <!-- Session Message -->
                            <p style="color:green;"><?php echo htmlentities($_SESSION['msg']); $_SESSION['msg']=""; ?></p>

                            <!-- Medicines Table -->
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Brand</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Description</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
$sql = mysqli_query($con, "SELECT * FROM medicines");
$cnt = 1;
while ($row = mysqli_fetch_array($sql)) {
?>
    <tr>
        <td><?php echo $cnt; ?>.</td>
        <td><?php echo htmlentities($row['name']); ?></td>
        <td><?php echo htmlentities($row['brand']); ?></td>
        <td><?php echo htmlentities($row['category']); ?></td>
        <td>Rs. <?php echo htmlentities($row['price']); ?></td>
        <td><?php echo htmlentities($row['description']); ?></td>
        <td>
            <?php if (!empty($row['image'])) { ?>
                <img src="uploads/medicines/<?php echo $row['image']; ?>" width="50">
            <?php } else { echo "No image"; } ?>
        </td>
        <td>
            <?php 
            $status = $row['availability_status'];
            if (!empty($status) && $status == 1) {
                echo "<span style='color:green;'>In Stock</span>";
            } else {
                echo "<span style='color:red;'>Out of Stock</span>";
            }            
            ?>
        </td>
        <td><?php echo htmlentities($row['created_at']); ?></td>
        <td>
            <a href="edit-medicine.php?id=<?php echo $row['id'];?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="medicine-management.php?id=<?php echo $row['id'];?>&del=delete" 
               onClick="return confirm('Are you sure you want to delete this medicine?');" 
               class="btn btn-sm btn-danger">Delete</a>
        </td>
    </tr>
<?php 
$cnt++;
} 
?>
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

<!-- JS files -->
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
