<?php
include '../db.php'; // Ensure the path to db.php is correct

// Set content type based on the query parameter 'format'
$format = isset($_GET['format']) ? strtolower($_GET['format']) : 'json';
header('Content-Type: application/' . ($format === 'xml' ? 'xml' : 'json'));

// Debugging: Output all query parameters
file_put_contents('debug.log', print_r($_GET, true), FILE_APPEND);

// Get the name parameter
$name = isset($_GET['name']) ? trim($conn->real_escape_string($_GET['name'])) : '';

// Check if the name parameter is empty
if ($name === '') {
    $error = ['error' => 'Missing product name parameter.'];
    if ($format === 'xml') {
        $xml = new SimpleXMLElement('<response/>');
        $xml->addChild('error', $error['error']);
        echo $xml->asXML();
    } else {
        echo json_encode($error);
    }
    $conn->close();
    exit;
}

// To fetch products by name from the database
$sql = "SELECT id, name, description, price, stock_quantity FROM products WHERE name LIKE '%$name%'";
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