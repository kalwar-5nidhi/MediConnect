<?php
include("include/config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $facilityId = mysqli_real_escape_string($con, $_POST['id']); // Assuming you changed the form name to 'id'
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $rating = intval($_POST['rating']); // Ensure it's an integer
    $comment = mysqli_real_escape_string($con, $_POST['comment']);
    $submissionDate = date("Y-m-d H:i:s");

    // Basic validation
    if ($rating >= 1 && $rating <= 5 && !empty($name) && !empty($comment) && !empty($facilityId)) {
        // Insert the review into the 'reviews' table
        // Make sure your 'reviews' table has a 'facility_id' column
        $insertQuery = mysqli_query($con, "INSERT INTO reviews (facility_id, name, rating, comment, submission_date) VALUES ('$facilityId', '$name', '$rating', '$comment', '$submissionDate')");

        if ($insertQuery) {
            // Option 1: Update the average rating in healthcare_facilities immediately
            // Assuming your healthcare_facilities table has a 'rating' column
            $updateAvgQuery = mysqli_query($con, "UPDATE healthcare_facilities SET rating = (SELECT AVG(rating) FROM reviews WHERE facility_id = '$facilityId') WHERE id = '$facilityId'");

            if ($updateAvgQuery) {
                echo "<script>alert('Thank you for your review!'); window.location.href = '../index.php';</script>";
                exit();
            } else {
                echo "<script>alert('Thank you for your review! However, there was an issue updating the facility rating.'); window.location.href = 'index.php';</script>";
                // Consider logging the error: error_log("Error updating average rating: " . mysqli_error($con));
                exit();
            }

            // Option 2: Just show a thank you message without immediate rating update
            // echo "<script>alert('Thank you for your review!'); window.location.href = 'index.php';</script>";
            // exit();

        } else {
            echo "<script>alert('Error submitting your review. Please try again later.'); window.location.href = '../index.php';</script>";
            // Consider logging the error: error_log("Error inserting review: " . mysqli_error($con));
            exit();
        }
    } else {
        echo "<script>alert('Please fill in all fields and provide a valid rating.'); window.location.href = '../index.php';</script>";
        exit();
    }
}
?>