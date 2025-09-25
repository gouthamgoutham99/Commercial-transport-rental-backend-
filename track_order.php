<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "truck_rental";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$orderId = $_GET['order_id'];

$sql = "SELECT status FROM bookings WHERE order_id = '$orderId'";
$result = $conn->query($sql);

$response = array();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response['status'] = $row['status'];
} else {
    $response['status'] = "not_found";
}

echo json_encode($response);

$conn->close();
?>
