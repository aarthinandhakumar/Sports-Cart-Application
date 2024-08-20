<?php
include 'db.php';
session_start();

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

// Check if products are found
echo "<title>Products - Admin View</title>";
if ($result->num_rows > 0) {
    echo "<div style='text-align: center;'>";
    echo "<h2>Product List</h2>";
    // Search form
    echo "<form method='GET' action='admin_product.php' style='margin-bottom: 20px;'>
            <input type='text' name='search' value='" . htmlspecialchars($search) . "' placeholder='Search by name or description' style='width: 200px; padding: 5px;'>
            <input type='submit' value='Search'>
        </form>";
    echo "<style>
             body {
             background-color: #D8BFD8;
            }
            table {
                width: 80%;
                margin: 20px auto;
                border-collapse: collapse;
                text-align: center;
                background-color: #ECDFEC;
            }
            .links {
            display: inline-block;
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
                <th></th>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock Quantity</th>
                <th></th>
            </tr>";
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $stock_quantity = $row["stock_quantity"];
        $outOfStock = $stock_quantity <= 0 ? 'out-of-stock' : '';
        $display_quantity = $stock_quantity > 0 ? $stock_quantity : 'Out of Stock';
        echo "<tr class='$outOfStock'>
                <td><a href='delete_product.php?id=$id' onclick='return confirm(\"Are you sure you want to remove this product?\")'>Delete</a></td>
                <td onclick='window.location.href=\"admin_product_details.php?id=$id\"'>" . $row["id"] . "</td>
                <td onclick='window.location.href=\"admin_product_details.php?id=$id\"'>" . $row["name"] . "</td>
                <td onclick='window.location.href=\"admin_product_details.php?id=$id\"'>" . $row["description"] . "</td>
                <td onclick='window.location.href=\"admin_product_details.php?id=$id\"'>" . $row["price"] . "</td>
                <td onclick='window.location.href=\"admin_product_details.php?id=$id\"'>" . $display_quantity . "</td>
                <td><a href='edit_product.php?id=$id'>Manage</a></td>
            </tr>";
    }
    echo "</table>";
    echo "<div style='text-align: center; margin-top: 20px;'>
            <a href='add_product.php' style='padding: 10px 20px; background-color: #9F5F9F; color: white; text-decoration: none; border-radius: 5px;'>Add a new product</a>
        </div>";
    echo "</div>";
} else {
    echo "<body style='background-color: #D8BFD8;'>";
    echo "<div style='text-align: center;'><h1>No Results</h1></div>";
}

$stmt->close();
$conn->close();
?>