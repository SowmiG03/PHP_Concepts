<?php
// cancel_booking.php
include('assets/conn.php'); // Include your database connection

// Get the booking ID and cancellation reason from POST
$booking_id = $_POST['booking_id'];
$cancellation_reason = $_POST['cancellation_reason'];

// Update the booking status to 'Cancelled'
$sql = "UPDATE bookings SET status = 'cancelled' WHERE booking_id = $booking_id";
$conn->query($sql);

// Store the cancellation reason in the `bookings` table
$sql = "INSERT INTO bookings (booking_id, cancellation_reason) VALUES ($booking_id, '$cancellation_reason')";
$conn->query($sql);

// Redirect back to the booking list or show a success message
header("Location: view_modify_booking.php?message=cancelled");
?>
