<?php
session_start();
include('include/config.php');

// Delete payment using prepared statements to prevent SQL injection
if (isset($_GET['del']) && is_numeric($_GET['del'])) {
    $del_id = intval($_GET['del']);
    $stmt = $con->prepare("DELETE FROM payment_transactions WHERE id = ?");
    $stmt->bind_param("i", $del_id);
    if ($stmt->execute()) {
        echo "<script>alert('Payment deleted successfully'); window.location.href='payment-transactions.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to delete payment');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Payment Transactions</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/fontawesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
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
                            <h1 class="mainTitle">Admin | Payment Transactions</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li class="active"><span>Payment Transactions</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="over-title margin-bottom-15">Manage <span class="text-bold">Payments</span></h5>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Transaction ID</th>
                                    <th>Customer Name</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Payment Date</th>
                                    <th>Amount</th>
                                    <th>Remarks</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $query = "SELECT * FROM payment_transactions ORDER BY payment_date DESC";
                                $result = mysqli_query($con, $query);
                                $cnt = 1;

                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $cnt; ?>.</td>
                                        <td><?php echo htmlspecialchars($row['transaction_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                                        <td><?php echo htmlspecialchars($row['payment_date']); ?></td>
                                        <td><?php echo htmlspecialchars($row['amount']); ?></td>
                                        <td><?php echo htmlspecialchars($row['remarks']); ?></td>
                                        <td>
                                            <a href="edit-payment.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                                            <a href="payment-transactions.php?del=<?php echo $row['id']; ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Are you sure you want to delete this payment?');">
                                                Delete
                                            </a>
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
</body>
</html>
