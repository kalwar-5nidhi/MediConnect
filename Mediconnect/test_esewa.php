<?php
$url = "https://rc-epay.esewa.com.np/api/epay/main/v2/form";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
echo "HTTP Code: $http_code<br>Error: $error";
curl_close($ch);
?>