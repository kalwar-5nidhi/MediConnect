<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('include/config.php');

// Initialize $medicines array and filters
$medicines = [];
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$price_filter = isset($_GET['price']) ? $_GET['price'] : '';

// Build the SQL query based on filters
$sql = "SELECT * FROM medicines WHERE 1=1";

if (!empty($search_term)) {
    $search_term = mysqli_real_escape_string($con, $search_term);
    $sql .= " AND (name LIKE '%$search_term%' OR description LIKE '%$search_term%')";
}

if (!empty($category_filter) && $category_filter != 'Category') {
    $category_filter = mysqli_real_escape_string($con, $category_filter);
    $sql .= " AND category = '$category_filter'";
}

if (!empty($price_filter) && $price_filter != 'Price') {
    if ($price_filter == 'Low to High') {
        $sql .= " ORDER BY price ASC";
    } elseif ($price_filter == 'High to Low') {
        $sql .= " ORDER BY price DESC";
    }
}

// Check database connection
if ($con) {
    // Fetch medicines data from the database
    $query = mysqli_query($con, $sql);

    if ($query) {
        // Fetch all rows as an associative array
        $medicines = mysqli_fetch_all($query, MYSQLI_ASSOC);
    } else {
        echo "<div class='container mx-auto px-4 py-12 text-red-500'>Error executing query: " . mysqli_error($con) . "</div>";
    }
} else {
    echo "<div class='container mx-auto px-4 py-12 text-red-500'>Error connecting to the database. Please check your 'include/config.php' file.</div>";
}

// Function to get unique categories from the medicines array
function getUniqueCategories($medicines) {
    $categories = ['Category'];
    foreach ($medicines as $medicine) {
        if (!in_array($medicine['category'], $categories)) {
            $categories[] = $medicine['category'];
        }
    }
    return $categories;
}

$unique_categories = getUniqueCategories($medicines);

// Handle Add to Cart form submission
if (isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // Default quantity is 1

    // Fetch product details from the database to ensure it exists
    $stmt = $con->prepare("SELECT id, name, price FROM medicines WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if ($product) {
        // Initialize the cart in the session if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if the product is already in the cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
            ];
        }
        // Optionally, redirect to the cart page or display a message
        header("Location: cart.php");
        exit();
    } else {
        // Handle case where product ID is invalid
        echo "<script>alert('Product not found.');</script>";
    }
}

// Payment options (Khalti, eSewa)
// $khalti_url = 'https://www.khalti.com/';
$esewa_url = 'https://www.esewa.com.np/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ePharmacy - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom Styling */
        .search-bar {
            transition: all 0.3s ease-in-out;
        }
        .search-bar:focus {
            box-shadow: 0 0 5px rgba(0, 150, 136, 0.5);
            outline: none;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 150, 136, 0.2);
        }
        .product-image {
            object-fit: cover;
            height: 200px;
        }
        .add-to-cart-btn {
            background-color: #009688;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .add-to-cart-btn:hover {
            background-color: #00796b;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-50 text-black font-sans">
    <header class="bg-teal-500 p-4">
        <div class="flex justify-between items-center max-w-screen-xl mx-auto">
            <a href="../index.php" class="text-white text-2xl font-bold">Pharmacy</a>
            <nav class="flex space-x-6 text-sm">
                <a href="cart.php" class="text-white hover:underline">Cart</a>
                <a href="login.php" class="text-white hover:underline">Login</a>
                <a href="register.php" class="text-white hover:underline">Register</a>
            </nav>
        </div>
    </header>

    <div class="container mx-auto px-4 py-12">
        <div class="flex justify-center mb-12">
            <form method="get" class="w-full sm:w-1/2">
                <input type="text" id="search" name="search" placeholder="Search for medicines..." class="search-bar w-full px-4 py-2 border border-gray-300 rounded-lg" value="<?php echo htmlspecialchars($search_term); ?>">
            </form>
        </div>

        <div class="flex justify-between mb-8">
            <form method="get" class="flex space-x-4">
                <select class="px-4 py-2 border border-gray-300 rounded-lg" name="category" onchange="this.form.submit()">
                    <?php foreach ($unique_categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category); ?>" <?php if ($category_filter == $category) echo 'selected'; ?>><?php echo htmlspecialchars($category); ?></option>
                    <?php endforeach; ?>
                </select>
                <select class="px-4 py-2 border border-gray-300 rounded-lg" name="price" onchange="this.form.submit()">
                    <option value="Price">Price</option>
                    <option value="Low to High" <?php if ($price_filter == 'Low to High') echo 'selected'; ?>>Low to High</option>
                    <option value="High to Low" <?php if ($price_filter == 'High to Low') echo 'selected'; ?>>High to Low</option>
                </select>
                <?php if (!empty($search_term) || !empty($category_filter) || !empty($price_filter)): ?>
                    <button type="button" onclick="window.location.href='epharmacy.php'" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Clear Filters</button>
                <?php endif; ?>
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_term); ?>">
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (!empty($medicines)): ?>
                <?php foreach ($medicines as $medicine): ?>
                    <div class="product-card bg-white shadow-lg p-6 rounded-lg hover:shadow-xl transition-shadow">
                        <?php if (isset($medicine['image']) && !empty($medicine['image'])): ?>
                            <img src="<?php echo htmlspecialchars($medicine['image']); ?>" alt="<?php echo htmlspecialchars($medicine['name'] ?? 'Product Image'); ?>" class="product-image w-full rounded-t-lg">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/400" alt="<?php echo htmlspecialchars($medicine['name'] ?? 'Product Image'); ?>" class="product-image w-full rounded-t-lg">
                        <?php endif; ?>
                        <div class="pt-4">
                            <h3 class="card-title"><?php echo htmlspecialchars($medicine['name'] ?? 'Product Name'); ?></h3>
                            <p class="text-gray-600 mt-2"><?php echo htmlspecialchars($medicine['description'] ?? 'Product Description'); ?></p>
                            <p class="text-teal-500 font-semibold mt-2">₹<?php echo htmlspecialchars($medicine['price'] ?? 'Price Not Available'); ?></p>
                            <a href="product-details.php?id=<?php echo htmlspecialchars($medicine['id'] ?? ''); ?>" class="mt-4 inline-block text-teal-500 hover:underline">View Details</a>
                            <form method="post" class="mt-4">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($medicine['id'] ?? ''); ?>">
                                <button type="submit" name="add_to_cart" class="add-to-cart-btn">Add to Cart</button>
                                <input type="hidden" name="quantity" value="1"> 
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-700">No medicines found based on your criteria.</p>
            <?php endif; ?>
        </div>

    </div>

    <footer class="bg-teal-500 text-white py-6 mt-12">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 ePharmacy. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
