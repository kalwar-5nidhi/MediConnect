<?php
require __DIR__ . "/config.php";

$email = $_POST["email"] ?? '';

if (empty($email)) {
    die("Email is required.");
}

// Generate token and its hash
$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30); // Token valid for 30 minutes

// Prepare and execute query
$sql = "UPDATE users SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?";
$stmt = mysqli_prepare($con, $sql);

if (!$stmt) {
    die("SQL prepare failed: " . mysqli_error($con));
}

mysqli_stmt_bind_param($stmt, "sss", $token_hash, $expiry, $email);
mysqli_stmt_execute($stmt);

// Check if update was successful
if (mysqli_stmt_affected_rows($stmt) > 0) {

    // Load mailer
    $mail = require __DIR__ . "/mailer.php";
    $mail->setFrom("noreply@example.com", "MediConnect");
    $mail->addAddress($email);
    $mail->Subject = "Reset Your Password";
    $mail->isHTML(true);

    $mail->Body = <<<HTML
    <p>We received a request to reset your password.</p>
    <p>Click the link below to reset your password:</p>
    <p><a href="http://localhost/Mediconnect/Mediconnect/medi/reset-password.php?token=$token">Reset Password</a></p>
    <p>This link will expire in 30 minutes.</p>
HTML;

    try {
        $mail->send();
        echo "Message sent, please check your inbox.";
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }

} else {
    echo "No account found with that email.";
}
?>
