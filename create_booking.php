<?php
include 'config.php';

$room_id = $_POST['room_id'];
$user_id = $_POST['user_id']; // Assume user_id is fetched from session or other auth mechanism
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];

// Check availability first
$availabilityCheck = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE room_id = ? AND status = 'Confirmed' AND NOT (end_time <= ? OR start_time >= ?)");
$availabilityCheck->bind_param("iss", $room_id, $start_time, $end_time);
$availabilityCheck->execute();
$availabilityCheck->bind_result($isAvailable);
$availabilityCheck->fetch();
$availabilityCheck->close();

if ($isAvailable == 0) {
    $stmt = $conn->prepare("INSERT INTO bookings (room_id, user_id, start_time, end_time, status) VALUES (?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("iiss", $room_id, $user_id, $start_time, $end_time);
    if ($stmt->execute()) {
        echo "Booking created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Room not available for the selected time.";
}
?>
