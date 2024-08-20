<?php
include '../db.php'; // Adjust the path to your db.php file if needed

// Set content type based on the query parameter 'format'
$format = isset($_GET['format']) ? strtolower($_GET['format']) : 'json';
header('Content-Type: application/' . ($format === 'xml' ? 'xml' : 'json'));

// To fetch all products from the database
$sql = "SELECT id, name, description, price, stock_quantity FROM products";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

if ($format === 'xml') {
    // Converts the data to XML format
    $xml = new SimpleXMLElement('<products/>');
    foreach ($products as $product) {
        $item = $xml->addChild('product');
        $item->addChild('id', $product['id']);
        $item->addChild('name', $product['name']);
        $item->addChild('description', $product['description']);
        $item->addChild('price', $product['price']);
        $item->addChild('stock_quantity', $product['stock_quantity']);
    }
    echo $xml->asXML();
} else {
    // Converts the data to JSON format
    echo json_encode($products);
}

$conn->close();
?>