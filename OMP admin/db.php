<?php
require 'vendor/autoload.php'; // MongoDB PHP driver autoload

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $db = $client->OMP; // database name
    $admins = $db->admins;   // collection for admins
} catch (Exception $e) {
    die("Error connecting to MongoDB: " . $e->getMessage());
}
?>
