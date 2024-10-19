<?php
include('assets/conn.php');

if (isset($_POST['department_id']) && isset($_POST['type_id'])) {
    $department_id = $_POST['department_id'];
    $type_id = $_POST['type_id'];

    $query = "SELECT hall_id, hall_name FROM hall_details WHERE department_id = '$department_id' AND type_id = '$type_id'";
    $result = mysqli_query($conn, $query);

    echo "<option value=''>Select Room</option>";
    while ($room = mysqli_fetch_assoc($result)) {
        echo "<option value='{$room['hall_id']}'>{$room['hall_name']}</option>";
    }
}
?>
