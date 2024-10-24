<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.html");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root"; // Update with your database username
$password = "";     // Update with your database password
$dbname = "wellness_synergy"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID and service ID
$user_id = $_SESSION['user_id'];
$service_id = $_GET['service_id'];

if ($service_id) {
    // Remove the service from registrations
    $sql = "DELETE FROM registrations WHERE service_id = '$service_id' AND user_id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        // Redirect to services page with a success message
        header("Location: services.php?message=Service%20Completed%20successfully");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Service ID is missing.";
}

$conn->close();
?>
