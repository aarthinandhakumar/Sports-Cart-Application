<?php
include 'db.php';


// Start the session
session_start();


// Fetch customers from the database
$sql = "SELECT id, name FROM customers";
$result = $conn->query($sql);


// Check if a customer is selected
if (isset($_POST['customer_id'])) {
    // Get the selected customer ID
    $customer_id = $_POST['customer_id'];


    // Fetch customer name to store in session
    $customer_query = "SELECT name FROM customers WHERE id = ?";
    $stmt = $conn->prepare($customer_query);
    $stmt->bind_param('i', $customer_id);
    $stmt->execute();
    $stmt->bind_result($customer_name);
    $stmt->fetch();
    $stmt->close();


    // Store customer details in session
    $_SESSION['customer_id'] = $customer_id;
    $_SESSION['username'] = $customer_name;
    $_SESSION['cart_key'] = 'cart_' . $_SESSION['customer_id'];


    // Clear the cart session data for the new user
    unset($_SESSION['cart']);


    header("Location: product.php"); // Redirect to the customer interface
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Switch User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
            position: relative;
            background-color: #F8C9BB;
        }
        .container {
            padding: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 100px; /* Pushes the content downwards */
        }
        .form-group {
            display: flex;
            gap: 80px; /* Space between dropdown and button */
            align-items: center;
        }
        select {
            padding: 10px;
            font-size: 16px;
        }
        button {
            padding: 10px;
            font-size: 14px;
            background-color: beige; /* Set button color to beige */
            border: 1px solid black;
            cursor: pointer;
        }
        button:hover {
            background-color: aqua; /* Slightly darker beige on hover */
        }
        .admin-link {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 16px;
            color: crimson;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <a href="admin_product.php" class="admin-link">Admin</a>
    <div class="container">
    <h1 style="margin-top: -50px; margin-bottom: 50px;">Smart Cart</h1>
        <form method="post" action="">
            <div class="form-group">
                <select name="customer_id">
                    <option value="">Select Customer</option>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                        }
                    } else {
                        echo "<option>No customers found</option>";
                    }
                    ?>
                </select>
                <button type="submit">Click to Shop</button>
            </div>
        </form>
    </div>
</body>
</html>


<?php
$conn->close();
?>