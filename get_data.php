<?php
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

$user_id = 7; // Replace with the actual user ID

$workoutData = [];
$mealData = [];

// Fetch workout data
$result = $conn->query("SELECT calories_burned FROM fitness_data WHERE user_id = $user_id");
while ($row = $result->fetch_assoc()) {
    $workoutData[] = $row['calories_burned'];
}

// Fetch meal data
$result = $conn->query("SELECT meals_count FROM meal_data WHERE user_id = $user_id");
while ($row = $result->fetch_assoc()) {
    $mealData[] = $row['meals_count'];
}

$response = [
    'workouts' => $workoutData,
    'meals' => $mealData
];

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
