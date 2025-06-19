<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include('../include/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile'])) {
    $adminId = $_SESSION['id'];

    // Sanitize inputs
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $mobileNumber = trim($_POST['mobile_number']);

    if (empty($fullName) || empty($email) || empty($mobileNumber)) {
        die("Please fill in all required fields.");
    }

    // Optional: You can add email and phone validation here

    $stmt = $con->prepare("UPDATE admin SET full_name = ?, email = ?, mobile_number = ? WHERE id = ?");
    $stmt->bind_param("sssi", $fullName, $email, $mobileNumber, $adminId);

    if ($stmt->execute()) {
        // Update session variables if needed
        $_SESSION['full_name'] = $fullName;
        $_SESSION['mobile_number'] = $mobileNumber;

        header("Location: admin-profile.php?success=1");
        exit;
    } else {
        die("Error updating profile: " . $stmt->error);
    }
} else {
    header("Location: admin-profile.php");
    exit;
}
?>
