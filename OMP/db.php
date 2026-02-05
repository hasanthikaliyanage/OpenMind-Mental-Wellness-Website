<?php
require 'vendor/autoload.php'; // Composer autoload

use MongoDB\Client;

try {
    // Local MongoDB default connection
    $client = new Client("mongodb://localhost:27017");

    // Select your database and collection
    $collection = $client->OMP->users;

    // Optional test connection
    // echo "Connected to MongoDB successfully.";
} catch (Exception $e) {
    die("Error connecting to MongoDB: " . $e->getMessage());
}
?>
