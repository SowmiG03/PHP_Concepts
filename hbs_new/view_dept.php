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

    <title>View Department</title>

</head>

<body>
    <?php include 'assets/header.php' ?>

    <div class="main-content mt-5">
        <div class="table-wrapper">
            <div class="card shadow-lg">
                <div class="card-body">
                    <!-- <h1 class="hall-details">School and Department Details</h1> -->
                    <table class="table table-bordered" >
                        <thead>
                        <center><h3 style="color:#0e00a3">School & Department Data</h3><br>
                        </center>
                            <tr>
                                <th style="padding: 15px; width:5%;">SL</th>
                                <th style="padding: 15px; width:30%;">School</th>
                                <th style="padding: 15px; width:40%;">Department</th>
                                <th style="padding: 15px; width:15%;">Action</th>
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
                                    echo "<td style='align-content:center;'>";

                                    echo '<button style="background: #78d278; padding: 6px 15px; border-radius: 10px; margin-left: 15px; border:0px;">
                            <a href="" style="color:black; padding:0;">View/Modify</a>
                          </button>';

                                    echo '<button style="background: #df9f66; padding: 6px 20px; border-radius: 10px; margin-left: 15px; margin-top: 5px; border:0px;">
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
            </div>
        </div>

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