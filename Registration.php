<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: rgb(245, 132, 38);
        }
        header img {
            width: 40px;
            margin-right: 10px;
        }
        header h1 {
            color: #333;
            margin: 0;
        }
        .container {
            width: 90%;
            max-width: 360px;
            margin: 50px auto;
            background-color: rgb(255, 253, 251);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: calc(100% - 40px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
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
        .password-strength-meter {
            text-align: left;
            margin-bottom: 20px;
        }
        .password-strength-meter div {
            display: inline-block;
            width: 20px;
            height: 5px;
            margin-right: 2px;
        }
        .password-weak { background-color: #ff4d4d; }
        .password-medium { background-color: #ffd166; }
        .password-strong { background-color: #4caf50; }
        .login-link {
            display: block;
            margin-top: 20px;
            text-decoration: none;
            color: #333;
        }

        @media only screen and (min-width: 768px) {
            .container {
                width: 60%;
            }
        }

    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("regPassword").addEventListener("input", function() {
                // Password strength meter logic
            });

            document.getElementById("regEmail").addEventListener("input", function() {
                // Email availability check logic
            });

            document.getElementById("registrationForm").addEventListener("submit", function(event) {
                event.preventDefault();
                var fullName = document.getElementById("regFullName").value;
                var email = document.getElementById("regEmail").value;
                var password = document.getElementById("regPassword").value;
                var confirmPassword = document.getElementById("confirmPassword").value;
                var userType = document.getElementById("userType").value;

                if (!validateFullName(fullName) || !validateEmail(email) || !validatePassword(password) || !validateUserType(userType)) {
                    document.getElementById("regErrorMessage").textContent = "Invalid input format.";
                    return;
                }

                if (password !== confirmPassword) {
                    document.getElementById("regErrorMessage").textContent = "Passwords do not match.";
                    return;
                }

                this.submit(); // Proceed with form submission
            });
        });

        function validateFullName(fullName) {
            return fullName.trim() !== "";
        }

        function validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function validatePassword(password) {
            return password.length >= 6;
        }

        function validateUserType(userType) {
            return userType !== "";
        }
    </script>
</head>
<body>
    <!-- Your header content -->
    <div class="container">
        <h2>Registration Form</h2>
        <?php
// Define the validateFullName function
function validateFullName($fullName) {
    return trim($fullName) !== "";
}

// Define the validateEmail function
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Define the validatePassword function
function validatePassword($password) {
    return strlen($password) >= 6;
}

// Define the validateUserType function
function validateUserType($userType) {
    return !empty($userType);
}

// Include the database connection script
include 'connect.php';

$registrationStatus = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username'])) {
        $username = htmlspecialchars(trim($_POST['username'])); 
    } else {
        $username = ""; 
    }

    $fullName = htmlspecialchars(trim($_POST['regFullName']));
    $email = htmlspecialchars(trim($_POST['regEmail']));
    $password = htmlspecialchars($_POST['regPassword']);
    $confirmPassword = htmlspecialchars($_POST['confirmPassword']);
    $userType = htmlspecialchars($_POST['userType']);

    if (empty($username)) {
        $errors[] = "Username is required.";
    }

    if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $errors[] = "Username can only contain letters, numbers, and underscores.";
    }

    if (strlen($username) < 3 || strlen($username) > 20) {
        $errors[] = "Username must be between 3 and 20 characters.";
    }

    // Validate full name, email, password, and user type using the defined functions
    if (!validateFullName($fullName) || !validateEmail($email) || !validatePassword($password) || !validateUserType($userType)) {
        $errors[] = "Invalid input format.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Check if the email is already registered
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors[] = "Email address is already registered.";
    }
    $stmt->close();

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, full_name, email, password, user_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $fullName, $email, $hashedPassword, $userType);

        if ($stmt->execute()) {
            // Registration successful, display a popup message
            echo "<script>alert('Registration successful!');</script>";
        } else {
            $registrationStatus = "Registration failed. Please try again later.";
        }

        $stmt->close();
    } else {
        foreach ($errors as $error) {
            $registrationStatus .= "<div class='error-message'>$error</div>";
        }
    }
}

$conn->close();
echo "<div id='regErrorMessage'>$registrationStatus</div>";
?>

<form id="registrationForm" method="post" action="registration.php">
            <!-- New input field for username -->
            <input type="text" id="username" name="username" placeholder="Username" required>

            <!-- Existing input fields -->
            <input type="text" id="regFullName" name="regFullName" placeholder="Full Name" required>
            <input type="email" id="regEmail" name="regEmail" placeholder="Email" required>
            <input type="password" id="regPassword" name="regPassword" placeholder="Password" required>
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
            <select id="userType" name="userType" required>
                <option value="" disabled selected>Select User Type</option>
                <option value="admin">Admin</option>
                <option value="regular">Regular</option>
            </select>
            <input type="submit" value="Register">
        </form>
        <a href="login.php" class="login-link">Already have an account? Login here.</a>
    </div>
</body>
</html>
