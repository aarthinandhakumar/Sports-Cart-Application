<?php
include 'db.php';
session_start();

echo "<div style='position: fixed; top: 10px; left: 10px; padding: 10px; background-color: beige; border: 1px solid #ddd; border-radius: 5px;'>
        <p style='margin: 0; font-size: 16px;'>";

echo "User: <strong>Admin</strong>";

echo "</p></div>";

echo "<div style='text-align: center; margin-bottom: 20px;'>";
echo "<h1>Smart Cart</h1>";
echo "<a href='admin_product.php' class='links'>Search Products</a> | ";
echo "<a href='customer.php' class='links'>My Customers</a> | ";
echo "<a href='index.php' class='links'>Home</a>";
echo "</div>";
// Handle add product form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];

    $sql = "INSERT INTO products (name, description, price, stock_quantity) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssdi', $name, $description, $price, $stock_quantity);
    $stmt->execute();
    $stmt->close();

    header('Location: admin_product.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #D8BFD8;
        }
        .links {
            display: inline-block;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: #d0bdf4;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 10px;
            margin-left:10px;
        }
        input[type="text"], input[type="number"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 20px;
            margin-left:10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            padding: 8px 16px;
            background-color: #600473;
            color: #c89666;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: auto;
            margin: 0 auto;
            display: block;
        }
        button:hover {
            background-color: #6B7A8F;
        }
        a {
            text-align: center;
            display: block;
            margin-top: 20px;
            text-decoration: none;
            color: #600473;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Product</h1>
        <form action="add_product.php" method="post">
            <input type="hidden" name="add_product" value="1">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" required>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required>
            <label for="stock_quantity">Stock Quantity:</label>
            <input type="number" id="stock_quantity" name="stock_quantity" required>
            <button type="submit">Add Product</button>
        </form>
        <a href="admin_product.php">Back to Product List</a>
    </div>
</body>
</html>