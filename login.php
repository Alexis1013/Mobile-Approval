<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: rgb(245, 132, 38);
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 2px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 999;
        }
        header img {
            width: 40px;
            margin-right: 10px;
        }
        .login-container {
            margin-top: 130px;
            background-color: rgb(255, 253, 251);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        input[type="email"],
        input[type="password"],
        input[type="submit"],
        select {
            width: calc(100% - 40px);
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: #ff4d4d;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<header>
   
    </a>
</header>

<div class="login-container">
    <h2>Login</h2>
    <?php
    include 'connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = htmlspecialchars(trim($_POST['email']));
        $password = htmlspecialchars($_POST['password']);
        $userType = htmlspecialchars($_POST['userType']);

        $stmt = $conn->prepare("SELECT id, password, user_type FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($userId, $hashedPassword, $userTypeDb);
            $stmt->fetch();

            if ($stmt->num_rows > 0) {
                if (password_verify($password, $hashedPassword)) {
                    if ($userTypeDb === $userType) {
                        // Start the session and set user details
                        session_start();
                        $_SESSION['user_id'] = $userId;
                        $_SESSION['user_type'] = $userTypeDb;

                        if ($userTypeDb === "admin") {
                            header("Location: admin_dashboard.php");
                        } else {
                            header("Location: regular_user_dashboard.php");
                        }
                        exit();
                    } else {
                        echo "<div class='error-message'>Selected user type does not match the account type.</div>";
                    }
                } else {
                    echo "<div class='error-message'>Invalid password.</div>";
                }
            } else {
                echo "<div class='error-message'>No user found with this email.</div>";
            }

            $stmt->close();
        } else {
            echo "<div class='error-message'>Error preparing statement: " . htmlspecialchars($conn->error) . "</div>";
        }
    }

    $conn->close();
    ?>

    <form action="login.php" method="post">
        <input type="email" id="email" name="email" placeholder="Email" required><br>
        <input type="password" id="password" name="password" placeholder="Password" required><br>
        <select id="userType" name="userType">
            <option value="regular">Regular User</option>
            <option value="admin">Admin User</option>
        </select><br>
        <input type="submit" value="Login">
    </form>
    <a href="registration.php" class="login-link">Don't have an account? Register here.</a>
</div>

</body>
</html>
