<?php
session_start(); // Start the session

// Check if the user is logged in, if not redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Replace 'login.php' with your actual login page
    exit();
}

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: login.php"); // Redirect to login page after logout
    exit();
}

include 'config.php'; // Include the database connection
// Fetch all rooms from the database
$sql = "SELECT room_name, location, capacity, equipment, image, status FROM rooms";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to BookingSpace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Font Awesome link -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* General reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styling */
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('bg website.png'); 
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }

        /* Navbar Styling */
        .navbar {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.9);
            color: rgb(114, 4, 4);
            padding: 8px 20px;
            justify-content: space-between;
            width: 100%;
            border-bottom: 2px solid #8B0000;
            z-index: 10;
        }

        .navbar-title {
            display: flex;
            align-items: center;
        }

        .navbar-title img {
            max-height: 30px;
            margin-right: 10px;
        }

        .navbar-title p {
            font-weight: bold;
            font-size: 20px;
            margin: 0;
        }

        .navbar-links {
            display: flex;
            align-items: center;
        }

        .navbar-links a {
            color: rgb(119, 4, 4);
            text-decoration: none;
            margin-right: 20px;
            font-size: 14px;
        }

        .navbar-links a:hover {
            color: #ddd;
        }

        .navbar-profile i {
            font-size: 24px;
        }

        /* Logout Button Styling */
        .logout-button {
            background-color: #800000;
            color: white;
            border: none;
            padding: 8px 15px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
        }

        .logout-button:hover {
            background-color: #d43f3f;
        }

        /* Welcome Text Container */
        .welcome-text-container {
            background-color: #8B0000;
            color: white;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
            width: 75%;
        }

        /* Search Bar Container */
        .search-bar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            border: 2px solid #8B0000;
            border-radius: 10px;
            padding: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: 20px auto;
        }

        .search-bar-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-right: 1px solid #8B0000;
            flex: 2; /* Give more space */
        }

        .search-bar-item:last-child {
            border-right: none;
        }

        .search-bar-item input, .search-bar-item select {
            border: none;
            outline: none;
            width: 100%;
        }

        .search-bar-item input::placeholder {
            color: #6c757d;
        }

        .search-bar-item.small {
            flex: 1; /* Less space */
        }

        .search-button {
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #0056b3;
        }

        /* Rooms Container */
        .rooms-container {
            max-width: 900px;
            width: 75%;
            margin-left: auto;
            margin-right: auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
        }

        /* Original Room Card Styling */
        .room {
            background-color: white;
            color: black;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: stretch;
            overflow: hidden;
            transition: transform 0.3s ease;
            width: 100%;
            min-height: 300px;
            padding: 10px;
            box-sizing: border-box;
        }

        .room img {
            width: calc(100% - 20px);
            margin: 0 auto;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }

        .room-details {
            padding: 10px;
            text-align: left;
            flex-grow: 1;
        }

        .room-details h3 {
            font-size: 1.1em;
            font-weight: bold;
            color: rgb(114, 4, 4);
            margin-bottom: 5px;
        }

        .room-details p {
            font-size: 1em;
            margin-bottom: 5px;
        }

        .room:hover {
            transform: translateY(-5px);
        }

        /* Availability status */
        .room-status {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-left: 10px;
        }

        .available {
            background-color: green;
        }

        .not-available {
            background-color: red;
        }

        /* Button Styling */
        .btn-quick-book {
            background-color: white;
            color: #8B0000;
            border: 2px solid #8B0000;
            font-weight: bold;
        }

        .btn-quick-book:hover {
            background-color: #8B0000;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <!-- Navbar -->
<div class="navbar">
    <div class="navbar-title">
        <img src="UTM-LOGO-FULL.png" alt="UTM Logo">
        <img src="Mjiit RoomMaster logo.png" alt="MJIIT Logo">
        <p>BookingSpace</p>
    </div>
    <div class="navbar-links">
        <a href="#">Home</a>
        <a href="my_bookings.php">My Bookings</a>
        <a href="rooms.php">Rooms</a>
        <a href="#">Analytics</a>
        <a href="#">Help</a>
    </div>
    
    <!-- Logout Button -->
    <div class="logout-button-container">
        <a href="?logout=true" class="logout-button">Logout</a>
    </div>

    <div class="navbar-profile">
        <a href="profile.php"><i class="fa-solid fa-user"></i></a> <!-- Profile icon -->
    </div>
</div>


    <!-- Welcome Page -->
    <div class="welcome-container my-5">
        <!-- Welcome Text Container -->
        <div class="welcome-text-container mb-4">
            <h2>Welcome to BookingSpace</h2>
            <p>Efficiently manage and book rooms at MJIIT, Universiti Teknologi Malaysia.</p>
            <button class="btn btn-quick-book mb-3">Quick Book</button>
        </div>

        <!-- Search Bar Container -->
        <div class="search-bar-container">
            <div class="search-bar-item">
                <input type="text" placeholder="Room Name">
            </div>
            <div class="search-bar-item">
                <select>
                    <option>Check-in Time</option>
                    <option>08:00</option>
                    <option>09:00</option>
                    <option>10:00</option>
                </select>
                <select>
                    <option>Check-out Time</option>
                    <option>10:00</option>
                    <option>11:00</option>
                    <option>12:00</option>
                </select>
            </div>
            <div class="search-bar-item small">
                <button class="search-button">Search</button>
            </div>
        </div>

        <!-- Rooms Display -->
        <div class="rooms-container">
            <?php while($row = $result->fetch_assoc()): ?>
            <div class="room">
                <img src="<?php echo $row['image']; ?>" alt="Room Image">
                <div class="room-details">
                    <h3><?php echo $row['room_name']; ?></h3>
                    <p>Location: <?php echo $row['location']; ?></p>
                    <p>Capacity: <?php echo $row['capacity']; ?> people</p>
                    <p>Equipment: <?php echo $row['equipment']; ?></p>
                    <p>Status: <span class="room-status <?php echo ($row['status'] == 'available' ? 'available' : 'not-available'); ?>"></span></p>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

    </div>

</body>
</html>
