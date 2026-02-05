<?php
session_start();
require 'vendor/autoload.php';
use MongoDB\Client;

if(!isset($_SESSION['therapist_index'])){
    echo json_encode([]);
    exit;
}

$client = new Client("mongodb://127.0.0.1:27017");
$db = $client->OMP;
$bookings = $db->bookings;

$therapistIndex = (int)$_SESSION['therapist_index'];

// Fetch only bookings for this therapist
$cursor = $bookings->find(['therapist_index' => $therapistIndex], ['sort' => ['created_at' => -1]]);
$result = iterator_to_array($cursor);

echo json_encode($result);
?>
