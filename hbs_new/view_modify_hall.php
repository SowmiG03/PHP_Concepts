<?php 
include 'assets/conn.php';
include 'assets/header.php';

$features = ['Wifi', 'AC', 'Projector', 'Audio_system', 'Podium', 'Ramp', 'Smart_board', 'Lift'];

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
                    <div class="card-body"> <center><h3 style="color:#170098; ">Hall Details</h3><br>
                    <center> 
                    <table class="table table-bordered">
            <thead>
                <tr>
                    <th style=" width:15%;">Date</th>
                    <th style=" width:15%;">Hall Details</th>
                    <th style=" width:20%;">Belongs to</th>
                    <th style=" width:15%;">Incharge Details</th>
                    <th style=" width:5%;">Availability</th>
                    <th style=" width:20%;">Features</th>
                    <th colspan="2" style="width: 100px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php  
                 $query = "SELECT hd.*, ht.type_name, s.school_name, d.department_name, sec.section_name 
                 FROM hall_details hd
                 JOIN hall_type ht ON hd.type_id = ht.type_id
                 LEFT JOIN schools s ON hd.school_id = s.school_id
                 LEFT JOIN departments d ON hd.department_id = d.department_id
                 LEFT JOIN section sec ON hd.section_id = sec.section_id";
       
       if ($user_role == 'hod') {
          $query .= " WHERE hd.department_id = ?";
          $value_to_bind = $_SESSION['department_id']; // Fetch the department_id from session
      } elseif ($user_role == 'dean') {
          $query .= " WHERE hd.school_id = ?";
          $value_to_bind = $_SESSION['school_id']; // Fetch the school_id from session
      }
      // Prepare the statement
      $result = $conn->prepare($query);
      if ($result) {
          $result->bind_param('i', $value_to_bind); // Bind the value to the placeholder
      }
      $result->execute();
  
  // Get the result
  $result_set = $result->get_result();
  
  
                  if ($result_set->num_rows > 0) {
                      while ($row = $result_set->fetch_assoc()) {
                        $features = []; // Reset features array for each row
                        ?>
                        <tr>
                            <td><?php echo $row['from_date']; ?></td>
                            <td>
                                <b style="color: #007bff;"><?php echo $row['type_name']; ?></b><br>
                                <?php echo $row['hall_name']; ?><br>
                                <?php echo $row['capacity']; ?><br>
                                <?php echo $row['floor']; ?><br>
                                <?php echo $row['zone']; ?>
                            </td>
                            <td>
                                <?php if (empty($row['section_id'])) { ?>
                                    <b style="color: #007bff;"><?php echo $row['school_name']; ?></b><br>
                                    <?php echo $row['department_name']; ?><br>
                                <?php } else { 
                                    echo $row['section_name'];
                                } ?>
                            </td>
                            <td>
                                <b style="color: #007bff;"><?php echo $row['incharge_name']; ?></b><br>
                                <?php echo $row['designation']; ?><br>
                                <?php echo $row['phone']; ?><br>
                                <?php echo $row['incharge_email']; ?><br>
                            </td>
                            <td><?php echo $row['availability']; ?></td>
                            <td>
                                <?php
                                // Collect features that are not "No"
                                if ($row['wifi'] != 'No') $features[] = $row['wifi'];
                                if ($row['ac'] != 'No') $features[] = $row['ac'];
                                if ($row['projector'] != 'No') $features[] = $row['projector'];
                                if ($row['smart_board'] != 'No') $features[] = $row['smart_board'];
                                if ($row['audio_system'] != 'No') $features[] = $row['audio_system'];
                                if ($row['podium'] != 'No') $features[] = $row['podium'];
                                if ($row['white_board'] != 'No') $features[] = $row['white_board'];
                                if ($row['blackboard'] != 'No') $features[] = $row['blackboard'];
                                if ($row['lift'] != 'No') $features[] = $row['lift'];
                                if ($row['ramp'] != 'No') $features[] = $row['ramp'];

                                // Join the features with a comma
                                $features_string = implode(', ', $features);
                                echo $features_string;
                                ?>
                            </td>
                            <td>
                                <button class="btn btn-outline-success">
                                    <a href="modify_hall.php?id=<?php echo $row['hall_id']; ?>" style="color:black; padding:0;">View/Modify</a>
                                </button>
                                <button style="padding:5px 14px; margin-top:15px;"  class="btn btn-outline-danger">
                                    <a href="delete_hall.php?id=<?php echo $row['hall_id']; ?>" onclick="return confirm('Are you sure you want to delete?')" style="color:black;">Delete</a>
                                </>
                            </td>
                        </tr>
                    <?php } 
                } else {
                    echo "<tr><td colspan='7'>No results found</td></tr>";
                } ?>
            </tbody>
        </table>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   
    <script>
        // Get all the dropdown buttons
        var dropdownBtns = document.querySelectorAll(".dropdown-btn");

        // Loop through the buttons to add event listeners
        dropdownBtns.forEach(function(btn) {
            btn.addEventListener("click", function() {
                // Toggle between showing and hiding the active dropdown container
                this.classList.toggle("collapsed");
                var dropdownContainer = this.nextElementSibling;
                if (dropdownContainer.style.display === "block") {
                    dropdownContainer.style.display = "none";
                } else {
                    dropdownContainer.style.display = "block";
                }
            });
        });
    </script>
</body>
</html>
