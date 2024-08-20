<?php
include '../db.php'; // Adjust the path to your db.php file if needed

// Set content type based on the query parameter 'format'
$format = isset($_GET['format']) ? strtolower($_GET['format']) : 'json';
header('Content-Type: application/' . ($format === 'xml' ? 'xml' : 'json'));

// Check if 'low' and 'high' parameters are set
if (!isset($_GET['low']) || !isset($_GET['high'])) {
    $error_message = "Error: Missing low or high price parameter.";
    if ($format === 'xml') {
        echo "<error><message>$error_message</message></error>";
    } else {
        echo json_encode(["error" => $error_message]);
    }
    exit();
}

$low = floatval($_GET['low']);
$high = floatval($_GET['high']);

// To fetch products within the specified price range from the database
$sql = "SELECT id, name, description, price, stock_quantity FROM products WHERE price BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('dd', $low, $high);
$stmt->execute();
$result = $stmt->get_result();

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