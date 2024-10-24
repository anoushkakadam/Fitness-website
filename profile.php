<?php
// profile.php

// Start session
session_start();

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

// Fetch user data
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="signup-container">
        <div class="signup-form">
            <h1>My Profile</h1>
            <?php if (!empty($user['profile_picture'])): ?>
            <div class="profile-picture">
                <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
            </div>
            <?php endif; ?>
            <form action="update_profile.php" method="post" enctype="multipart/form-data">
                <input type="file" name="profile_picture" accept="image/*">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" placeholder="Name" required>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Email" required>
                <input type="date" name="dob" value="<?php echo htmlspecialchars($user['birthdate']); ?>" placeholder="Birthdate" required>
                <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" placeholder="Address" required>
                <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone_number']); ?>" placeholder="Phone Number" required>
                <input type="number" name="height" value="<?php echo htmlspecialchars($user['height']); ?>" placeholder="Height" step="0.01" required>
                <button type="submit">Update Profile</button>
            </form>
        </div>
    </div>
</body>
</html>
