<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
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

// Initialize message variable
$message = "";

// Handle registration
if (isset($_GET['service_id'])) {
    $service_id = $_GET['service_id'];
    $user_id = $_SESSION['user_id']; // Get the logged-in user ID

    // Check if the user is already registered for the service
    $check_sql = "SELECT * FROM registrations WHERE service_id='$service_id' AND user_id='$user_id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        $message = "You are already registered for this service!";
    } else {
        $sql = "INSERT INTO registrations (service_id, user_id) VALUES ('$service_id', '$user_id')";

        if ($conn->query($sql) === TRUE) {
            $message = "You have successfully registered!";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
} else {
    $message = "Service ID is missing.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Wellness Synergy</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('./woman-doing-workout.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .message-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.6);
        }
        .message-box {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }
        .message-box h1 {
            margin-bottom: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <div class="message-box">
            <h1><?php echo htmlspecialchars($message); ?></h1>
        </div>
    </div>
</body>
</html>
