<?php
session_start();
include('include/config.php');
if (isset($_POST['submit'])) {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $city = $_POST['city'];
    $gender = $_POST['gender'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = mysqli_query($con, "INSERT INTO users (fullName, email, city, gender, password, regDate) 
        VALUES ('$fullName', '$email', '$city', '$gender', '$password', NOW())");

    if ($query) {
        $_SESSION['msg'] = "User added successfully!";
        header("Location: manage-user.php");
        exit();
    } else {
        $_SESSION['msg'] = "Error adding user!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Add New User</h2>
        <form method="post">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullName" required class="form-control">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required class="form-control">
            </div>
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" class="form-control">
            </div>
            <div class="form-group">
                <label>Gender</label>
                <select name="gender" class="form-control">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required class="form-control">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Add User</button>
        </form>
    </div>
</body>
</html>
