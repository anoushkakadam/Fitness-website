<?php
// save_data.php

// Start the session
session_start();

// Database connection
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "wellness_synergy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user ID from session
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

// Save workout data
if (isset($_POST['workout_days'])) {
    $workout_days = intval($_POST['workout_days']);
    $sql = "INSERT INTO fitness_data (user_id, workout_days) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $workout_days);
    $stmt->execute();
}

// Save meal data
if (isset($_POST['meals_count'])) {
    $meals_count = intval($_POST['meals_count']);
    $sql = "INSERT INTO meal_data (user_id, meals_count) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $meals_count);
    $stmt->execute();
}

$conn->close();
?>
