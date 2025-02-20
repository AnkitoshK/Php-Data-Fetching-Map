<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "admin"; // Your MySQL username
$password = ""; // Your MySQL password
$database = "map_database"; // Database name

$enable_product = true;
error_log("enable_product: " . $enable_product);

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Get optional parameters for pagination
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5000; // Fetch in batches

// Fetch locations from the database with pagination
$sql = "SELECT X as latitude, Y as longitude, remark FROM latlongdata LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$locations = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }

    echo json_encode([
        "enable_product" => $enable_product,
        "locations" => $locations
    ]);
} else {
    echo json_encode([
        "error" => "No locations found",
        "enable_product" => $enable_product
    ]);
}

$conn->close();
?>
