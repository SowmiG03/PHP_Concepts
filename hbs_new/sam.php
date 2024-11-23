<?php
include('assets/conn.php');

// Fetch parameters from GET request
$school_id = $_GET['school_id'] ?? null;
$department_id = $_GET['department_id'] ?? null;
$type_id = $_GET['type_id'] ?? null;
$from_date = $_GET['from_date'] ?? null;
$to_date = $_GET['to_date'] ?? null;
$booking_type = $_GET['booking_type'] ?? null;
$session_choice = $_GET['session_choice'] ?? null;
$slots = isset($_GET['slots']) ? $_GET['slots'] : [];
$capacity = isset($_GET['capacity']) ? $_GET['capacity'] : '';
$features = isset($_GET['features']) ? $_GET['features'] : [];

$query = "SELECT DISTINCT 
            h.hall_id, 
            h.hall_name, 
            h.capacity,
            CONCAT_WS(', ',
                IF(h.wifi != 'No', 'WIFI', NULL),
                IF(h.ac != 'No', 'AC', NULL),
                IF(h.projector != 'No', 'Projector', NULL),
                IF(h.computer != 'No', 'Computer', NULL),
                IF(h.audio_system != 'No', 'Audio System', NULL),
                IF(h.podium != 'No', 'Podium', NULL),
                IF(h.ramp != 'No', 'Ramp', NULL),
                IF(h.smart_board != 'No', 'Smart Board', NULL),
                IF(h.lift != 'No', 'Lift', NULL),
                IF(h.white_board != 'No', 'White Board', NULL),
                IF(h.blackboard != 'No', 'Blackboard', NULL)
            ) AS features,
            s.school_name, 
            d.department_name, 
            rt.type_name,
            h.image
          FROM hall_details h
          LEFT JOIN schools s ON h.school_id = s.school_id
          LEFT JOIN departments d ON h.department_id = d.department_id
          LEFT JOIN hall_type rt ON h.type_id = rt.type_id
          WHERE 1=1";

// Apply filters for school, department, and type
if ($school_id) {
    $query .= " AND h.school_id = " . intval($school_id);
}
if ($department_id) {
    $query .= " AND h.department_id = " . intval($department_id);
}
if ($type_id) {
    $query .= " AND h.type_id = " . intval($type_id);
}

// Apply capacity filter
if ($capacity) {
    if ($capacity == '200') {
        $query .= " AND h.capacity >= 200";
    } else {
        $query .= " AND h.capacity <= " . intval($capacity);
    }
}

// Apply feature filters
if (!empty($features)) {
    foreach ($features as $feature) {
        $feature = mysqli_real_escape_string($conn, $feature);
        $query .= " AND h.$feature != 'No'";
    }
}

// Filter by availability based on the selected booking dates and slots/sessions
if ($from_date && $to_date) {
    $selected_slots = [];
    
    if ($booking_type === 'session') {
        if ($session_choice === 'fn') {
            $selected_slots = [1, 2, 3, 4]; // Morning slots
        } elseif ($session_choice === 'an') {
            $selected_slots = [5, 6, 7, 8]; // Afternoon slots
        } elseif ($session_choice === 'both') {
            $selected_slots = [1, 2, 3, 4, 5, 6, 7, 8]; // Both sessions
        }
    } elseif ($booking_type === 'slot' && !empty($slots)) {
        $selected_slots = $slots;
    }

    if (!empty($selected_slots)) {
        $slot_conditions = [];
        foreach ($selected_slots as $slot) {
            $slot_conditions[] = "FIND_IN_SET($slot, b.slot_or_session)";
        }
        $slot_condition = implode(" OR ", $slot_conditions);
    
        $query .= " AND h.hall_id NOT IN (
            SELECT DISTINCT b.hall_id 
            FROM bookings b
            WHERE (
                (b.booking_date BETWEEN '$from_date' AND '$to_date') 
                OR (b.start_date <= '$to_date' AND b.end_date >= '$from_date')
            )
            AND ($slot_condition)
        )";
    }
}

// Execute the query
$result = mysqli_query($conn, $query);

// Check if query execution was successful
if (!$result) {
    die('Query failed: ' . mysqli_error($conn));
}

// Check if rooms are available
if (mysqli_num_rows($result) > 0):
    while ($room = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-4 room-card">
            <div class="card mx-auto" style="width: 80%;">
                <img src="<?php echo $room['image']; ?>" class="card-img-top" alt="Room Image" style="height: 150px; object-fit: cover; margin:10px;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $room['hall_name']; ?></h5>
                    <p class="card-text">
                        <strong>School:</strong> <?php echo $room['school_name']; ?><br>
                        <strong>Department:</strong> <?php echo $room['department_name']; ?><br>
                        <strong>Type:</strong> <?php echo $room['type_name']; ?><br>
                        <strong>Capacity:</strong> <?php echo $room['capacity']; ?><br>
                        <strong>Features:</strong> <?php echo $room['features']; ?>
                    </p>
                    <a href="book_room.php?hall_id=<?php echo $room['hall_id']; ?>&from_date=<?php echo $from_date; ?>&to_date=<?php echo $to_date; ?>&booking_type=<?php echo $booking_type; ?>&session_choice=<?php echo $session_choice; ?>&slots=<?php echo implode(',', $slots); ?>" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </div>
    <?php endwhile;
else: ?>
    <div class="col-12">
        <p class="text-center text-muted">No rooms available for the selected criteria.</p>
    </div>
<?php endif;

mysqli_close($conn);
?>