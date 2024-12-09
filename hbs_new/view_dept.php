
<?php include 'assets/header.php' ?>

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
                    <div class="card-body"> <center><h3 style="color:#170098; ">School and department details</h3><br>
                    <center> 
                    <table class="table table-bordered">
    <thead>
        <tr>
            <th style="width:5%;">SL</th>
            <th style="width:30%;">School</th>
            <th style="width:40%;">Department</th>
            <th colspan="1" style="width: 100px; text-align: center;">Actions</th>
            </tr>
    </thead>
    <tbody>

        <?php
        include 'assets/conn.php';

        // SQL query to fetch school_name and department_name
        $sql = "SELECT s.school_name, d.department_id, d.department_name
                FROM departments d
                JOIN schools s ON d.school_id = s.school_id
                ORDER BY s.school_name, d.department_name";

        // Execute the query
        $result = $conn->query($sql);
        $schools = [];

        if ($result->num_rows > 0) {
            // Group departments by school
            while ($row = $result->fetch_assoc()) {
                $schools[$row['school_name']][] = [
                    'department_name' => $row['department_name'],
                    'department_id' => $row['department_id']
                ];
            }

            $i = 1; // Serial number counter
            foreach ($schools as $school_name => $departments) {
                echo "<tr>";
                echo "<td>" . $i . "</td>";
                echo "<td>" . $school_name . "</td>";
                
                // Format the departments list with numbering (1., 2., etc.)
                echo "<td>";
                foreach ($departments as $index => $department) {
                    echo ($index + 1) . ". " . $department['department_name'] . "<br>";
                }
                echo "</td>";

                // Action buttons
                echo "<td style='text-align:center; display: flex; justify-content: center; align-items: center; flex-direction: column;'>";
    echo '<button class="btn btn-outline-success">
            <a href="" style="color:black; padding:0;">View/Modify</a>
          </button>';

    echo '<button class="btn btn-outline-danger" style="padding:5px 14px; margin-top:15px;">
            <a href="" onclick="return confirm(\'Are you sure you want to delete?\')" style="color:black;">Delete</a>
          </button><br>';
echo "</td>";
echo "</tr>";

                $i++; // Increment serial number
            }
        } else {
            echo '<tr><td colspan="4">No data found</td></tr>';
        }

        // Close the connection
        $conn->close();
        ?>
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