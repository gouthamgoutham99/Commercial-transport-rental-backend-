<?php
header("Content-Type: application/json");

// Database connection
$host = "localhost";   // Change if needed
$user = "root";        // Your DB username
$pass = "";            // Your DB password
$db   = "truck_rental"; // Your DB name

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection failed: " . $conn->connect_error]));
}

// Get POST values safely
$username    = $_POST['username']    ?? '';
$phoneno     = $_POST['phoneno']     ?? '';
$truck_name  = $_POST['truck_name']  ?? '';
$truck_style = $_POST['truck_style'] ?? '';
$truck_type  = $_POST['truck_type']  ?? '';
$truck_color = $_POST['truck_color'] ?? '';
$price       = $_POST['truck_price'] ?? '';
$address     = $_POST['address']     ?? '';
$start_date  = $_POST['start_date']  ?? '';
$end_date    = $_POST['end_date']    ?? '';
$start_time  = $_POST['pickup_time'] ?? '';
$end_time    = $_POST['drop_time']   ?? '';

// ✅ Convert date to YYYY-MM-DD
if (!empty($start_date)) {
    $start_date = date('Y-m-d', strtotime($start_date));
}
if (!empty($end_date)) {
    $end_date = date('Y-m-d', strtotime($end_date));
}

// ✅ Validate required fields
if (empty($username) || empty($phoneno) || empty($truck_name) || empty($price)) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

// ✅ Generate unique order_id (ORD + date + padded id)
$result = $conn->query("SELECT IFNULL(MAX(id),0) + 1 AS next_id FROM bookings");
$row = $result->fetch_assoc();
$nextId = $row['next_id'];
$order_id = "ORD" . date("Ymd") . str_pad($nextId, 3, "0", STR_PAD_LEFT);

// ✅ Insert query using prepared statement
$stmt = $conn->prepare("INSERT INTO bookings 
    (order_id, username, phoneno, truck_name, truck_style, truck_type, truck_color, price, address, start_date, end_date, start_time, end_time, created_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

$stmt->bind_param(
    "sssssssssssss",
    $order_id,
    $username,
    $phoneno,
    $truck_name,
    $truck_style,
    $truck_type,
    $truck_color,
    $price,
    $address,
    $start_date,
    $end_date,
    $start_time,
    $end_time
);

// ✅ Execute & check
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Booking saved successfully", "order_id" => $order_id]);
} else {
    echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
