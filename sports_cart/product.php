<?php
include 'db.php';
session_start();

echo "<div style='position: fixed; top: 10px; left: 10px; padding: 10px; background-color: beige; border: 1px solid #ddd; border-radius: 5px;'>
        <p style='margin: 0; font-size: 16px;'>";

// Display the username if set
if (isset($_SESSION['username'])) {
    $username = htmlspecialchars($_SESSION['username']);
    echo "User: <strong>$username</strong>";
} else {
    // Redirect to homepage if username is not set
    header('Location: index.php');
    exit();
}

echo "</p></div>";

// Initialize cart if not already
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Initialize search query
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Fetch products from the database with search functionality
$sql = "SELECT id, name, description, price, stock_quantity FROM products WHERE name LIKE ? OR description LIKE ?";
$stmt = $conn->prepare($sql);
$searchParam = "%{$search}%";
$stmt->bind_param('ss', $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();


// Display the main menu
echo "<title>Product List</title>";
echo "<div style='text-align: center; margin-bottom: 20px;'>";
echo "<h1>Smart Cart</h1>";

echo "<a href='product.php'>Search Products</a> | ";
echo "<a href='my_orders.php'>My Orders</a> | ";
echo "<a href='cart.php'>View Cart</a> | ";
echo "<a href='index.php'>Home</a>";
echo "</div>";

// Check if products are found
if ($result->num_rows > 0) {
    echo "<div style='text-align: center;'>";
    echo "<h2>Product List</h2>";
    // Search form
    echo "<form method='GET' action='product.php' style='margin-bottom: 20px;'>
            <input type='text' name='search' value='" . htmlspecialchars($search) . "' placeholder='Search by name or description' style='width: 200px; padding: 5px;'>
            <input type='submit' value='Search'>
        </form>";
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
            tr {
                cursor: pointer;
            }
            tr:hover {
                background-color: #ffd9d9;
            }
            .out-of-stock {
                color: red;
                font-weight: bold;
                cursor: not-allowed;
            }
        </style>";
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock Quantity</th>
            </tr>";
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $stock_quantity = $row["stock_quantity"];
        $outOfStock = $stock_quantity <= 0 ? 'out-of-stock' : '';
        $display_quantity = $stock_quantity > 0 ? $stock_quantity : 'Out of Stock';
        echo "<tr class='$outOfStock' onclick='window.location.href=\"product_details.php?id=$id\"'>
                <td>" . $row["id"] . "</td>
                <td>" . $row["name"] . "</td>
                <td>" . $row["description"] . "</td>
                <td>" . $row["price"] . "</td>
                <td>" . $display_quantity . "</td>
            </tr>";
    }    
    echo "</table>";
    echo "</div>";
} else {
    echo "<body style='background-color: #F8D3A5;'>";
    echo "<div style='text-align: center;'><h1>No Results</h1></div>";
}

$stmt->close();
$conn->close();
?>