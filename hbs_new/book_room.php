<?php 
include('assets/conn.php');

$features = ['Wifi', 'AC', 'Projector', 'Audio_system', 'Podium', 'Ramp', 'Smart_board', 'Lift'];

// Fetch all schools
$schools_query = "SELECT school_id, school_name FROM schools";
$schools_result = mysqli_query($conn, $schools_query);

// Retrieve values from the URL
$hall_id = $_GET['hall_id'] ?? '';
$school_name = $_GET['school_name'] ?? '';
$department_name = $_GET['department_name'] ?? '';
$room_name = $_GET['room_name'] ?? '';
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';
$booking_type = trim($_GET['booking_type'] ?? '');
$session_choice = trim($_GET['session_choice'] ?? $_POST['session_choice'] ?? ''); 
$selectedSlots = $_GET['slots'] ?? $_POST['slots'] ?? []; // Handle slots as an array

// Convert slots to an array if it's a comma-separated string (GET method)
if (is_string($selectedSlots)) {
    $selectedSlots = explode(',', $selectedSlots);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Booking - Check Availability</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/design.css" />

</head>
<body>
<?php include 'assets/header.php' ?>


    <div class="container mt-5" style="margin-left: 16%;">
        <div class="row justify-content-center">
            <div class="col-md-8">
          
                <div class="card shadow-lg">
                    <div class="card-body"> <center> <h3 style="color:#0e00a3">Hall Booking</h3><br>
                    </center>
                        <form id="checkAvailabilityForm">
                        <div class="mb-4">
    <h4 class="form-section-title">Hall Selection</h4>
    <div class="form-group mb-3">
        <label class="form-label">Hall Type:</label><br>
        <div class="btn-group" role="group" aria-label="Room Type">
            <input type="radio" class="btn-check" name="type_id" id="seminar" value="1" <?php echo (isset($_GET['type_id']) && $_GET['type_id'] == '1') ? 'checked' : ''; ?> disabled>
            <label class="btn btn-outline-primary" for="seminar">Seminar Hall</label>

            <input type="radio" class="btn-check" name="type_id" id="auditorium" value="2" <?php echo (isset($_GET['type_id']) && $_GET['type_id'] == '2') ? 'checked' : ''; ?> disabled>
            <label class="btn btn-outline-primary" for="auditorium">Auditorium</label>

            <input type="radio" class="btn-check" name="type_id" id="lecture" value="3" <?php echo (isset($_GET['type_id']) && $_GET['type_id'] == '3') ? 'checked' : ''; ?>disabled>
            <label class="btn btn-outline-primary" for="lecture">Lecture Hall</label>

            <input type="radio" class="btn-check" name="type_id" id="conference" value="4" <?php echo (isset($_GET['type_id']) && $_GET['type_id'] == '4') ? 'checked' : ''; ?>disabled>
            <label class="btn btn-outline-primary" for="conference">Conference Hall</label>
        </div>
    </div>
    <input type="hidden" name="hall_id" value="<?php echo $hall_id; ?>">

    <div class="form-group">
        <label for="school_name">School</label>
        <input type="text" name="school_name" id="school_name" class="form-control" value="<?php echo $school_name; ?>" readonly>
    </div>

    <div class="form-group">
        <label for="department_name">Department</label>
        <input type="text" name="department_name" id="department_name" class="form-control" value="<?php echo $department_name; ?>" readonly>
    </div>

                            <div class="form-group">
                                <label for="room">Hall Name</label>
                                <input type="text" name="room" id="room" class="form-control" value="<?php echo $room_name; ?>" readonly>
                            </div>
                        </div>
                        <?php
// Debugging: Check retrieved values
$from_date = trim($from_date);
$to_date = trim($to_date);

// Ensure the dates are in the correct format for the input type
if (!empty($from_date)) {
    $from_date_obj = DateTime::createFromFormat('d-m-Y', $from_date);
    if ($from_date_obj) {
        $from_date = $from_date_obj->format('Y-m-d');
    }
}

if (!empty($to_date)) {
    $to_date_obj = DateTime::createFromFormat('d-m-Y', $to_date);
    if ($to_date_obj) {
        $to_date = $to_date_obj->format('Y-m-d');
    } 
}

?>
                        <div class="mb-4">
                            <h4 class="form-section-title">Booking Details</h4>
                            <div class="row">
        <div class="col-md-6 mb-3">
            <label for="start_date" class="form-label">From:</label>
            <input type="date" name="start_date" id="start_date" class="form-control" 
                   value="<?php echo htmlspecialchars($from_date); ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="end_date" class="form-label">To:</label>
            <input type="date" name="end_date" id="end_date" class="form-control" 
                   value="<?php echo htmlspecialchars($to_date); ?>" required>
        </div>
    </div>
 
    <div class="form-group mb-3">
        <label class="form-label">Booking Type:</label><br>
        <div class="btn-group" role="group" aria-label="Booking Type">
            <input type="radio" class="btn-check" name="booking_type" id="session" value="session"
                <?php echo ($booking_type === 'session') ? 'checked' : ''; ?> 
                onchange="showSessionOptions()">
            <label class="btn btn-outline-primary" for="session">Session</label>
            <input type="radio" class="btn-check" name="booking_type" id="slot" value="slot"
                <?php echo ($booking_type === 'slot') ? 'checked' : ''; ?> 
                onchange="showSlotOptions()">
            <label class="btn btn-outline-primary" for="slot">Slot</label>
        </div>
    </div>


<!-- Session Options -->
<div class="form-group mb-3" id="session_options" style="display: <?php echo ($booking_type === 'session') ? 'block' : 'none'; ?>;">
    <label class="form-label">Choose Session:</label><br>
    <div class="btn-group" role="group" aria-label="Session Choice">
        <input type="radio" class="btn-check" name="session_choice" id="fn" value="fn"
            <?php echo ($session_choice === 'fn') ? 'checked' : ''; ?>>
        <label class="btn btn-outline-secondary" for="fn">Forenoon</label>

        <input type="radio" class="btn-check" name="session_choice" id="an" value="an"
            <?php echo ($session_choice === 'an') ? 'checked' : ''; ?>>
        <label class="btn btn-outline-secondary" for="an">Afternoon</label>

        <input type="radio" class="btn-check" name="session_choice" id="both" value="both"
            <?php echo ($session_choice === 'both') ? 'checked' : ''; ?>>
        <label class="btn btn-outline-secondary" for="both">Both</label>
    </div>
</div>

<!-- Slot Options -->
<div class="form-group mb-3" id="slot_options" style="display: <?php echo ($booking_type === 'slot') ? 'block' : 'none'; ?>;">
    <label class="form-label">Choose Slot(s):</label>
    <div class="row">
        <?php
        $selectedSlots = isset($_GET['slots']) ? explode(',', $_GET['slots']) : [];
        $slots = [
            1 => '09:30am',
            2 => '10:30am',
            3 => '11:30am',
            4 => '12:30pm',
            5 => '01:30pm',
            6 => '02:30pm',
            7 => '03:30pm',
            8 => '04:30pm'
        ];
        foreach ($slots as $slotId => $slotLabel) {
            $isChecked = in_array($slotId, $selectedSlots) ? 'checked' : '';
            echo "
            <div class='col-md-3 mb-2'>
                <div class='form-check'>
                    <input class='form-check-input' type='checkbox' name='slots[]' id='slot{$slotId}' value='{$slotId}' {$isChecked}>
                    <label class='form-check-label' for='slot{$slotId}'>{$slotLabel}</label>
                </div>
            </div>";
        }
        ?>
    </div>
</div>
      <input type="hidden" id="slot_or_session" name="slot_or_session" value="">

                            <div class="text-center">
                                <button type="button" class="btn btn-success btn-lg" onclick="checkAvailability()">Check Availability</button>
                            </div>
                        </form>
                        <div class="text-center">
                        <div id="availabilityStatus"></div>
                        </div>
    
    <!-- "Book Now" button initially hidden -->
    <div id="bookNowContainer" style="display: none;">
    <div class="text-center">
                                <button type="button" class="btn btn-info btn-lg"  id="bookNowButton">Book Now</button>
    </div>
    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'assets/footer.php' ?>

    <script>
        // Include all JavaScript functions here (showSessionOptions, showSlotOptions, checkAvailability, etc.)
        function showSessionOptions() {
            document.getElementById('session_options').style.display = 'block';
            document.getElementById('slot_options').style.display = 'none';
        }

        function showSlotOptions() {
            document.getElementById('session_options').style.display = 'none';
            document.getElementById('slot_options').style.display = 'block';
        }

        function checkAvailability() {
    console.log("Button clicked");
    
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


    fetch('room_availability.php', {
        method: 'POST',
       
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);  // Debug response data
        if (data.error) {
            throw new Error(data.error);
        }
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
        console.error('Fetch error:', error); // Log the error in the console for debugging
        const availabilityStatus = document.getElementById('availabilityStatus');
        availabilityStatus.innerHTML = "<p class='text-danger'>There was an issue with the request: " + error.message + "</p>";
    });
}
        function proceedToBooking() {
            const form = document.getElementById('checkAvailabilityForm');
            const formData = new FormData(form);
            const queryString = new URLSearchParams(formData).toString();
            window.location.href = 'complete_booking.php?' + queryString;
        }


document.getElementById('bookNowButton').addEventListener('click', function() {
        // Redirect to complete_booking.php with all form details as a query string
        const hallId = document.querySelector('[name="hall_id"]').value; // Get hall_id from the form
    const startDate = document.getElementById('start_date').value; // Start date input
    const endDate = document.getElementById('end_date').value; // End date input
    const slotOrSession = document.querySelector('[name="slot_or_session"]').value; // Slot or session input

    // Create the query string from the collected data
    const queryString = new URLSearchParams({
        hall_id: hallId,
        start_date: startDate,
        end_date: endDate,
        slot_or_session: slotOrSession
    }).toString();

    // Redirect to complete_booking.php with the query string
    window.location.href = 'complete_booking.php?' + queryString;
});

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const checkAvailabilityForm = document.getElementById('checkAvailabilityForm');
            if (checkAvailabilityForm) {
        checkAvailabilityForm.addEventListener('submit', function(event) {
            event.preventDefault();
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