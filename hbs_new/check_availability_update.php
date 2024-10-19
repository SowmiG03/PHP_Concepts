<?php
include('assets/conn.php');
function logDebug($message) {
    file_put_contents('debug.log', date('[Y-m-d H:i:s] ') . $message . PHP_EOL, FILE_APPEND);
}

if (isset($_POST['hall_id']) && isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['booking_type'])) {
    $hall_id = $_POST['hall_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];  // Added end date
    $booking_type = $_POST['booking_type'];
    $current_booking_id = $_POST['current_booking_id'];
    logDebug("Current Booking ID: " . $current_booking_id);
 $availability = 'available';  // Default availability status

    // Define the time slot ranges for FN, AN, and both
    $fn_slots = [1, 2, 3, 4];  // Morning session: 9:30, 10:30, 11:30, 12:30
    $an_slots = [5, 6, 7, 8];  // Afternoon session: 1:30, 2:30, 3:30, 4:30
    $both_slots = array_merge($fn_slots, $an_slots);  // Both sessions: all slots

    // Function to check availability based on slots
    function checkSlotsAvailability($conn, $hall_id, $start_date, $end_date, $slots_to_check, $current_booking_id = null) {
        $slots_string = implode(",", $slots_to_check);
        
        // Update query to exclude the current booking
        $query = "SELECT * FROM bookings 
                  WHERE hall_id = ? 
                  AND ((start_date BETWEEN ? AND ?) OR (end_date BETWEEN ? AND ?))
                  AND status IN ('approved', 'booked')";
    
        // If current_booking_id is provided, add condition to exclude it
        if ($current_booking_id) {
            $query .= " AND id != ?";
        }
    
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            logDebug("Prepare failed: " . $conn->error);
            return false;
        }
    
        // Bind parameters
        if ($current_booking_id) {
            $stmt->bind_param("sssssi", $hall_id, $start_date, $end_date, $start_date, $end_date, $current_booking_id);
        } else {
            $stmt->bind_param("sssss", $hall_id, $start_date, $end_date, $start_date, $end_date);
        }
    
        if (!$stmt->execute()) {
            logDebug("Execute failed: " . $stmt->error);
            return false;
        }
    
        $result = $stmt->get_result();
        $is_booked = false;
    
        while ($row = $result->fetch_assoc()) {
            // Split slot_or_session by comma
            $booked_slots = explode(',', $row['slot_or_session']);
            $booked_slots = array_map('intval', $booked_slots);  // Convert to integers
            
            // Check for overlapping slots
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
        logDebug("Params: $hall_id, $start_date, $end_date" . ($current_booking_id ? ", $current_booking_id" : ""));
        logDebug("Result rows: " . $result->num_rows);
        logDebug("Is booked: " . ($is_booked ? 'Yes' : 'No'));
    
        return $is_booked;
    }
    // If booking type is 'session' (FN, AN, or BOTH)
    if ($booking_type == 'session') {
        $session_choice = $_POST['session_choice'];  // 'fn', 'an', or 'both'

        // Determine which slots to check based on the session choice
        if ($session_choice == 'fn') {
            $slots_to_check = $fn_slots;
        } elseif ($session_choice == 'an') {
            $slots_to_check = $an_slots;
        } elseif ($session_choice == 'both') {
            $slots_to_check = $both_slots;
        }

        // Check availability for the entire session
        if (checkSlotsAvailability($conn, $hall_id, $start_date, $end_date, $slots_to_check)) {
            $availability = 'booked';  // If any slot is booked, mark the session as booked
        }

    } elseif ($booking_type == 'slot') {
        // If booking type is individual slots
        $slots = isset($_POST['slots']) ? $_POST['slots'] : [];  // Array of selected slots
        $slots_to_check = array_map('intval', $slots);  // Ensure slots are integers

        // Check availability for the selected individual slots
        if (!empty($slots_to_check) && checkSlotsAvailability($conn, $hall_id, $start_date, $end_date, $slots_to_check)) {
            $availability = 'booked';  // If any selected slot is booked, mark the availability as booked
        }
    }

    logDebug("Hall ID: " . $hall_id);
    logDebug("Start Date: " . $start_date);
    logDebug("End Date: " . $end_date);  // Log end date
    logDebug("Booking Type: " . $booking_type);
    logDebug("Slots to Check: " . implode(',', $slots_to_check ?? $slots ?? []));
    logDebug("Availability Result: " . $availability);

    // Return availability status as JSON
    echo json_encode(['availability' => $availability]);
}
?>
