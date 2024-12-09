<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

fathiya
// Check if the form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];  // You should hash this password and compare against hashed password in DB

    // SQL to check the username and password, and get user role
    $sql = "SELECT user_id, email FROM users WHERE username = ? AND password = ?";  // Adjust this to use password hashing
=======
    // Check the database for the user
    $sql = "SELECT * FROM users WHERE username = ?";
 main
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
fathiya
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['user_id'];  // Set user_id in session

        // Determine user type based on the email domain
        if ($username === 'mjadmin') {
            header("Location: admin_dashboard.php");  // Redirect to admin dashboard
        } elseif (strpos($row['email'], '@graduate.utm.my') !== false) {
            header("Location: home.php");  // Redirect to home for UTM insiders
        } else {
            header("Location: guest_home.php");  // Redirect to guest home for other users
        }
        exit;

    if ($user) {
        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            header("Location: home.php");
            exit;
        } else {
            $error_message = "Invalid username or password.";
        }
 main
    } else {
        $error_message = "User not found.";
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
    <title>Sign In</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('./image/Background.jpeg'); /* Add your background image path */
            background-size: cover;
            background-position: center;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .navbar {
            background-color: #fff;
            padding: 10px 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: absolute;
            top: 0;
            width: 100%;
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
            color: #2a9d8f;
        }
        .profile-icon {
            color: #333;
            font-size: 24px;
            margin-right: 20px;
        }
        .form-container {
            background-color: #fff;
            max-width: 600px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
            opacity: 0.95;
        }
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
        .form-container input[type="text"],
        .form-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-container .button-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .form-container button {
            background-color: #2a9d8f;
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
        .error-message {
            color: red;
            text-align: center;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">
            <img src="./image/utmlogo.jpg" alt="UTM Logo">
            <img src="./image/MJIIT LOGO.jpg" alt="MJIIT Logo">
        </div>
        <div>
            <a href="#">Home</a>
            <a href="#">My Bookings</a>
            <a href="#">Rooms</a>
            <a href="#">Analytics</a>
            <a href="#">Help</a>
            <span class="profile-icon">&#128100;</span>
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <div class="form-header">
            <img src="./image/utmlogo.jpg" alt="UTM Logo" height="50px" width="100px">
            <img src="./image/MJIIT LOGO.jpg" alt="MJIIT Logo" height="50px" width="100px">
            <span>Malaysia-Japan International Institute of Technology</span>
        </div>
        <h1>Sign In</h1>
        <?php
        if (isset($error_message)) {
            echo "<p class='error-message'>$error_message</p>";
        }
        ?>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Enter your username" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <div class="button-container">
                <button type="submit">Login</button>
                <button type="button" onclick="location.href='guest_signup.php';">Guest Sign Up</button>
            </div>
        </form>
    </div>

</body>
</html>
