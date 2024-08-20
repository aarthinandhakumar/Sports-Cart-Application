<?php
session_start();
include 'db.php';

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

echo "</p></div>";
echo "<div style='text-align: center; margin-bottom: 20px;'>";
echo "<h1>Smart Cart</h1>";
echo "<a href='product.php'>Search Products</a> | ";
echo "<a href='my_orders.php'>My Orders</a> | ";
echo "<a href='cart.php'>View Cart</a> | ";
echo "<a href='index.php'>Home</a>";
echo "</div>";
// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: index.php"); // Redirect to login if not logged in
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Query to fetch orders for the logged-in customer
$query = "SELECT * FROM orders WHERE customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <style>
            body {
             background-color: #F8D3A5;
            }
            th {
                background-color: #f2f2f2;
            }
            table{
                background-color: #F1DCC3;
            }
            </style>
</head>
<body>
<div style='text-align: center; margin: 20px auto; width: 80%;'>
    <h2>My Orders</h2>

    <!-- List Previous Orders -->
    <table border="1" style="margin: 0 auto; width: 60%; text-align:center;">
        <tr>
            <th>Order ID</th>
            <th>Order Date</th>
            <th>Total Amount</th>
            <th>Details</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                <td><?php echo htmlspecialchars($row['total_amount']); ?></td>
                <td><a href="order_items.php?order_id=<?php echo htmlspecialchars($row['order_id']); ?>">View Details</a></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <?php
    $stmt->close();
    $conn->close();
    ?>
    </div>
</body>
</html>