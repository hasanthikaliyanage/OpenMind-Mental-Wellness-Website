<?php
session_start();
require 'vendor/autoload.php';

if(!isset($_SESSION['admin'])){
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if($id){
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $db = $client->OMP;
    $usersCollection = $db->users;
    
    $usersCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
}

header("Location: manageUsers.php");
exit;
