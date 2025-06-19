<?php
define('DB_SERVER','127.0.0.1'); // or 'localhost'
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','mediconnect');

$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
