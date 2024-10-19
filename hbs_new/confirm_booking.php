<?php
include('assets/conn.php'); // Include database connection file

// Retrieve form data
$hall_id = $_POST['hall_id'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$slot_or_session = $_POST['slot_or_session'];
$purpose = $_POST['purpose'];
$purpose_name = $_POST['purpose_name'];
$students_count = $_POST['students_count'];
$organiser_name = $_POST['organiser_name'];
$organiser_department = $_POST['organiser_department'];
$organiser_mobile = $_POST['organiser_mobile'];
$organiser_email = $_POST['organiser_email'];
$booking_date = date('Y-m-d'); // Current date for booking_date

// Validate input
if (empty($hall_id)) {
    $missingFields[] = 'hall_id';
}
if (empty($start_date)) {
    $missingFields[] = 'start_date';
}
if (empty($end_date)) {
    $missingFields[] = 'end_date';
}
if (empty($slot_or_session)) {
    $missingFields[] = 'slot_or_session';
}
if (!empty($missingFields)) {
    echo "<script>console.log('Missing fields: " . implode(', ', $missingFields) . "');</script>";
    die("Error: Missing required fields");
}
// Define the slot mappings for FN and AN
$fn_slots = [1, 2, 3, 4];  // Morning session slots
$an_slots = [5, 6, 7, 8];  // Afternoon session slots
$both_slots = [1, 2, 3, 4, 5, 6, 7, 8];  // Afternoon session slots

// Check if the slot_or_session is a session ('fn' or 'an') and map it to the corresponding slots
if ($slot_or_session == 'fn') {
    $slot_or_session = implode(',', $fn_slots);  // Convert to "1,2,3,4"
} elseif ($slot_or_session == 'an') {
    $slot_or_session = implode(',', $an_slots);  // Convert to "5,6,7,8"
} elseif ($slot_or_session == 'both') {
    $slot_or_session = implode(',', $both_slots);  // Convert to "5,6,7,8"
}

// Check if the room is already booked for the selected time slots
$checkQuery = "SELECT * FROM bookings WHERE hall_id = ? AND start_date = ? AND slot_or_session = ? AND status IN ('approved', 'pending')";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("iss", $hall_id, $start_date, $slot_or_session);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows > 0) {
    echo "The room is already booked for the selected time.";
    exit;
}

// Insert booking into the database
$status = 'pending'; // Set booking status

$insertQuery = "INSERT INTO bookings (hall_id, start_date, end_date, purpose, purpose_name, 
                    students_count, organiser_name, organiser_department, 
                    organiser_mobile, organiser_email, slot_or_session, booking_date, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$insertStmt = $conn->prepare($insertQuery);
$insertStmt->bind_param("issssisssssss", $hall_id, $start_date, $end_date, $purpose, 
                        $purpose_name, $students_count, $organiser_name, 
                        $organiser_department, $organiser_mobile, $organiser_email, 
                        $slot_or_session, $booking_date, $status);

if ($insertStmt->execute()) {
    echo "<script>
    alert('Booking confirmed successfully!');
    window.location.href = 'book_hall.php';
</script>";
exit();

} else {
    echo "Error: " . $insertStmt->error;
}

// Close the prepared statements and database connection
$checkStmt->close();
$insertStmt->close();
$conn->close();
?>
