<?php
require 'vendor/autoload.php';

$client = new MongoDB\Client(
    "mongodb+srv://hashiliyanage231_db_user:<db_password>@openmindcluster.u3wdvre.mongodb.net/?appName=OpenMindCluster"

$db = $client->OMP;

$therapistsCollection = $db->therapists;
$bookingsCollection = $db->bookings;
?>
