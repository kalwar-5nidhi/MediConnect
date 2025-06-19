<?php
session_start();
error_reporting(0);
include('include/config.php');

// Delete user logic
if (isset($_GET['del']) && isset($_GET['id'])) {
    $uid = intval($_GET['id']);
    mysqli_query($con, "DELETE FROM users WHERE id ='$uid'");
    $_SESSION['msg'] = "User deleted successfully!!";
    header('Location: manage-users.php'); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Manage Users</title>
    <!-- Include your CSS files here -->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
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
                                <h1 class="mainTitle">Admin | Manage Users</h1>
                            </div>
                            <ol class="breadcrumb">
                                <li><span>Admin</span></li>
                                <li class="active"><span>Manage Users</span></li>
                            </ol>
                        </div>
                    </section>

                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="over-title margin-bottom-15">Manage <span class="text-bold">Users</span></h5>
                                <a href="add-user.php" class="btn btn-success" style="margin-bottom:15px;">
                                    <i class="fa fa-plus"></i> Add New User
                                </a>
                                <p style="color:red;"><?php echo htmlentities($_SESSION['msg']); ?></p>    
                                <table class="table table-hover" id="sample-table-1">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Registered On</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = mysqli_query($con, "SELECT * FROM users");
                                        $cnt = 1;
                                        while ($row = mysqli_fetch_array($sql)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?>.</td>
                                            <td><?php echo htmlentities($row['full_name']); ?></td>
                                            <td><?php echo htmlentities($row['email']); ?></td>
                                            <td><?php echo htmlentities($row['created_at']); ?></td>
                                            <td>
                                                <a href="edit-user.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-xs">Edit</a>
                                                <a href="manage-users.php?id=<?php echo $row['id']; ?>&del=delete" onClick="return confirm('Are you sure you want to delete this user?');" class="btn btn-danger btn-xs">Delete</a>
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
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
