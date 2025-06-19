<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle updating cart quantities
if (isset($_POST['update_cart'])) {
    if (!empty($_SESSION['cart'])) {
        foreach ($_POST['quantity'] as $id => $qty) {
            $quantity = intval($qty);
            if ($quantity > 0) {
                $_SESSION['cart'][$id]['quantity'] = $quantity;
            } elseif ($quantity == 0) {
                unset($_SESSION['cart'][$id]); // Remove item if quantity is 0
            }
        }
    }
    header("Location: cart.php");
    exit();
}

// Handle removing items from the cart
if (isset($_GET['remove']) && isset($_GET['id'])) {
    $remove_id = $_GET['id'];
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
    }
    header("Location: cart.php");
    exit();
}

$total_price = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ePharmacy - Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom Styling */
    </style>
</head>
<body class="bg-gray-50 text-black font-sans">
    <header class="bg-teal-500 p-4">
        <div class="flex justify-between items-center max-w-screen-xl mx-auto">
            <a href="../index.php" class="text-white text-2xl font-bold">ePharmacy</a>
            <nav class="flex space-x-6 text-sm">
                <!-- <a href="categories.php" class="text-white hover:underline">Categories</a> -->
                <a href="cart.php" class="text-white hover:underline">Cart</a>
                <a href="login.php" class="text-white hover:underline">Login</a>
                <a href="signup.php" class="text-white hover:underline">Signup</a>
            </nav>
        </div>
    </header>

    <div class="container mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold mb-8">Shopping Cart</h2>

        <?php if (empty($_SESSION['cart'])): ?>
            <p class="text-gray-700">Your cart is empty.</p>
            <a href="epharmacy.php" class="inline-block mt-4 text-teal-500 hover:underline">Continue Shopping</a>
        <?php else: ?>
            <form method="post">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 p-2">Product Name</th>
                            <th class="border border-gray-300 p-2">Price</th>
                            <th class="border border-gray-300 p-2">Quantity</th>
                            <th class="border border-gray-300 p-2">Subtotal</th>
                            <th class="border border-gray-300 p-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                            <tr>
                                <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($item['name']); ?></td>
                                <td class="border border-gray-300 p-2">₹<?php echo htmlspecialchars($item['price']); ?></td>
                                <td class="border border-gray-300 p-2">
                                    <input type="number" name="quantity[<?php echo $id; ?>]" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="0" class="w-20 text-center border border-gray-300 rounded">
                                </td>
                                <td class="border border-gray-300 p-2">₹<?php echo htmlspecialchars($item['price'] * $item['quantity']); ?></td>
                                <td class="border border-gray-300 p-2 text-center">
                                    <a href="cart.php?remove=true&id=<?php echo $id; ?>" class="text-red-500 hover:underline">Remove</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100">
                            <td class="border border-gray-300 p-2 font-bold" colspan="3">Total</td>
                            <td class="border border-gray-300 p-2 font-bold">₹<?php echo htmlspecialchars($total_price); ?></td>
                            <td class="border border-gray-300 p-2"></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="p-4 text-right">
                                <button type="submit" name="update_cart" class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600">Update Cart</button>
                                <a href="checkout.php" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 ml-4">Checkout</a>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        <?php endif;