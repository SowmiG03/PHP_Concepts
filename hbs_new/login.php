<?php
session_start();

// Database connection
include 'assets/conn.php';
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input fields
    if (empty($email) || empty($password)) {
        $error_msg = "Email and password are required!";
    } else {
        // Prepare SQL to fetch user details
        $sql = "SELECT user_id, username, email, password, role, department_id, school_id FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            // Check if the user exists
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id, $username, $conn_email, $conn_password, $role, $department_id, $school_id);
                $stmt->fetch();

                // Verify the password
                if (password_verify($password, $conn_password)) {
                    // Set session variables
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['email'] = $conn_email;
                    $_SESSION['role'] = $role;
                    $_SESSION['username'] = $username;
                    $_SESSION['department_id'] = $department_id;
                    $_SESSION['school_id'] = $school_id;

                    // Redirect based on user role
                    if ($role == 'hod' || $role == 'dean') {
                        header("Location: status_update.php");
                    } else {
                        header("Location: home.php");
                    }
                    exit();
                } else {
                    $error_msg = "Incorrect email or password!";
                }
            } else {
                $error_msg = "Incorrect email or password!";
            }

            // Close the statement
            $stmt->close();
        } else {
            // SQL preparation failed
            $error_msg = "Database query error: " . $conn->error;
        }
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Pondicherry University</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
        }

        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            margin: 80px auto;
        }

        .login-container h3 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .login-container .form-control {
            height: 45px;
            font-size: 14px;
        }


        .login-container p {
            margin-top: 15px;
            text-align: center;
        }

        .login-container p a {
            color: #007bff;
            text-decoration: none;
        }

        .login-container p a:hover {
            text-decoration: underline;
        }

        .alert {
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h3>Login</h3>
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-1">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_msg; ?>
                </div>
            <?php endif; ?><br>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </form>
    </div>
</body>
</html>
