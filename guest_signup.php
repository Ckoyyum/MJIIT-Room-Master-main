<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords
    if ($password !== $confirm_password) {
        echo "<p style='color:red;'>Passwords do not match!</p>";
        exit;
    }

    // Hash the password entered by the guest
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the guest into the database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Account created successfully! <a href='login.php'>Login here</a></p>";
    } else {
        if ($stmt->errno === 1062) { // Duplicate entry
            echo "<p style='color:red;'>Username or email already exists!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Sign Up</title>
    <style>
        /* Background Image Styling */
        body {
            font-family: Arial, sans-serif;
            background-image: url('image/Background.jpeg'); /* Replace with the actual background image URL */
            background-size: cover;
            background-position: center;
            margin: 0;
        }

        /* Header Navigation Bar */
        .navbar {
            background-color: #fff;
            padding: 10px 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .navbar .logo {
            display: flex;
            align-items: center;
        }

        .navbar img {
            height: 50px;
            margin-right: 10px;
        }

        .navbar a {
            text-decoration: none;
            color: #333;
            margin: 0 10px;
            font-weight: bold;
        }

        .navbar a:hover {
            color: #2a9d8f; /* Teal hover color */
        }

        .profile-icon {
            color: #333;
            font-size: 24px;
            margin-right: 20px;
        }

        /* Centered Form Container */
        .form-container {
            background-color: #fff;
            max-width: 600px; /* Increased width */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
            margin: 100px auto;
            opacity: 0.95;
        }

        /* Form Header Styling */
        .form-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .form-header img {
            height: 50px;
            margin-right: 10px;
        }

        .form-header span {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        /* Input Fields */
        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        /* Sign Up Button */
        .form-container button {
            background-color: #2a9d8f; /* Teal button color */
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #21867a;
        }

        /* Footer Links */
        .footer-links {
            margin-top: 10px;
        }

        .footer-links a {
            color: #2a9d8f;
            text-decoration: none;
            font-size: 14px;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        .back-button {
            display: block;
            margin: 20px 0;
            color: #2a9d8f;
            text-decoration: none;
            font-size: 14px;
        }

        .back-button:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Header Section with Navbar -->
    <div class="navbar">
        <div class="logo">
            <img src="UTM-LOGO-FULL.png" alt="UTM Logo">
            <img src="Mjiit RoomMaster logo.png" alt="MJIIT Logo">
        </div>
        <div>
            <a href="#">Home</a>
            <a href="#">My Bookings</a>
            <a href="#">Rooms</a>
            <a href="#">Analytics</a>
            <a href="#">Help</a>
            <span class="profile-icon">&#128100;</span> <!-- Unicode character for profile icon -->
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <div class="form-header">
            <img src="UTM-LOGO-FULL.png" alt="UTM Logo" height="50px" width="100px"> <!-- Replace with actual UTM logo URL -->
            <img src="Mjiit RoomMaster logo.png" alt="MJIIT Logo"> <!-- Replace with actual MJIIT logo URL -->
            <span>Malaysia-Japan International Institute of Technology</span>
        </div>
        <h1>Sign Up</h1>
        <form action="" method="POST">
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
            <button type="submit">Sign Up</button>
        </form>
        <a href="login.php" class="back-button">Already have an account? Login here</a>
    </div>

</body>
</html>
