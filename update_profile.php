<?php
// Start session
session_start();

$_SESSION['profile_picture'] = $profile_picture_path;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wellness_synergy";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Get user input
$name = $_POST['name'];
$email = $_POST['email'];
$dob = $_POST['dob'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$height = $_POST['height'];

// Handle profile picture upload
$profile_picture = null;
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    $uploaded_file = $upload_dir . basename($_FILES['profile_picture']['name']);
    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploaded_file)) {
        $profile_picture = $uploaded_file;
    } else {
        echo "Error uploading profile picture.";
        exit();
    }
}

// Update user data in the database
$sql = "UPDATE users SET name = ?, email = ?, birthdate = ?, address = ?, phone_number = ?, height = ?, profile_picture = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssi", $name, $email, $dob, $address, $phone, $height, $profile_picture, $user_id);
if ($stmt->execute()) {
    header("Location: profile.php?success=1");
} else {
    echo "Error updating profile: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
