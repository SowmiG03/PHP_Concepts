
<?php

session_start(); // Ensure session is started

$loggedIn = isset($_SESSION['role']);
$user_role = $loggedIn ? $_SESSION['role'] : null;
$username=$_SESSION['username'];
$user_id=$_SESSION['user_id'];
$department_id=$_SESSION['department_id'];
$school_id=$_SESSION['school_id'];
// Check if the user is logged in by checking if a session variable is set
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit(); // Stop further execution
}
?>



    </nav>

    <nav class="navbar navbar-light" style="position: fixed;">
        <img src="image/logo/PU_Logo_Full.png" alt="Pondicherry University Logo" style="margin: 10px 50px;" class="logo">
        <!-- <span class="ml-auto"> -->
            <h5 style="color: white;">UNIVERSITY HALL BOOKING SYSTEM</h5>
            <?php if (isset($_SESSION['username'])): ?>
            <span style="margin:50px 20px 0px 0px;">
            <a href="#" class="text-white">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?></a> | 
            <a href="#" class="text-white">My Account</a> | 
            <a href="logout.php" class="text-white">Logout</a>
            <?php else: ?>
        <a href="login.php" class="text-white">Login</a>
    <?php endif; ?>
            </span>
            
        <!-- </span> -->
    </nav>


    <!-- Side Navigation -->
    <div class="side-nav">
        <a href="home.php">Dashboard</a>

        <!-- Expandable Departments Menu -->
        <button class="dropdown-btn">Hall Details</button>
        <div class="dropdown-container">
        <?php if ($user_role == 'admin' || $user_role == 'dean'): ?>
               <a href="add_hall.php">Add Hall</a>   
            <a href="add_school_dept.php">Add Department</a>   
            <a href="view_dept.php">View/Modify Department</a>    
             <?php endif; ?>
             <a href="view_modify_hall.php">View/Modify Hall</a> 
        </div>

    

        <button class="dropdown-btn">Booking Details</button>
<div class="dropdown-container">
    <a href="find_halls.php">Find Halls</a>
    <a href="book_hall.php">Add Booking</a>
   


    <?php if ($user_role == 'dean'): ?>
        <a href="status_update.php">Approve Bookings</a>
    <?php endif; ?>

        <a href="view_modify_booking.php">Manage Bookings</a>
        

            <!-- <a href="delete_booking.php">Delete Bookings</a> -->
            
        </div>

     
    </div>
<script>document.addEventListener('DOMContentLoaded', function () {
    const dropdownBtns = document.querySelectorAll('.dropdown-btn');
    const dropdownContainers = document.querySelectorAll('.dropdown-container');

    // Check for previously opened menu from sessionStorage
    const activeMenuIndex = sessionStorage.getItem('activeMenuIndex');

    if (activeMenuIndex) {
        dropdownBtns[activeMenuIndex].classList.add('active');
        dropdownContainers[activeMenuIndex].classList.add('active');
    }

    // Attach event listeners to each dropdown button
    dropdownBtns.forEach((btn, index) => {
        btn.addEventListener('click', function () {
            // Toggle the current dropdown menu
            const dropdown = this.nextElementSibling;

            if (dropdown.classList.contains('active')) {
                dropdown.classList.remove('active');
                this.classList.remove('collapsed');
                sessionStorage.removeItem('activeMenuIndex');
            } else {
                // Close all other dropdowns
                dropdownContainers.forEach((container) => container.classList.remove('active'));
                dropdownBtns.forEach((btn) => btn.classList.remove('collapsed'));

                // Open the selected dropdown
                dropdown.classList.add('active');
                this.classList.add('collapsed');
                sessionStorage.setItem('activeMenuIndex', index);
            }
        });
    });
});
</script>