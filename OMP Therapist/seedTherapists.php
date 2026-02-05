<?php
require 'vendor/autoload.php';
use MongoDB\Client;

$client = new Client("mongodb://127.0.0.1:27017");
$db = $client->OMP;
$therapists = $db->therapists;

$therapists->drop(); // remove old data

$therapists->insertMany([
    [
        'index' => 0,
        'name' => 'Dr. Sarah Williams',
        'specialty' => 'Child Therapist',
        'bio' => 'Specializes in early childhood development and emotional well-being.',
        'email' => 'sarah@example.com',
        'image' => 'img/sarah.jpeg'
    ],
    [
        'index' => 1,
        'name' => 'Mr. John Doe',
        'specialty' => 'Marriage Counselor',
        'bio' => 'Experienced in couples therapy and conflict resolution.',
        'email' => 'john@example.com',
        'image' => 'img/johnn.jpeg'
    ],
    [
        'index' => 2,
        'name' => 'Ms. Emily Brown',
        'specialty' => 'Anxiety Specialist',
        'bio' => 'Focuses on anxiety disorders and mindfulness-based therapy.',
        'email' => 'emily@example.com',
        'image' => 'img/emily.jpeg'
    ]
]);

echo "Therapists inserted successfully!";
