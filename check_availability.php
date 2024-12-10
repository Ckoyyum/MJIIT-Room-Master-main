<?php
include 'config.php';

$room_id = $_POST['room_id'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];

$stmt = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE room_id = ? AND status = 'Confirmed' AND NOT (end_time <= ? OR start_time >= ?)");
$stmt->bind_param("iss", $room_id, $start_time, $end_time);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

echo json_encode(['available' => $count == 0]);
?>
