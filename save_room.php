<?php
include 'db.php';

$room_name = $_POST['room_name'];
$capacity = $_POST['capacity'];
$equipment = $_POST['equipment'];
$status = $_POST['status'];

$sql = "INSERT INTO rooms (room_name, capacity, equipment, status) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("siss", $room_name, $capacity, $equipment, $status);

if ($stmt->execute()) {
    echo "New room added successfully. <a href='admin_dashboard.php'>Back to Dashboard</a>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
