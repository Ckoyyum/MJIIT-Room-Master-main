<?php
session_start();

// Include database connection
include('db_connect.php'); 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Get user information from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT username, email FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);  // Bind the user_id parameter
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

// Get user's room bookings
$booking_query = "SELECT rooms.room_name, bookings.booking_date, bookings.start_time, bookings.end_time 
                  FROM bookings 
                  JOIN rooms ON bookings.room_id = rooms.room_id 
                  WHERE bookings.user_id = ?";
$booking_stmt = $conn->prepare($booking_query);
$booking_stmt->bind_param("i", $user_id);  // Bind the user_id parameter
$booking_stmt->execute();
$booking_result = $booking_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f9f9f9;
            color: #333;
        }

        header {
            background: #2a9d8f;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        header p {
            margin: 5px 0;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .back-button {
            display: inline-block;
            margin: 20px;
            padding: 10px 15px;
            background: #2a9d8f;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .back-button:hover {
            background: #21867a;
        }

        .content {
            padding: 20px;
        }

        .content h2 {
            margin-top: 0;
            color: #2a9d8f;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background: #2a9d8f;
            color: #fff;
        }

        table tr:hover {
            background: #f1f1f1;
        }

        footer {
            text-align: center;
            padding: 10px;
            background: #2a9d8f;
            color: #fff;
            position: absolute;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>

<header>
    <h1>User Profile</h1>
    <p>Welcome, <?php echo htmlspecialchars($user['username']); ?></p>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
</header>

<div class="container">
    <a href="home.php" class="back-button">← Back to Home</a>
    <div class="content">
        <h2>Your Room Bookings</h2>
        <?php if ($booking_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Room Name</th>
                        <th>Booking Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($booking = $booking_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['room_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                            <td><?php echo htmlspecialchars($booking['start_time']); ?></td>
                            <td><?php echo htmlspecialchars($booking['end_time']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No bookings found.</p>
        <?php endif; ?>
    </div>
</div>

<footer>
    &copy; <?php echo date("Y"); ?> RoomMaster. All Rights Reserved.
</footer>

</body>
</html>

<?php
// Close the database connection
$stmt->close();
$booking_stmt->close();
$conn->close();
?>
