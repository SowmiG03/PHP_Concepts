<?php 
include('assets/conn.php');

$features = ['Wifi', 'AC', 'Projector', 'Audio_system', 'Podium', 'Ramp', 'Smart_board', 'Lift'];

// Fetch all schools
$schools_query = "SELECT school_id, school_name FROM schools";
$schools_result = mysqli_query($conn, $schools_query);

// Fetch all departments
$departments_query = "SELECT department_id, department_name FROM departments";
$departments_result = mysqli_query($conn, $departments_query);

// Fetch all room types
$room_types_query = "SELECT type_id, type_name FROM hall_type";
$room_types_result = mysqli_query($conn, $room_types_query);

// Initialize filter variables
$school_id = isset($_GET['school_id']) ? $_GET['school_id'] : '';
$department_id = isset($_GET['department_id']) ? $_GET['department_id'] : '';
$type_id = isset($_GET['type_id']) ? $_GET['type_id'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$booking_type = isset($_GET['booking_type']) ? $_GET['booking_type'] : 'session';
$session_choice = isset($_GET['session_choice']) ? $_GET['session_choice'] : '';
$slots = isset($_GET['slots']) ? $_GET['slots'] : [];

// Function to check if a room is available
function isRoomAvailable($conn, $hall_id, $date, $booking_type, $session_choice, $slots) {
    // Implement your availability check logic here
    // This is a placeholder function
    return true;
}

// Fetch available rooms based on filters
$rooms_query = "SELECT h.hall_id, h.hall_name, s.school_name, d.department_name, rt.type_name 
                FROM hall_details h
                JOIN schools s ON h.school_id = s.school_id
                JOIN departments d ON h.department_id = d.department_id
                JOIN hall_type rt ON h.type_id = rt.type_id
                WHERE 1=1";

if ($school_id) {
    $rooms_query .= " AND h.school_id = $school_id";
}
if ($department_id) {
    $rooms_query .= " AND h.department_id = $department_id";
}
if ($type_id) {
    $rooms_query .= " AND h.type_id = $type_id";
}

$rooms_result = mysqli_query($conn, $rooms_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Rooms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/design.css" />
    <style>
        .filter-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .room-card {
            margin-bottom: 20px;
        }
        .main-content {
  margin-left: 270px;
  margin-right: 20px;
  padding-top: 60px;
  overflow: hidden;
}
.card-body{
    padding:5px;
}

    </style>
</head>
<body>
<?php include 'assets/header.php' ?>

<div class="main-content">
            <div class="col-md-12">
          
                <!-- <div class="card shadow-lg"> -->
    <div class="filter-section">
        <form id="filterForm" method="GET">
        <div class="row">
    <div class="col-md-5  d-flex align-items-center">
        <label for="school" class="form-label mb-0 me-2">School:</label>
        <select name="school_id" id="school" class="form-select">
    <option value="">Select a School</option>
    <?php while ($school = mysqli_fetch_assoc($schools_result)): ?>
        <option value="<?php echo $school['school_id']; ?>" 
            <?php echo (isset($_POST['school_id']) && $_POST['school_id'] == $school['school_id']) ? 'selected' : ''; ?>>
            <?php echo $school['school_name']; ?>
        </option>
    <?php endwhile; ?>
</select>

    </div>
    <div class="col-md-1 d-flex align-items-center"></div>

    <div class="col-md-6 d-flex align-items-center">
        <label for="room_type" class="form-label mb-0 me-2">Room Type:</label>
        <div class="d-flex">
            <div class="form-check me-3">
                <input type="radio" name="type_id" id="type1" class="form-check-input" value="1" <?php echo ($type_id == 1) ? 'checked' : 'checked'; ?>>
                <label class="form-check-label" for="type1">Seminar Hall</label>
            </div>
            <div class="form-check me-3">
                <input type="radio" name="type_id" id="type2" class="form-check-input" value="2" <?php echo ($type_id == 2) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="type2">Auditorium</label>
            </div>
            <div class="form-check">
                <input type="radio" name="type_id" id="type3" class="form-check-input" value="3" <?php echo ($type_id == 3) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="type3">Lecture Hall</label>
            </div>
        </div>
    </div>
    
</div>

               
<div class="row">
    <div class="col-md-5 d-flex align-items-center">
        <label for="department" class="form-label mb-0 me-2">Department:</label>
        <select name="department_id" id="department" class="form-select">
        <option value="">Select Department</option>
        </select>
    </div>
    <div class="col-md-1 d-flex align-items-center"></div>
    <div class="col-md-3 d-flex align-items-start">
    <label for="from-date" class="form-label mb-0 me-2">From:</label>
    <input type="date" name="from_date" id="from-date" class="form-control">
</div>
<div class="col-md-3 d-flex align-items-start">
    <label for="to-date" class="form-label mb-0 me-2">To:</label>
    <input type="date" name="to_date" id="to-date" class="form-control">
</div>

</div>



            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="booking_type" id="session" value="session" <?php echo ($booking_type == 'session') ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary" for="session">Session</label>
                        <input type="radio" class="btn-check" name="booking_type" id="slot" value="slot" <?php echo ($booking_type == 'slot') ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary" for="slot">Slot</label>
                    </div>
                </div>
                <div class="col-md-9 mb-3" id="session_options" style="display: <?php echo ($booking_type == 'session') ? 'block' : 'none'; ?>;">
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="session_choice" id="fn" value="fn" <?php echo ($session_choice == 'fn') ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-secondary" for="fn">Forenoon</label>
                        <input type="radio" class="btn-check" name="session_choice" id="an" value="an" <?php echo ($session_choice == 'an') ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-secondary" for="an">Afternoon</label>
                        <input type="radio" class="btn-check" name="session_choice" id="both" value="both" <?php echo ($session_choice == 'both') ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-secondary" for="both">Both</label>
                    </div>
                </div>
                <div class="col-md-9 mb-3" id="slot_options" style="display: <?php echo ($booking_type == 'slot') ? 'block' : 'none'; ?>;">
                    <div class="btn-group" role="group">
                        <?php
                        $slot_times = ['09:30am', '10:30am', '11:30am', '12:30pm', '01:30pm', '02:30pm', '03:30pm', '04:30pm'];
                        foreach ($slot_times as $index => $time) {
                            $slot_value = $index + 1;
                            echo '<input type="checkbox" class="btn-check" name="slots[]" id="slot' . $slot_value . '" value="' . $slot_value . '" ' . (in_array($slot_value, $slots) ? 'checked' : '') . '>';
                            echo '<label class="btn btn-outline-secondary" for="slot' . $slot_value . '">' . $time . '</label>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            
        </form>
    </div>
    <div class="row">
        <!-- Filter Section -->
        <div class="col-md-2">
            <form id="hallForm">
                <h5>Capacity</h5>
                <div class="form-check">
                    <input type="radio" name="capacity" value="50" id="capacity50" class="form-check-input">
                    <label for="capacity50" class="form-check-label">Up to 50</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="capacity" value="100" id="capacity100" class="form-check-input">
                    <label for="capacity100" class="form-check-label">Up to 100</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="capacity" value="150" id="capacity150" class="form-check-input">
                    <label for="capacity150" class="form-check-label">Up to 150</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="capacity" value="200" id="capacity200" class="form-check-input">
                    <label for="capacity200" class="form-check-label">200 or More</label>
                </div>

                <h5 class="mt-4">Features</h5>
                <div class="form-check">
                    <input type="checkbox" name="features[]" value="projector" id="projector" class="form-check-input">
                    <label for="projector" class="form-check-label">Projector</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="features[]" value="smart_board" id="smartboard" class="form-check-input">
                    <label for="smartboard" class="form-check-label">Smartboard</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="features[]" value="ac" id="ac" class="form-check-input">
                    <label for="ac" class="form-check-label">AC</label>
                </div>
               
                <div class="form-check">
                    <input type="checkbox" name="features[]" value="computer" id="computer" class="form-check-input">
                    <label for="computer" class="form-check-label">Computer</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="features[]" value="audio_system" id="audio_system" class="form-check-input">
                    <label for="audio_system" class="form-check-label">Audio System</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="features[]" value="podium" id="podium" class="form-check-input">
                    <label for="podium" class="form-check-label">Podium</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="features[]" value="ramp" id="ramp" class="form-check-input">
                    <label for="ramp" class="form-check-label">Ramp</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="features[]" value="white_board" id="white_board" class="form-check-input">
                    <label for="white_board" class="form-check-label">White Board</label>
                </div>
                <!-- Add other features -->
                
            </form>
        </div>

        <!-- Rooms Section -->
        <div class="col-md-10">
            <div class="row" id="roomsContainer">
                <!-- Room Cards -->
                <?php while ($room = mysqli_fetch_assoc($rooms_result)): ?>
                    <?php if (isRoomAvailable($conn, $room['hall_id'], $date, $booking_type, $session_choice, $slots)): ?>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $room['hall_name']; ?></h5>
                                    <p class="card-text">
                                        <strong>School:</strong> <?php echo $room['school_name']; ?><br>
                                        <strong>Department:</strong> <?php echo $room['department_name']; ?><br>
                                        <strong>Type:</strong> <?php echo $room['type_name']; ?>
                                    </p>
                                    <a href="book_room.php?hall_id=<?php echo $room['hall_id']; ?>&date=<?php echo $date; ?>&booking_type=<?php echo $booking_type; ?>&session_choice=<?php echo $session_choice; ?>&slots=<?php echo implode(',', $slots); ?>" class="btn btn-primary">Book Now</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>
<?php include 'assets/footer.php' ?>
<script>
    $(document).ready(function() {
        // Toggle between session and slot options
        $('input[name="booking_type"]').change(function() {
            if ($(this).val() === 'session') {
                $('#session_options').show();
                $('#slot_options').hide();
            } else {
                $('#session_options').hide();
                $('#slot_options').show();
            }
        });

        // Initialize date picker
        flatpickr("#date", {
            minDate: "today",
            dateFormat: "Y-m-d"
        });
        let today = new Date().toISOString().split('T')[0];
        $('#from-date').attr('min', today);

        // Ensure "To" date cannot be before "From" date
        $('#from-date').change(function () {
            let fromDate = $(this).val();
            $('#to-date').attr('min', fromDate); // Set the minimum value for "To" date
            if ($('#to-date').val() < fromDate) {
                $('#to-date').val(fromDate); // Automatically adjust "To" date if invalid
            }
        });

        // Ensure "From" date cannot be after "To" date
        $('#to-date').change(function () {
            let toDate = $(this).val();
            let fromDate = $('#from-date').val();
            if (fromDate && fromDate > toDate) {
                $('#from-date').val(toDate); // Automatically adjust "From" date if invalid
            }
        });

        // Load departments based on selected school and hall type
        $('#school').change(function() {
            var school_id = $(this).val();
            var type_id = $('input[name="type_id"]:checked').val();

            // Clear the department and room selections
            $('#department').html('<option value="">Select Department</option>');
            $('#room').html('<option value="">Select Room</option>');

            if (school_id && type_id) {
                // Show loading feedback (optional)
                $('#department').html('<option value="">Loading...</option>');

                $.ajax({
                    type: 'POST',
                    url: 'fetch_departments.php',
                    data: { school_id: school_id, type_id: type_id },
                    success: function(response) {
                        $('#department').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching departments:", error);
                        alert("Failed to fetch departments. Please try again.");
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
                // Show loading feedback (optional)
                $('#room').html('<option value="">Loading...</option>');

                $.ajax({
                    type: 'POST',
                    url: 'fetch_rooms.php',
                    data: { department_id: department_id, type_id: type_id },
                    success: function(response) {
                        $('#room').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching rooms:", error);
                        alert("Failed to fetch rooms. Please try again.");
                    }
                });
            }
        });

        // Handle form submission (if still needed)
        $('#filterForm, #hallForm').on('submit', function(event) {            event.preventDefault(); // Prevent the form from refreshing the page
            event.preventDefault(); // Prevent the form from refreshing the page
            filterRooms();
        });

       
    $('#filterForm input, #filterForm select, #hallForm input').on('change', function () {
        filterRooms();
    });

    function filterRooms() {
        let filterData = $('#filterForm').serialize(); // Data from filterForm (school, type, date range)
        let hallData = $('#hallForm').serialize(); // Data from hallForm (capacity, features)
        let formData = filterData + '&' + hallData;

        // Send AJAX request
        $.ajax({
            url: 'filter_rooms.php', // Backend endpoint for filtering
            method: 'GET',           // Use GET since you're filtering
            data: formData,          // Send serialized form data
            success: function (response) {
                $('#roomsContainer').html(response); // Update the room cards dynamically
            },
            error: function () {
                alert('Failed to fetch room data. Please try again.');
            }
        });
    }

    // Initial load of rooms
    filterRooms();
});
</script>
