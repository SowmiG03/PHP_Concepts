<?php
include 'assets/conn.php';
include 'assets/header.php';
$sql = "
    SELECT b.*, r.*, d.*, ht.type_name  -- Fetching all booking, hall_details, department, and hall type details
    FROM bookings b
    JOIN hall_details r ON b.hall_id = r.hall_id
    LEFT JOIN departments d ON d.department_id = r.department_id
    LEFT JOIN hall_type ht ON r.type_id = ht.type_id  -- Joining hall_type table to get the type_name
    WHERE b.status = 'pending'";  // Filter by hall type using type_name from hall_type table

// Apply filtering based on the role
if ($_SESSION['role'] === 'hod') {
    // If the user is HOD, filter by department
    $department_id = $_SESSION['department_id'];  // Assuming the HOD's department ID is stored in the session
    $sql .= " AND r.department_id = '$department_id'";  // Add department filter
} elseif ($_SESSION['role'] === 'dean') {
    // If the user is the Dean, filter by school or related department
    $school_id = $_SESSION['school_id'];  // Assuming dean's school_id is stored in session
    $sql .= " AND d.school_id = '$school_id'";  // Add school filter for the dean
} elseif ($_SESSION['role'] === 'admin') {
    // If the user is an Admin, show all pending bookings (no additional filtering)
    // No additional conditions needed for admin
}


// Admin sees all, no filter needed

// Order by the most recent bookings
$sql .= " ORDER BY b.booking_id DESC";

