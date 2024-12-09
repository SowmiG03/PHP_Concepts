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
    background-color: #fffbf3; /* Light background color */
    border: 1px solid #ddd; /* Subtle border */
    border-radius: 8px; /* Rounded corners */
    padding: 0 20px 0px 20px; /* Padding around the content */
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

<div class="main-content">
    <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-body">
                    <h2 class="card-title text-center mb-4">Booking Details</h2>
<div class="booking-details" style="display: flex; justify-content: space-between; align-items: flex-start;">
    <!-- Left Side: Hall Name, Date, and Slot -->
    <div style="flex: 1; display: flex; flex-direction: column; align-items: flex-start;">
        <!-- Hall Name at the Top -->
        <h1 class="hall-name" style="color: #000000; font-size: 1.5rem; margin-bottom: 1rem;">
            <?php echo htmlspecialchars($hall_name); ?>
        </h1>

        <!-- Date and Slot Section Below -->
        <div class="booking-summary" style="display: flex; flex-direction: column; font-weight: bold;">
            <!-- Date Section -->
            <?php 
            // Assuming $start_date and $end_date are in a format that can be parsed by DateTime
            $start_date_obj = new DateTime($start_date);
            $end_date_obj = new DateTime($end_date);

            // Format dates as dd mm yyyy
            $formatted_start_date = $start_date_obj->format('d/m/Y');
            $formatted_end_date = $end_date_obj->format('d/m/Y');
            ?>
            <p class="date-info" style="margin: 0;">
                <?php if ($formatted_start_date === $formatted_end_date): ?>
                    Date: <?php echo htmlspecialchars($formatted_start_date); ?>
                <?php else: ?>
                    From: <?php echo htmlspecialchars($formatted_start_date); ?> to: <?php echo htmlspecialchars($formatted_end_date); ?>
                <?php endif; ?>
            </p>

            <!-- Session/Slot Section -->
            <p class="slot-info" style="margin: 0;">
  
    <?php 
        // Define slot timings (start times)
        $slotTimings = [
            '1' => '9:30 am',
            '2' => '10:30 am',
            '3' => '11:30 am',
            '4' => '12:30 pm',
            '5' => '1:30 pm',
            '6' => '2:30 pm',
            '7' => '3:30 pm',
            '8' => '4:30 pm',
            '9' => '5:30 pm'
        ];

        // Parse the selected slots or session type
        $selectedSlots = explode(',', $slot_or_session);

        // Predefined session ranges
        $predefinedSessions = [
            'fn' => ['start' => '1', 'end' => '4', 'label' => 'Forenoon'],
            'an' => ['start' => '5', 'end' => '8', 'label' => 'Afternoon'],
            'both' => ['start' => '1', 'end' => '8', 'label' => 'Full Day']
        ];

        if (isset($predefinedSessions[$slot_or_session])) {
            // Handle predefined sessions
            $startSlot = $predefinedSessions[$slot_or_session]['start'];
            $endSlot = $predefinedSessions[$slot_or_session]['end'];
            $session = $predefinedSessions[$slot_or_session]['label'];
            $timing = $slotTimings[$startSlot] . " to " . $slotTimings[$endSlot + 1];
            echo "<ul style='list-style-type: none; padding: 0;'>
                    <li><strong>Session: </strong>$session</li>
                    <li><strong>Time: </strong>$timing</li>
                </ul>";
        } elseif (count($selectedSlots) > 1) {
            // Dynamic slot selection for custom ranges
            $validSlots = array_keys($slotTimings);
            $allValid = array_reduce($selectedSlots, function($carry, $slot) use ($validSlots) {
                return $carry && in_array(trim($slot), $validSlots);
            }, true);

            if ($allValid) {
                $startSlot = min($selectedSlots);
                $endSlot = max($selectedSlots);

                // Adjust end time to the next slot
                $timing = $slotTimings[$startSlot] . " to " . $slotTimings[$endSlot + 1];
                echo "<ul style='list-style-type: none; padding: 0;'>
                        <li><strong>Custom Slots:</strong></li>";

                // List each slot one below the other
                foreach ($selectedSlots as $slot) {
                    echo "<li>$slotTimings[$slot]</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Invalid Slot</p>";
            }
        } elseif (count($selectedSlots) === 1 && isset($slotTimings[$selectedSlots[0]])) {
            // Single slot
            $timing = $slotTimings[$selectedSlots[0]] . " to " . $slotTimings[$selectedSlots[0] + 1];
            echo "<ul style='list-style-type: none; padding: 0;'>
                    <li><strong>Slot:</strong></li>
                    <li>$timing</li>
                </ul>";
        } else {
            // Invalid selection
            echo "<p>Invalid Slot</p>";
        }
    ?>
</p>

        </div>
    </div>

    <!-- Vertical Line -->
    <div style="height: 160px; border-left: 2px solid #ccc; margin: 0 30px;"></div>

    <!-- Right Side: School and Department (Center Aligned) -->
    <div style="flex: 1; display: flex; flex-direction: column; align-items: center;">
    <h2 class="department-name" style="color: #6e6e6e; text-align: center; margin-top: 20px; margin-bottom: 10px;">
        <?php echo htmlspecialchars($department_name); ?>
    </h2>
    <h3 class="school-name" style="color: #505050; text-align: center; margin-top: 10px; margin-bottom: 20px;">
        <?php echo htmlspecialchars($school_name); ?>
    </h3>
</div>

</div>


<form id="userDetailsForm" action="confirm_booking.php" method="POST">
    <input type="hidden" name="hall_id" value="<?php echo $hall_id; ?>">
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
    <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
    <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
    <input type="hidden" name="slot_or_session" value="<?php echo htmlspecialchars($slot_or_session); ?>">
    <input type="hidden" name="booking_date" value="<?php echo htmlspecialchars(date('Y-m-d')); ?>">
    <input type="hidden" name="status" value="pending">

    <fieldset class="mt-4">
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
                <button type="submit" id="submitBtn" class="btn btn-success btn-lg">Book Now</button>
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
   
    document.addEventListener("DOMContentLoaded", function() {
        const submitBtn = document.getElementById('submitBtn');
        
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent the form from submitting by default

            let isValid = true;

            // Validate Number of Students
            const studentsCount = document.getElementById('students_count').value;
            if (!studentsCount || studentsCount <= 0) {
                alert('Please enter a valid number of students.');
                isValid = false;
            }

            // Validate Organiser's Name (must not contain numbers or special characters)
            const organiserName = document.getElementById('organiser_name').value;
            const namePattern = /^[A-Za-z\s.]+$/;
            if (!organiserName) {
                alert('Organiser name is required.');
                isValid = false;
            } else if (!namePattern.test(organiserName)) {
                alert('Organiser name should not contain numbers or special characters.');
                isValid = false;
            }

            // Validate Organiser's Department
            const organiserDepartment = document.getElementById('organiser_department').value;
            if (!organiserDepartment) {
                alert('Department is required.');
                isValid = false;
            }

            // Validate Organiser's Mobile Number (10-digit)
            const mobilePattern = /^[0-9]{10}$/;
            const organiserMobile = document.getElementById('organiser_mobile').value;
            if (!organiserMobile || !mobilePattern.test(organiserMobile)) {
                alert('Please enter a valid 10-digit mobile number.');
                isValid = false;
            }

            // Validate Organiser's Email
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            const organiserEmail = document.getElementById('organiser_email').value;
            if (!organiserEmail || !emailPattern.test(organiserEmail)) {
                alert('Please enter a valid email address.');
                isValid = false;
            }

            // If form is valid, show success message, else prevent form submission
            if (isValid) {
                document.getElementById("userDetailsForm").submit();
                
            }
        });

        // Department suggestion logic
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

        // Attach suggestDepartments function to the input field
        document.getElementById('organiser_department').addEventListener('input', function() {
            suggestDepartments(this.value);
        });
    });


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