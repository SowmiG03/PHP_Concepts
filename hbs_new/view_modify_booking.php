<?php
include('assets/conn.php'); // Include your database connection
include 'assets/header.php';
$id = $_SESSION['user_id']; // Logged-in user's ID
$role = $_SESSION['role']; // User role from session

// Base query to fetch booking details
$query = "
SELECT 
  b.*,        -- Booking details
  r.*,        -- Hall details
  d.department_name,  -- Department name
  ht.type_name  -- Hall type name
FROM 
  bookings b
JOIN 
  hall_details r ON b.hall_id = r.hall_id
LEFT JOIN 
  departments d ON r.department_id = d.department_id
LEFT JOIN 
  hall_type ht ON r.type_id = ht.type_id
";

// Apply different conditions based on the user role
if ($role == 'admin') {
    // Admin: Fetch all bookings
    $query .= " 
    ORDER BY 
      b.booking_id DESC"; // Most recent bookings first
} else {
    // Other users: Fetch only their own bookings
    $query .= "
    WHERE 
      b.user_id = $id
    ORDER BY 
      b.booking_id DESC"; // Most recent bookings first
}

// Prepare the statement
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Error preparing the statement: " . $conn->error); // Show the error message
}

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();
$slot_timings = [
    1 => '9:30 AM',
    2 => '10:30 AM',
    3 => '11:30 AM',
    4 => '12:30 PM',
    5 => '1:30 PM',
    6 => '2:30 PM',
    7 => '3:30 PM',
    8 => '4:30 PM'
];