// Execute the query
$result = mysqli_query($conn, $sql);
// Handle the result as needed

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['booking_id'], $_POST['new_status'])) {
    // Sanitize input
    $booking_id = filter_input(INPUT_POST, 'booking_id', FILTER_SANITIZE_NUMBER_INT);
    $new_status = filter_input(INPUT_POST, 'new_status', FILTER_SANITIZE_STRING);

    // Validate status
    if (in_array($new_status, ['approved', 'rejected'])) {
        // Update the booking status in the database
        $sql_update = "UPDATE bookings SET status = ? WHERE booking_id = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("si", $new_status, $booking_id);
        
        if ($stmt->execute()) {
            $hall_name = $_POST['hall_name']; // Pass the organiser name in the form
            // Redirect back with a success message
            header("Location: status_update.php?status={$new_status}");
            exit(); // Ensure no further code is executed after redirection
        }
    }
    
    $stmt->close();
    $conn->close();
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
         .table-wrapper {
            overflow-x: auto; /* Allow horizontal scrolling */
            max-width: 100%;
        }
        /* Add any additional styles here */
        .container1 {
            margin-left: 250px;
            width: calc(100% - 250px); /* Make container take full width minus side nav */
            max-width: none; /* Remove any max-width restriction */
        }
        .container1-fluid {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2%;
        }
        .card {
            border: none;
        }
        h3{
            font-family: 'Times New Roman', Times, serif;
        }
        .btn {
    width: 100px; /* Set consistent width */
    margin: 5px; /* Add spacing between buttons */
    font-size: 16px; /* Ensure consistent font size */
    text-align: center; /* Center text */
    line-height: 30px; /* Vertically center text */
    padding: 3px;
  }
    </style>
</head>
<body>

<div class="container1-fluid">
        <div class="container1 mt-5">
            <div class="table-wrapper">
            <div class="card shadow-lg">
                    <div class="card-body">
                   
                    <?php
if (isset($_GET['status'])) {
    $status = htmlspecialchars($_GET['status']);
    $message = '';

    if ($status === 'approved') {
        $message = "Booking approved successfully.";
    } elseif ($status === 'rejected') {
        $message = "Booking rejected.";
    }

    echo "<script>alert('$message');</script>";
}
?>


     <table width="100%" class="table table-bordered">
        <thead> <center><h3 style="color:#0e00a3">Approve / Reject Bookings</h3><br>
        </center>
            <tr>
                <!-- <th width="35vh">Hall Type & Hall Name</th>
                <th width="15vh">Booking Date</th>
                <th width="20vh">Date & Slot/Session</th>
                <th width="10vh">Booked By</th>
                <th width="5vh">Status</th>
                <th width="5vh">Action</th> -->
                <th width="20%">Hall Details</th>
                <th width="10%">Booked On</th>
                <th width="15%">Date & Time</th>
                <th width="20%">Booked By</th>
                <th width="10%">Status</th>
                <th width="10%">Action</th>

            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><span style="color:blue;"><?php echo strtoupper($row['hall_name']);?></span> <br>
                      <b>  <?php echo ucwords($row['type_name']);?>  </b><br>
                        <?php echo $row['department_name'];?>  
                        </td>
                        <td><?php echo date("d-m-Y", strtotime($row['booking_date'])) . "  <br>";?></td>
                       <td>
                <?php 
                $booking_id = $row['booking_id'];
                    if ($row['start_date'] == $row['end_date']) {
                        echo date("d-m-Y", strtotime($row['start_date']))."<br>" ;
                    } else {
                        echo date("d-m-Y", strtotime($row['start_date'])) . " to " . date("d-m-Y", strtotime($row['end_date'])) . "<br>";
                    }
                    $booked_slots_string = $row['slot_or_session']; 
$booked_slots = array_map('intval', explode(',', $booked_slots_string)); // Convert to an array of integers
sort($booked_slots); 


$forenoon_slots = [1, 2, 3, 4]; // Forenoon slots
$afternoon_slots = [5, 6, 7, 8]; // Afternoon slots
$full_day_slots = [1, 2, 3, 4, 5, 6, 7, 8]; // Full Day slots

// Map of slot numbers to time strings
$slot_timings = [
    1 => '9:30 AM - 10:30 AM',
    2 => '10:30 AM - 11:30 AM',
    3 => '11:30 AM - 12:30 PM',
    4 => '12:30 PM - 1:30 PM',
    5 => '1:30 PM - 2:30 PM',
    6 => '2:30 PM - 3:30 PM',
    7 => '3:30 PM - 4:30 PM',
    8 => '4:30 PM - 5:30 PM'
];


$booking_type = '';
$booked_timings = '';


if ($booked_slots === $full_day_slots) {
    $booking_type = "Full Day";
} elseif ($booked_slots === $forenoon_slots) {
    $booking_type = "Forenoon";
} elseif ($booked_slots === $afternoon_slots) {
    $booking_type = "Afternoon";
} else {
    // If it's a custom slot, show only the booked timings
    $booked_timings = implode("<br> ", array_map(function($slot) use ($slot_timings) {
        return $slot_timings[$slot];
    }, $booked_slots));
}

// Output the booking type if it exists
if (!empty($booking_type)) {
    echo "(" . $booking_type . ")<br>";
}

// If there are booked timings (custom slots), display them
if (!empty($booked_timings)) {
    echo "(" . $booked_timings . ")";
}

                ?>
                <br><b><?php echo ucwords($row['purpose_name']);?></b> 
            </td>
            <td><b><?php echo  ucwords($row['organiser_department']);?></b><br>
                <?php echo  ucwords($row['organiser_name']);?><br>
         <?php echo $row['organiser_mobile'];?><br>
            <?php echo $row['organiser_email'];?>

            
            </td>

                        <td><?php echo ucwords(htmlspecialchars($row['status'])); ?></td>
                        <td>
                        <form action="status_update.php" method="post" style="display:inline;" onsubmit="return confirmAction(event);">
    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($row['booking_id']); ?>">
    <button type="submit" name="new_status" value="approved" class="btn btn-outline-success">Approve</button>
    <button type="submit" name="new_status" value="rejected" class="btn btn-outline-danger">Reject</button>
</form>

                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No pending requests.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</div>
</div>
</div>

</div>
    </div>
    <?php include 'assets/footer.php' ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmAction(event) {
    // Get the button that was clicked
    const button = event.submitter; // This will give you the clicked button
    const action = button.value; // Get the value of the clicked button

    // Define separate confirmation messages
    const approveMessage = "Are you sure you want to approve this booking?";
    const rejectMessage = "Are you sure you want to reject this booking?";

    // Determine the message based on the action
    const message = (action === 'approved') ? approveMessage : rejectMessage;

    // Display confirmation dialog and return true to submit the form if confirmed
    return confirm(message);
}


</script>
</body>


</html>

<?php
$conn->close();
?>