<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .navbar {
            background-color: rgb(245, 132, 38);
            overflow: hidden;
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
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* Two columns */
            gap: 20px;
        }
        h1 {
            color: #333333;
            text-align: center;
            margin-bottom: 30px;
        }
        .charts {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* Two columns */
            gap: 20px;
        }
        canvas {
            max-width: 100%;
            height: 150px; /* Adjusted height for a smaller size */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .approval-button {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        .approval-button button {
            padding: 10px 20px;
            background-color: rgb(245, 132, 38);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .approval-button button:hover {
            background-color: #ddd;
            color: black;
        }
        /* Notification bar styles */
        .notification {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            z-index: 999;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 10px;
            width: 80%;
            display: none;
        }
        .notification.show {
            display: block;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="admin_approve_leave.php">Approval</a>
    <a href="purchase_order_form.php">Purchase Orders</a> <!-- Link to Purchase Order Form -->
    <a href="logout.php" onclick="logout()">Logout</a> <!-- Changed href to "#" and added onclick event -->
</div>

<div class="container">
    <h1>Welcome Admin</h1>
    <div class="charts">
        <div>
            <canvas id="transactionChart"></canvas>
            <canvas id="categoryPieChart"></canvas>
        </div>
        <div>
            <canvas id="transactionComparisonChart"></canvas>
            <canvas id="monthlyRadarChart"></canvas>
        </div>
    </div>
</div>

<!-- Notification bars -->
<div id="leaveNotification" class="notification"></div>

<script>
    // Sample transaction data
    const transactionData = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June'],
        datasets: [{
            label: 'Transaction Amount',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
            data: [2000, 2500, 1800, 3000, 2200, 3500]
        }]
    };

    // Sample comparison transaction data
    const comparisonTransactionData = {
        labels: ['2021', '2022', '2023', '2024'],
        datasets: [{
            label: 'Yearly Transaction Amount',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1,
            data: [35000, 42000, 38000, 50000]
        }]
    };

    // Get the canvas elements
    const ctx = document.getElementById('transactionChart').getContext('2d');
    const ctxComparison = document.getElementById('transactionComparisonChart').getContext('2d');

    // Create the bar chart for monthly transactions
    const transactionChart = new Chart(ctx, {
        type: 'bar',
        data: transactionData,
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    // Create the line chart for yearly transaction comparison
    const transactionComparisonChart = new Chart(ctxComparison, {
        type: 'line',
        data: comparisonTransactionData,
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    // Sample category-wise transaction data for Pie Chart
    const categoryPieData = {
        labels: ['Food', 'Shopping', 'Transport', 'Utilities', 'Entertainment'],
        datasets: [{
            data: [30, 20, 15, 25, 10],
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)'
            ]
        }]
    };

    // Sample monthly transaction data for Radar Chart
    const monthlyRadarData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Transactions',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            pointBackgroundColor: 'rgba(54, 162, 235, 1)',
            data: [2000, 2500, 1800, 3000, 2200, 3500]
        }]
    };

    // Get the new canvas elements
    const ctxCategoryPie = document.getElementById('categoryPieChart').getContext('2d');
    const ctxMonthlyRadar = document.getElementById('monthlyRadarChart').getContext('2d');

    // Create the Pie Chart for category-wise transactions
    const categoryPieChart = new Chart(ctxCategoryPie, {
        type: 'pie',
        data: categoryPieData,
        options: {
            responsive: true,
            legend: {
                position: 'right',
                labels: {
                    fontSize: 12
                }
            }
        }
    });

    // Create the Radar Chart for monthly transactions
    const monthlyRadarChart = new Chart(ctxMonthlyRadar, {
        type: 'radar',
        data: monthlyRadarData,
        options: {
            scale: {
                ticks: {
                    beginAtZero: true
                }
            }
        }
    });

    // Function to show notification
    function showNotification(type, message) {
        const notification = document.getElementById(type + 'Notification');
        notification.textContent = message;
        notification.classList.add('show');
        setTimeout(function() {
            notification.classList.remove('show');
        }, 3000);
    }

    // Simulating the approval of leave request
    document.getElementById('leaveNotification').addEventListener('click', function() {
        showNotification('leave', 'Leave request approved!');
    });

    // Logout function
    function logout() {
        // Perform any logout actions here (e.g., clearing session, redirecting to login page)
        alert('You have been logged out.');
        window.location.href = "logout.html"; // Replace with the actual path to your logout page
    }
</script>

</body>
</html>
