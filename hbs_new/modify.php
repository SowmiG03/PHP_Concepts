<?php 

include('assets/conn.php');


if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    // Prepare the SQL statement with a JOIN to fetch hall name from hall_details table
    $stmt = $conn->prepare("
        SELECT b.*, v.hall_name 
        FROM bookings AS b 
        JOIN hall_details AS v ON b.hall_id = v.hall_id 
        WHERE b.booking_id = ?
    ");
    $stmt->bind_param("i", $booking_id);
    
    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the booking details
    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();
        
        // Add the logic to determine session_choice
        if (in_array($booking['slot_or_session'], [1, 2, 3, 4])) {
            $booking['session_choice'] = 'fn'; // Forenoon
        } elseif (in_array($booking['slot_or_session'], [5, 6, 7, 8])) {
            $booking['session_choice'] = 'an'; // Afternoon
        } elseif (in_array($booking['slot_or_session'], range(1, 8))) {
            $booking['session_choice'] = 'both'; // Both sessions
        }
    } else {
        echo "No booking found for the provided ID.";
        $booking = null; // Set booking to null to avoid undefined variable warning
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Booking ID is not set.";
    $booking = null; // Set booking to null to avoid undefined variable warning
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/design.css" />
    <title>Edit Booking</title>

    
</head>
<body>
<?php include 'assets/header.php' ?>


<div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
           
                <div class="card shadow-lg">
                <div class="card-body"> <center><h3 style="color:#170098">Update Booking</h3>
                <center> 
                <form id="checkAvailabilityForm" action="modify_update_hall.php" method="POST">
                <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking['booking_id']); ?>">
        <input type="hidden"  id="room" name="hall_id" value="<?php echo htmlspecialchars($booking['hall_id']); ?>">
        <input type="hidden" name="booking_date" value="<?php echo date('Y-m-d'); ?>">
        <input type="hidden" name="current_booking_id" value="<?php echo isset($booking['booking_id']) ? $booking['booking_id'] : ''; ?>">

                            <div class="form-group mb-3">
                                <label for="room_name" class="form-label">Room Name:</label>
                                <input type="text"  class="form-control"  name="room_name" value="<?php echo htmlspecialchars($booking['hall_name']); ?>" disabled>
                            </div> 

                        <div class="mb-4">
                            <div class="row">
                            <div class="col-md-6 mb-3">
    <label for="start_date" class="form-label">Start Date:</label>
    <input type="date" id="start_date" name="start_date" class="form-control" 
    value="<?php echo htmlspecialchars($booking['start_date']); ?>" required>
</div>

<div class="col-md-6 mb-3">
    <label for="end_date" class="form-label">End Date:</label>
    <input type="date" id="end_date" name="end_date" class="form-control" 
    value="<?php echo htmlspecialchars($booking['end_date']); ?>" required>
