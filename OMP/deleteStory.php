<?php
require 'vendor/autoload.php';
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->OMP->stories;

$id = $_GET['id'];
$collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);

header('Location: blogs.php');
exit();
