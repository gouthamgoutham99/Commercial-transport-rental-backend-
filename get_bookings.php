<?php
$conn = new mysqli("localhost", "root", "", "truck_rental");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM bookings ORDER BY id DESC");

$bookings = [];

while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

header('Content-Type: application/json');
echo json_encode($bookings);
?>
