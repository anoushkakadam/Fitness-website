<?php
// signup.php

// Database connection
$servername = "localhost"; // Update with your database server
$username = "root"; // Update with your database username
$password = ""; // Update with your database password
$dbname = "wellness_synergy"; // Update with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$dob = $_POST['dob'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$height = $_POST['height'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Basic validation
if ($password !== $confirm_password) {
    echo "Passwords do not match.";
    exit;
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare and execute SQL statement
$sql = "INSERT INTO users (name, email, password, birthdate, address, phone_number, height) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $name, $email, $hashed_password, $dob, $address, $phone, $height);

if ($stmt->execute()) {
    header("Location: login.html"); // Redirect to login page
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
