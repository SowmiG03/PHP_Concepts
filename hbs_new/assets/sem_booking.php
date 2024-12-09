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
    <script src="book_hall.js"></script>

<style>
    h3{
        font-family: 'Times New Roman', Times, serif;
    }
    .form-group {
            margin-bottom: 15px;
        }
        .hidden {
            display: none;
        }
        .weekday-options {
            display: flex;
            gap: 10px;
        }



        .form-check {
  margin-right: 15px; /* Space between each option */
}

.d-flex.gap-3 {
  gap: 20px; /* Adjust the gap size between options */
}

</style>
</head>
<body>
<?php include 'assets/header.php' ?>


    <div class="container mt-5" style="margin-left: 16%;">
        <div class="row justify-content-center">
            <div class="col-md-8">
            <input type="hidden"  name="user_id" id="user_id" value="<?php echo $_SESSION['user_id']?>" required>
          
                <div class="card shadow-lg">
                    <div class="card-body"> <center> <h3 style="color:#0e00a3">Hall Booking</h3><br>
                    </center>
                        <form id="checkAvailabilityForm">
                        <div class="mb-4">
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
                        <div class="row">
  <div class="form-group d-flex align-items-center">
    <label class="form-label me-3 mb-0">Semester Booking:</label>
    <div class="d-flex gap-3"> <!-- Flexbox for spacing -->
      <div class="form-check">
        <input type="radio" id="semester_no" name="semester" value="no" class="form-check-input" checked onchange="toggleWeekdays(this.value)">
        <label for="semester_no" class="form-check-label">No</label>
      </div>
      <div class="form-check">
        <input type="radio" id="semester_yes" name="semester" value="yes" class="form-check-input" onchange="toggleWeekdays(this.value)">
        <label for="semester_yes" class="form-check-label">Yes</label>
      </div>
    </div>
  </div>
</div>


                        <div class="mb-4">
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
 <div class="form-group hidden" id="weekdayField">
  <div class="form-group d-flex align-items-center">

  <label class="form-label me-3 mb-0">Select Weekday:</label>
  <div class="d-flex gap-3"> <!-- Flexbox with wrapping and gap -->
    <div class="form-check">
      <input type="radio" id="mon" name="weekday" value="mon" class="form-check-input">
      <label for="mon" class="form-check-label">Mon</label>
    </div>
    <div class="form-check">
      <input type="radio" id="tue" name="weekday" value="tue" class="form-check-input">
      <label for="tue" class="form-check-label">Tue</label>
    </div>
    <div class="form-check">
      <input type="radio" id="wed" name="weekday" value="wed" class="form-check-input">
      <label for="wed" class="form-check-label">Wed</label>
    </div>
    <div class="form-check">
      <input type="radio" id="thu" name="weekday" value="thu" class="form-check-input">
      <label for="thu" class="form-check-label">Thu</label>
    </div>
    <div class="form-check">
      <input type="radio" id="fri" name="weekday" value="fri" class="form-check-input">
      <label for="fri" class="form-check-label">Fri</label>
    </div>
    <div class="form-check">
      <input type="radio" id="sat" name="weekday" value="sat" class="form-check-input">
      <label for="sat" class="form-check-label">Sat</label>
    </div>
  </div>
</div></div>


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
    <script src="book_hall.js"></script>

    <script>
       
</script>
    </script>
</body>
</html>