<<<<<<< HEAD
<?php
include 'CONNECTION.php';

if(isset($_GET['id']))
{
    $id=$_GET['id'];
    $sql_update="select * from contact where id='$id'";
    $res=mysqli_query($connection,$sql_update);
    //print_r($res);
   
    if($row=mysqli_num_rows($res)==1)
    {
       $record= mysqli_fetch_assoc($res);  
    }
    else{
        echo "no record found";
    }



?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Form</title>
    <link rel="stylesheet" type="text/css" href="style.css" >
</head>
<body>

<div class="form-container">
        <h2>User Information Form</h2>

<form method="post" action="update_logic.php">
    <div class="form-group">
        <input type="hidden" id="id" name="id" required value="<?php echo $record['id']; ?>">
    </div>

    <!-- First Name -->
    <div class="form-group">
        <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname" required value="<?php echo htmlspecialchars($record['firstname']); ?>">
    </div>

    <!-- Last Name -->
    <div class="form-group">
        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname" required value="<?php echo htmlspecialchars($record['lastname']); ?>">
    </div>

    <!-- Email -->
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($record['email']); ?>">
    </div>

    <!-- Phone Number -->
    <div class="form-group">
        <label for="phonenumber">Phone Number</label>
        <input type="tel" id="phonenumber" name="phonenumber" required value="<?php echo htmlspecialchars($record['phonenumber']); ?>">
    </div>

    <!-- Date of Birth -->
    <div class="form-group">
        <label for="dob">Date of Birth</label>
        <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($record['dob']); ?>" required>
    </div>

    <!-- Gender -->
    <div class="form-group">
        <label>Gender:</label>
        <div class="gender-options">
            <input type="radio" id="gender-female" name="gender" value="Female" <?php echo ($record['gender'] == 'Female') ? 'checked' : ''; ?> required> Female
            <input type="radio" id="gender-male" name="gender" value="Male" <?php echo ($record['gender'] == 'Male') ? 'checked' : ''; ?> required> Male
            <input type="radio" id="gender-transgender" name="gender" value="Transgender" <?php echo ($record['gender'] == 'Transgender') ? 'checked' : ''; ?> required> Transgender
        </div>
    </div>

    <!-- Address -->
    <div class="form-group">
        <label for="address">Address</label>
        <textarea id="address" name="address" rows="3" required><?php echo htmlspecialchars($record['address']); ?></textarea>
    </div>

    <!-- Tags/Labels -->
    <div class="form-group">
        <label for="tags">Nick Name</label>
        <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars($record['tags']); ?>">
    </div>

    <!-- Type of Contact -->
    <div class="form-group">
        <label for="contact-type">Type of Contact</label>
        <select id="contact-type" name="contact_type" required>
            <option value="" disabled>Select type</option>
            <option value="Friend" <?php echo ($record['contact_type'] == 'Friend') ? 'selected' : ''; ?>>Friend</option>
            <option value="Relation" <?php echo ($record['contact_type'] == 'Relation') ? 'selected' : ''; ?>>Relation</option>
            <option value="Colleague" <?php echo ($record['contact_type'] == 'Colleague') ? 'selected' : ''; ?>>Colleague</option>
            <option value="Teacher" <?php echo ($record['contact_type'] == 'Teacher') ? 'selected' : ''; ?>>Teacher</option>
            <option value="Family" <?php echo ($record['contact_type'] == 'Family') ? 'selected' : ''; ?>>Family</option>
            <option value="Business" <?php echo ($record['contact_type'] == 'Business') ? 'selected' : ''; ?>>Business</option>
            <option value="Acquaintance" <?php echo ($record['contact_type'] == 'Acquaintance') ? 'selected' : ''; ?>>Acquaintance</option>
            <option value="Neighbor" <?php echo ($record['contact_type'] == 'Neighbor') ? 'selected' : ''; ?>>Neighbor</option>
            <option value="Mentor" <?php echo ($record['contact_type'] == 'Mentor') ? 'selected' : ''; ?>>Mentor</option>
            <option value="Client" <?php echo ($record['contact_type'] == 'Client') ? 'selected' : ''; ?>>Client</option>
            <option value="Other" <?php echo ($record['contact_type'] == 'Other') ? 'selected' : ''; ?>>Other</option>
        </select>
    </div>

    <!-- Submit Button -->
    <div class="form-group">
        <input type="submit" name="update" value="Update">
    </div>
</form>
</div>

   </body>
   </html>
   <?php
   }
   else{
    echo "something went wrong";
   }
=======
<?php
include 'CONNECTION.php';

