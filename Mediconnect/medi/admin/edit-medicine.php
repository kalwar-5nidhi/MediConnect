<?php
session_start();
include('include/config.php');
error_reporting(0);

$id = intval($_GET['id']);

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $status = $_POST['availability_status'];

    // Handle image upload
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($image_tmp, "uploads/medicines/" . $image);
        $query = mysqli_query($con, "UPDATE medicines SET name='$name', brand='$brand', category='$category', price='$price', description='$description', image='$image', availability_status='$status' WHERE id='$id'");
    } else {
        $query = mysqli_query($con, "UPDATE medicines SET name='$name', brand='$brand', category='$category', price='$price', description='$description', availability_status='$status' WHERE id='$id'");
    }

    if ($query) {
        $_SESSION['msg'] = "Medicine updated successfully!";
        header('location:medicine-management.php');
    } else {
        $_SESSION['msg'] = "Something went wrong. Please try again.";
    }
}

// Fetch current data
$query = mysqli_query($con, "SELECT * FROM medicines WHERE id='$id'");
$medicine = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Edit Medicine</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Medicine</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" required class="form-control" value="<?php echo htmlentities($medicine['name']); ?>">
        </div>
        <div class="mb-3">
            <label>Brand</label>
            <input type="text" name="brand" required class="form-control" value="<?php echo htmlentities($medicine['brand']); ?>">
        </div>
        <div class="mb-3">
            <label>Category</label>
            <input type="text" name="category" required class="form-control" value="<?php echo htmlentities($medicine['category']); ?>">
        </div>
        <div class="mb-3">
            <label>Price (Rs.)</label>
            <input type="number" name="price" required class="form-control" value="<?php echo htmlentities($medicine['price']); ?>">
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" required class="form-control"><?php echo htmlentities($medicine['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label>Current Image</label><br>
            <?php if ($medicine['image']) { ?>
                <img src="uploads/medicines/<?php echo htmlentities($medicine['image']); ?>" width="100"><br><br>
            <?php } else { echo "No image uploaded."; } ?>
            <input type="file" name="image" class="form-control">
            <small>Upload only if you want to change the current image.</small>
        </div>
        <div class="mb-3">
    <label>Availability Status</label>
    <select name="availability_status" class="form-control" required>
        <option value="1" <?php if((int)$medicine['availability_status'] === 1) echo 'selected'; ?>>In Stock</option>
        <option value="0" <?php if((int)$medicine['availability_status'] === 0) echo 'selected'; ?>>Out of Stock</option>
    </select>
</div>
        <button type="submit" name="submit" class="btn btn-primary">Update Medicine</button>
    </form>
</div>
</body>
</html>
