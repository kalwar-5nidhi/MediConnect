<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
session_start();

$client = new Google_Client();
$client->setClientId('879873327686-u6obr4sedocsq4jv8a2fshi4uimfnh2h.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/Mediconnect/Mediconnect/medi/google-callback.php');
$client->addScope('email');
$client->addScope('profile');

echo "Start<br>";

if (isset($_GET['code'])) {
    echo "Code received<br>";

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    echo "Token fetched<br>";

    if (isset($token['error'])) {
        die('Token error: ' . $token['error']);
    }

    $client->setAccessToken($token);
    echo "Access token set<br>";

    $oauth = new Google_Service_Oauth2($client);
    $userinfo = $oauth->userinfo->get();
    echo "User info fetched<br>";

    $email = $userinfo->email;
    $full_name = $userinfo->name;
    $social_id = $userinfo->id;

    $conn = new mysqli("localhost", "root", "", "mediconnect");
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    $stmt = $conn->prepare("SELECT id, full_name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name);
        $stmt->fetch();
        $_SESSION['loggedin'] = true;
        $_SESSION['id'] = $id;
        $_SESSION['full_name'] = $name;
        $_SESSION['role'] = 'user';
        echo "User logged in<br>";
        header("Location: ../index.php");
        exit();
    } else {
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, social_id, auth_provider) VALUES (?, ?, ?, 'google')");
        $stmt->bind_param("sss", $full_name, $email, $social_id);
        if ($stmt->execute()) {
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $stmt->insert_id;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['role'] = 'user';
            echo "User signed up and logged in<br>";
            header("Location: user-dashboard.php");
            exit();
        } else {
            echo "Signup failed: " . $stmt->error;
        }
    }
} else {
    echo "No code in URL";
}
