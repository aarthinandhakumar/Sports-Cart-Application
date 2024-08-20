<?php
include 'db.php';
session_start();
echo "<div style='position: fixed; top: 10px; left: 10px; padding: 10px; background-color: beige; border: 1px solid #ddd; border-radius: 5px;'>
        <p style='margin: 0; font-size: 16px;'>";

echo "User: <strong>Admin</strong>";

echo "</p></div>";
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id) {
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $stmt->close();
}

header('Location: admin_product.php');
exit();
?>
