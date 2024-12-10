<?php
session_start();
include 'config.php'; // Include your database connection

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $room = $_POST['room'];
    $date = $_POST['date'];
    $start_time = $_POST['startTime'];
    $end_time = $_POST['endTime'];
    
    // Insert booking request into the database
    $sql = "INSERT INTO bookings (user_id, room_id, booking_date, start_time, end_time, status)
            VALUES (?, (SELECT room_id FROM rooms WHERE room_name = ?), ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $user_id, $room, $date, $start_time, $end_time);
    
    if ($stmt->execute()) {
        header("Location: my_bookings.php?message=Booking request submitted successfully.");
    } else {
        echo "Error: " . $stmt->error;
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
    <title>Book a Room - MJIIT RoomMaster</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        label {
            display: block;
            font-weight: bold;
            margin-top: 10px;
        }

        input[type="text"], input[type="date"], input[type="time"], select {
            width: 100%; /* Ensures full width */
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Ensures consistent width */
        }

        button {
            width: 100%; /* Ensures full width */
            padding: 10px;
            background: #8B0000;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s;
            box-sizing: border-box; /* Ensures consistent width */
        }

        button:hover {
            background: #B22222;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Book a Room</h2>
        <form id="bookingForm" action="book_room.php" method="POST">
            <label for="room">Select Room</label>
            <select id="room" name="room" required>
                <option value="Bilik Kuliah 10">Bilik Kuliah 10</option>
                <option value="Seminar Room">Seminar Room</option>
                <option value="Bilik Kuliah 02">Bilik Kuliah 02</option>
                <!-- Add more rooms as needed -->
            </select>

            <label for="date">Date</label>
            <input type="date" id="date" name="date" required>

            <label for="startTime">Start Time</label>
            <input type="time" id="startTime" name="startTime" required>

            <label for="endTime">End Time</label>
            <input type="time" id="endTime" name="endTime" required>

            <button type="submit">Confirm Booking</button>
        </form>
    </div>
</body>
</html>
