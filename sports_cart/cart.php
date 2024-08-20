    <?php
    ob_start();
    include 'db.php';
    session_start();

    // Ensure cart_key is set
    if (!isset($_SESSION['cart_key'])) {
    $_SESSION['cart_key'] = 'cart_' . $_SESSION['customer_id'];
    }
    $cart_key = $_SESSION['cart_key'];

    // Display the username if set
    echo "<div style='position: fixed; top: 10px; left: 10px; padding: 10px; background-color: beige; border: 1px solid #ddd; border-radius: 5px;'>
    <p style='margin: 0; font-size: 16px;'>";
    if (isset($_SESSION['username'])) {
    $username = htmlspecialchars($_SESSION['username']);
    echo "User: <strong>$username</strong>";
    } else {
    header('Location: index.php');
    exit();
    }
    echo "</p></div>";

    // Initialize cart
    $cartItems = isset($_SESSION[$cart_key]) ? $_SESSION[$cart_key] : [];
    $total = 0;

    // Fetch product details if the cart is not empty
    if (!empty($cartItems)) {
    $ids = implode(',', array_keys($cartItems));
    $sql = "SELECT id, name, price, stock_quantity FROM products WHERE id IN ($ids)";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
    echo "<div style='text-align: center; margin-bottom: 20px;'>";
    echo "<h1>Smart Cart</h1>";
    echo "<a href='product.php'>Search Products</a> | ";
    echo "<a href='my_orders.php'>My Orders</a> | ";
    echo "<a href='cart.php'>View Cart</a> | ";
    echo "<a href='index.php'>Home</a>";
    echo "</div>";
    echo "<style>
    body {
    background-color: #F8D3A5;
    }
    table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    text-align: center;
    background-color: #F1DCC3;
    }
    th, td {
    border: 1px solid black;
    padding: 8px;
    }
    th {
    background-color: #f2f2f2;
    }
    .button {
    background-color: #D66F62;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    }
    .button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
    }
    .icon {
    cursor: pointer;
    }
    .icon img {
    width: 20px;
    }
    .out-of-stock {
    color: red;
    }
    .hidden {
    display: none;
    }
    .tooltip {
    position: relative;
    display: inline-block;
    }
    .tooltip .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: #555;
    color: #fff;
    text-align: center;
    border-radius: 5px;
    padding: 5px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -60px;
    opacity: 0;
    transition: opacity 0.3s;
    }
    .tooltip:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
    }
    </style>";
    echo "<form method='POST' action='cart.php'>";
    echo "<table>
    <tr>
    <th>Action</th>
    <th>ID</th>
    <th>Name</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Total</th>
    </tr>";
    while ($row = $result->fetch_assoc()) {
    $id = $row["id"];
    $name = htmlspecialchars($row["name"]);
    $price = $row["price"];
    $stock_quantity = $row["stock_quantity"];
    $quantity = $cartItems[$id];
    $itemTotal = $price * $quantity;
    $total += $itemTotal;
    $availableToAdd = $stock_quantity - $quantity;
    $isOutOfStock = $availableToAdd <= 0;
    $quantityControlDisabled = $quantity <= 0;
    echo "<tr>
    <td>
    <a href='cart.php?remove=$id' class='button'>
    Remove
    </a>
    </td>
    <td>$id</td>
    <td>$name</td>
    <td>$" . number_format($price, 2) . "</td>
    <td>
    <button type='button' class='button' onclick='changeQuantity($id, -1)' " . ($quantity <= 1 ? 'disabled' : '') . ">-</button>
    <input type='number' name='quantity[$id]' value='$quantity' min='1' max='$stock_quantity' readonly>
    <button type='button' class='button tooltip' onclick='changeQuantity($id, 1)' " . ($availableToAdd <= 0 ? 'disabled' : '') . ">
    +
    <span class='tooltiptext'>Stock available: " . ($availableToAdd >= 0 ? $availableToAdd : 0) . "</span>
    </button>
    </td>
    <td>$" . number_format($itemTotal, 2) . "</td>
    </tr>";
    }
    echo "<tr>
    <td colspan='5' style='text-align: right;'><strong>Total:</strong></td>
    <td><strong>$" . number_format($total, 2) . "</strong></td>
    </tr>";
    echo "</table>";
    echo "<div style='text-align: center; margin-top: 20px;'>";
    echo "<button type='submit' name='update' class='button'>Save Cart</button>";
    echo "</div>";
    echo "<div style='text-align: center; margin-top: 20px;'>";
    echo "<button type='submit' name='submit_order' class='button'>Submit Order</button>";
    echo "</div>";
    echo "<div style='text-align: center; margin-top: 20px;'>";
    echo "<a href='product.php' class='button'>Add More Products</a>";
    echo "</div>";
    echo "</form>";
    echo "<title>View Cart</title>";
    }
    } else {
    echo "<body style='background-color: #F8D3A5;'>";
    echo "<div style='text-align: center;'><h1>Your cart is empty</h1></div>";
    echo "<div style='text-align: center; margin-top: 20px;'>";
    echo "<a href='product.php' class='button'>View our Products</a>";
    echo "</div>";
    echo "<title>Empty Cart</title>";
    }

    // Handle form submissions for updating or removing items
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
    foreach ($_POST['quantity'] as $product_id => $new_quantity) {
    $product_id = intval($product_id);
    $new_quantity = intval($new_quantity);
    $stock_quantity = getProductStock($product_id);
    if ($new_quantity >= 1 && $new_quantity <= $stock_quantity) {
    $_SESSION[$cart_key][$product_id] = $new_quantity;
    } else {
    echo "<div style='text-align: center;'>Invalid quantity for product ID $product_id. Please check stock availability.</div>";
    }
    }
    header("Location: cart.php");
    exit();
    } elseif (isset($_POST['submit_order'])) {
    $customer_id = $_SESSION['customer_id']; // Assuming customer_id is stored in session
    $stmt = $conn->prepare("INSERT INTO orders (customer_id, order_date, total_amount) VALUES (?, NOW(), ?)");
    $stmt->bind_param('id', $customer_id, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id; // Get the ID of the newly inserted order
    $stmt->close();
    foreach ($cartItems as $product_id => $quantity) {
        $product_id = intval($product_id);
        $quantity = intval($quantity);
        // Fetching Product Details
        $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $stmt->bind_result($product_name, $price);
        $stmt->fetch();
        $stmt->close();
        // Inserting Product Details into order_items
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, product_name, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('iiisd', $order_id, $product_id, $quantity, $product_name, $price);
        $stmt->execute();
        $stmt->close();
    // Update the stock quantity in the database
    $stmt = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
    $stmt->bind_param('ii', $quantity, $product_id);
    $stmt->execute();
    $stmt->close();
    }
    unset($_SESSION[$cart_key]);
    header("Location: order_confirmation.php");
    exit();
    }
    }

    // Handle cart item removal
    if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    if (isset($_SESSION[$cart_key][$remove_id])) {
    unset($_SESSION[$cart_key][$remove_id]);
    // Redirect to prevent re-submission of the removal request
    header("Location: cart.php");
    exit();
    }
    }

    // Function to get product stock quantity
    function getProductStock($product_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT stock_quantity FROM products WHERE id = ?");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $stmt->bind_result($stock_quantity);
    $stmt->fetch();
    $stmt->close();
    return $stock_quantity;
    }
    echo "<script>
    // Store the initial stock quantity in a data attribute when the page loads
    document.addEventListener('DOMContentLoaded', function () {
    var quantityInputs = document.querySelectorAll('input[name^=\"quantity\"]');
    quantityInputs.forEach(function (input) {
    var maxQuantity = parseInt(input.max, 10);
    input.dataset.initialStock = maxQuantity;
    });
    });

    function changeQuantity(productId, change) {
    var quantityInput = document.querySelector('input[name=\"quantity[' + productId + ']\"]');
    var currentQuantity = parseInt(quantityInput.value, 10);
    var maxQuantity = parseInt(quantityInput.max, 10);

    // Calculate new quantity
    var newQuantity = currentQuantity + change;
    newQuantity = Math.max(newQuantity, 1); // Ensure quantity is at least 1

    // Calculate the available stock based on the initial stock and new quantity
    var initialStock = parseInt(quantityInput.dataset.initialStock, 10);
    var availableToAdd = initialStock - newQuantity;

    // Update the quantity input value
    quantityInput.value = newQuantity;

    // Update button states
    var addButton = document.querySelector('button[onclick=\"changeQuantity(' + productId + ', 1)\"]');
    var subtractButton = document.querySelector('button[onclick=\"changeQuantity(' + productId + ', -1)\"]');
    subtractButton.disabled = newQuantity <= 1;
    addButton.disabled = availableToAdd <= 0;

    // Update max attribute of input field
    quantityInput.max = initialStock;

    // Update tooltip text
    var tooltip = addButton.querySelector('.tooltiptext');
    tooltip.textContent = 'Stock available: ' + (availableToAdd >= 0 ? availableToAdd : 0);
    }
    </script>";
    $conn->close();
    ob_end_flush();
    ?>
