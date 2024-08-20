<?php
include 'db.php';

echo "<div style='position: fixed; top: 10px; left: 10px; padding: 10px; background-color: beige; border: 1px solid #ddd; border-radius: 5px; z-index: 1000;'>
        <p style='margin: 0; font-size: 16px;'>";

echo "User: <strong>Admin</strong>";

echo "</p></div>";

echo "<div style='text-align: center; margin: 60px auto 20px; width: 80%;'>";
echo "<h1>Smart Cart</h1>";
echo "<a href='admin_product.php' class='links'>Search Products</a> | ";
echo "<a href='customer.php' class='links'>My Customers</a> | ";
echo "<a href='index.php' class='links'>Home</a>";
echo "</div>";

// Check if the customer id is passed in the URL
$customer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($customer_id == 0) {
    echo "<div style='text-align: center;'><h1>Invalid Customer</h1></div>";
    exit();
}

// Query to fetch orders for the specified customer
$query = "SELECT * FROM orders WHERE customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Orders</title>
    <style>
        .links {
            display: inline-block;
        }
        body {
            background-color: #D8BFD8;
        }
        th {
                background-color: #f2f2f2;
            }
            table{
                background-color: #ECDFEC;
            }
        </style>
</head>
<body>
<div style='text-align: center; margin: 20px auto; width: 80%;'>
    <h2>Customer Orders</h2>

    <!-- List Previous Orders -->
    <table border="1" style="margin: 0 auto; width: 80%; text-align:center;">
        <tr>
            <th>Order ID</th>
            <th>Order Date</th>
            <th>Total Amount</th>
            <th>Details</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                <td><?php echo htmlspecialchars($row['total_amount']); ?></td>
                <td><a href="customer_order_details.php?order_id=<?php echo htmlspecialchars($row['order_id']); ?>&customer_id=<?php echo htmlspecialchars($customer_id); ?>">View Details</a></td>
                <td><a href="delete_order.php?order_id=<?php echo htmlspecialchars($row['order_id']); ?>&customer_id=<?php echo htmlspecialchars($customer_id); ?>" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a></td>
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
