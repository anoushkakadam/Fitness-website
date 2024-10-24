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
    die("Connection failed: ". $conn->connect_error);
}

// Get user ID
$user_id = $_SESSION['user_id'];

// Fetch registered services
$sql = "SELECT s.id, s.title, s.image_url, s.url, s.days, s.mins
        FROM services s
        JOIN registrations r ON s.id = r.service_id
        WHERE r.user_id = '$user_id'";

$result = $conn->query($sql);
$services = $result->fetch_all(MYSQLI_ASSOC);

// Update progress data in the database
if (isset($_GET['service_id'])) {
    $service_id = $_GET['service_id'];
    $sql = "SELECT * FROM user_progress WHERE user_id = '$user_id' AND service_id = '$service_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $sql = "UPDATE user_progress SET progress = progress + 1 WHERE user_id = '$user_id' AND service_id = '$service_id'";
    } else {
        $sql = "INSERT INTO user_progress (user_id, service_id, progress) VALUES ('$user_id', '$service_id', 1)";
    }

    $conn->query($sql);
}

$conn->close();
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
        <section class="services-list">
            <?php foreach ($services as $service):?>
            <div class="service-item">
                <div class="service-image" style="background-image: url('<?php echo $service['image_url'];?>');"></div>
                <h2><a href="program.php?service_id=<?php echo $service['id'];?>"><?php echo $service['title'];?></a></h2>
                <p><?php echo $service['days'];?> Days</p>
                <p><?php echo $service['mins'];?> Mins</p>
                <a href="Finish.php?service_id=<?php echo $service['id'];?>" style="display: block; margin: 15px 0; margin-left: 150px; margin-right: 150px; padding: 10px; background-color: #000; color: #fff; text-decoration: none; border-radius: 5px;">Finish</a>
            </div>
            <?php endforeach;?>
        </section>
    </main>
</body>
</html>