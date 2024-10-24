<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wellness_synergy";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$service_id = isset($_GET['service_id']) ? intval($_GET['service_id']) : 0;
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
$progress = 0;

if ($service_id > 0) {
    $stmt = $conn->prepare("SELECT title FROM services WHERE id =?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $service = $result->fetch_assoc();
        $service_name = $service['title'];
    }
    $stmt->close();

    $stmt = $conn->prepare("SELECT day, task, video_url_1, video_url_2, video_url_3, thumbnail_url_1, thumbnail_url_2, thumbnail_url_3 FROM program_one WHERE service_id =?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $program = $result->fetch_all(MYSQLI_ASSOC);
    }
    $stmt->close();

    // Add the code here
    $progress = 0;

    if ($user_id > 0) {
        $stmt = $conn->prepare("SELECT progress FROM user_progress WHERE user_id =? AND service_id =?");
        $stmt->bind_param("ii", $user_id, $service_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user_progress = $result->fetch_assoc();
            $progress = $user_progress['progress'];
        }
        $stmt->close();
    }
}

$conn->close();

$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenge Schedule</title>
    <link rel="stylesheet" href="program.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        header {
            background-color: #fff;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        nav {
            display: flex;
            justify-content: flex-start;
        }
        nav a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            margin-right: 20px;
            transition: color 0.3s;
        }
        nav a:hover {
            color: #333;
        }
        .progress-container {
            width: 100%;
            background-color: #ddd;
            margin: 20px 0;
        }
        .progress-bar {
            width: 0%;
            height: 25px;
            background-color: #4caf50;
            text-align: center;
            color: white;
            line-height: 25px;
            border-radius: 5px;
        }
        main {
            padding: 20px;
        }
        .challenge {
            max-width: 800px;
            margin: 0 auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            margin: 0 0 20px 0;
            padding: 0;
            color: #333;
        }
        .schedule {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .day {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .day h3 {
            margin: 0 0 15px 0;
            color: #555;
        }
        .tasks {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .task {
            background-color: #ddd;
            height: auto;
            padding: 10px;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .task p {
            margin: 0;
        }
        .task a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            transition: color 0.3s;
        }
        .task a:hover {
            color: #0056b3;
        }
        .videos {
            display: flex;
            gap: 150px;
        }
        .video {
            flex: 1;
            text-align: center;
        }
        .video img {
            max-width: 150px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .video p {
            margin-top: 10px;
        }
        .video a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="index2.html">Home</a>
            <a href="services.php">Services</a>
            <a href="about.html">About us</a>
        </nav>
    </header>
    <main>
    <!-- <div class="progress-container">
    <div class="progress-bar" id="progress-bar"><?php echo htmlspecialchars($progress) . '%'; ?></div>
     </div> -->

        <section class="challenge">
            <h1><?php echo htmlspecialchars($service_name); ?></h1>
            <h2>Full Schedule</h2>
            <div class="schedule">
                <?php if (!empty($program)): ?>
                    <?php foreach ($program as $day_program): ?>
                    <div class="day">
                        <h3>Day <?php echo htmlspecialchars($day_program['day']); ?></h3>
                        <div class="tasks">
                            <div class="task">
                                <div class="videos">
                                    <?php if (!empty($day_program['thumbnail_url_1'])): ?>
                                    <div class="video">
                                        <a href="<?php echo htmlspecialchars($day_program['video_url_1']); ?>" target="_blank" class="video-link" data-video-url="<?php echo htmlspecialchars($day_program['video_url_1']); ?>">
                                            <img src="<?php echo htmlspecialchars($day_program['thumbnail_url_1']); ?>" alt="Thumbnail 1">
                                        </a>
                                        <p><a href="<?php echo htmlspecialchars($day_program['video_url_1']); ?>" target="_blank">Warm Up</a></p>
                                    </div>
                                    <?php endif; ?>
                                    <?php if (!empty($day_program['thumbnail_url_2'])): ?>
                                    <div class="video">
                                        <a href="<?php echo htmlspecialchars($day_program['video_url_2']); ?>" target="_blank" class="video-link" data-video-url="<?php echo htmlspecialchars($day_program['video_url_2']); ?>">
                                            <img src="<?php echo htmlspecialchars($day_program['thumbnail_url_2']); ?>" alt="Thumbnail 2">
                                        </a>
                                        <p><a href="<?php echo htmlspecialchars($day_program['video_url_2']); ?>" target="_blank">Workout</a></p>
                                    </div>
                                    <?php endif; ?>
                                    <?php if (!empty($day_program['thumbnail_url_3'])): ?>
                                    <div class="video">
                                        <a href="<?php echo htmlspecialchars($day_program['video_url_3']); ?>" target="_blank" class="video-link" data-video-url="<?php echo htmlspecialchars($day_program['video_url_3']); ?>">
                                            <img src="<?php echo htmlspecialchars($day_program['thumbnail_url_3']); ?>" alt="Thumbnail 3">
                                        </a>
                                        <p><a href="<?php echo htmlspecialchars($day_program['video_url_3']); ?>" target="_blank">Cool Down</a></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <!-- <p><?php echo htmlspecialchars($day_program['task']); ?></p> -->
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No schedule found for this service.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <!-- <script>
        document.addEventListener('DOMContentLoaded', function () {
    var progressBar = document.getElementById('progress-bar');
    var videoLinks = document.querySelectorAll('.video-link');
    var totalVideos = videoLinks.length;

    fetch('/get_progress.php?service_id=')
 .then(response => response.json())
 .then(data => {
        console.log('Got progress:', data);
        var progress = data.progress;
        progressBar.style.width = (progress / totalVideos) * 100 + '%';
        progressBar.textContent = Math.round((progress / totalVideos) * 100) + '%';

        videoLinks.forEach((link, index) => {
            link.addEventListener('click', function () {
                console.log('Clicked on video link', index);
                if (index >= progress) {
                    progress++;
                    console.log('Updating progress to', progress);
                    fetch('/save_progress.php?service_id=', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ progress: progress })
                    })
                 .then(response => response.json())
                 .then(data => console.log('Progress saved:', data))
                 .catch(error => console.error('Error:', error));
                }

                progressBar.style.width = (progress / totalVideos) * 100 + '%';
                progressBar.textContent = Math.round((progress / totalVideos) * 100) + '%';
            });
        });
    })
 .catch(error => console.error('Error:', error));
}); -->
    </script>
</body>
</html>
