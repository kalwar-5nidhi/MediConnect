<?php
include('include/config.php');

$id = intval($_GET['id']);
if (isset($_POST['update'])) {
    $transaction_id = $_POST['transaction_id'];
    $payment_method = $_POST['payment_method'];
    $status = $_POST['status'];
    $payment_date = $_POST['payment_date'];
    $remarks = $_POST['remarks'];

    $query = mysqli_query($con, "UPDATE payment_transactions 
        SET transaction_id='$transaction_id', 
            payment_method='$payment_method', 
            status='$status', 
            payment_date='$payment_date',
            remarks='$remarks'
        WHERE id='$id'");

    if ($query) {
        echo "<script>alert('Payment Updated'); window.location.href = 'payment-transactions.php';</script>";
    } else {
        echo "<script>alert('Update Failed');</script>";
    }
}

$result = mysqli_query($con, "SELECT * FROM payment_transactions WHERE id='$id'");
$row = mysqli_fetch_array($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Payment</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Payment</h2>
        <form method="POST">
            <div class="form-group">
                <label>Transaction ID</label>
                <input type="text" name="transaction_id" class="form-control" value="<?php echo $row['transaction_id']; ?>" required>
            </div>
            <div class="form-group">
                <label>Customer Name</label>
                <input type="text" name="customer_name" class="form-control" value="<?php echo $row['customer_name']; ?>" readonly>
            </div>
            <div class="form-group">
                <label>Payment Method</label>
                <input type="text" name="payment_method" class="form-control" value="<?php echo $row['payment_method']; ?>">
            </div>
            <div class="form-group">
                <label>Payment Status</label>
                <select name="status" class="form-control">
                    <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="Completed" <?php if ($row['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                    <option value="Failed" <?php if ($row['status'] == 'Failed') echo 'selected'; ?>>Failed</option>
                </select>
            </div>
            <div class="form-group">
                <label>Payment Date</label>
                <input type="datetime-local" name="payment_date" class="form-control" 
                    value="<?php echo date('Y-m-d\TH:i', strtotime($row['payment_date'])); ?>">
            </div>
            <div class="form-group">
                <label>Remarks</label>
                <textarea name="remarks" class="form-control"><?php echo $row['remarks']; ?></textarea>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
            <a href="payment-transactions.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
