<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
include('include/config.php');

// Use $con to match checkout.php
if (!isset($con) || !$con) {
    $error = mysqli_connect_error() ?: 'Connection not initialized';
    error_log("Database connection failed: $error");
    die("Database connection failed. Please try again later.");
}

// Function to generate HMAC-SHA256 signature
function generateSignature($data, $secret) {
    $message = 'total_amount='. $data['total_amount'] . ',transaction_uuid=' . $data['transaction_uuid'] . ',product_code=' . $data['product_code'];
    return base64_encode(hash_hmac('sha256', $message, $secret, true));
}

// Handle initial redirect from checkout (initiate eSewa payment)
if (isset($_GET['order_id'])) {
    // Sanitize order_id
    if (!is_numeric($_GET['order_id'])) {
        error_log("Invalid order_id: {$_GET['order_id']}");
        header('Location: esewa_failure.php?error=Invalid%20order%20ID');
        exit();

    }
    $order_id = mysqli_real_escape_string($con, $_GET['order_id']);
    
    // Fetch order details
    $sql = "SELECT invoice_no, total_amount FROM orders WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        $error = mysqli_error($con);
        error_log("Prepare failed for order_id $order_id: $error");
        die("Database error: $error");
    }
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Execute failed for order_id $order_id: " . mysqli_error($con));
        die("Database error: Query execution failed");
    }
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) == 1) {
        $order = mysqli_fetch_assoc($result);
        $invoice_no = $order['invoice_no'] ?: 'ORDER_' . $order_id; // Fallback
        $amount = $order['total_amount'];

        // eSewa payment initiation URL (ePay v2 testing)
       $esewa_url = "https://rc-epay.esewa.com.np/api/epay/main/v2/form";
        // Fallback URL (uncomment for production testing, use with caution)
        //  $esewa_url = "https://epay.esewa.com.np/api/epay/main/v2/form";

        // Use ngrok for local testing (replace with your ngrok URL)
        $success_url = "http://localhost/Mediconnect/Mediconnect/medi/esewa_success.php";
        $failure_url = "http://localhost/Mediconnect/Mediconnect/medi/esewa_failure.php";
        // Example with ngrok: $success_url = "https://abc123.ngrok.io/Mediconnect/Mediconnect/medi/esewa_success.php";

        // eSewa form data (ePay v2)
        $data = [
            'amount' => $amount,
            'tax_amount' => 0,
            'total_amount' => $amount,
            'transaction_uuid' => $invoice_no, // Ensure uniqueness
            'product_code' => 'EPAYTEST',
            'product_service_charge' => 0,
            'product_delivery_charge' => 0,
            'success_url' => $success_url,
            'failure_url' => $failure_url,
            'signed_field_names' => 'total_amount,transaction_uuid,product_code'
        ];

        // Generate signature
        $secret_key = '8gBm/:&EnhH.1/q'; // Test secret key
        $data['signature'] = generateSignature($data, $secret_key);

        // echo("<script>console.log('Data: " . $data . "');</script>");

        // Generate and auto-submit eSewa payment form
        error_log("Redirecting to eSewa with data: " . print_r($data, true));
        echo '<!DOCTYPE html><html><head><title>Redirecting to eSewa</title></head><body>';
        echo '<form id="esewa_form" action="' . $esewa_url . '" method="POST">';
        foreach ($data as $key => $value) {
            echo '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars($value) . '">';
        }
        echo '</form>';
        echo '<script>document.getElementById("esewa_form").submit();</script>';
        echo '</body></html>';
        exit();
    } else {
        error_log("Order not found for ID: $order_id");
        header('Location: esewa_failure.php?error=Order%20not%20found');
        exit();
    }
}

// Invalid request
error_log("Invalid request to esewa-payment.php: " . print_r($_REQUEST, true));
die('Invalid request parameters.');
?>