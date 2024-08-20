<?php
include 'db.php';
session_start();

if (!isset($_SESSION['cart_key'])) {
    $_SESSION['cart_key'] = 'cart_' . $_SESSION['customer_id'];
}

$cart_key = $_SESSION['cart_key'];

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if ($product_id == 0) {
    $_SESSION['error'] = "Invalid product ID.";
    header("Location: product.php");
    exit();
}

if (!isset($_SESSION[$cart_key])) {
    $_SESSION[$cart_key] = array();
}

// Fetch the stock quantity from the database
$sql = "SELECT stock_quantity FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    $_SESSION['error'] = "Product not found.";
    header("Location: product.php");
    exit();
}

$stock_quantity = $product['stock_quantity'];
$current_cart_quantity = isset($_SESSION[$cart_key][$product_id]) ? $_SESSION[$cart_key][$product_id] : 0;

// Check if the addition exceeds the stock
if ($current_cart_quantity + $quantity > $stock_quantity) {
    $_SESSION['error'] = "You cannot add more than the available stock.";
    header("Location: product_details.php?id=$product_id");
    exit();
}

// Add to cart
if (isset($_SESSION[$cart_key][$product_id])) {
    $_SESSION[$cart_key][$product_id] += $quantity;
} else {
    $_SESSION[$cart_key][$product_id] = $quantity;
}

header('Location: product.php');
exit();
?>