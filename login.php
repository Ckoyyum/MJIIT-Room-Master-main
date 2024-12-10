<?php
session_start();
include 'db_connect.php'; // Ensure this connects to your database properly

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Query to check if the user is an admin
    $sql_admin = "SELECT * FROM admins WHERE username = ?";
    $stmt_admin = $conn->prepare($sql_admin);

    if ($stmt_admin === false) {
        die('MySQL prepare error: ' . $conn->error);
    }

    $stmt_admin->bind_param("s", $username);
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();
    $admin = $result_admin->fetch_assoc();

    // Query to check if the user is a regular user
    $sql_user = "SELECT * FROM users WHERE username = ?";
    $stmt_user = $conn->prepare($sql_user);

    if ($stmt_user === false) {
        die('MySQL prepare error: ' . $conn->error);
    }

    $stmt_user->bind_param("s", $username);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $user = $result_user->fetch_assoc();

    // If the user is an admin, validate admin credentials
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['username'] = $admin['username'];
        $_SESSION['role'] = 'admin';
        $_SESSION['user_id'] = $admin['admin_id'];

        // Redirect to the admin dashboard
        header("Location: admin_dashboard.php");
        exit();
    }

    // If the user is a regular user, validate user credentials
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = 'user';
        $_SESSION['user_id'] = $user['user_id'];

        // Redirect to the user home page
        header("Location: home.php");
        exit();
    }

    // If neither matches, show an error message
    $error_message = "Invalid username or password.";

    $stmt_admin->close();
    $stmt_user->close();
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
            background-image: url('./image/Background.jpeg'); 
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
        .navbar .logo img {
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
        .form-header img {
            height: 50px;
            margin-right: 10px;
        }
        .form-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .form-container button {
            background-color: #2a9d8f;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
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
            <span class="profile-icon">&#128100;</span>
        </div>
    </div>
    <div class="form-container">
        <div class="form-header">
            <img src="UTM-LOGO-FULL.png" alt="UTM Logo">
            <img src="Mjiit RoomMaster logo.png" alt="MJIIT Logo">
            <span>Malaysia-Japan International Institute of Technology</span>
        </div>
        <h1>Sign In</h1>
        <?php
        if (!empty($error_message)) {
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
