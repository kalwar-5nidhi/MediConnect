<?php
session_start(); // Start the session to store user information

require_once __DIR__ . '/../vendor/autoload.php'; 

$fb = new \Facebook\Facebook([
  'app_id' => '649037627996181', 
  'app_secret' => '9347b9f77aca99ca2f3a1f395e120e6c', 
  'default_graph_version' => 'v15.0',
]);

$helper = $fb->getRedirectLoginHelper();

try {
  // Get the access token from the Facebook callback
  $accessToken = $helper->getAccessToken();

  if (!isset($accessToken)) {
    if ($helper->getError()) {
      // The user denied the request
      echo 'Error: ' . $helper->getError() . "\n";
      echo 'Error Code: ' . $helper->getErrorCode() . "\n";
      echo 'Error Reason: ' . $helper->getErrorReason() . "\n";
      echo 'Error Description: ' . $helper->getErrorDescription() . "\n";
    } else {
      echo 'Bad request';
    }
    exit;
  }

  // OAuth 2.0 client handler
  $oAuth2Client = $fb->getOAuth2Client();

  // Use the access token to get the user's information
  $response = $fb->get('/me?fields=id,name,email', $accessToken);
  $user = $response->getGraphUser();

  // Store user data in the session or database
  $_SESSION['fb_access_token'] = (string) $accessToken;
  $_SESSION['fb_user_id'] = $user->getId();
  $_SESSION['fb_user_name'] = $user->getName();
  $_SESSION['fb_user_email'] = $user->getEmail();

  // You can now use the user's info as needed, e.g., register or log them in
  // Redirect the user to the homepage or wherever you want them to go after login
  header('Location: home.php'); // Redirect to the home page or dashboard

} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}
?>
