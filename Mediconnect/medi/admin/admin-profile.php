<?php
include('../include/config.php'); // Make sure the DB connection is included

// Example: Replace with the actual admin ID if session is not used
$adminId = 1; // or retrieve from a safe GET/POST value if appropriate

// Fetch admin details
$stmt = $con->prepare("SELECT full_name, email, mobile_number FROM admin WHERE id = ?");
$stmt->bind_param("i", $adminId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Admin not found.";
    exit;
}

$admin = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Profile - MediConnect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container my-5">
    <h2>Admin Profile</h2>
    <form method="POST" action="save_profile.php" onsubmit="return validateForm()">
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" required
                   value="<?= htmlspecialchars($admin['full_name']) ?>">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" required
                   value="<?= htmlspecialchars($admin['email']) ?>">
        </div>

        <div class="mb-3">
            <label for="mobile_number" class="form-label">Mobile Number</label>
            <input type="text" class="form-control" id="mobile_number" name="mobile_number" required
                   value="<?= htmlspecialchars($admin['mobile_number']) ?>">
        </div>

        <button type="submit" class="btn btn-primary" name="save_profile">Save Changes</button>
    </form>
</div>

<script>
function validateForm() {
    let fullName = document.getElementById('full_name').value.trim();
    let email = document.getElementById('email').value.trim();
    let mobile = document.getElementById('mobile_number').value.trim();

    if (!fullName || !email || !mobile) {
        alert('Please fill in all required fields.');
        return false;
    }
    return true;
}
</script>
</body>
</html>
