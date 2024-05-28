<?php
session_start(); // Start the session

// Check if user is logged in and is a regular user
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'regular') {
    header("Location: login.php"); // Redirect unauthorized users to login page
    exit();
}

// Include database connection
include 'connect.php';

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Check if form data is posted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $leave_type = $_POST['leave_type'];
    $start_date = $_POST['start_date_leave'];
    $end_date = $_POST['end_date_leave'];
    $reason = $_POST['reason_leave'];

    // Insert leave request into database
    $stmt = $conn->prepare("INSERT INTO leave_requests (user_id, leave_type, start_date, end_date, reason, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("issss", $user_id, $leave_type, $start_date, $end_date, $reason);

    if ($stmt->execute()) {
        // Leave request submitted successfully
        echo "Leave request submitted successfully.";
    } else {
        // Error in submitting leave request
        echo "Error in submitting leave request. Please try again.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request Submitted</title>
</head>
<body>
    <h2>Leave Request</h2>
    <p>Leave request submitted successfully. Please wait for approval.</p>
    <a href="regular_user_dashboard.php">Back to Dashboard</a>
</body>
</html>
