<?php
session_start();
require 'vendor/autoload.php';
header('Content-Type: application/json');

if(!isset($_SESSION['admin'])){
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

$id = $_POST['id'] ?? null;
$action = $_POST['action'] ?? null;

if(!$id || !$action){
    echo json_encode(['status'=>'error','message'=>'Invalid request']);
    exit;
}

$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->OMP;
$usersCollection = $db->users;

try {
    if($action==='block'){
        $usersCollection->updateOne(['_id'=>new MongoDB\BSON\ObjectId($id)], ['$set'=>['status'=>'blocked']]);
    } elseif($action==='unblock'){
        $usersCollection->updateOne(['_id'=>new MongoDB\BSON\ObjectId($id)], ['$set'=>['status'=>'active']]);
    } elseif($action==='delete'){
        $usersCollection->deleteOne(['_id'=>new MongoDB\BSON\ObjectId($id)]);
    }
    echo json_encode(['status'=>'success']);
} catch(Exception $e){
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}
