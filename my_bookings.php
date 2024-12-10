<?php
session_start(); // Start the session to access session variables
include 'config.php'; // Include the database connection setup

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please <a href='login.php'>login</a> to view your bookings.";
    exit;
}

// Assuming `user_id` is stored in the session upon login
$user_id = $_SESSION['user_id'];

// Fetch bookings from the database
$sql = "SELECT b.*, r.room_name 
        FROM bookings b
        JOIN rooms r ON b.room_id = r.room_id
        WHERE b.user_id = ? 
        ORDER BY b.booking_date DESC, b.start_time DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
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

        /* Header Styling */
        .booking-header {
            background-color: #8B0000;
            color: white;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            width: 80%;
            margin-left: auto; /* Centering */
            margin-right: auto; /* Centering */
        }

        /* Booking List Container */
        .my-booking-container {
            margin-top: 50px;
            padding: 20px;
            background-color: white;
            border-radius: 6px;
            width: 75%;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Booking List Styling */
        .booking-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .booking-item {
            background-color: #e0e0e0;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .booking-details {
            flex-grow: 1;
            margin-right: 20px;
        }

        .booking-item h3 {
            color: #000000;
            margin-bottom: 5px;
            padding-bottom: 5px;
        }

        .booking-item p {
            margin: 5px 0;
        }

        .status-confirmed {
            color: green;
            font-weight: bold;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .status-rejected {
            color: red;
            font-weight: bold;
        }

        .cancel-btn {
            background-color: #8B0000;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            margin-left: auto;
        }

        .cancel-btn:hover {
            background-color: #5f2a1e;
        }

        /* Button Styling */
        .btn-book-new {
            background-color: white;
            color: #8B0000;
            border: 2px solid #8B0000;
            font-weight: bold;
        }

        .btn-book-new:hover {
            background-color: #8B0000;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-title">
            <img src="UTM-LOGO-FULL.png" alt="UTM Logo">
            <img src="Mjiit RoomMaster logo.png" alt="MJIIT Logo">
            <p>BookingSpace</p>
        </div>
        <div class="navbar-links">
            <a href="home.php">Home</a>
            <a href="#">My Bookings</a>
            <a href="rooms.php">Rooms</a>
            <a href="#">Analytics</a>
            <a href="#">Help</a>
        </div>
        <div class="navbar-profile">
            <i class="fa-regular fa-user"></i>
        </div>
    </div>

    <!-- Main Booking Section -->
    <div class="container my-5">
        <div class="booking-header mb-4">
            <h2>My Bookings</h2>
            <p>You may find the status of your bookings here</p>
            <button class="btn btn-book-new mb-3">Book New</button>
        </div>

        <!-- My Bookings Section -->
        <div class="my-booking-container">
            <h4 style="text-align: left; color: #000000; margin-bottom: 20px;">My Bookings</h4>
            <div class="booking-list">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '
                        <div class="booking-item">
                            <div class="booking-details">
                                <h5>' . htmlspecialchars($row['room_name']) . '</h5>
                                <p><strong>Date:</strong> ' . htmlspecialchars($row['booking_date']) . '</p>
                                <p><strong>Time:</strong> ' . htmlspecialchars($row['start_time']) . ' - ' . htmlspecialchars($row['end_time']) . '</p>
                                <p><strong>Status:</strong> <span class="' . (htmlspecialchars($row['status']) === 'Confirmed' ? 'status-confirmed' : (htmlspecialchars($row['status']) === 'Pending' ? 'status-pending' : 'status-rejected')) . '">' . htmlspecialchars($row['status']) . '</span></p>
                            </div>
                            <a href="cancel_booking.php?id=' . htmlspecialchars($row['booking_id']) . '" class="cancel-btn" onclick="return confirm(\'Are you sure you want to cancel this booking?\')">Cancel</a>
                        </div>';
                    }
                } else {
                    echo '<p>You have no bookings at the moment.</p>';
                }
                $conn->close();
                ?>
            </div>
        </div>
    </div>

</body>
</html>
