<?php

try {
    $db = new PDO('sqlite:' . __DIR__ . '/messages.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("DB Connection Failed: " . $e->getMessage());
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "shohaynet";
$port = 3306; 

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>