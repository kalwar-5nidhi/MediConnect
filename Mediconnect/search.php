<?php
include_once('include/config.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $type = $_GET['type'] ?? '';
    $location = $_GET['location'] ?? '';
    $specialty = $_GET['specialty'] ?? '';
    $userLat = $_GET['latitude'] ?? 0;
    $userLng = $_GET['longitude'] ?? 0;

    $query = "SELECT id, name, type, specialty, operational_status, contact, rating, latitude, longitude, address,
                     (6371 * acos(cos(radians($userLat)) * cos(radians(latitude)) * cos(radians(longitude) - radians($userLng)) + sin(radians($userLat)) * sin(radians(latitude)))) AS distance
              FROM healthcare_facilities
              WHERE 1=1";

    if ($type) {
        $query .= " AND type = '" . mysqli_real_escape_string($con, $type) . "'";
    }
    if ($specialty) {
        $query .= " AND specialty LIKE '%" . mysqli_real_escape_string($con, $specialty) . "%'";
    }
    if ($location) {
        $query .= " AND (address LIKE '%" . mysqli_real_escape_string($con, $location) . "%' OR name LIKE '%" . mysqli_real_escape_string($con, $location) . "%')";
    }

    $query .= " ORDER BY distance ASC";

    $result = mysqli_query($con, $query);
    $facilities = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $facilities[] = $row;
    }

    echo json_encode($facilities);
} else {
    echo json_encode(['error' => 'Invalid request method']);
}