<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('include/config.php');

// Redirect if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

$error_message = "";
$success_message = "";

if (isset($_POST['place_order'])) {
    $customer_name = mysqli_real_escape_string($con, $_POST['customer_name'] ?? '');
    $customer_email = mysqli_real_escape_string($con, $_POST['customer_email'] ?? '');
    $customer_phone = mysqli_real_escape_string($con, $_POST['customer_phone'] ?? '');
    $delivery_address = mysqli_real_escape_string($con, $_POST['delivery_address'] ?? '');
    $payment_method_input = mysqli_real_escape_string($con, $_POST['payment_method'] ?? 'cash_on_delivery');
    $payment_method = ($payment_method_input === 'esewa' ? 'eSewa' : 'Cash on Delivery');

    $pharmacy_query = "SELECT id FROM pharmacies LIMIT 1";
    $pharmacy_result = mysqli_query($con, $pharmacy_query);
    if ($pharmacy_result && mysqli_num_rows($pharmacy_result) > 0) {
        $pharmacy_id = mysqli_fetch_assoc($pharmacy_result)['id'];
    } else {
        $error_message = "No pharmacy is available right now.";
    }

    $status = 'Pending';
    $order_date = $created_at = date("Y-m-d H:i:s");
    $invoice_no = 'ORDER_' . time() . '_' . rand(1000, 9999);

    if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || empty($delivery_address)) {
        $error_message = "Please fill in all required fields.";
    } elseif (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif (empty($error_message)) {
        mysqli_begin_transaction($con);
        $order_successful = true;

        $insert_order_query = "INSERT INTO orders (pharmacy_id, order_date, status, total_amount, delivery_address, customer_name, customer_email, customer_phone, payment_method, created_at, invoice_no) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $insert_order_query);
        if (!$stmt) {
            error_log("Prepare failed for order insert: " . mysqli_error($con));
            $error_message = "Database error.";
            mysqli_rollback($con);
        } else {
            mysqli_stmt_bind_param($stmt, "issssssssss", $pharmacy_id, $order_date, $status, $total_price, $delivery_address, $customer_name, $customer_email, $customer_phone, $payment_method, $created_at, $invoice_no);
            $insert_order_result = mysqli_stmt_execute($stmt);

            if ($insert_order_result) {
                $order_id = mysqli_insert_id($con);
                foreach ($_SESSION['cart'] as $item) {
                    $stmt_item = mysqli_prepare($con, "INSERT INTO order_items (order_id, medicine_id, quantity, price_each) VALUES (?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt_item, "iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
                    if (!mysqli_stmt_execute($stmt_item)) {
                        $order_successful = false;
                        $error_message = "Failed to insert order item.";
                        error_log("Order item insert failed for order_id $order_id: " . mysqli_error($con));
                        break;
                    }
                    mysqli_stmt_close($stmt_item);
                }

                mysqli_stmt_close($stmt);

                if ($order_successful) {
                    mysqli_commit($con);
                    unset($_SESSION['cart']);
                    $success_message = "Order placed successfully! Order ID: $order_id";
                    if ($payment_method_input === 'esewa') {
                        header("Location: esewa-payment.php?order_id=$order_id");
                        exit();
                    }
                } else {
                    mysqli_rollback($con);
                }
            } else {
                error_log("Order insert failed: " . mysqli_error($con));
                mysqli_rollback($con);
                $error_message = "Failed to create order.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MediConnect - Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-black">
    <header class="bg-teal-500 p-4">
        <div class="max-w-screen-xl mx-auto flex justify-between items-center">
            <a href="../index.php" class="text-white text-2xl font-bold">MediConnect</a>
            <nav class="space-x-6 text-sm">
                <a href="cart.php" class="text-white hover:underline">Cart</a>
                <a href="login.php" class="text-white hover:underline">Login</a>
                <a href="signup.php" class="text-white hover:underline">Signup</a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold mb-6">Checkout</h2>

        <?php if (!empty($error_message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-4 rounded">
                <?php echo $error_message; ?>
            </div>
        <?php elseif (!empty($success_message)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-4 rounded">
                <?php echo $success_message; ?>
                <a href="epharmacy.php" class="text-teal-600 block mt-2 underline">Continue Shopping</a>
            </div>
        <?php endif; ?>

        <?php if (empty($success_message)): ?>
            <div class="grid md:grid-cols-2 gap-8">
                <form method="post">
                    <h3 class="text-xl font-semibold mb-4">Delivery Info</h3>
                    <input name="customer_name" placeholder="Full Name" class="w-full mb-4 px-3 py-2 border rounded" required>
                    <input type="email" name="customer_email" placeholder="Email" class="w-full mb-4 px-3 py-2 border rounded" required>
                    <input type="tel" name="customer_phone" placeholder="Phone Number" class="w-full mb-4 px-3 py-2 border rounded" required>
                    <textarea name="delivery_address" placeholder="Delivery Address" rows="4" class="w-full mb-4 px-3 py-2 border rounded" required></textarea>
                    
                    <label class="block font-semibold mb-2">Payment Method:</label>
                    <div class="space-y-2 mb-6">
                        <label class="flex items-center">
                            <input type="radio" name="payment_method" value="cash_on_delivery" checked class="mr-2"> Cash on Delivery
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="payment_method" value="esewa" class="mr-2"> Pay with eSewa
                        </label>
                    </div>

                    <button type="submit" name="place_order" class="bg-teal-500 text-white py-2 px-4 rounded">Place Order</button>
                </form>

                <div>
                    <h3 class="text-xl font-semibold mb-4">Order Summary</h3>
                    <ul class="mb-4">
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <li class="flex justify-between py-2 border-b">
                                <span><?php echo htmlspecialchars($item['name']); ?> (<?php echo $item['quantity']; ?> × <?php echo number_format($item['price'], 2); ?>)</span>
                                <span><?php echo number_format($item['quantity'] * $item['price'], 2); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="text-lg font-bold border-t pt-4">
                        Total: <span class="float-right"><?php echo number_format($total_price, 2); ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <footer class="bg-teal-500 text-white text-center py-4">
        © 2025 MediConnect
    </footer>
</body>
</html>
