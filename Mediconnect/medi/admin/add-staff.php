<?php
session_start();
error_reporting(0);
include('include/config.php');

if(isset($_POST['submit'])) {
    $full_name = $_POST['full_name'];
    $role = $_POST['role'];
    $phone_number = $_POST['phone_number'];
    $status = $_POST['status'];
    $joined_date = date('Y-m-d'); // today's date

    $query = mysqli_query($con, "INSERT INTO staff (full_name, role, phone_number, status, joined_date) 
                                 VALUES ('$full_name', '$role', '$phone_number', '$status', '$joined_date')");

    if($query) {
        $_SESSION['msg'] = "Staff added successfully!";
        header('location:manage-staffs.php');
    } else {
        $_SESSION['msg'] = "Error adding staff!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Add Staff</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Add New Staff</h2>
    <form method="post">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Role</label>
            <input type="text" name="role" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone_number" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <button type="submit" name="submit" class="btn btn-primary mt-3">Add Staff</button>
    </form>
</div>
</body>
</html>
