<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        #errorMessages {
    color: red;
    font-weight: bold;
}
    </style>
</head>
<body>

<div class="form-container">
        <h2>User Information Form</h2>
        <div class="button-container">
        <button><a href="create.php">View Contact</a></button>
        <button><a href="create.php">Update Or Delete Contact</a></button>
    </div>
        <form method="post" action="insert.php" id="registrationForm">
        <div id="errorMessages"></div>
            <!-- First Name -->
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" required placeholder="Enter your first name">
            </div>

            <!-- Last Name -->
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" required placeholder="Enter your last name">
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>

            <!-- Phone Number -->
            <div class="form-group">
                <label for="phonenumber">Phone Number</label>
                <input type="tel" id="phonenumber" name="phonenumber" required placeholder="Enter your phone number">
            </div>

            <!-- Date of Birth -->
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" required>
            </div>

            <div class="form-group">
    <label>Gender:</label>
    <div class="gender-options">
       
            <input type="radio" id="gender-female" name="gender" value="female" required> Female
      
       
            <input type="radio" id="gender-male" name="gender" value="male" required> Male
      
        
            <input type="radio" id="gender-transgender" name="gender" value="transgender" required> Transgender
      
    </div>
</div>


            <!-- Address -->
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="3" required placeholder="Enter your address"></textarea>
            </div>

            <!-- Tags/Labels -->
            <div class="form-group">
                <label for="tags">Nick Name</label>
                <input type="text" id="tags" name="tags" placeholder="Enter nick names">
            </div>

            <!-- Type of Contact -->
            <div class="form-group">
                <label for="contact-type">Type of Contact</label>
                <select id="contact-type" name="contact_type" required>
                    <option value="" disabled selected>Select type</option>
                    <option value="friend">Friend</option>
                    <option value="relation">Relation</option>
                    <option value="colleague">Colleague</option>
                    <option value="teacher">Teacher</option>
                    <option value="family">Family</option>
                    <option value="business">Business</option>
                    <option value="acquaintance">Acquaintance</option>
                    <option value="neighbor">Neighbor</option>
                    <option value="mentor">Mentor</option>
                    <option value="client">Client</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <input type="submit" value="Submit" name="submitbtn">
            </div>
        </form>
      
    </div>
    
    <script>
document.getElementById("registrationForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent default form submission to run validation checks first

    // Get form values
    const firstname = document.getElementById("firstname").value;
    const lastname = document.getElementById("lastname").value;
    const email = document.getElementById("email").value;
    const errorMessages = document.getElementById("errorMessages");

    // Clear any previous error messages
    errorMessages.innerHTML = "";

    // Validate uname: Must contain both uppercase and lowercase letters and be at least 6 characters long
    const fnamePattern = /^[a-zA-Z]{6,}$/;
if (!fnamePattern.test(firstname)) {
    errorMessages.innerHTML += "<p> First name must contain only alphabetic characters and be more than 5 characters long.</p>";
}
const lnamePattern = /[a-zA-Z]/;
if (!lnamePattern.test(lastname)) {
    errorMessages.innerHTML += "<p>Last name must contain at least one alphabetic character.</p>";
}


    // Validate email: General pattern for a valid email address
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email)) {
        errorMessages.innerHTML += "<p>Please enter a valid email address.</p>";
    }

    // If no error messages, form is valid, and we can proceed with submission
    if (errorMessages.innerHTML === "") {
        // Create a hidden input to include the submit button name
        const hiddenInput = document.createElement("input");
        hiddenInput.setAttribute("type", "hidden");
        hiddenInput.setAttribute("name", "submitbtn");
        hiddenInput.setAttribute("value", "Submit");

        // Append hidden input to form
        document.getElementById("registrationForm").appendChild(hiddenInput);

        // Programmatically submit the form
        document.getElementById("registrationForm").submit();
    }
});

</script>
</body>

   </html>