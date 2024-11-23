<?php
include('assets/conn.php');

function logDebug($message) {
    file_put_contents('debug.log', date('[Y-m-d H:i:s] ') . $message . PHP_EOL, FILE_APPEND);
}

logDebug("Received POST data: " . print_r($_POST, true));

header('Content-Type: application/json');

// Define the required fields
$required_fields = ['hall_id', 'start_date', 'end_date', 'booking_type']; // Removed session_choice and slots temporarily
$missing_fields = [];

// Check for missing required fields
foreach ($required_fields as $field) {
    if (!isset($_POST[$field])) {
        $missing_fields[] = $field;
    }
}

// If any required fields are missing, return an error response
if (count($missing_fields) > 0) {
    echo json_encode(['error' => 'Missing required fields', 'fields' => $missing_fields]);
    exit;
}

// Now we can safely proceed with the rest of the logic

// Get POST data
$hall_id = $_POST['hall_id'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];  // Added end date
$booking_type = $_POST['booking_type'];

$availability = 'available';  // Default availability status

// Define the time slot ranges for FN, AN, and both
$fn_slots = [1, 2, 3, 4];  // Morning session: 9:30, 10:30, 11:30, 12:30
$an_slots = [5, 6, 7, 8];  // Afternoon session: 1:30, 2:30, 3:30, 4:30
$both_slots = array_merge($fn_slots, $an_slots);  // Both sessions: all slots

// Function to check availability based on slots
function checkSlotsAvailability($conn, $hall_id, $start_date, $end_date, $slots_to_check) {
    $query = "SELECT * FROM bookings 
              WHERE hall_id = ? 
              AND (
                  (? BETWEEN start_date AND end_date) OR
                  (? BETWEEN start_date AND end_date) OR
                  (start_date BETWEEN ? AND ?) OR
                  (end_date BETWEEN ? AND ?)
              )
              AND status IN ('approved', 'booked')";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        logDebug("Prepare failed: " . $conn->error);
        return false;
    }

    $stmt->bind_param("sssssss", $hall_id, $start_date, $end_date, $start_date, $end_date, $start_date, $end_date);

    if (!$stmt->execute()) {
        logDebug("Execute failed: " . $stmt->error);
        return false;
    }

    $result = $stmt->get_result();
    $is_booked = false;

    while ($row = $result->fetch_assoc()) {
        $booked_slots = explode(',', $row['slot_or_session']);
        $booked_slots = array_map('intval', $booked_slots);
        
        $overlap = array_intersect($slots_to_check, $booked_slots);
        
        logDebug("Booked slots: " . implode(',', $booked_slots));
        logDebug("Requested slots: " . implode(',', $slots_to_check));
        logDebug("Overlap: " . implode(',', $overlap));
        
        if (!empty($overlap)) {
            $is_booked = true;
            break;
        }
    }

    logDebug("Query: " . $query);
    logDebug("Params: $hall_id, $start_date, $end_date");
    logDebug("Result rows: " . $result->num_rows);
    logDebug("Is booked: " . ($is_booked ? 'Yes' : 'No'));

    return $is_booked;
}

// Now check if the booking type is 'session' or 'slot'
if ($booking_type == 'session') {
    // Ensure session_choice is provided in POST
    if (!isset($_POST['session_choice'])) {
        echo json_encode(['error' => 'Missing session_choice field']);
        exit;
    }
    
    $session_choice = $_POST['session_choice'];  // 'fn', 'an', or 'both'

    // Determine which slots to check based on the session choice
    if ($session_choice == 'fn') {
        $slots_to_check = $fn_slots;
    } elseif ($session_choice == 'an') {
        $slots_to_check = $an_slots;
    } elseif ($session_choice == 'both') {
        $slots_to_check = $both_slots;
    } else {
        echo json_encode(['error' => 'Invalid session_choice value']);
        exit;
    }

    // Check availability for the entire session
    if (checkSlotsAvailability($conn, $hall_id, $start_date, $end_date, $slots_to_check)) {
        $availability = 'booked';  // If any slot is booked, mark the session as booked
    }

} elseif ($booking_type == 'slot') {
    // Ensure slots are provided in POST
    if (!isset($_POST['slots'])) {
        echo json_encode(['error' => 'Missing slots field']);
        exit;
    }

    // If booking type is individual slots
    $slots = $_POST['slots'];  // Array of selected slots
    $slots_to_check = array_map('intval', $slots);  // Ensure slots are integers

    // Check availability for the selected individual slots
    if (!empty($slots_to_check) && checkSlotsAvailability($conn, $hall_id, $start_date, $end_date, $slots_to_check)) {
        $availability = 'booked';  // If any selected slot is booked, mark the availability as booked
    }
}

// Logging for debugging
logDebug("Hall ID: " . $hall_id);
logDebug("Start Date: " . $start_date);
logDebug("End Date: " . $end_date);  // Log end date
logDebug("Booking Type: " . $booking_type);
logDebug("Slots to Check: " . implode(',', $slots_to_check ?? $slots ?? []));
logDebug("Availability Result: " . $availability);

// Return availability status as JSON
echo json_encode(['availability' => $availability]);
?>
