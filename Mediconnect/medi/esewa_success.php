<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('include/config.php');

// Load PHPMailer classes
require 'include/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'include/vendor/phpmailer/phpmailer/src/SMTP.php';
require 'include/vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check for payment data
if (!isset($_GET['data'])) {
    header('Location: esewa_failure.php?error=No%20payment%20data%20received');
    exit();
}

// Decode the base64-encoded data
$decoded_data = base64_decode($_GET['data']);
if (!$decoded_data) {
    header('Location: esewa_failure.php?error=Failed%20to%20decode%20payment%20data');
    exit();
}

// Parse the JSON data
$payment_response = json_decode($decoded_data, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    header('Location: esewa_failure.php?error=Invalid%20payment%20data%20format');
    exit();
}

// Extract necessary details
$transaction_uuid = $payment_response['transaction_uuid'] ?? null;
$total_amount = $payment_response['total_amount'] ?? null;
$status = $payment_response['status'] ?? null;
$ref_id = $payment_response['ref_id'] ?? '';

if (!$transaction_uuid || !$total_amount || !$status) {
    header('Location: esewa_failure.php?error=Missing%20payment%20details');
    exit();
}

// Verify payment status
if ($status === 'COMPLETE') {
    // Fetch order
    $sql = "SELECT id, total_amount, invoice_no, customer_email, customer_name FROM orders WHERE invoice_no = ?";
    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        error_log("Prepare failed for transaction_uuid $transaction_uuid: " . mysqli_error($con));
        header('Location: esewa_failure.php?error=Database%20error');
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $transaction_uuid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) == 1) {
        $order = mysqli_fetch_assoc($result);

        // Verify amount
        if (abs(floatval($total_amount) - floatval($order['total_amount'])) < 0.01) {
            // Update order status
            $sql = "UPDATE orders SET status = 'Paid' WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            if (!$stmt) {
                error_log("Prepare failed for status update: " . mysqli_error($con));
                header('Location: esewa_failure.php?error=Database%20error');
                exit();
            }
            mysqli_stmt_bind_param($stmt, "i", $order['id']);
            if (mysqli_stmt_execute($stmt)) {
                // Log transaction
                $transaction_id = 'TXN_' . time() . '_' . $order['id'];
                $sql = "INSERT INTO payment_transactions (transaction_id, amount, payment_method, status, payment_date) VALUES (?, ?, 'eSewa', 'Completed', NOW())";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "sd", $transaction_id, $order['total_amount']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                // Send email
                $customer_email = $order['customer_email'] ?? $_SESSION['email'] ?? 'user@example.com';
                $customer_name = $order['customer_name'] ?? $_SESSION['full_name'] ?? 'Valued Customer';

                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'n71671064@gmail.com'; // your email
                    $mail->Password = 'mati oanw tvbg avbq';   // Gmail App Password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('n71671064@gmail.com', 'MediConnect');
                    $mail->addAddress($customer_email, $customer_name);

                    $mail->isHTML(true);
                    $mail->Subject = 'Payment Confirmation - MediConnect';
                    $mail->Body = "
                        <h3>Hello $customer_name,</h3>
                        <p>Thank you for your payment.</p>
                        <p><strong>Transaction ID:</strong> $transaction_uuid<br>
                           <strong>Amount:</strong> Rs. $total_amount<br>
                           <strong>Status:</strong> Paid<br>
                           <strong>Delivery Status:</strong> Processing for delivery</p>
                        <br>
                        <p>We appreciate your trust in MediConnect.</p>
                        <p>Best regards,<br>MediConnect Team</p>
                    ";

                    $mail->send();
                } catch (Exception $e) {
                    error_log("Mail Error: " . $mail->ErrorInfo);
                }
            } else {
                error_log("Update failed: " . mysqli_error($con));
                header('Location: esewa_failure.php?error=Database%20error');
                exit();
            }
        } else {
            error_log("Amount mismatch for order: Expected {$order['total_amount']}, Got $total_amount");
            header('Location: esewa_failure.php?error=Amount%20mismatch');
            exit();
        }
    } else {
        error_log("Order not found for transaction_uuid: $transaction_uuid");
        header('Location: esewa_failure.php?error=Order%20not%20found');
        exit();
    }
} else {
    error_log("Transaction failed for transaction_uuid $transaction_uuid: Status = $status");
    header('Location: esewa_failure.php?error=Payment%20verification%20failed');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MediConnect - Payment Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-black">
    <header class="bg-teal-500 p-4">
        <div class="max-w-screen-xl mx-auto">
            <a href="../index.php" class="text-white text-2xl font-bold">MediConnect</a>
        </div>
    </header>
    <main class="container mx-auto px-4 py-12 text-center">
        <h2 class="text-2xl font-bold mb-4 text-green-600">Payment Successful</h2>
        <p class="mb-4">Thank you for your payment. Transaction ID: <?php echo htmlspecialchars($transaction_uuid); ?></p>
        <div class="space-x-4">
            <a href="epharmacy.php" class="bg-teal-500 text-white py-2 px-4 rounded">Continue Shopping</a>
            <a href="#contact_us" class="bg-gray-500 text-white py-2 px-4 rounded">Contact Support</a>
        </div>
    </main>
    <footer class="bg-teal-500 text-white text-center py-4">
        © <?php echo date("Y"); ?> MediConnect
    </footer>
</body>
</html>
