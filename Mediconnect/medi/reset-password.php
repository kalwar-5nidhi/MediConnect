<?php
session_start();
include("include/config.php");

// Code for updating Password
if (isset($_POST['change'])) {
    // Get user session data (name and email)
    $full_name = $_SESSION['full_name'];
    $email = $_SESSION['email'];

    // Validate if both password fields match
    if ($_POST['password'] !== $_POST['password_again']) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Get new password from form and hash it
        $newpassword = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

        // Update the password in the database
        $query = mysqli_query($con, "UPDATE users SET password='$newpassword' WHERE full_Name='$full_name' AND email='$email'");

        // Check if the password was updated successfully
        if ($query) {
            echo "<script>alert('Password successfully updated.');</script>";
            echo "<script>window.location.href ='login.php'</script>"; // Redirect to login page
        } else {
            echo "<script>alert('Error updating password.');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .reset-container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .reset-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            position: relative;
            margin-bottom: 20px;
        }
        .form-control {
            padding-right: 40px;
        }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
        }
        .form-actions {
            text-align: right;
        }
        .form-actions button {
            width: 100%;
        }
        .text-center {
            text-align: center;
        }
        .text-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>MediConnect</h2>
        <form name="passwordreset" method="post" onsubmit="return valid();">
            <p class="text-center">Please set your new password.</p>

            <div class="form-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="New Password" required>
                <i class="fa fa-eye toggle-password" toggle="#password"></i>
            </div>

            <div class="form-group">
                <input type="password" class="form-control" id="password_again" name="password_again" placeholder="Confirm Password" required>
                <i class="fa fa-eye toggle-password" toggle="#password_again"></i>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary" name="change">Change Password</button>
            </div>

            <div class="text-center mt-3">
                Already have an account? <a href="login.php">Login</a>
            </div>
        </form>
        <p class="text-center mt-4">&copy; <span class="text-bold">MediConnect</span></p>
    </div>

    <script>
        function valid() {
            if (document.passwordreset.password.value !== document.passwordreset.password_again.value) {
                alert("Password and Confirm Password do not match!");
                document.passwordreset.password_again.focus();
                return false;
            }
            return true;
        }

        document.querySelectorAll(".toggle-password").forEach(function (element) {
            element.addEventListener("click", function () {
                const input = document.querySelector(this.getAttribute("toggle"));
                if (input.getAttribute("type") === "password") {
                    input.setAttribute("type", "text");
                    this.classList.remove("fa-eye");
                    this.classList.add("fa-eye-slash");
                } else {
                    input.setAttribute("type", "password");
                    this.classList.remove("fa-eye-slash");
                    this.classList.add("fa-eye");
                }
            });
        });
    </script>
</body>
</html>
