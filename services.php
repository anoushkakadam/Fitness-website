<?php
session_start();

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

// Query to fetch data
$sql = "SELECT id, title, days, mins, image_url, url FROM services";
$result = $conn->query($sql);

// Initialize an array to store data
$services = [];

if ($result->num_rows > 0) {
    // Fetch all rows as an associative array
    while($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

$conn->close();

// Retrieve the message from the URL
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : "";

// Check if the user is logged in
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - Fitness Website</title>
    <link rel="stylesheet" href="services.css">
</head>
<body>
    <header>
        <nav>
            <a href="index2.html">Home</a>
            <a href="services.php" class="active">Services</a>
            <a href="about.html">About us</a>
        </nav>
    </header>
    <main>
        <?php if ($message): ?>
        <div class="message-container">
            <div class="message-box">
                <h1><?php echo $message; ?></h1>
            </div>
        </div>
        <?php endif; ?>
        <section class="services-list">
            <?php foreach ($services as $service): ?>
            <div class="service-item">
                <div class="service-image" style="background-image: url('<?php echo $service['image_url']; ?>');"></div>
                <?php if ($is_logged_in): ?>
                    <h2><a href="program.php?service_id=<?php echo $service['id']; ?>"><?php echo $service['title']; ?></a></h2>
                <?php else: ?>
                    <h2><?php echo $service['title']; ?></h2>
                <?php endif; ?>
                <p><?php echo $service['days']; ?> Days</p>
                <p><?php echo $service['mins']; ?> Mins</p>
                <a href="register.php?service_id=<?php echo $service['id']; ?>" style="display: block; margin: 15px 0;margin-left: 150px; margin-right: 150px; padding: 10px; background-color: #000; color: #fff; text-decoration: none; border-radius: 5px;">Register</a>
            </div>
            <?php endforeach; ?>
        </section>
    </main>
    <footer>
        <div class="contact">
            <h3>Wellness Synergy</h3>
            <p>Contact: 0987654321</p>
            <p>Email: wellnessynergy@gmail.com</p>
        </div>
        <div class="footer-text">
            <p>&copy; Anoushka Kadam 2024</p>
        </div>
    </footer>
</body>
</html>
