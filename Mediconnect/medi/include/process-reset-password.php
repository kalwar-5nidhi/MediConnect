<?php
require 'config.php'; // Your DB connection file
$token = $_GET['token'] ?? null;

if (!$token) {
    die("Invalid or missing token.");
}

$token_hash = hash("sha256", $token);

// Check if token exists and is not expired
$stmt = $con->prepare("SELECT * FROM users WHERE reset_token = ? AND token_expire > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Token is invalid or expired.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Enter New Password</h2>
    <form action="update-password.php" method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <label>New Password:</label><br>
        <input type="password" name="password" required><br>
        <label>Confirm Password:</label><br>
        <input type="password" name="password_confirmation" required><br><br>
        <input type="submit" value="Update Password">
    </form>
</body>
</html>
