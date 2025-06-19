<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mediconnect";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['signup_submit'])) {
    // Sanitize form data
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $mobile_number = mysqli_real_escape_string($conn, $_POST['mobile_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $sign_in_as = $_POST['sign_in_as'];

    // Decide table
    $table_name = ($sign_in_as === 'user') ? 'users' : (($sign_in_as === 'admin') ? 'admin' : '');
    if (!$table_name) {
        echo "Invalid sign-in option";
        exit();
    }

    // Check for email
    $email_check_stmt = $conn->prepare("SELECT id FROM $table_name WHERE email = ?");
    $email_check_stmt->bind_param("s", $email);
    $email_check_stmt->execute();
    $email_check_stmt->store_result();

    if ($email_check_stmt->num_rows > 0) {
        echo "The email address is already in use. Please use a different email.";
        $email_check_stmt->close();
        exit();
    }
    $email_check_stmt->close();

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO $table_name (full_name, mobile_number, email, password) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssss", $full_name, $mobile_number, $email, $hashed_password);

        if ($stmt->execute()) {
            $stmt->close();

            session_start();
            $_SESSION['user_role'] = $sign_in_as;

          switch ($sign_in_as) {
              case 'user':
                  header("Location: user-dashboard.php");
                  break;
              case 'admin':
                  header("Location: admin/dashboard.php");
                  break;
          }
            exit();
        } else {
            echo "Error: " . $stmt->error;
            $stmt->close();
        }
    } else {
        echo "Failed to prepare statement: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign Up - MediConnect</title>
  <link rel="stylesheet" href="../assets/css/signup.css">

  <!-- Google Sign-In -->
  <script src="https://accounts.google.com/gsi/client" async defer></script>

  <!-- Facebook SDK -->
  <script async defer crossorigin="anonymous"
    src="https://connect.facebook.net/en_US/sdk.js"></script>

  <script>
    // Facebook SDK Initialization
    window.fbAsyncInit = function () {
      FB.init({
        appId: '1130371318776961',
        cookie: true,
        xfbml: true,
        version: 'v12.0'
      });
      FB.AppEvents.logPageView();
    };

    // Facebook login
    function checkLoginState() {
      FB.getLoginStatus(function (response) {
        if (response.status === 'connected') {
          window.location.href = "http://localhost/FYP/facebook-login.php?token=" + response.authResponse.accessToken;
        }
      });
    }

    // Google login
    function googleLogin() {
      google.accounts.id.prompt();
    }
  </script>
</head>

<body>
  <div class="container">
    <h1>Welcome to MediConnect</h1>
    <h3>Create an account to connect with MediConnect</h3>

    <!-- Manual Sign Up -->
    <form action="signup.php" method="POST">
      <input type="text" name="full_name" placeholder="Full Name" required>
      <input type="tel" name="mobile_number" placeholder="Phone Number" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>

      <div class="Signinas">
        <label for="Signin-as">Sign Up As:</label>
        <select id="Signin-as" name="sign_in_as" required>
          <option value="">Role</option>
          <option value="user">Customer</option>
          <option value="admin">Admin</option>
        </select>
      </div>

      <button type="submit" name="signup_submit">Sign Up</button>
    </form>

    <div class="or"><span>OR</span></div>

<div class="social-login">
  <a href="google-login.php" aria-label="Continue with Google" class="google-login-btn">
    <img src="assets/images/google.png" alt="Sign in with Google" />
  </a>
</div>


                <!-- <a href="facebook-login.php">
                    <img src="Facebook.png" alt="Facebook">
                </a>  -->

    <div class="signup">
      <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
  </div>
</body>
</html>
