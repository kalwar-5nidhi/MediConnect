<?php
// eSewa Payment Integration - Failure Callback
$error_message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : 'Payment was unsuccessful or canceled.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MediConnect - Payment Failed</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-black">
    <header class="bg-teal-500 p-4">
        <div class="max-w-screen-xl mx-auto">
            <a href="../index.php" class="text-white text-2xl font-bold">MediConnect</a>
        </div>
    </header>
    <main class="container mx-auto px-4 py-12 text-center">
        <h2 class="text-2xl font-bold mb-4 text-red-600">Payment Unsuccessful</h2>
        <p class="mb-4"><?php echo $error_message; ?></p>
        <div class="space-x-4">
            <a href="cart.php" class="bg-teal-500 text-white py-2 px-4 rounded">Return to Cart</a>
            <a href="http://localhost/Mediconnect/Mediconnect/#contact_us" class="bg-gray-500 text-white py-2 px-4 rounded">Contact Support</a>
        </div>
    </main>
    <footer class="bg-teal-500 text-white text-center py-4">
        © <?php echo date("Y"); ?> MediConnect
    </footer>
</body>
</html>