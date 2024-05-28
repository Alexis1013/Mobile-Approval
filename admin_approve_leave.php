<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session if it's not already active
}

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php"); // Redirect unauthorized users to login page
    exit();
}

// Include database connection
include 'connect.php';

// Handle form submission for leave request approval
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['leave_request_id'], $_POST['status'])) {
    $leave_request_id = intval($_POST['leave_request_id']);
    $status = $conn->real_escape_string($_POST['status']);
    
    $stmt = $conn->prepare("UPDATE leave_requests SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $leave_request_id);

    if ($stmt->execute()) {
        echo "<script>alert('Leave request updated successfully.'); window.location.href='admin_approve_leave.php';</script>";
    } else {
        echo "Error updating leave request: " . $conn->error;
    }

    $stmt->close();
}

// Fetch all leave requests
$result = $conn->query("SELECT lr.id, lr.user_id, lr.leave_type, lr.start_date, lr.end_date, lr.reason, lr.status FROM leave_requests lr");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Approve Leave Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        select, button {
            padding: 5px 10px;
            margin-top: 5px;
            margin-right: 10px;
        }
        button {
            background-color: rgb(245, 132, 38);
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: rgb(220, 110, 30);
        }
        a.back {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a.back:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="javascript:history.go(-1)" class="back">Back</a>
        <h2>Approve Leave Requests</h2>
        <?php
        if ($result) {
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Leave Type</th><th>Start Date</th><th>End Date</th><th>Reason</th><th>Status</th><th>Action</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['leave_type']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['start_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['end_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['reason']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td>";
                    echo "<form method='post' action='admin_approve_leave.php'>";
                    echo "<input type='hidden' name='leave_request_id' value='" . htmlspecialchars($row['id']) . "'>";
                    echo "<select name='status'>";
                    echo "<option value='Pending'" . ($row['status'] === 'Pending' ? ' selected' : '') . ">Pending</option>";
                    echo "<option value='Approved'" . ($row['status'] === 'Approved' ? ' selected' : '') . ">Approved</option>";
                    echo "<option value='Rejected'" . ($row['status'] === 'Rejected' ? ' selected' : '') . ">Rejected</option>";
                    echo "</select>";
                    echo "<button type='submit' name='approve'>Approve</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No leave requests found.";
            }
        } else {
            echo "Error fetching leave requests. Please try again.";
        }
        ?>
    </div>
</body>
</html>
