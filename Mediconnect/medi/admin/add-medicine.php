<?php
session_start();
include('include/config.php');
error_reporting(0);

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $status = $_POST['availability_status'];

    // Upload image
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($image_tmp, "uploads/medicines/" . $image);

    $query = mysqli_query($con, "INSERT INTO medicines(name, brand, category, price, description, image, availability_status, created_at) 
                                VALUES('$name', '$brand', '$category', '$price', '$description', '$image', '$status', NOW())");
    
    if ($query) {
        $_SESSION['msg'] = "Medicine added successfully!";
        header('location:medicine-management.php');
    } else {
        $_SESSION['msg'] = "Something went wrong. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Add Medicine</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Add New Medicine</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Brand</label>
            <input type="text" name="brand" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Category</label>
            <input type="text" name="category" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Price (Rs.)</label>
            <input type="number" name="price" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" required class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Upload Image</label>
            <input type="file" name="image" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Availability Status</label>
            <select name="availability_status" class="form-control" required>
                <option value="1">In Stock</option>
                <option value="0">Out of Stock</option>
            </select>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Add Medicine</button>
    </form>
</div>
</body>
</html>
