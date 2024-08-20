<?php
include 'db.php';

echo "<div style='position: fixed; top: 10px; left: 10px; padding: 10px; background-color: beige; border: 1px solid #ddd; border-radius: 5px; z-index: 1000;'>
        <p style='margin: 0; font-size: 16px;'>";

echo "User: <strong>Admin</strong>";

echo "</p></div>";
echo "<body style='background-color: #D8BFD8;'>";
echo "<div style='text-align: center; margin: 60px auto 20px; width: 80%;'>";
echo "<h1>Smart Cart</h1>";
echo "<a href='admin_product.php' class='links'>Search Products</a> | ";
echo "<a href='customer.php' class='links'>My Customers</a> | ";
echo "<a href='index.php' class='links'>Home</a>";
echo "</div>";

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT id, name, description, price, stock_quantity FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
if (!$product) {
    echo "<div style='text-align: center;'><h1>Product Not Found</h1></div>";
    exit;
}
echo "<title>Product Details - Admin View</title>";
echo "<div style='text-align: center; margin-bottom: 20px;'>";
echo "<h2>Product Details</h2>";
echo "</div>";
echo "<div style='text-align: center;'>";
echo "<h3>" . htmlspecialchars($product['name']) . "</h3>";
echo "<p>" . htmlspecialchars($product['description']) . "</p>";
echo "<p>Price: $" . htmlspecialchars($product['price']) . "</p>";
$stock_quantity = $product['stock_quantity'];
echo "<p id='stock-status'>Stock Quantity: " . htmlspecialchars($stock_quantity) . "</p>";
echo "</div>";

$stmt->close();
$conn->close();
?>