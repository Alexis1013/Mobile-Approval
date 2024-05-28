<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect unauthorized users to login page
    exit();
}

// Include database connection
include 'connect.php';

// Check if an approval action is submitted by admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve']) && $_SESSION['user_type'] === 'admin') {
    $leave_request_id = $_POST['leave_request_id'];
    $status = $_POST['status'];

    // Update leave request status in database
    $stmt = $conn->prepare("UPDATE leave_requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $leave_request_id);

    if ($stmt->execute()) {
        $status_notification = "Leave request status updated successfully.";
    } else {
        $status_notification = "Error updating leave request status. Please try again.";
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
    <title>Leave Request</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('metsBG.jpg'); /* Add your background image URL */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .navbar {
            background-color: rgb(245, 132, 38);
            overflow: hidden;
            padding-left: 20px;
            padding-right: 20px;
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
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group select,
        .form-group textarea {
            width: calc(100% - 40px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group textarea {
            resize: vertical;
        }
        .form-group button {
            width: calc(100% - 40px);
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-sizing: border-box;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .status-notification {
            text-align: center;
            margin-bottom: 10px;
            color: green;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="regular_user_dashboard.php" class="back-btn">Back</a>
</div>

<div class="container">
    <h2>Request Leave</h2>
    <?php if (isset($status_notification)) : ?>
        <div class="status-notification"><?php echo $status_notification; ?></div>
    <?php endif; ?>
    <form id="leave-form" method="post" action="approval_of_leave.php">
        <div class="form-group">
            <label for="leave_type">Type of Leave:</label>
            <select id="leave_type" name="leave_type" required>
                <option value="">Select leave type</option>
                <option value="Annual">Annual Leave</option>
                <option value="Sick">Sick Leave</option>
                <option value="Maternity">Maternity Leave</option>
                <option value="Paternity">Paternity Leave</option>
                <option value="Unpaid">Unpaid Leave</option>
                <!-- Add more options as needed -->
            </select>
        </div>
        <!-- Add status field -->
        <div class="form-group">
            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="">Select status</option>
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
            </select>
        </div>
        <div class="form-group">
            <label for="start_date_leave">Start Date:</label>
            <input type="date" id="start_date_leave" name="start_date_leave" required>
        </div>
        <div class="form-group">
            <label for="end_date_leave">End Date:</label>
            <input type="date" id="end_date_leave" name="end_date_leave" required>
        </div>
        <div class="form-group">
            <label for="reason_leave">Reason:</label>
            <textarea id="reason_leave" name="reason_leave" rows="4" placeholder="Enter reason for leave" required></textarea>
        </div>
        <div class="form-group">
            <button type="submit">Submit Leave Request</button>
        </div>
    </form>
</div>

    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.js"></script>
<script>
    flatpickr('.form-group input[type="date"]', {
        dateFormat: "Y-m-d"
    });
</script>

</body>
</html>
