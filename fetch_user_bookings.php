<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Database connection
$conn = new mysqli("localhost", "root", "", "truck_rental");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit;
}

// Check username
if (!isset($_GET['username']) || empty($_GET['username'])) {
    echo json_encode(["status" => "error", "message" => "Username required"]);
    exit;
}

$username = $conn->real_escape_string($_GET['username']);

// Fetch bookings (no status)
$sql = "SELECT id, order_id, username, phoneno, truck_name, truck_style, truck_type, truck_color, price, address, start_date, end_date, start_time, end_time, created_at 
        FROM bookings 
        WHERE username='$username' 
        ORDER BY created_at DESC";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed: " . $conn->error]);
    exit;
}

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode([
    "status" => "success",
    "bookings" => $bookings
]);

$conn->close();
?>
