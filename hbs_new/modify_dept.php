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
     
    <title>admin Home</title>

</head>
<body>

   <?php include 'assets/header.php' ?>
    <div class="container">
        <div class="row">
            <div class="col-2">
                
            </div>
            <div class="col-10 pt-5 mt-5">

            <?php
                include 'assets/conn.php';
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $stmt = $conn->prepare("SELECT * FROM departments WHERE department_id = ?");
                    $stmt->bind_param('i', $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $dept = $result->fetch_assoc();
                
                    // Check if room exists
                    if (!$dept) {
                        echo "Room not found!";
                        exit;
                    }
                }
            ?>

                <!-- Seminar Hall Form -->
                <form action="insert.php" method="post" style="margin:30px 100px; background-color:white; padding: 50px; border-radius:15px;" enctype="multipart/form-data">
                <h1 class="mb-4" style="color:#4c37dc;">Update Hall</h1>

                        <label class="form-label mt-4" for="room_name"> School:</label>
                        <input type="text" id="school" name="school" value="<?php echo $dept['school_name'] ?>"  >

                        <label for="capacity">Department:</label>
                        <input type="text" id="department" value="<?php echo $dept['department_name'] ?>" name="department" >

                        <input type="submit" name="update" value="Update">
                        
                </form>

            


            </div>
        </div>
    </div>
    <!-- <footer>
        <p>&copy; 2024 University Hall Booking System | All Rights Reserved</p>
    </footer> -->

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    
</body>
</html>

<?php
include 'assets/conn.php';
// $id = $_GET['id'];
if (isset($_POST['update']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    

    
    $school = $conn->real_escape_string($_POST['school']);
    $department = $conn->real_escape_string($_POST['department']);
   
    // Update query
    $sql = "UPDATE school_department SET 
                school_name = '$school', 
                department_name = '$department', 
               
            WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.assign('view_dept.php')</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>
