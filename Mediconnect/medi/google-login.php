<?php
// Autoload Google API client
require_once __DIR__ . '/../vendor/autoload.php';

session_start();

// Google OAuth credentials
define('CLIENT_ID', '879873327686-u6obr4sedocsq4jv8a2fshi4uimfnh2h.apps.googleusercontent.com');
define('CLIENT_SECRET', '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');
define('REDIRECT_URI', 'http://localhost/Mediconnect/Mediconnect/medi/google-callback.php');

// Create Google Client
$client = new Google_Client();
$client->setClientId(CLIENT_ID);
$client->setClientSecret(CLIENT_SECRET);
$client->setRedirectUri(REDIRECT_URI);
$client->addScope("email");
$client->addScope("profile");

// Get Google OAuth URL
$authUrl = $client->createAuthUrl();

// Redirect user to Google's OAuth 2.0 consent screen
header('Location: ' . $authUrl);
exit();
?>
