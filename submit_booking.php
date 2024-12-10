<?php
session_start();
include 'config.php'; // Include your database connection setup

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $room = $_POST['room'];
    $date = $_POST['booking_date'];
    $checkin_time = $_POST['checkin_time'];
    $checkout_time = $_POST['checkout_time'];

    // Get room ID based on room name
    $sql_room = "SELECT room_id FROM rooms WHERE room_name = ?";
    $stmt_room = $conn->prepare($sql_room);
    $stmt_room->bind_param("s", $room);
    $stmt_room->execute();
    $stmt_room->bind_result($room_id);
    $stmt_room->fetch();
    $stmt_room->close();

    if ($room_id) {
        // Insert booking into the database
        $sql = "INSERT INTO bookings (user_id, room_id, booking_date, start_time, end_time, status)
                VALUES (?, ?, ?, ?, ?, 'Pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisss", $user_id, $room_id, $date, $checkin_time, $checkout_time);

        if ($stmt->execute()) {
            $_SESSION['booking_message'] = "Booking request submitted successfully.";
            header("Location: my_bookings.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
    $conn->close();
}
?>
