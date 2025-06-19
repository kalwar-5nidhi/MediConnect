<?php
if (isset($_POST['id'])) {
    $facilityId = $_POST['id'];
    // Perform database query to fetch details
    $sql = mysqli_query($con, "SELECT * FROM healthcare_facilities WHERE id = '$facilityId'");
    if ($row = mysqli_fetch_array($sql)) {
        // Display the facility details
        echo "<p>Name: " . htmlentities($row['name']) . "</p>";
        echo "<p>Type: " . htmlentities($row['type']) . "</p>";
        echo "<p>Location: " . htmlentities($row['location']) . "</p>";
        echo "<p>Specialty: " . htmlentities($row['specialty']) . "</p>";
        echo "<p>Status: " . htmlentities($row['operational_status']) . "</p>";
        echo "<p>Rating: " . htmlentities($row['rating']) . "</p>";
        echo "<p>Contact: " . htmlentities($row['contact']) . "</p>";
    } else {
        echo "No details found.";
    }
}
?>
