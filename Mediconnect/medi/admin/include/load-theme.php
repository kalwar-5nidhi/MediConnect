<?php
if (!isset($con)) {
    include('config.php');
}

$theme = 'light'; // default
$result = $con->query("SELECT setting_value FROM settings WHERE setting_key = 'theme'");
if ($result && $row = $result->fetch_assoc()) {
    $theme = $row['setting_value'];
}
?>
