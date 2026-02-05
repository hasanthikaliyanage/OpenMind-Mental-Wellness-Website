<?php
session_start();
require '../vendor/autoload.php';

if (!isset($_SESSION['therapist'])) {
    header("Location: therapistLogin.php");
    exit;
}

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->OMP->bookings;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = new MongoDB\BSON\ObjectId($_POST['id']);
    $status = $_POST['status'];

    $collection->updateOne(
        ['_id' => $id],
        ['$set' => ['status' => $status]]
    );
}

header("Location: therapistDashboard.php");
exit;
