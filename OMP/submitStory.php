<?php
require 'vendor/autoload.php';
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->OMP->stories;

$collection->insertOne([
  'name' => $_POST['name'],
  'title' => $_POST['title'],
  'story' => $_POST['story'],
  'created_at' => new MongoDB\BSON\UTCDateTime()
]);

header('Location: blogs.php');
exit();
