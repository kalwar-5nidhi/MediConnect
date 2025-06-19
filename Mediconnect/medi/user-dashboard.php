<?php
session_start();

// Uncomment this block to require login
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$id = $_SESSION['id'];

// Connect to DB
$conn = new mysqli("localhost", "root", "", "mediconnect");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user info
$stmt = $conn->prepare("SELECT full_name, email, mobile_number FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($full_name, $email, $mobile_number);
$stmt->fetch();
$stmt->close();

// Fetch medicine orders for this user by email
$order_stmt = $conn->prepare("
    SELECT invoice_no, order_date, status, total_amount, payment_method, delivery_address 
    FROM orders 
    WHERE customer_email = ? 
    ORDER BY order_date DESC
");
$order_stmt->bind_param("s", $email);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>User Dashboard - MediConnect</title>
    <style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f9f9f9;
        color: #333;
    }

    header {
        background: #007bff;
        color: white;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    nav {
        background: #ffffff;
        padding: 2rem 1rem;
        width: 220px;
        float: left;
        height: 100vh;
        border-right: 1px solid #e0e0e0;
        position: fixed;
        top: 0;
        left: 0;
    }

    nav a {
        display: block;
        margin: 1rem 0;
        text-decoration: none;
        color: #007bff;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    nav a:hover {
        color: #0056b3;
        padding-left: 5px;
    }

    button.logout {
        background: #dc3545;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        cursor: pointer;
        margin-top: 2rem;
        width: 100%;
        border-radius: 4px;
        font-weight: bold;
    }

    button.logout:hover {
        background: #c82333;
    }

    main {
        margin-left: 240px;
        padding: 2rem;
    }

    section {
        margin-bottom: 3rem;
        background-color: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
    }

    h2 {
        margin-top: 0;
        color: #343a40;
        border-bottom: 2px solid #007bff;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }

    p {
        margin: 0.5rem 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
    }

    th, td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #dee2e6;
        text-align: left;
    }

    th {
        background-color: #f1f1f1;
        font-weight: 600;
        color: #495057;
    }

    tr:hover {
        background-color: #f9f9f9;
    }

    @media screen and (max-width: 768px) {
        nav {
            width: 100%;
            height: auto;
            float: none;
            position: relative;
            border-right: none;
            text-align: center;
        }

        main {
            margin-left: 0;
            padding: 1rem;
        }

        button.logout {
            width: auto;
        }
    }
    </style>
</head>
<body>

<header>
    <h1>Welcome, <?php echo htmlspecialchars($full_name); ?></h1>
</header>

<nav>
    <a href="#profile">Profile</a>
    <a href="#orders">Your Orders</a>
    <a href="logout.php"><button class="logout">Logout</button></a>
</nav>

<main>
    <section id="profile">
        <h2>Your Profile</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($full_name); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Mobile:</strong> <?php echo htmlspecialchars($mobile_number); ?></p>
    </section>

    <section id="orders">
        <h2>Your Medicine Orders</h2>
        <?php if ($order_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Invoice No</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Total Amount</th>
                        <th>Payment Method</th>
                        <th>Delivery Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $order_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['invoice_no']); ?></td>
                            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                            <td>Rs. <?php echo htmlspecialchars($order['total_amount']); ?></td>
                            <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                            <td><?php echo htmlspecialchars($order['delivery_address']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No orders found for your account.</p>
        <?php endif; ?>
    </section>
</main>

</body>
</html>
