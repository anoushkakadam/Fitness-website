<?php
// login.php

session_start(); // Start the session at the beginning

// After validating the login credentials
$_SESSION['profile_picture'] = $user['profile_picture'];


// Database connection
$servername = "localhost"; // Update with your database server
$username = "root"; // Update with your database username
$password = ""; // Update with your database password
$dbname = "wellness_synergy"; // Update with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];

// Prepare and execute SQL statement
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verify password
    if (password_verify($password, $user['password'])) {
        // Store user ID in session
        $_SESSION['user_id'] = $user['id'];
        header("Location: index2.html"); // Redirect to index2 page
        exit(); // Ensure no further code is executed after redirection
    } else {
        echo "Invalid email or password.";
    }
} else {
    echo "No user found with that email.";
}

$stmt->close();
$conn->close();
?>
