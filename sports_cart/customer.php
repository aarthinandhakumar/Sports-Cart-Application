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

// Fetch all customers from the database
$query = "SELECT id, name FROM customers";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Customers</title>
    <style>
        body {
            background-color: #D8BFD8;
        }
        .links {
            display: inline-block;
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
    <h2>Customers</h2>

    <!-- List Customers -->
    <table border="1" style="margin: 0 auto; width: 30%; text-align:center;">
        <tr>
            <th>Customer ID</th>
            <th>Customer Name</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><a href="customer_orders.php?id=<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['name']); ?></a></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <?php
    $conn->close();
    ?>
    </div>
</body>
</html>