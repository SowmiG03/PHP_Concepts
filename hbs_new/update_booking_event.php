<?php
include('assets/conn.php');

// Get the posted form data
$booking_id = $_POST['booking_id'];
$hall_id = $_POST['hall_id'];
// $start_date = $_POST['start_date'];
// $end_date = $_POST['end_date'];
$purpose = $_POST['purpose'];
$purpose_name = $_POST['purpose_name'];
$students_count = $_POST['students_count'];
$organiser_name = $_POST['organiser_name'];
$organiser_department = $_POST['organiser_department'];
$organiser_mobile = $_POST['organiser_mobile'];
$organiser_email = $_POST['organiser_email'];
$booking_date = $_POST['booking_date'];
$status = $_POST['status']; // or another status depending on your logic


// Update the booking in the database
$sql = "UPDATE bookings SET 
            hall_id = '$hall_id',
            purpose = '$purpose',
            purpose_name = '$purpose_name',
            students_count = '$students_count',
            organiser_name = '$organiser_name',
            organiser_department = '$organiser_department',
            organiser_mobile = '$organiser_mobile',
            organiser_email = '$organiser_email',
            booking_date = '$booking_date',
            status = '$status'
        WHERE booking_id = '$booking_id'";

if ($conn->query($sql) === TRUE) {
    // Redirect to the view_modify_bookings.php page
    header("Location: view_modify_booking.php");
    exit(); // Make sure to call exit after header redirection
} else {
    // Handle errors
    echo "Error updating booking: " . $conn->error;
}
$conn->close();
?>
