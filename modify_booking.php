<?php
include 'config.php';

$booking_id = $_POST['booking_id'];
$new_start_time = $_POST['new_start_time'];
$new_end_time = $_POST['new_end_time'];

$stmt = $conn->prepare("UPDATE bookings SET start_time = ?, end_time = ? WHERE booking_id = ?");
$stmt->bind_param("ssi", $new_start_time, $new_end_time, $booking_id);
if ($stmt->execute()) {
    echo "Booking updated successfully!";
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
?>