// Close connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondicherry University - Hall Booking System</title>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/design.css" />
       <style>

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
    /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            max-width: 400px;
        }

        .close-modal {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-modal:hover,
        .close-modal:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
        h3{
            font-family: 'Times New Roman', Times, serif;
        }
    </style>
    </style>
</head>

<body>

    <div class="container1-fluid">
        <div class="container1 mt-5">
            <div class="table-wrapper">
           <div class="card">
                    <div class="card-body"> <center><h3 style="color:#170098; ">Manage Bookings</h3><br>
                    <center> 
                    <table class="table table-bordered">
                    <tr>
        <th style="width: 150px;"><center>Booked Date</center></th>
        <th style="width: 300px;"><center>Hall Details</center></th>
        <th style="width: 150px;"><center>Purpose</center></th>
        <th style="width: 200px;"><center>Date & Time</center></th>
        <th style="width: 350px;"><center>Booked By</center></th>
        <th style="width: 100px;"><center>Status</center></th>
        <th colspan="2" style="width: 100px; text-align: center;">Actions</th>

    </tr>

                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php 
                                echo date("d-m-Y", strtotime($row['booking_date'])) . "  <br>";
                                ?>
                            </td>
                        <td><span style="color:blue;"><?php echo strtoupper($row['hall_name']);?></span> <br>

                                <b><?php echo ucwords($row['type_name']);?></b><br>  
                                <?php echo $row['department_name'];?>   <br>  
                            </td>
                            <td>
                                <b><?php echo ucwords($row['purpose']);?></b><br>
                                <?php echo $row['purpose_name'];?>
                            </td>
                            <td><center>
                                <?php 
                                $booking_id = $row['booking_id'];
                                if ($row['start_date'] == $row['end_date']) {
                                    echo date("d-m-Y", strtotime($row['start_date'])) . " <br>";
                                } else {
                                    echo date("d-m-Y", strtotime($row['start_date'])) . "<br> to <br> " . date("d-m-Y", strtotime($row['end_date'])) . " <br>";
                                }
                                $booked_slots_string = $row['slot_or_session']; 
                                $booked_slots = array_map('intval', explode(',', $booked_slots_string));
                                sort($booked_slots); 

                                $forenoon_slots = [1, 2, 3, 4];
                                $afternoon_slots = [5, 6, 7, 8];
                                $full_day_slots = [1, 2, 3, 4, 5, 6, 7, 8];

                                $slot_timings = [
                                    1 => '9:30 AM ',
                                    2 => '10:30 AM',
                                    3 => '11:30 AM ',
                                    4 => '12:30 PM ',
                                    5 => '1:30 PM ',
                                    6 => '2:30 PM',
                                    7 => '3:30 PM ',
                                    8 => '4:30 PM '
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
                                    $booked_timings = implode(", ", array_map(function($slot) use ($slot_timings) {
                                        return $slot_timings[$slot];
                                    }, $booked_slots));
                                }

                                if (!empty($booking_type)) {
                                    echo "<b>" . $booking_type . " </b><br>";
                                }

                                if (!empty($booked_timings)) {
                                    echo "<b>" . $booked_timings . " </b>";
                                }
                                ?>
                            </center></td>
                            <td>
                            <b><?php echo $row['organiser_name'];?></b><br>
                                <?php echo $row['organiser_department'];?><br>
                                <?php echo $row['organiser_mobile'];?><br>
                                <?php echo $row['organiser_email'];?>
                            </td>
                            <td><center><?php echo ucwords(htmlspecialchars($row['status'])); ?></center></td>
                            <td class="actions" border="0">
                                <button class="btn btn-outline-success" onclick="window.location.href='edit_booking.php?id=<?php echo $row['booking_id']; ?>'">Modify</button>
                                <button style="padding:5px 14px; margin-top:15px;" class="btn btn-outline-secondary" onclick="openCancelModal(<?php echo $row['booking_id']; ?>)">Cancel</button></td>
                                <?php if ($role === 'admin'): ?>
        <td>
            <button class="btn btn-outline-danger" onclick="window.location.href='delete_booking.php?id=<?php echo $row['booking_id']; ?>'">Delete</button>
        </td>
    <?php endif; ?>                            </td>
                        </td>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>

    <!-- Cancel Booking Modal -->
    <div id="cancelModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <center><h3>Cancel Booking</h3></center>
            <form onsubmit="return handleCancelBooking(event);">
                <input type="hidden" name="booking_id" id="cancelBookingId">
                <div class="form-group">
                    <label for="reason">Reason for Cancellation:</label>
                    <select name="reason" id="reason" required>
                        <option value="" disabled selected>Select a reason</option>
                        <option value="Change of plans">Change of plans</option>
                        <option value="Scheduling conflict">Scheduling conflict</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <textarea name="other_reason" id="other_reason" placeholder="Please specify..." style="display:none; width: 100%; height: 50px; resize: vertical;"></textarea>
                </div>
                <center><button type="submit" class="btn-update">Submit</button></center>
            </form>
        </div>
    </div>
    <?php include 'assets/footer.php' ?>

    <script>
        function openEditBookingModal(bookingId) {
            fetch(`get_booking_details.php?id=${bookingId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('bookingId').value = data.booking_id;
                    document.getElementById('roomName').value = data.name;
                    document.getElementById('department_name').value = data.department_name;
                    document.getElementById('startDate').value = data.start_date;
                    document.getElementById('endDate').value = data.end_date;
                    document.getElementById('status').value = data.status;
                    document.getElementById('purpose').value = data.purpose;
                    document.getElementById('purpose_name').value = data.purpose_name;
                    document.getElementById('organiser_name').value = data.organiser_name;
                    document.getElementById('organiser_mobile').value = data.organiser_mobile;
                    document.getElementById('organiser_email').value = data.organiser_email;
                    document.getElementById('organiser_department').value = data.organiser_department;
                    document.getElementById('bookingDetailsModal').style.display = 'block';
                })
                .catch(error => console.error('Error:', error));
        }

        function closeBookingDetailsModal() {
            
            document.getElementById('bookingDetailsModal').style.display = 'none';
        }

        function handleEditBooking(event) {
            event.preventDefault();
            const form = document.getElementById('editBookingForm');
            const formData = new FormData(form);

            fetch('edit_booking.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('successfully')) {
                    alert('Booking updated successfully!');
                    location.reload();
                } else {
                    alert('Error updating booking. Please try again.');
                }
                closeBookingDetailsModal();
            })
            .catch(error => console.error('Error:', error));
        }

        function openCancelModal(bookingId) {
            document.getElementById('cancelBookingId').value = bookingId;
            document.getElementById('cancelModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('cancelModal').style.display = 'none';
        }

        document.getElementById('reason').addEventListener('change', function() {
            var otherReasonTextarea = document.getElementById('other_reason');
            if (this.value === 'Other') {
                otherReasonTextarea.style.display = 'block';
                otherReasonTextarea.required = true;
            } else {
                otherReasonTextarea.style.display = 'none';
                otherReasonTextarea.required = false;
                otherReasonTextarea.value = '';
            }
        });

        function handleCancelBooking(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            fetch('cancel_booking.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('something wrong')) {
                    alert('Booking cancelled successfully!');
                    location.reload();
                } else {
                    alert('Error cancelling booking. Please try again.');
                    }
                closeModal();
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>