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
                    <form action="update_booking_event.php" method="post">
                    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking['booking_id']); ?>">
        <input type="hidden" name="hall_id" value="<?php echo htmlspecialchars($booking['hall_id']); ?>">
        <input type="hidden" name="booking_date" value="<?php echo date('Y-m-d'); ?>">

                            <div class="form-group mb-3">
                                <label for="room_name" class="form-label">Room Name:</label>
                                <input type="text"  class="form-control"  name="room_name" value="<?php echo htmlspecialchars($booking['hall_name']); ?>" disabled>
                            </div> 

                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label">Start Date:</label>
                                    <input type="date" name="start_date" class="form-control" readonly value="<?php echo htmlspecialchars($booking['start_date']); ?>" required onchange="setEndDate()">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label">End Date:</label>
                                    <input type="date" name="end_date" class="form-control" readonly  value="<?php echo htmlspecialchars($booking['end_date']); ?>" required>
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
                            <div class="mb-3">
                <label class="form-label">Purpose of Booking</label>
                <div class="btn-group w-100" role="group" aria-label="Purpose of Booking">
                <input type="radio" class="btn-check"  name="purpose" id="purpose_event" value="event" <?php echo ($booking['purpose'] == 'event') ? 'checked' : ''; ?>>
                    <label class="btn btn-outline-primary" for="purpose_event">Event</label>
                    <input type="radio"  class="btn-check" name="purpose" id="purpose_class" value="class" <?php echo ($booking['purpose'] == 'class') ? 'checked' : ''; ?>>
                    <label class="btn btn-outline-primary" for="purpose_class">Class</label>
                </div>
            </div>

            <div class="mb-3">
    <label for="purpose_name" class="form-label">Name of the Programme/Event</label>
    <textarea class="form-control" id="purpose_name" name="purpose_name" rows="3" required><?php echo htmlspecialchars($booking['purpose_name']); ?></textarea>
</div>


                            <div class="mb-3">
                <label for="students_count" class="form-label">Number of Students Expected</label>
                <input type="number" class="form-control" id="students_count" name="students_count" value="<?php echo htmlspecialchars($booking['students_count']); ?>" required min="1">
            </div>
                        </div>

            

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="organiser_name" class="form-label">Organiser's Name</label>
                    <input type="text" class="form-control" id="organiser_name" name="organiser_name" value="<?php echo htmlspecialchars($booking['organiser_name']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="organiser_department" class="form-label">Organiser's Department</label>
                    <input type="text" class="form-control" id="organiser_department" name="organiser_department" required oninput="suggestDepartments(this.value)" value="<?php echo htmlspecialchars($booking['organiser_department']); ?>" required>
                    <div id="suggestions" class="suggestions-box"></div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="organiser_mobile" class="form-label">Organiser's Contact Number</label>
                    <input type="text" class="form-control" id="organiser_mobile" name="organiser_mobile" value="<?php echo htmlspecialchars($booking['organiser_mobile']); ?>" required pattern="[0-9]{10}" title="Please enter a valid 10-digit mobile number.">
                </div>
                <div class="col-md-6">
                    <label for="organiser_email" class="form-label">Organiser's Email ID</label>
                    <input type="email" class="form-control" id="organiser_email" name="organiser_email" value="<?php echo htmlspecialchars($booking['organiser_email']); ?>" required>
                </div>
            </div>
            <input type="hidden" name="status" value="<?php echo htmlspecialchars($booking['status']); ?>">


            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success btn-lg">Update Booking</button>
            </div>
        </form>
    </fieldset>

                   
<script>
</script>
</html>    <?php include 'assets/footer.php' ?>
