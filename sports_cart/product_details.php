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

echo "</p></div>";
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
echo "<title>Product Details</title>";
echo "<div style='text-align: center; margin-bottom: 20px;'>";
echo "<h1>Smart Cart</h1>";
echo "<a href='product.php'>Search Products</a> | ";
echo "<a href='my_orders.php'>My Orders</a> | ";
echo "<a href='cart.php'>View Cart</a> | ";
echo "<a href='index.php'>Home</a>";
echo "</div>";
echo "<div style='text-align: center;'>";
echo "<h2>Product Details</h2>";
echo "<h3>" . htmlspecialchars($product['name']) . "</h3>";
echo "<p>" . htmlspecialchars($product['description']) . "</p>";
echo "<p>Price: $" . htmlspecialchars($product['price']) . "</p>";
$stock_quantity = $product['stock_quantity'];
echo "<p id='stock-status'>Stock Quantity: " . htmlspecialchars($stock_quantity) . "</p>";
$disabled = $stock_quantity <= 0 ? 'disabled' : '';
$disabledClass = $stock_quantity <= 0 ? 'disabled' : '';
echo "<form id='cart-form'>
        <button type='button' class='quantity-btn' onclick='changeQuantity(-1)' $disabled>-</button>
        <span id='quantity'>1</span>
        <button type='button' class='quantity-btn' onclick='changeQuantity(1)' $disabled>+</button>
        <button type='button' class='add-to-cart-btn $disabledClass' onclick='addToCart()' $disabled>Add to Cart</button>
    </form></div>";
echo "<style>
        body {
             background-color: #F8D3A5;
            }
        .quantity-btn {
            padding: 5px;
            border: 1px solid black;
            background-color: #e0e0e0;
            cursor: pointer;
        }
        .quantity-btn:disabled {
            background-color: #f0f0f0;
            cursor: not-allowed;
        }
        .add-to-cart-btn {
            padding: 5px 10px;
            border: 1px solid black;
            background-color: #c0c0c0;
            cursor: pointer;
        }
        .add-to-cart-btn.disabled {
            background-color: #e0e0e0;
            cursor: not-allowed;
        }
    </style>";
$stmt->close();
$conn->close();
?>
<script>
function changeQuantity(change) {
    var quantitySpan = document.getElementById('quantity');
    var currentQuantity = parseInt(quantitySpan.textContent);
    var newQuantity = Math.max(1, currentQuantity + change);
    var maxQuantity = <?php echo $stock_quantity; ?>;
    if (newQuantity > maxQuantity) newQuantity = maxQuantity;
    quantitySpan.textContent = newQuantity;
    // Update the button status based on the quantity
    var addToCartBtn = document.querySelector('.add-to-cart-btn');
    var minusBtn = document.querySelector('.quantity-btn:first-of-type');
    var plusBtn = document.querySelector('.quantity-btn:last-of-type');
    if (maxQuantity <= 0) {
        addToCartBtn.classList.add('disabled');
        addToCartBtn.disabled = true;
        minusBtn.disabled = true;
        plusBtn.disabled = true;
    } else {
        addToCartBtn.classList.remove('disabled');
        addToCartBtn.disabled = false;
        minusBtn.disabled = (newQuantity <= 1);
        plusBtn.disabled = (newQuantity >= maxQuantity);
    }
}
function addToCart() {
    var quantitySpan = document.getElementById('quantity');
    var quantity = parseInt(quantitySpan.textContent);
    var maxQuantity = <?php echo $stock_quantity; ?>;


    if (quantity > maxQuantity) {
        alert('Quantity exceeds stock availability.');
        return;
    }


    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'add_to_cart.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            alert('Product added to cart');
        } else {
            alert('Failed to add product to cart');
        }
    };
    xhr.send('product_id=<?php echo $product_id; ?>&quantity=' + quantity);
}
</script>