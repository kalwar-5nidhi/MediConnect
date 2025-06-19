<?php
session_start();
include('include/config.php');

foreach ($_POST as $key => $value) {
    $key = $con->real_escape_string($key);
    $value = $con->real_escape_string($value);

    $sql = "INSERT INTO settings (setting_key, setting_value)
            VALUES ('$key', '$value')
            ON DUPLICATE KEY UPDATE setting_value = '$value'";
    $con->query($sql);
}

header("Location: settings.php?updated=1");
exit();
