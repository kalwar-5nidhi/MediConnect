<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// DB Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mediconnect";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $mobile = trim($_POST["mobile_number"]);
    $password_input = $_POST["password"];

    $roles = [
        "admin" => "admin",
        "user" => "users"
    ];

    $user_found = false;

    foreach ($roles as $role => $table) {
        $stmt = $conn->prepare("SELECT id, full_name, mobile_number, password FROM $table WHERE mobile_number = ?");
        if (!$stmt) {
            $error_message = "Database error.";
            break;
        }

        $stmt->bind_param("s", $mobile);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();

            if (password_verify($password_input, $row["password"])) {
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $row["id"];
                $_SESSION["full_name"] = $row["full_name"];
                $_SESSION["mobile_number"] = $row["mobile_number"];
                $_SESSION["role"] = $role;

                $user_found = true;

                // Redirect based on role
                if ($role === "admin") {
                    header("Location: admin/dashboard.php");
                } else {
                    header("Location: user-dashboard.php");
                }
                exit;
            } else {
                $error_message = "Invalid password. Please try again.";
                break;
            }
        }

        $stmt->close();
    }

    if (!$user_found && $error_message === "") {
        $error_message = "User not found. Please check your mobile number or sign up.";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - MediConnect</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/login.css" />
    <style>
        /* Additional inline styles for Google button */
        .google-login-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            background: #fff;
            cursor: pointer;
            text-decoration: none;
            color: #444;
            font-weight: 600;
            margin-top: 1rem;
            width: 100%;
            box-sizing: border-box;
            transition: background-color 0.3s ease;
        }
        .google-login-btn:hover {
            background-color: #f7f7f7;
        }
        .google-login-btn img {
            height: 20px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to MediConnect</h1>
        <h3>Login to your account</h3>

        <?php if (!empty($error_message)) : ?>
            <p style="color:red;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php" onsubmit="return validateForm()">
            <div class="Num">
                <label for="mobile-number">Mobile Number</label>
                <input
                    type="text"
                    id="mobile-number"
                    name="mobile_number"
                    placeholder="Enter mobile number"
                    required
                />
            </div>
            <div class="Pass">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter password"
                    required
                />
            </div>
            <div class="submit">
                <button type="submit" name="login">Login</button>
            </div>
            <div class="forgot">
                <a href="include/forget_password.php">Forgot Password?</a>
            </div>
            <div class="or">
                <span>OR</span>
            </div>
        </form>

      <div class="social-login">
        <a href="google-login.php" aria-label="Continue with Google" class="google-login-btn">
            <img src="assets/images/google.png" alt="Sign in with Google" />
        </a>
    </div>



        <div class="signup" style="margin-top: 1rem;">
            <p>New? <a href="signup.php">Sign Up!</a></p>
        </div>
    </div>

    <script>
        function validateForm() {
            var mobileNumber = document.getElementById("mobile-number").value;
            var password = document.getElementById("password").value;

            if (mobileNumber.trim() === "" || password.trim() === "") {
                alert("Please fill in all fields.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
