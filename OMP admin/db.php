<?php
require 'vendor/autoload.php';

$client = new MongoDB\Client(
    "mongodb+srv://USERNAME:PASSWORD@openmindcluster.u3wdvre.mongodb.net/OMP?retryWrites=true&w=majority"
);

$db = $client->OMP;

$therapistsCollection = $db->therapists;
$bookingsCollection = $db->bookings;
?>
