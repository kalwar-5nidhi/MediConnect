<?php
session_start();
include('include/config.php');

// Get eSewa parameters
$oid = $_GET['oid'] ?? '';
$amt = $_GET['amt'] ?? '';
$refId = $_GET['refId'] ?? '';

// Validate parameters
if (empty($oid) || empty($amt) || empty($refId)) {
    die("Missing required payment parameters.");
}

// Verify payment with eSewa
$url = "https://uat.esewa.com.np/epay/transrec";
$data = [
    'amt' => $amt,
    'scd' => 'EPAYTEST',
    'pid' => $oid,
    'rid' => $refId
];

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);

if ($response === false) {
    die("eSewa verification failed: " . curl_error($curl));
}

curl_close($curl);

// Check payment status
if (strpos($response, 'Success') !== false) {
    // Update order status
    $update_sql = "UPDATE orders SET status='Paid' WHERE invoice_no=?";
    $stmt = mysqli_prepare($con, $update_sql);
    mysqli_stmt_bind_param($stmt, "s", $oid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Fetch order
    $order_sql = "SELECT * FROM orders WHERE invoice_no=?";
    $order_stmt = mysqli_prepare($con, $order_sql);
    mysqli_stmt_bind_param($order_stmt, "s", $oid);
    mysqli_stmt_execute($order_stmt);
    $order_result = mysqli_stmt_get_result($order_stmt);

    if ($order = mysqli_fetch_assoc($order_result)) {
        $email = $order['customer_email'];
        $name = $order['customer_name'];
        $invoice = $order['invoice_no'];
        $amount = $order['total_amount'];

        // Lookup user ID for notification
        $user_sql = "SELECT id, full_name FROM users WHERE email=?";
        $user_stmt = mysqli_prepare($con, $user_sql);
        mysqli_stmt_bind_param($user_stmt, "s", $email);
        mysqli_stmt_execute($user_stmt);
        $user_result = mysqli_stmt_get_result($user_stmt);

        if ($user = mysqli_fetch_assoc($user_result)) {
            $user_id = $user['id'];
            $user_name = $user['full_name'];

            // Insert notification
            $msg = "Your order (Invoice: $invoice) of amount Rs. $amount has been successfully placed. Delivery is on the way.";
            $notif_sql = "INSERT INTO notifications (user_id, message, status, created_at) VALUES (?, ?, 'unread', NOW())";
            $notif_stmt = mysqli_prepare($con, $notif_sql);
            mysqli_stmt_bind_param($notif_stmt, "is", $user_id, $msg);
            mysqli_stmt_execute($notif_stmt);
            mysqli_stmt_close($notif_stmt);

            // Send confirmation email
            require __DIR__ . "/vendor/autoload.php";
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'n71671064@gmail.com'; // your Gmail
            $mail->Password = 'mati oanw tvbg avbq';   // app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('n71671064@gmail.com', 'MediConnect');
            $mail->addAddress($email, $user_name);
            $mail->isHTML(true);
            $mail->Subject = 'Order Confirmation';
            $mail->Body = "
                <p>Hello " . htmlspecialchars($user_name) . ",</p>
                <p>Your order (Invoice No: <strong>" . htmlspecialchars($invoice) . "</strong>) has been confirmed and will be delivered shortly.</p>
                <p>Total Amount: <strong>Rs. " . htmlspecialchars($amount) . "</strong></p>
                <p>Thank you for shopping with MediConnect!</p>
            ";

            if (!$mail->send()) {
                error_log('Email sending failed: ' . $mail->ErrorInfo);
            }
        }
    }

    // Redirect to success page
    header("Location: order_success.php?invoice=" . urlencode($oid));
    exit();
} else {
    echo "<h3>Payment verification failed.</h3>";
}
?>
