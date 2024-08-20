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

// Check if the order_id and customer_id are passed in the URL
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$customer_id = isset($_GET['customer_id']) ? intval($_GET['customer_id']) : 0;

if ($order_id == 0 || $customer_id == 0) {
    echo "<div style='text-align: center;'><h1>Invalid Order</h1></div>";
    exit();
}

// Query to fetch order items for the specified order
$query = "SELECT oi.order_item_id, oi.product_name, oi.quantity, oi.price 
FROM order_items oi
WHERE oi.order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle form submission for updating quantities
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantities'])) {
    foreach ($_POST['quantities'] as $item_id => $quantity) {
        $update_query = "UPDATE order_items SET quantity = ? WHERE order_item_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ii", $quantity, $item_id);
        $update_stmt->execute();
        $update_stmt->close();
    }
    // Recalculate order total amount
    $recalculate_query = "UPDATE orders SET total_amount = (SELECT SUM(oi.quantity * p.price) FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?) WHERE order_id = ?";
    $recalculate_stmt = $conn->prepare($recalculate_query);
    $recalculate_stmt->bind_param("ii", $order_id, $order_id);
    $recalculate_stmt->execute();
    $recalculate_stmt->close();
    // Refresh the page to show updated quantities and prices
    header("Location: customer_order_details.php?order_id=$order_id&customer_id=$customer_id");
    exit();
}

// Handle deletion of order items
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $item_id = $_POST['item_id'];
    $delete_query = "DELETE FROM order_items WHERE order_item_id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $item_id);
    $delete_stmt->execute();
    $delete_stmt->close();
    // Recalculate order total amount
    $recalculate_query = "UPDATE orders SET total_amount = (SELECT SUM(oi.quantity * p.price) FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?) WHERE order_id = ?";
    $recalculate_stmt = $conn->prepare($recalculate_query);
    $recalculate_stmt->bind_param("ii", $order_id, $order_id);
    $recalculate_stmt->execute();
    $recalculate_stmt->close();
    // Refresh the page to show updated quantities and prices
    header("Location: customer_order_details.php?order_id=$order_id&customer_id=$customer_id");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
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
            input[type="number"] {
            text-align: center;
        }
        </style>
</head>
<body>
<div style='text-align: center; margin: 20px auto; width: 80%;'>
    <h2>Order Details</h2>

    <!-- List Order Items -->
    <form method="POST" action="customer_order_details.php?order_id=<?php echo htmlspecialchars($order_id); ?>&customer_id=<?php echo htmlspecialchars($customer_id); ?>">
        <table border="1" style="margin: 0 auto; width: 80%; text-align:center;">
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price per Unit</th>
                <th>Total Price</th>
                <th>Update Quantity</th>
                <th>Delete</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><input type="number" name="quantities[<?php echo htmlspecialchars($row['order_item_id']); ?>]" value="<?php echo htmlspecialchars($row['quantity']); ?>" min="1"></td>
                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                    <td><?php echo htmlspecialchars($row['quantity'] * $row['price']); ?></td>
                    <td><input type="submit" name="update_quantities" value="Save"></td>
                    <td>
                        <form method="POST" action="customer_order_details.php?order_id=<?php echo htmlspecialchars($order_id); ?>&customer_id=<?php echo htmlspecialchars($customer_id); ?>">
                            <button type="button" name="delete_item" value="Delete" onclick="return confirm('Warning: This product cannot be deleted as it is currently assigned to an order.')">Delete</button>
                            <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($row['order_item_id']); ?>">
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </form>

    <?php
    $stmt->close();
    $conn->close();
    ?>
    </div>
</body>
</html>
