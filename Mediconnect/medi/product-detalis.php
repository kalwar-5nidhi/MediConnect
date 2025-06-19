<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('include/config.php');

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='container mx-auto px-4 py-12 text-red-500'>Invalid request. No medicine selected.</div>";
    exit();
}

$medicine_id = intval($_GET['id']);

// Fetch medicine details
$stmt = $con->prepare("SELECT * FROM medicines WHERE id = ?");
$stmt->bind_param("i", $medicine_id);
$stmt->execute();
$result = $stmt->get_result();
$medicine = $result->fetch_assoc();
$stmt->close();

if (!$medicine) {
    echo "<div class='container mx-auto px-4 py-12 text-red-500'>Medicine not found.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($medicine['name']); ?> - Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-black font-sans">

<header class="bg-teal-500 p-4">
    <div class="flex justify-between items-center max-w-screen-xl mx-auto">
        <a href="epharmacy.php" class="text-white text-2xl font-bold">Pharmacy</a>
        <nav class="flex space-x-6 text-sm">
            <a href="cart.php" class="text-white hover:underline">Cart</a>
        </nav>
    </div>
</header>

<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/2">
                <?php if (!empty($medicine['image'])): ?>
                    <img src="<?php echo htmlspecialchars($medicine['image']); ?>" alt="<?php echo htmlspecialchars($medicine['name']); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <img src="https://via.placeholder.com/400" alt="No Image" class="w-full h-full object-cover">
                <?php endif; ?>
            </div>
            <div class="md:w-1/2 p-8">
                <h2 class="text-2xl font-bold mb-4"><?php echo htmlspecialchars($medicine['name']); ?></h2>
                <p class="text-gray-700 mb-4"><?php echo htmlspecialchars($medicine['description']); ?></p>
                <p class="text-teal-500 font-semibold text-lg mb-4">₹<?php echo htmlspecialchars($medicine['price']); ?></p>
                <p class="text-gray-500 mb-2"><strong>Category:</strong> <?php echo htmlspecialchars($medicine['category']); ?></p>

                <form method="post" action="epharmacy.php">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($medicine['id']); ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" name="add_to_cart" class="add-to-cart-btn mt-4 px-6 py-2 bg-teal-500 text-white rounded hover:bg-teal-700">
                        Add to Cart
                    </button>
                </form>
                <a href="epharmacy.php" class="block mt-6 text-teal-500 hover:underline">← Back to Shop</a>
            </div>
        </div>
    </div>
</div>

<footer class="bg-teal-500 text-white py-6 mt-12">
    <div class="container mx-auto text-center">
        <p>&copy; 2025 ePharmacy. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>