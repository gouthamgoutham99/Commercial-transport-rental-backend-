<?php
header("Content-Type: application/json; charset=UTF-8");
$host = "localhost";
$user = "root";
$pass = "";
$db = "truck_rental";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["status" => "fail", "message" => "Database connection error"]);
    exit;
}

$username = $_POST['username'] ?? '';

if ($username) {
    $stmt = $conn->prepare("SELECT username, phone, email FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            "status" => "success",
            "username" => $row["username"],
            "phoneno" => $row["phone"],
            "email" => $row["email"]
        ]);
    } else {
        echo json_encode(["status" => "fail", "message" => "User not found"]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "fail", "message " => "Username required"]);
}

$conn->close(); 
?>
