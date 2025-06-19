<?php
session_start();
error_reporting(0);
include('include/config.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = mysqli_query($con, "SELECT * FROM users WHERE id='$id'");
    $row = mysqli_fetch_array($query);
}

if (isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];

    mysqli_query($con, "UPDATE users SET fullName='$fullname', address='$address', city='$city', gender='$gender', email='$email', updationDate=NOW() WHERE id='$id'");
    $_SESSION['msg'] = "User updated successfully!!";
    header('Location: manage-user.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Edit User</h2>
        <form method="post">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" class="form-control" value="<?php echo htmlentities($row['fullName']); ?>" required>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" class="form-control" value="<?php echo htmlentities($row['address']); ?>">
            </div>
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" class="form-control" value="<?php echo htmlentities($row['city']); ?>">
            </div>
            <div class="form-group">
                <label>Gender</label>
                <select name="gender" class="form-control" required>
                    <option value="male" <?php if($row['gender'] == 'male') echo 'selected'; ?>>Male</option>
                    <option value="female" <?php if($row['gender'] == 'female') echo 'selected'; ?>>Female</option>
                    <option value="other" <?php if($row['gender'] == 'other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlentities($row['email']); ?>" required>
            </div>
            <button type="submit" name="submit" class="btn btn-success">Update User</button>
            <a href="manage-users.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
