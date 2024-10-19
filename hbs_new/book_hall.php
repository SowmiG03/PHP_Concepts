<?php 
include('assets/conn.php');

$features = ['Wifi', 'AC', 'Projector', 'Audio_system', 'Podium', 'Ramp', 'Smart_board', 'Lift'];

// Fetch all schools
$schools_query = "SELECT school_id, school_name FROM schools";
$schools_result = mysqli_query($conn, $schools_query);
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
                                    <input type="radio" class="btn-check" name="type_id" id="seminar" value="1" required>
                                    <label class="btn btn-outline-primary" for="seminar">Seminar Hall</label>

                                    <input type="radio" class="btn-check" name="type_id" id="auditorium" value="2">
                                    <label class="btn btn-outline-primary" for="auditorium">Auditorium</label>

                                    <input type="radio" class="btn-check" name="type_id" id="lecture" value="3">
                                    <label class="btn btn-outline-primary" for="lecture">Lecture Hall</label>

                                    <input type="radio" class="btn-check" name="type_id" id="conference" value="4">
                                    <label class="btn btn-outline-primary" for="conference">Conference Hall</label>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="school" class="form-label">School:</label>
                                <select name="school_id" id="school" class="form-select" required>
                                    <option value="">Select School</option>
                                    <?php while($school = mysqli_fetch_assoc($schools_result)): ?>
                                        <option value="<?php echo $school['school_id']; ?>"><?php echo $school['school_name']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="department" class="form-label">Department:</label>
                                <select name="department_id" id="department" class="form-select" required>
                                    <option value="">Select Department</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="room" class="form-label">Room:</label>
                                <select name="hall_id" id="room" class="form-select" required>
                                    <option value="">Select Room</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h4 class="form-section-title">Booking Details</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label">From:</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label">To:</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Booking Type:</label><br>
                                <div class="btn-group" role="group" aria-label="Booking Type">
                                    <input type="radio" class="btn-check" name="booking_type" id="session" value="session" required onclick="showSessionOptions()">
                                    <label class="btn btn-outline-primary" for="session">Session</label>

                                    <input type="radio" class="btn-check" name="booking_type" id="slot" value="slot" required onclick="showSlotOptions()">
                                    <label class="btn btn-outline-primary" for="slot">Slot</label>
                                </div>
                            </div>

                            <div class="form-group mb-3" id="session_options" style="display:none;">
                                <label class="form-label">Choose Session:</label><br>
                                <div class="btn-group" role="group" aria-label="Session Choice">
                                    <input type="radio" class="btn-check" name="session_choice" id="fn" value="fn">
                                    <label class="btn btn-outline-secondary" for="fn">Forenoon</label>

                                    <input type="radio" class="btn-check" name="session_choice" id="an" value="an">
                                    <label class="btn btn-outline-secondary" for="an">Afternoon</label>

                                    <input type="radio" class="btn-check" name="session_choice" id="both" value="both">
                                    <label class="btn btn-outline-secondary" for="both">Both</label>
                                </div>
                            </div>

                            <div class="form-group mb-3" id="slot_options" style="display:none;">
                                <label class="form-label">Choose Slot(s):</label><br>
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="slots[]" id="slot1" value="1">
                                            <label class="form-check-label" for="slot1">09:30am</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="slots[]" id="slot2" value="2">
                                            <label class="form-check-label" for="slot2">10:30am</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="slots[]" id="slot3" value="3">
                                            <label class="form-check-label" for="slot3">11:30am</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="slots[]" id="slot4" value="4">
                                            <label class="form-check-label" for="slot4">12:30pm</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="slots[]" id="slot5" value="5">
                                            <label class="form-check-label" for="slot5">01:30pm</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="slots[]" id="slot6" value="6">
                                            <label class="form-check-label" for="slot6">02:30pm</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="slots[]" id="slot7" value="7">
                                            <label class="form-check-label" for="slot7">03:30pm</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="slots[]" id="slot8" value="8">
                                            <label class="form-check-label" for="slot8">04:30pm</label>
                                        </div>
                                    </div>
                                </div>
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

    fetch('check_availability.php', {
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

        function proceedToBooking() {
            const form = document.getElementById('checkAvailabilityForm');
            const formData = new FormData(form);
            const queryString = new URLSearchParams(formData).toString();
            window.location.href = 'complete_booking.php?' + queryString;
        }

// Load departments based on selected school and hall type
$('#school').change(function() {
    var school_id = $(this).val();
    var type_id = $('input[name="type_id"]:checked').val();
    
    // Clear the department and room selections
    $('#department').html('<option value="">Select Department</option>');
    $('#room').html('<option value="">Select Room</option>');

    if (school_id && type_id) {
        $.ajax({
            type: 'POST',
            url: 'fetch_departments.php',
            data: {school_id: school_id, type_id: type_id},
            success: function(response) {
                console.log(response);  // Log the response for debugging
                $('#department').html(response);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching departments:", error);
            }
        });
    }
});

// Load rooms based on selected department and hall type
$('#department').change(function() {
    var department_id = $(this).val();
    var type_id = $('input[name="type_id"]:checked').val();

    // Clear the room selection
    $('#room').html('<option value="">Select Room</option>');

    if (department_id && type_id) {
        $.ajax({
            type: 'POST',
            url: 'fetch_rooms.php',
            data: {department_id: department_id, type_id: type_id},
            success: function(response) {
                console.log("Department ID:", department_id);
                console.log("Hall Type:", type_id);
                $('#room').html(response);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching rooms:", error);
            }
        });
    }
});

$('input[name="hall_type"]').change(function() {
    // Clear all dropdowns
    $('#school').val('').change(); // Reset school dropdown
    $('#department').html('<option value="">Select Department</option>'); // Reset department dropdown
    $('#room').html('<option value="">Select Room</option>'); // Reset room dropdown
});
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