<?php
// Include the config.php to connect to the database
include_once('medi/include/config.php');

// Get the query parameter from the URL (GET request)
$query = $_GET['query'] ?? '';

// Sanitize the query to prevent SQL injection (important for user input)
$query = mysqli_real_escape_string($con, $query);

// Query to search for healthcare facilities by name
$sql = "SELECT name, type FROM healthcare_facilities WHERE name LIKE '%$query%' LIMIT 5";

// Execute the query
$result = mysqli_query($con, $sql);

// Initialize an array to hold the results
$facilities = [];

// Fetch the results and store them in the $facilities array
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $facilities[] = $row;
    }
}

// Return the results as a JSON response
echo json_encode($facilities);

// Close the database connection
mysqli_close($con);
?>