</div>

                            </div>



                            <div class="form-group mb-3">
                                <label class="form-label">Booking Type:</label><br>
                                <div class="btn-group" role="group" aria-label="Booking Type">
                                    <input type="radio" class="btn-check" name="booking_type" id="session" value="session" required onclick="showSessionOptions()"<?php echo ($booking['slot_or_session'] == 'session') ? 'checked' : ''; ?>>
                                    <label class="btn btn-outline-primary" for="session">Session</label>


                                    <input type="radio" class="btn-check" name="booking_type" id="slot" value="slot" required onclick="showSlotOptions()" <?php echo ($booking['slot_or_session'] == 'slot') ? 'checked' : ''; ?>>
                                    <label class="btn btn-outline-primary" for="slot">Slot</label>
                                </div>
                            </div>

                            <div class="form-group mb-3" id="session_options" style="display:none;">
                                <label class="form-label">Choose Session:</label><br>
                                <div class="btn-group" role="group" aria-label="Session Choice">
                                    <input type="radio" class="btn-check" name="session_choice" value="fn" id="fn" <?php echo ($booking['slot_or_session'] == '1,2,3,4') ? 'checked' : ''; ?>>
                                    <label class="btn btn-outline-secondary" for="fn">Forenoon</label>

                                    <input type="radio" class="btn-check" name="session_choice" value="an" id="an" <?php echo ($booking['slot_or_session'] == '5,6,7,8') ? 'checked' : ''; ?>>
                                    <label class="btn btn-outline-secondary" for="an">Afternoon</label>

                                    <input type="radio" class="btn-check" name="session_choice" value="both" id="both" <?php echo ($booking['slot_or_session'] == '1,2,3,4,5,6,7,8') ? 'checked' : ''; ?>>
                                    <label class="btn btn-outline-secondary" for="both">Both</label>
                                </div>
                            </div>

                            <div class="form-group mb-3" id="slot_options" style="display:none;">
                                <label class="form-label">Choose Slot(s):</label><br>
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="slots[]" id="slot1" value="1" <?php echo (in_array(1, explode(',', $booking['slot_or_session']))) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="slot1">09:30am</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="slots[]" id="slot2" value="2" <?php echo (in_array(2, explode(',', $booking['slot_or_session']))) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="slot2">10:30am</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="slots[]" id="slot3" value="3" <?php echo (in_array(3, explode(',', $booking['slot_or_session']))) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="slot3">11:30am</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="slots[]" id="slot4" value="4" <?php echo (in_array(4, explode(',', $booking['slot_or_session']))) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="slot4">12:30pm</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="slots[]" id="slot5" value="5" <?php echo (in_array(5, explode(',', $booking['slot_or_session']))) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="slot5">01:30pm</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="slots[]" id="slot6" value="6" <?php echo (in_array(6, explode(',', $booking['slot_or_session']))) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="slot6">02:30pm</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="slots[]" id="slot7" value="7" <?php echo (in_array(7, explode(',', $booking['slot_or_session']))) ? 'checked' : ''; ?>> 
                                            <label class="form-check-label" for="slot7">03:30pm</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="slots[]" id="slot8" value="8" <?php echo (in_array(8, explode(',', $booking['slot_or_session']))) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="slot8">04:30pm</label>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                            

                            <input type="hidden" id="slot_or_session" name="slot_or_session" value="">

<div class="text-center">
    <button type="button" class="btn btn-success btn-lg" onclick="checkAvailability()">Check Availability</button>
</div>
<div class="text-center">
<div id="availabilityStatus"></div>
</div>
<!-- "Book Now" button initially hidden -->
<div id="bookNowContainer" style="display: none;">
<div class="text-center">
    <button type="submit" class="btn btn-info btn-lg"  id="bookNowButton">Book Now</button>
</div></form>
</div>
</div>
</div>
</div>
</div>
</div>
<?php include 'assets/footer.php' ?>
                   
<script>
    
   
// Show session options or slot options based on user selection
function showSessionOptions() {
    document.getElementById('session_options').style.display = 'block';
    document.getElementById('slot_options').style.display = 'none';
}

function showSlotOptions() {
    document.getElementById('session_options').style.display = 'none';
    document.getElementById('slot_options').style.display = 'block';
}

