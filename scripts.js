document.addEventListener('DOMContentLoaded', () => {
    const workoutForm = document.getElementById('workout-form');
    const mealForm = document.getElementById('meal-form');
    const workoutGraphCtx = document.getElementById('workout-graph').getContext('2d');
    const mealGraphCtx = document.getElementById('meal-graph').getContext('2d');

    let workoutData = [];
    let mealData = [];

    const workoutChart = new Chart(workoutGraphCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Calories Burned',
                data: workoutData,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                fill: false
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const mealChart = new Chart(mealGraphCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Meals Count',
                data: mealData,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Fetch existing data from server
    fetch('get_data.php')
        .then(response => response.json())
        .then(data => {
            workoutData = data.workouts;
            mealData = data.meals;
            updateWorkoutGraph();
            updateMealGraph();
        });

    workoutForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const daysWorkedOut = document.getElementById('workout-days').value;
        if (daysWorkedOut) {
            const calorieBurn = daysWorkedOut * 500; // Assuming 500 calories burned per workout
            workoutData.push(calorieBurn);
            updateWorkoutGraph();
            // Send data to server
            fetch('save_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `workout_days=${daysWorkedOut}`
            });
        }
    });

    mealForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const mealsCount = document.getElementById('meals-count').value;
        if (mealsCount) {
            mealData.push(mealsCount);
            updateMealGraph();
            // Send data to server
            fetch('save_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `meals_count=${mealsCount}`
            });
        }
    });

    function updateWorkoutGraph() {
        workoutChart.data.labels = Array.from({ length: workoutData.length }, (_, i) => `Day ${i + 1}`);
        workoutChart.data.datasets[0].data = workoutData;
        workoutChart.update();
    }

    function updateMealGraph() {
        mealChart.data.labels = Array.from({ length: mealData.length }, (_, i) => `Day ${i + 1}`);
        mealChart.data.datasets[0].data = mealData;
        mealChart.update();
    }
});
