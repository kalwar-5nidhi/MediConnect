<?php
session_start();
error_reporting(0);
include('include/config.php');

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $order_id = intval($_GET['id']);
    $query = mysqli_query($con, "DELETE FROM orders WHERE id='$order_id'");
    if ($query) {
        $_SESSION['msg'] = "Order deleted successfully!";
    } else {
        $_SESSION['msg'] = "Failed to delete order!";
    }
    header('Location: manage-delivery.php');
    exit();
}

// Handle Update
if (isset($_POST['update'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];
    $query = mysqli_query($con, "UPDATE orders SET status='$status' WHERE id='$order_id'");
    if ($query) {
        $_SESSION['msg'] = "Order status updated successfully!";
        header('Location: manage-delivery.php');
        exit();
    } else {
        $_SESSION['msg'] = "Error updating order!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Delivery Management</title>
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
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
            <h1 class="mainTitle">Admin | Delivery Management</h1>
        </div>
        <ol class="breadcrumb">
            <li><span>Admin</span></li>
            <li class="active"><span>Delivery Management</span></li>
        </ol>
    </div>
</section>

<div class="container-fluid container-fullw bg-white">
<div class="row">
<div class="col-md-12">
<?php if(isset($_SESSION['msg'])) { ?>
<div class="alert alert-info">
    <?php echo htmlentities($_SESSION['msg']); unset($_SESSION['msg']); ?>
</div>
<?php } ?>

<?php
// Handle View or Edit
if (isset($_GET['action']) && ($_GET['action'] == 'view' || $_GET['action'] == 'edit') && isset($_GET['id'])) {
    $order_id = intval($_GET['id']);
    $sql = mysqli_query($con, "SELECT * FROM orders WHERE id='$order_id'");
    $row = mysqli_fetch_array($sql);

    if ($_GET['action'] == 'view') {
?>
<h5 class="over-title margin-bottom-15">View <span class="text-bold">Order</span></h5>
<table class="table table-bordered">
    <tr><th>Order ID</th><td><?php echo $row['id']; ?></td></tr>
    <tr><th>User ID</th><td><?php echo $row['user_id']; ?></td></tr>
    <tr><th>Pharmacy ID</th><td><?php echo $row['pharmacy_id']; ?></td></tr>
    <tr><th>Order Date</th><td><?php echo $row['order_date']; ?></td></tr>
    <tr><th>Status</th><td><?php echo $row['status']; ?></td></tr>
    <tr><th>Total</th><td><?php echo $row['total_amount']; ?></td></tr>
    <tr><th>Address</th><td><?php echo $row['delivery_address']; ?></td></tr>
    <tr><th>Customer Name</th><td><?php echo $row['customer_name']; ?></td></tr>
    <tr><th>Customer Email</th><td><?php echo $row['customer_email']; ?></td></tr>
    <tr><th>Customer Phone</th><td><?php echo $row['customer_phone']; ?></td></tr>
    <tr><th>Payment Method</th><td><?php echo $row['payment_method']; ?></td></tr>
    <tr><th>Created At</th><td><?php echo $row['created_at']; ?></td></tr>
</table>
<a href="manage-delivery.php" class="btn btn-primary">Back</a>

<?php
    } elseif ($_GET['action'] == 'edit') {
?>
<h5 class="over-title margin-bottom-15">Edit <span class="text-bold">Order</span></h5>
<form method="post">
    <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
    <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control" required>
            <option value="">Select Status</option>
            <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
            <option value="Processing" <?php if($row['status']=='Processing') echo 'selected'; ?>>Processing</option>
            <option value="Delivered" <?php if($row['status']=='Delivered') echo 'selected'; ?>>Delivered</option>
            <option value="Cancelled" <?php if($row['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
        </select>
    </div>
    <button type="submit" name="update" class="btn btn-success">Update</button>
    <a href="manage-delivey.php" class="btn btn-secondary">Cancel</a>
</form>

<?php
    }
} else {
?>
<h5 class="over-title margin-bottom-15">Manage <span class="text-bold">Deliveries</span></h5>
<table class="table table-hover" id="sample-table-1">
<thead>
<tr>
    <th>#</th>
    <th>User ID</th>
    <th>Pharmacy ID</th>
    <th>Order Date</th>
    <th>Status</th>
    <th>Total</th>
    <th>Address</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Payment</th>
    <th>Created At</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php
$sql = mysqli_query($con, "SELECT * FROM orders ORDER BY created_at DESC");
$cnt = 1;
while($row = mysqli_fetch_array($sql)) {
?>
<tr>
    <td><?php echo $cnt;?>.</td>
    <td><?php echo $row['user_id'];?></td>
    <td><?php echo $row['pharmacy_id'];?></td>
    <td><?php echo $row['order_date'];?></td>
    <td><?php echo $row['status'];?></td>
    <td><?php echo $row['total_amount'];?></td>
    <td><?php echo $row['delivery_address'];?></td>
    <td><?php echo $row['customer_name'];?></td>
    <td><?php echo $row['customer_email'];?></td>
    <td><?php echo $row['customer_phone'];?></td>
    <td><?php echo $row['payment_method'];?></td>
    <td><?php echo $row['created_at'];?></td>
    <td>
        <a href="manage-delivery.php?action=view&id=<?php echo $row['id'];?>" class="btn btn-primary btn-xs">View</a>
        <a href="manage-delivery.php?action=edit&id=<?php echo $row['id'];?>" class="btn btn-warning btn-xs">Edit</a>
        <a href="manage-delivery.php?action=delete&id=<?php echo $row['id'];?>" class="btn btn-danger btn-xs" onclick="return confirm('Delete this record?');">Delete</a>
    </td>
</tr>
<?php
$cnt++;
}
?>
</tbody>
</table>
<?php } ?>

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