window.onload = function() {
        var bookingType = "<?php echo $booking['slot_or_session']; ?>";

        // Convert bookingType to an array of slots
        var slots = bookingType.split(',').map(function(slot) {
            return parseInt(slot.trim(), 10);
        });

        // Determine booking type and select corresponding options
        if (slots.length === 4 && slots.every(slot => [1, 2, 3, 4].includes(slot))) {
            // If exactly 4 valid slots (1-4), select forenoon session
            document.querySelector('input[name="booking_type"][value="session"]').checked = true;
            showSessionOptions();
            document.querySelector('input[name="session_choice"][value="fn"]').checked = true; // Select Forenoon
        } else if (slots.length === 4 && slots.every(slot => [5, 6, 7, 8].includes(slot))) {
            // If exactly 4 valid slots (5-8), select afternoon session
            document.querySelector('input[name="booking_type"][value="session"]').checked = true;
            showSessionOptions();
            document.querySelector('input[name="session_choice"][value="an"]').checked = true; // Select Afternoon
        } else if (slots.length === 8 && slots.every(slot => slot >= 1 && slot <= 8)) {
            // If exactly 8 valid slots (1-8), select both sessions
            document.querySelector('input[name="booking_type"][value="session"]').checked = true;
            showSessionOptions();
            document.querySelector('input[name="session_choice"][value="both"]').checked = true; // Select Both
        } else {
            // If any other valid slots, select slot options
            document.querySelector('input[name="booking_type"][value="slot"]').checked = true;
            showSlotOptions();
            slots.forEach(function(slot) {
                // Check selected slots
                if (slot >= 1 && slot <= 8) { // Ensure slot is valid
                    document.querySelector(`input[name="slots[]"][value="${slot}"]`).checked = true; // Check selected slots
                }
            });
        }
    };

    function checkAvailability() {
    
    // Check if all required fields are filled
    const hallId = document.getElementById('room').value;
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const bookingType = document.querySelector('input[name="booking_type"]:checked');

    if (!hallId || !startDate || !endDate || !bookingType) {
        alert("Please fill in all required fields before checking availability.");
        return;
    }

    const bookingTypeValue = bookingType.value;
    
    if (bookingTypeValue === 'session') {
        const selectedSession = document.querySelector('input[name="session_choice"]:checked');
        if (!selectedSession) {
            alert("Please select a session before checking availability.");
            return;
        }
    } else if (bookingTypeValue === 'slot') {
        const selectedSlots = document.querySelectorAll('input[name="slots[]"]:checked');
        if (selectedSlots.length === 0) {
            alert("Please select at least one time slot before checking availability.");
            return;
        }
    }

    const formData = new FormData(document.getElementById('checkAvailabilityForm'));
    
    // Determine slot_or_session value
    let slotOrSession = '';
    if (bookingTypeValue === 'session') {
        slotOrSession = document.querySelector('input[name="session_choice"]:checked').value;
    } else {
        slotOrSession = Array.from(document.querySelectorAll('input[name="slots[]"]:checked'))
                             .map(input => input.value)
                             .join(',');
    }
    
    // Set the slot_or_session hidden input
    document.getElementById('slot_or_session').value = slotOrSession;

    fetch('check_availability_update.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const availabilityStatus = document.getElementById('availabilityStatus');
        if (data.availability === 'available') {
            availabilityStatus.innerHTML = "<p class='text-success'>The room is available for booking!</p>";
            document.getElementById('bookNowContainer').style.display = 'block';  // Show button
        } else {
            availabilityStatus.innerHTML = "<p class='text-danger'>The room is already booked for your selected time.</p>";
            document.getElementById('bookNowContainer').style.display = 'none';  // Hide button
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('availabilityStatus').innerHTML = "<p class='text-danger'>An error occurred while checking availability. Please try again.</p>";
    });
}

      

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const checkAvailabilityForm = document.getElementById('checkAvailabilityForm');
            if (checkAvailabilityForm) {
        checkAvailabilityForm.addEventListener('submit', function(event) {
            checkAvailability();
        });
    }
    document.addEventListener('click', function(event) {
        const suggestionsDiv = document.getElementById("suggestions");
        if (suggestionsDiv && !suggestionsDiv.contains(event.target) && event.target.id !== 'organiser_department') {
            suggestionsDiv.style.display = 'none';
        }
    });

});
document.addEventListener("DOMContentLoaded", function() {
    const startDateInput = document.getElementById("start_date");
    const endDateInput = document.getElementById("end_date");

    // Disable past dates
    const today = new Date().toISOString().split('T')[0];
    startDateInput.setAttribute('min', today);
    endDateInput.setAttribute('min', today);

    startDateInput.addEventListener("input", function() {
        // Get the selected start date
        const startDate = new Date(startDateInput.value);
        // Set the end date to the start date if it's empty or less than start date
        if (!endDateInput.value || new Date(endDateInput.value) < startDate) {
            endDateInput.value = startDateInput.value;
        }
        // Update the min attribute of end date to ensure it can't be before the start date
        endDateInput.setAttribute('min', startDateInput.value);
    });

    endDateInput.addEventListener("input", function() {
        // If the selected end date is before the start date, reset it
        if (new Date(endDateInput.value) < new Date(startDateInput.value)) {
            endDateInput.value = startDateInput.value;
        }
    });
});

    </script>
</body>
</html>