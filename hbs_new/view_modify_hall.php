<?php 
include 'assets/conn.php';

$features = ['Wifi', 'AC', 'Projector', 'Audio_system', 'Podium', 'Ramp', 'Smart_board', 'Lift'];

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
     
    <title>Admin Home</title>
</head>
<body>
    <?php include 'assets/header.php' ?>

    <div class="main-content">
        <h1 class="hall-details">Hall Details</h1>
        <table class="table table-bordered" style="border: 1px solid;">
            <thead>
                <tr>
                    <th style="background-color: #ecec82; width:10%;">Date</th>
                    <th style="background-color: #ecec82; width:15%;">Hall Details</th>
                    <th style="background-color: #ecec82; width:20%;">Belongs to</th>
                    <th style="background-color: #ecec82; width:15%;">Incharge Details</th>
                    <th style="background-color: #ecec82; width:5%;">Availability</th>
                    <th style="background-color: #ecec82; width:20%;">Features</th>
                    <th style="background-color: #ecec82; width:10%;">Actions</th>
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
                
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // echo $row['department_id']; 
                        // echo $row['department_name']; 
                        // die;
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
                                <button style="background: #78d278; padding: 6px 15px; border-radius: 10px; margin-bottom: 5px; border:0;">
                                    <a href="modify_hall.php?id=<?php echo $row['hall_id']; ?>" style="color:black; padding:0;">View/Modify</a>
                                </button>
                                <button style="background: #df9f66; padding: 6px 20px; border-radius: 10px; border:0;">
                                    <a href="delete_hall.php?id=<?php echo $row['hall_id']; ?>" onclick="return confirm('Are you sure you want to delete?')" style="color:black;">Delete</a>
                                </button>
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
