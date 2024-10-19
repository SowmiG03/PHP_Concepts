<?php 

include 'assets/conn.php';

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
     
    <title>Add Hall</title>
    <style>

        .form-section {
            display: none;
        }
        .form-section.active {
            display: block;
        }
    </style>
</head>
<body>

    <?php include 'assets/header.php' ?>

    <div class="main-content mt-3">
    
    <h2 style="text-align: center;">Add School or Department</h2>

<!-- Radio buttons to choose between School or Department -->
            <form style="margin:30px 100px; background-color:white; padding: 50px; border-radius:15px;">
                <label for="type" style="margin:0 0 10px 0; font-size: 20px; font-weight: 600;">Choose Type:</label><br>
                <label style="margin-left: 50px;">
                    <input type="radio" name="type" value="school" onclick="toggleForm()" checked>
                    School
                    <input type="radio" style="margin-left: 25px;" name="type" value="department" onclick="toggleForm()">
                    Department
                </label>
                <!-- <label>
                    <input type="radio" name="type" value="department" onclick="toggleForm()">
                    Department
                </label> -->

                <!-- Form for School -->
                <div id="school-form" class=" form-section active mt-3">
                    <!-- <hr> -->
                    <!-- <h3>School Details</h3> -->
                    <label class="form-label" for="school">School Name:</label>
                    <input type="text" class="form-control" id="school" name="school" placeholder="Enter School Name">


                    <label class="form-label" for="dean-name">Dean Name:</label>
                    <input type="text" class="form-control" id="dean-name" name="dean-name">

                    <label class="form-label" for="dean-contact">Dean Contact Number:</label>
                    <input type="text" class="form-control" id="dean-contact" name="dean-contact">

                    <label class="form-label" for="dean-email">Dean Email:</label>
                    <input type="email" class="form-control" id="dean-email" name="dean-email">

                    <label class="form-label" for="dean-intercom">Dean Intercom:</label>
                    <input type="text" class="form-control" id="dean-intercom" name="dean-intercom">

                    <label class="form-label" for="dean-status">Dean Status:</label>
                    <input type="text" class="form-control" id="dean-status" name="dean-status">
                </div>

                <!-- Form for Department -->
                <div id="department-form" class="form-section mt-3">
                    <!-- <h3>Department Details</h3> -->
                    <div id="departmentFields" >
                            <label class="form-label" style="margin-top: 8px;">School Name:</label>
                            <select style="padding:12px;" name="school_name" id="school_name">
                            <option value="">Select School</option>

                                    <?php
                                        include 'assets/conn.php';
                                        $sql = "SELECT DISTINCT school_name, school_id FROM schools";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<option value="' . $row['school_id'] . '">' . $row['school_name'] . '</option>';
                                            }
                                        }
                                    ?>
                                </select>

                            <!-- <label class="form-label" style="margin-top: 8px;">Department :</label>
                            <select style="padding: 7px;" name="department_name" id="department_name">
                                <option value="">Select Department</option>
                            </select> -->
                        </div>
                    <label class="form-label" for="department-name">Department Name:</label>
                    <input type="text" class="form-control" id="department-name" name="department-name">

                    <label class="form-label" for="hod-name">HOD Name:</label>
                    <input type="text" class="form-control" id="hod-name" name="hod-name">

                    <label class="form-label" for="hod-contact">HOD Contact Mobile:</label>
                    <input type="text" class="form-control" id="hod-contact" name="hod-contact">

                    <label class="form-label" for="designation">Designation:</label>
                    <input type="text" class="form-control" id="designation" name="designation">

                    <label class="form-label" for="hod-email">HOD Contact Email:</label>
                    <input type="email" class="form-control" id="hod-email" name="hod-email">

                    <label class="form-label" for="hod-intercom">HOD Intercom:</label>
                    <input type="text" class="form-control" id="hod-intercom" name="hod-intercom">
                </div>

                <button class="btn btn-primary" style="width: 30%; padding:10px; margin-left:35%; " type="submit">Submit</button>
            </form>      
        </div>
    <!-- <footer>
        <p>&copy; 2024 University Hall Booking System | All Rights Reserved</p>
    </footer> -->

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
        function toggleForm() {
            const selectedType = document.querySelector('input[name="type"]:checked').value;
            document.getElementById('school-form').classList.remove('active');
            document.getElementById('department-form').classList.remove('active');
            
            if (selectedType === 'school') {
                document.getElementById('school-form').classList.add('active');
            } else {
                document.getElementById('department-form').classList.add('active');
            }
        }
        
        window.onload = function() {
            toggleForm();  // Set the default view on page load
        }
    </script>



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





