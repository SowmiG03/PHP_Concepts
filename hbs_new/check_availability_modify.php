<?php
require 'assest/conn.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hallId = $_POST['hall_id'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    // Query to check availability with status condition
    $query = "SELECT COUNT(*) as count 
              FROM bookings 
              WHERE hall_id = ? 
              AND status IN ('approved', 'booked') -- Only consider approved/booked slots
              AND ((start_date <= ? AND end_date >= ?) OR 
                   (start_date <= ? AND end_date >= ?))";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $hallId, $endDate, $startDate, $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Check if any approved/booked bookings exist in the specified date range
    if ($row['count'] > 0) {
        echo json_encode(['available' => false]);
    } else {
        echo json_encode(['available' => true]);
    }

    $stmt->close();
    $conn->close();
}
?>
