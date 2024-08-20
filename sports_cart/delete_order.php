<?php
include 'db.php';
echo "<div style='position: fixed; top: 10px; left: 10px; padding: 10px; background-color: beige; border: 1px solid #ddd; border-radius: 5px;'>
        <p style='margin: 0; font-size: 16px;'>";

echo "User: <strong>Admin</strong>";

echo "</p></div>";
// Check if the order id is passed in the URL
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$customer_id = isset($_GET['customer_id']) ? intval($_GET['customer_id']) : 0;

if ($order_id == 0 || $customer_id == 0) {
    echo "<div style='text-align: center;'><h1>Invalid Order</h1></div>";
    exit();
}

// Delete the order items first
$delete_items_query = "DELETE FROM order_items WHERE order_id = ?";
$delete_items_stmt = $conn->prepare($delete_items_query);
$delete_items_stmt->bind_param("i", $order_id);
$delete_items_stmt->execute();
$delete_items_stmt->close();

// Delete the order
$delete_order_query = "DELETE FROM orders WHERE order_id = ?";
$delete_order_stmt = $conn->prepare($delete_order_query);
$delete_order_stmt->bind_param("i", $order_id);
$delete_order_stmt->execute();
$delete_order_stmt->close();

// Redirect back to the customer's orders page
header("Location: customer_orders.php?id=" . $_GET['customer_id']);
exit();
?>