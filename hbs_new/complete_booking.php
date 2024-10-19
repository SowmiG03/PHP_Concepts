<?php
include('assets/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $hall_id = isset($_GET['hall_id']) ? intval($_GET['hall_id']) : 0; // Ensure hall_id is an integer
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
    $slot_or_session = isset($_GET['slot_or_session']) ? $_GET['slot_or_session'] : '';

    // Fetch hall details from the database
    $hall_query = "SELECT h.hall_name, d.department_name, s.school_name 
                   FROM hall_details h 
                   JOIN departments d ON h.department_id = d.department_id 
                   JOIN schools s ON d.school_id = s.school_id 
                   WHERE h.hall_id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($hall_query);

    // Check if prepare was successful
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error)); // Display the error
    }

    // Bind parameters
    $stmt->bind_param("i", $hall_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $hall = $result->fetch_assoc();

    if ($hall) {
        $hall_name = $hall['hall_name']; // Extract hall name from $hall array
        $department_name = $hall['department_name']; // Extract department name from $hall array
        $school_name = $hall['school_name']; // Extract school name from $hall array
    
        // Construct the hall details string
        $hallDetails = $hall_name . ' - ' . $department_name . ' (' . $school_name . ')';
    } else {
        $hallDetails = "Hall not found.";
    }
    
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
    <style>
       
.booking-details {
    background-color: #f9f9f9; /* Light background color */
    border: 1px solid #ddd; /* Subtle border */
    border-radius: 8px; /* Rounded corners */
    padding: 20px; /* Padding around the content */
    margin: 20px; /* Margin around the booking details */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
}

.school-name {
    font-size: 15px; /* Larger font for school name */
    font-weight: bold; /* Bold text */
    text-align: center; /* Center align */
    margin-bottom: 10px; /* Space below */
}

.department-name{
    font-size: 18px; /* Medium font size */
    text-align: center; /* Center align */
    margin-bottom: 5px; /* Space below */
}
.hall-name {
    font-size: 18px; /* Medium font size */
    text-align: center; /* Center align */
    margin-bottom: 1px; /* Space below */
}


.date-info,
.slot-info {
    font-size: 16px; /* Standard font size */
    margin-bottom: 5px; /* Space below */
}

    </style>
</head>
<body>
<?php include 'assets/header.php' ?>

    <div class="container mt-5">
    <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-body">
                    <h2 class="card-title text-center mb-4">Booking Details</h2>
                    <div class="booking-details">
                    <h1 class="hall-name"><?php echo htmlspecialchars($hall_name); ?></h1>    
                    <h2 class="department-name"><?php echo htmlspecialchars($department_name); ?></h2>
                    <h3 class="school-name"><?php echo htmlspecialchars($school_name); ?></h3>
                    <?php 
// Assuming $start_date and $end_date are in a format that can be parsed by DateTime
$start_date_obj = new DateTime($start_date);
$end_date_obj = new DateTime($end_date);

// Format dates as dd mm yyyy
$formatted_start_date = $start_date_obj->format('d/m/Y');
$formatted_end_date = $end_date_obj->format('d/m/Y');

if ($formatted_start_date === $formatted_end_date): ?>
    <p class="date-info">Date: <?php echo htmlspecialchars($formatted_start_date); ?></p>
<?php else: ?>
    <p class="date-info">From: <?php echo htmlspecialchars($formatted_start_date); ?> to: <?php echo htmlspecialchars($formatted_end_date); ?></p>
<?php endif; ?>


    <p class="slot-info">
    <p>Session/Slot: 
    <?php 
        // Define slot timings
        $slotTimings = [
            '1' => '9:30 to 10:30',
            '2' => '10:30 to 11:30',
            '3' => '11:30 to 12:30',
            '4' => '12:30 to 1:30',
            '5' => '1:30 to 2:30',
            '6' => '2:30 to 3:30',
            '7' => '3:30 to 4:30',
            '8' => '4:30 to 5:30'
        ];

        // Get the timing based on the slot
        $timing = isset($slotTimings[$slot_or_session]) ? htmlspecialchars($slotTimings[$slot_or_session]) : 'Invalid Slot';

        // Determine the session type based on $slot_or_session
        if ($slot_or_session === 'fn') {
            $session = 'Forenoon';
            $timing = '9:30 to 12:30'; // Combine all Forenoon slots
        } elseif ($slot_or_session === 'an') {
            $session = 'Afternoon';
            $timing = '1:30 to 5:30'; // Combine all Afternoon slots
        } elseif ($slot_or_session === 'both') {
            $session = 'Both FN & AN';
            $timing = '9:30 to 5:30'; // Combine both time slots
        } else {
            $session = 'Invalid Session';
            $timing = 'Invalid Slot';
        }

        // Output the combined timing and session
        echo "$timing ($session)";
    ?>
</p>

    </p>
</div>

<h2>User Details</h2>
<form id="userDetailsForm" action="confirm_booking.php" method="POST">
    <input type="hidden" name="hall_id" value="<?php echo $hall_id; ?>">
    <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
    <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
    <input type="hidden" name="slot_or_session" value="<?php echo htmlspecialchars($slot_or_session); ?>">
    <input type="hidden" name="booking_date" value="<?php echo htmlspecialchars(date('Y-m-d')); ?>">
    <input type="hidden" name="status" value="pending">

    <fieldset class="mt-4">
        <legend class="form-section-title">Booking Details</legend>
        <form id="bookForm" method="POST" action="confirm_booking.php">
             
            <div class="mb-3">
                <label class="form-label">Purpose of Booking</label>
                <div class="btn-group w-100" role="group" aria-label="Purpose of Booking">
                    <input type="radio" class="btn-check" id="purpose_event" name="purpose" value="event" required>
                    <label class="btn btn-outline-primary" for="purpose_event">Event</label>
                    <input type="radio" class="btn-check" id="purpose_class" name="purpose" value="class" required>
                    <label class="btn btn-outline-primary" for="purpose_class">Class</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="purpose_name" class="form-label">Name of the Programme/Event</label>
                <textarea class="form-control" id="purpose_name" name="purpose_name" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="students_count" class="form-label">Number of Students Expected</label>
                <input type="number" class="form-control" id="students_count" name="students_count" required>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="organiser_name" class="form-label">Organiser's Name</label>
                    <input type="text" class="form-control" id="organiser_name" name="organiser_name" required>
                </div>
                <div class="col-md-6">
                    <label for="organiser_department" class="form-label">Organiser's Department</label>
                    <input type="text" class="form-control" id="organiser_department" name="organiser_department" required oninput="suggestDepartments(this.value)">
                    <div id="suggestions" class="suggestions-box"></div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="organiser_mobile" class="form-label">Organiser's Contact Number</label>
                    <input type="text" class="form-control" id="organiser_mobile" name="organiser_mobile" required>
                </div>
                <div class="col-md-6">
                    <label for="organiser_email" class="form-label">Organiser's Email ID</label>
                    <input type="email" class="form-control" id="organiser_email" name="organiser_email" required>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success btn-lg">Book Now</button>
            </div>
        </form>
    </fieldset>
</div>
</form>
</div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'assets/footer.php' ?>

</body>
</html>
<script>
    
function suggestDepartments(query) {
    if (query.length === 0) {
        document.getElementById("suggestions").style.display = "none";
        return;
    }

    fetch('get_departments.php?query=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            const suggestionsDiv = document.getElementById("suggestions");
            suggestionsDiv.innerHTML = '';

            if (data.length > 0) {
                const fragment = document.createDocumentFragment();
                data.forEach(department => {
                    const suggestion = document.createElement('div');
                    suggestion.textContent = department;
                    suggestion.className = 'suggestion-item';
                    suggestion.onclick = function() {
                        document.getElementById('organiser_department').value = department;
                        suggestionsDiv.style.display = 'none';
                    };
                    fragment.appendChild(suggestion);
                });
                suggestionsDiv.appendChild(fragment);
                suggestionsDiv.style.display = 'block';
            } else {
                suggestionsDiv.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error fetching department suggestions:', error);
        });
}

</script>