if(isset($_GET['id']))
{
    $id=$_GET['id'];
    $sql_update="select * from contact where id='$id'";
    $res=mysqli_query($connection,$sql_update);
    //print_r($res);
   
    if($row=mysqli_num_rows($res)==1)
    {
       $record= mysqli_fetch_assoc($res);  
    }
    else{
        echo "no record found";
    }



?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Form</title>
    <link rel="stylesheet" type="text/css" href="style.css" >
</head>
<body>

<div class="form-container">
        <h2>User Information Form</h2>

<form method="post" action="update_logic.php">
    <div class="form-group">
        <input type="hidden" id="id" name="id" required value="<?php echo $record['id']; ?>">
    </div>

    <!-- First Name -->
    <div class="form-group">
        <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname" required value="<?php echo htmlspecialchars($record['firstname']); ?>">
    </div>

    <!-- Last Name -->
    <div class="form-group">
        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname" required value="<?php echo htmlspecialchars($record['lastname']); ?>">
    </div>

    <!-- Email -->
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($record['email']); ?>">
    </div>

    <!-- Phone Number -->
    <div class="form-group">
        <label for="phonenumber">Phone Number</label>
        <input type="tel" id="phonenumber" name="phonenumber" required value="<?php echo htmlspecialchars($record['phonenumber']); ?>">
    </div>

    <!-- Date of Birth -->
    <div class="form-group">
        <label for="dob">Date of Birth</label>
        <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($record['dob']); ?>" required>
    </div>

    <!-- Gender -->
    <div class="form-group">
        <label>Gender:</label>
        <div class="gender-options">
            <input type="radio" id="gender-female" name="gender" value="Female" <?php echo ($record['gender'] == 'Female') ? 'checked' : ''; ?> required> Female
            <input type="radio" id="gender-male" name="gender" value="Male" <?php echo ($record['gender'] == 'Male') ? 'checked' : ''; ?> required> Male
            <input type="radio" id="gender-transgender" name="gender" value="Transgender" <?php echo ($record['gender'] == 'Transgender') ? 'checked' : ''; ?> required> Transgender
        </div>
    </div>

    <!-- Address -->
    <div class="form-group">
        <label for="address">Address</label>
        <textarea id="address" name="address" rows="3" required><?php echo htmlspecialchars($record['address']); ?></textarea>
    </div>

    <!-- Tags/Labels -->
    <div class="form-group">
        <label for="tags">Nick Name</label>
        <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars($record['tags']); ?>">
    </div>

    <!-- Type of Contact -->
    <div class="form-group">
        <label for="contact-type">Type of Contact</label>
        <select id="contact-type" name="contact_type" required>
            <option value="" disabled>Select type</option>
            <option value="Friend" <?php echo ($record['contact_type'] == 'Friend') ? 'selected' : ''; ?>>Friend</option>
            <option value="Relation" <?php echo ($record['contact_type'] == 'Relation') ? 'selected' : ''; ?>>Relation</option>
            <option value="Colleague" <?php echo ($record['contact_type'] == 'Colleague') ? 'selected' : ''; ?>>Colleague</option>
            <option value="Teacher" <?php echo ($record['contact_type'] == 'Teacher') ? 'selected' : ''; ?>>Teacher</option>
            <option value="Family" <?php echo ($record['contact_type'] == 'Family') ? 'selected' : ''; ?>>Family</option>
            <option value="Business" <?php echo ($record['contact_type'] == 'Business') ? 'selected' : ''; ?>>Business</option>
            <option value="Acquaintance" <?php echo ($record['contact_type'] == 'Acquaintance') ? 'selected' : ''; ?>>Acquaintance</option>
            <option value="Neighbor" <?php echo ($record['contact_type'] == 'Neighbor') ? 'selected' : ''; ?>>Neighbor</option>
            <option value="Mentor" <?php echo ($record['contact_type'] == 'Mentor') ? 'selected' : ''; ?>>Mentor</option>
            <option value="Client" <?php echo ($record['contact_type'] == 'Client') ? 'selected' : ''; ?>>Client</option>
            <option value="Other" <?php echo ($record['contact_type'] == 'Other') ? 'selected' : ''; ?>>Other</option>
        </select>
    </div>

    <!-- Submit Button -->
    <div class="form-group">
        <input type="submit" name="update" value="Update">
    </div>
</form>
</div>

   </body>
   </html>
   <?php
   }
   else{
    echo "something went wrong";
   }
>>>>>>> 86520faca08c813af9e50670f05bae37d3c343a3
   ?>