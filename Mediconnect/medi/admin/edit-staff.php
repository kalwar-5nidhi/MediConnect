<?php
session_start();
error_reporting(0);
include('include/config.php');

$staff_id = intval($_GET['id']);

if(isset($_POST['submit'])) {
    $full_name = $_POST['full_name'];
    $role = $_POST['role'];
    $phone_number = $_POST['phone_number'];
    $status = $_POST['status'];

    $query = mysqli_query($con, "UPDATE staff SET full_name='$full_name', role='$role', phone_number='$phone_number', status='$status' WHERE staff_id='$staff_id'");

    if($query) {
        $_SESSION['msg'] = "Staff updated successfully!";
        header('location:manage-staffs.php');
    } else {
        $_SESSION['msg'] = "Error updating staff!";
    }
}

$ret = mysqli_query($con, "SELECT * FROM staff WHERE staff_id='$staff_id'");
$row = mysqli_fetch_array($ret);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Edit Staff</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Edit Staff Details</h2>
    <form method="post">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" value="<?php echo htmlentities($row['full_name']); ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Role</label>
            <input type="text" name="role" value="<?php echo htmlentities($row['role']); ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone_number" value="<?php echo htmlentities($row['phone_number']); ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="On_duty" <?php if($row['status']=='On_duty') echo 'selected'; ?>>On_duty</option>
                <option value="Off_duty" <?php if($row['status']=='Off_duty') echo 'selected'; ?>>Off_duty</option>
                <option value="Avaliable" <?php if($row['status']=='Avaliable') echo 'selected'; ?>>Avaliable</option>
            </select>
        </div>
        <button type="submit" name="submit" class="btn btn-success mt-3">Update Staff</button>
    </form>
</div>
</body>
</html>
