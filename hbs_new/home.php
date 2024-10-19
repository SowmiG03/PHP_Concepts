<?php 
include 'assets/conn.php';

$features = ['Wifi', 'AC', 'Projector', 'Audio_system', 'Podium', 'Ramp', 'Smart_board', 'Lift'];
$roomTypes = ['Auditorium', 'Conference Room', 'Classroom', 'Lab']; // Example room types
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>admin Home</title>
    <link rel="stylesheet" href="assets/design.css" />
    
</head>
<body>
    <!-- Navbar -->
    <?php include 'assets/header.php'; ?>
    <!-- Main Content -->
    <div class="main-content mt-3">
        <h1>Dashboard Page</h1>
        <p>Your main content will appear here. Adjust it as needed based on your application functionality.</p>
    </div>

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





