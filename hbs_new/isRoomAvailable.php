<?php function isRoomAvailable($conn, $hall_id, $date, $booking_type, $session_choice, $slots) {
    // Check if there are any bookings for the given hall on the given date
    $query = "SELECT * FROM bookings WHERE hall_id = ? AND date = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $hall_id, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // No bookings for this day, room is available
        return true;
    }

    // Check availability based on booking type
    if ($booking_type == 'session') {
        // For session bookings, check if the requested session is available
        $query = "SELECT session FROM bookings WHERE hall_id = ? AND date = ? AND session IN ('fn', 'an', 'both')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $hall_id, $date);
        $stmt->execute();
        $result = $stmt->get_result();

        $booked_sessions = [];
        while ($row = $result->fetch_assoc()) {
            $booked_sessions[] = $row['session'];
        }

        if (in_array('both', $booked_sessions) || 
            ($session_choice == 'both' && (in_array('fn', $booked_sessions) || in_array('an', $booked_sessions))) ||
            ($session_choice != 'both' && in_array($session_choice, $booked_sessions))) {
            return false;
        }
        return true;
    } else {
        // For slot bookings, check if any of the requested slots are available
        $query = "SELECT slot FROM bookings WHERE hall_id = ? AND date = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $hall_id, $date);
        $stmt->execute();
        $result = $stmt->get_result();

        $booked_slots = [];
        while ($row = $result->fetch_assoc()) {
            $booked_slots[] = $row['slot'];
        }

        foreach ($slots as $slot) {
            if (in_array($slot, $booked_slots)) {
                return false;
            }
        }
        return true;
    }
}
?>