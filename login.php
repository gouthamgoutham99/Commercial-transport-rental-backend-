<?php
error_reporting(0); // Suppress all warnings/notices
session_start();
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$user = "root";
$pass = "";
$db = "truck_rental";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["status" => "fail", "message" => "Database error"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // --- Hardcoded admin login (works even if DB doesn't have admin) ---
    if ($username === 'admin' && $password === 'admin123') {
        echo json_encode([
            "status" => "success",
            "username" => "admin",
            "phoneno" => "9999999999"
        ]);
        exit;
    }

    // --- Regular user login from database ---
    $stmt = $conn->prepare("SELECT id, username, phone, password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            echo json_encode([
                "status" => "success",
                "username" => $row['username'],
                "phoneno" => $row['phone']
            ]);
        } else {
            echo json_encode(["status" => "fail", "message" => "Invalid password"]);
        }
    } else {
        echo json_encode(["status" => "fail", "message" => "User not found"]);
    }
    $stmt->close();
}

$conn->close();
?>
