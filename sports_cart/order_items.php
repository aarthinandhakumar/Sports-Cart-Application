<?php
include 'db.php';
session_start();

echo "<div style='position: fixed; top: 10px; left: 10px; padding: 10px; background-color: beige; border: 1px solid #ddd; border-radius: 5px;'>
        <p style='margin: 0; font-size: 16px;'>";

// Display the username if set
if (isset($_SESSION['username'])) {
    $username = htmlspecialchars($_SESSION['username']);
    echo "User: <strong>$username</strong>";
}else {
    // Redirect to homepage if username is not set
    header('Location: index.php');
    exit();
}
echo "<title>Order Summary</title>";
echo "</p></div>";
echo "<div style='text-align: center; margin-bottom: 20px;'>";
echo "<h1>Smart Cart</h1>";
echo "<a href='product.php'>Search Products</a> | ";
echo "<a href='my_orders.php'>My Orders</a> | ";
echo "<a href='cart.php'>View Cart</a> | ";
echo "<a href='index.php'>Home</a>";
echo "</div>";

// Check if order ID is provided
if (!isset($_GET['order_id'])) {
    echo "Order ID is required.";
    exit();
}

$order_id = intval($_GET['order_id']);

// Fetch order details
$stmt = $conn->prepare("SELECT oi.product_name, oi.quantity, oi.price, o.order_date, o.total_amount
                         FROM order_items oi
                         JOIN orders o ON oi.order_id = o.order_id
                         WHERE oi.order_id = ?");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();

// Initialize variables
$order_date = "";
$total_amount = 0;
$total = 0;
echo "<body style = 'background-color: #F8D3A5;'>";
echo "<div style='text-align: center; margin: 20px auto; width: 80%;'>";
echo "<h2>Order Summary</h2>";
echo "<table border='1' style='margin: 0 auto; width: 60%; text-align: center; background-color: #F1DCC3;'>
        <tr>
            <th style='background-color: #f2f2f2;'>Product Name</th>
            <th style='background-color: #f2f2f2;'>Quantity</th>
            <th style='background-color: #f2f2f2;'>Price</th>
        </tr>";

// Fetch order date and total amount
if ($order_details = $result->fetch_assoc()) {
    $order_date = $order_details['order_date'];
    $total_amount = $order_details['total_amount'];
    // Add the item to the table
    do {
        $item_total = $order_details['quantity'] * $order_details['price']; // Calculate the total for the item
        $total += $item_total; // Accumulate the total price
        echo "<tr>
                <td>{$order_details['product_name']}</td>
                <td>{$order_details['quantity']}</td>
                <td>\$" . number_format($item_total, 2) . "</td> <!-- Updated line -->
              </tr>";
    } while ($order_details = $result->fetch_assoc());
}

echo "</table>";
echo "<p>Order Date: {$order_date}</p>";
echo "<p>Total Amount: \$" . number_format($total_amount, 2) . "</p>";
echo "</div>";

$stmt->close();
$conn->close();
?>