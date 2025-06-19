<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('include/config.php'); // Adjust this path if needed

// Ensure the database connection is successful
if (!$con) {
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Database connection failed: ' . mysqli_connect_error()));
    exit();
}

$query = "SELECT hospital_name, driver_name, ambulance_no AS ambulance_number, phone AS contact FROM ambulances LIMIT 5";
$result = mysqli_query($con, $query);

$ambulances = array();
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $ambulances[] = $row;
        }
        header('Content-Type: application/json');
        echo json_encode($ambulances);
    } else {
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'No ambulance records found.'));
    }
    mysqli_free_result($result);
} else {
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Error executing query: ' . mysqli_error($con)));
}

mysqli_close($con);
?>
