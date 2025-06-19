<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php'; 

use Facebook\Facebook;

$fb = new Facebook([
    'app_id' => '649037627996181', 
    'app_secret' => '9347b9f77aca99ca2f3a1f395e120e6c', 
    'default_graph_version' => 'v12.0',
]);

$helper = $fb->getRedirectLoginHelper();
$accessToken = '';

try {
    if (isset($_GET['token'])) {
        $accessToken = $_GET['token'];
    } else {
        $accessToken = $helper->getAccessToken();
    }
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (!isset($accessToken)) {
    echo 'Access token could not be obtained.';
    exit;
}

try {
    $response = $fb->get('/me?fields=id,name,email', $accessToken);
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

$userData = $response->getGraphUser();

$facebook_id = $userData['id'];
$full_name = $userData['name'];
$email = $userData['email'];

// Connect to database
$conn = new mysqli('localhost', 'root', '', 'mediconnect');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if user already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE social_id = ? AND auth_provider = 'facebook'");
$stmt->bind_param("s", $facebook_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // User exists, log them in
    $_SESSION['user_role'] = 'user';
    $_SESSION['user_email'] = $email;
    header("Location: index.php");
    exit();
} else {
    // Insert new user
    $insert = $conn->prepare("INSERT INTO users (full_name, email, auth_provider, social_id) VALUES (?, ?, 'facebook', ?)");
    $insert->bind_param("sss", $full_name, $email, $facebook_id);
    if ($insert->execute()) {
        $_SESSION['user_role'] = 'user';
        $_SESSION['user_email'] = $email;
        header("Location: index.php");
        exit();
    } else {
        echo "Failed to register via Facebook.";
    }
}

$stmt->close();
$conn->close();
?>
