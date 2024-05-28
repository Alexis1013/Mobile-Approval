<?php
// Function to validate purchase order data
function validatePurchaseOrder($data) {
    $errors = [];

    // Your existing validation logic here...

    return $errors;
}

// Helper function to validate date format
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

// Function to insert data into the database
function insertPurchaseOrder($data) {
    // Replace these values with your actual database credentials
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "leave_management_system";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement
    $sql = "INSERT INTO purchase_orders (purchase_order_number, supplier_name, item, quantity, price, order_date) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind parameters and execute statement
    $stmt->bind_param("sssids", $data['purchase_order_number'], $data['supplier_name'], $data['item'], $data['quantity'], $data['price'], $data['order_date']);
    $stmt->execute();

    // Close statement and connection
    $stmt->close();
    $conn->close();
}

// Example usage
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $purchaseOrderData = [
        'purchase_order_number' => $_POST['purchase_order_number'] ?? '',
        'supplier_name' => $_POST['supplier_name'] ?? '',
        'item' => $_POST['item'] ?? '',
        'quantity' => $_POST['quantity'] ?? '',
        'price' => $_POST['price'] ?? '',
        'order_date' => $_POST['order_date'] ?? '',
    ];

    $errors = validatePurchaseOrder($purchaseOrderData);

    if (empty($errors)) {
        // Proceed with storing purchase order data in the database
        insertPurchaseOrder($purchaseOrderData);
        // Display popup message using JavaScript
        echo "<script>alert('Purchase order validated and stored successfully!');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('drystorage.png'); 
            background-size: cover;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, textarea {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .back-button {
    position: absolute;
    top: 10px;
    left: 10px;
    background-color: #6c757d;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 4px;
}

        .back-button:hover {
            background-color: #5a6268;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin_dashboard.php" class="back-button">Back</a>
        <h1>Purchase Order Form</h1>
        <?php if (!empty($errors)) : ?>
            <div class="error">Please correct the errors below:</div>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="purchase_order_number">Purchase Order Number:</label>
            <input type="text" id="purchase_order_number" name="purchase_order_number" value="<?php echo htmlspecialchars($purchaseOrderData['purchase_order_number'] ?? ''); ?>" required>
            <?php if (!empty($errors['purchase_order_number'])) : ?>
                <div class="error"><?php echo $errors['purchase_order_number']; ?></div>
            <?php endif; ?>

            <label for="supplier_name">Supplier Name:</label>
            <input type="text" id="supplier_name" name="supplier_name" value="<?php echo htmlspecialchars($purchaseOrderData['supplier_name'] ?? ''); ?>" required>
            <?php if (!empty($errors['supplier_name'])) : ?>
                <div class="error"><?php echo $errors['supplier_name']; ?></div>
            <?php endif; ?>

            <label for="item">Item:</label>
            <input type="text" id="item" name="item" value="<?php echo htmlspecialchars($purchaseOrderData['item'] ?? ''); ?>" required>
            <?php if (!empty($errors['item'])) : ?>
                <div class="error"><?php echo $errors['item']; ?></div>
            <?php endif; ?>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($purchaseOrderData['quantity'] ?? ''); ?>" required>
            <?php if (!empty($errors['quantity'])) : ?>
                <div class="error"><?php echo $errors['quantity']; ?></div>
            <?php endif; ?>

            <label for="price">Price:</label>
            <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($purchaseOrderData['price'] ?? ''); ?>" required>
            <?php if (!empty($errors['price'])) : ?>
                <div class="error"><?php echo $errors['price']; ?></div>
            <?php endif; ?>

            <label for="order_date">Order Date:</label>
            <input type="date" id="order_date" name="order_date" value="<?php echo htmlspecialchars($purchaseOrderData['order_date'] ?? ''); ?>" required>
            <?php if (!empty($errors['order_date'])) : ?>
                <div class="error"><?php echo $errors['order_date']; ?></div>
            <?php endif; ?>

            <input type="submit" value="Submit Purchase Order">
        </form>
    </div>
</body>
</html>
