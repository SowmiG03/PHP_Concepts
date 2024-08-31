<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact List</title>
    <link rel="stylesheet" type="text/css" href="style.css">
 
</head>
<body>
<div class="table-container">
 
    <?php
    include 'CONNECTION.php';
    $sql = "SELECT * FROM contact";
    $res = mysqli_query($connection, $sql);
    $nrow = mysqli_num_rows($res);
    if ($nrow > 0) {
        echo '<table class="contact-table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>First Name</th>';
        echo '<th>Last Name</th>';
        echo '<th>Phone Number</th>';
        echo '<th>Email</th>';
        echo '<th>Date of Birth</th>';
        echo '<th>Address</th>';
        echo '<th>Tags</th>';
        echo '<th>Gender</th>';
        echo '<th>Contact Type</th>';
        echo '<th>Actions</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['id']) . '</td>';
            echo '<td>' . htmlspecialchars($row['firstname']) . '</td>';
            echo '<td>' . htmlspecialchars($row['lastname']) . '</td>';
            echo '<td>' . htmlspecialchars($row['phonenumber']) . '</td>';
            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
            echo '<td>' . htmlspecialchars($row['dob']) . '</td>';
            echo '<td>' . htmlspecialchars($row['address']) . '</td>';
            echo '<td>' . htmlspecialchars($row['tags']) . '</td>';
            echo '<td>' . htmlspecialchars($row['gender']) . '</td>';
            echo '<td>' . htmlspecialchars($row['contact_type']) . '</td>';
            echo '<td><a href="delete.php?id=' . urlencode($id) . '" class="action-link">Delete</a> | <a href="update.php?id=' . urlencode($id) . '" class="action-link">Update</a></td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>No contacts found.</p>';
    }
    ?>
       </div>

    <div class="button-container">
        <button><a href="index.php">Add Contact</a></button>
    </div>


    
</body>

</html>
