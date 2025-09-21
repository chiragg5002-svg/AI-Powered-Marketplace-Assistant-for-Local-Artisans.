<?php
$host = "localhost";     // MySQL server
$dbname = "craft_store"; // Database name
$user = "root";          // MySQL username
$pass = "Chirag@123";              // MySQL password (leave empty if none)

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Uncomment the line below for debugging
    // echo "Connected successfully";
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
