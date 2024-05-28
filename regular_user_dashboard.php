<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!-- Add any additional styles or scripts specific to the user dashboard -->
    <!-- Example: Chart.js for interactive charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Add custom CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .chart-container {
            margin-bottom: 20px;
            text-align: center;
        }
        .navbar {
            background-color: rgb(245, 132, 38);
            overflow: hidden;
            margin-bottom: 20px;
        }
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        .navbar a.right {
            float: right;
        }
        /* Added design elements */
        .container {
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 24px;
            margin-bottom: 30px;
        }
        .chart-container {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .chart-container canvas {
            width: 100%;
            height: auto;
        }
        .task-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .task-list li {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .task-list li:last-child {
            margin-bottom: 0;
        }
        .task-list li:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="request_leave.php">Request Leave</a>
        <a href="login.php" class="right">Logout</a>
    </div>
    <div class="container">
        <h1>Welcome User!</h1>
        
        <!-- Example: Interactive Chart -->
        <div class="chart-container">
            <canvas id="userActivityChart" width="400" height="200"></canvas>
        </div>

    </div>

    <!-- Add any additional scripts for functionality -->
    <script>
        // Example: Interactive Chart (Replace with actual data)
        const userActivityData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'User Activity',
                data: [50, 60, 70, 80, 90, 100],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        const ctx = document.getElementById('userActivityChart').getContext('2d');
        const userActivityChart = new Chart(ctx, {
            type: 'line',
            data: userActivityData
        });
    </script>
</body>
</html>
