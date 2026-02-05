<?php
require __DIR__ . '/vendor/autoload.php';

// Therapist list (same as in findSupport.php)
$therapists = [
    [
        'name' => 'Dr. Sarah Williams',
        'specialty' => 'Child Therapist',
        'bio' => 'Specializes in early childhood development and emotional well-being.',
        'image' => 'img/sarah.jpeg'
    ],
    [
        'name' => 'Mr. John Doe',
        'specialty' => 'Marriage Counselor',
        'bio' => 'Experienced in couples therapy and conflict resolution.',
        'image' => 'img/johnn.jpeg'
    ],
    [
        'name' => 'Ms. Emily Brown',
        'specialty' => 'Anxiety Specialist',
        'bio' => 'Focuses on anxiety disorders and mindfulness-based therapy.',
        'image' => 'img/emily.jpeg'
    ]
];

// Get form data
$name = $_POST['name'] ?? '';
$address = $_POST['address'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';
$therapistIndex = $_POST['therapist_index'] ?? null;

// Validate therapist
if ($therapistIndex === null || !isset($therapists[$therapistIndex])) {
    die("Invalid therapist selected.");
}
$therapist = $therapists[$therapistIndex];

// Connect MongoDB
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->OMP->bookings;

// Insert booking
$collection->insertOne([
    'name' => $name,
    'address' => $address,
    'phone' => $phone,
    'email' => $email,
    'date' => $date,
    'time' => $time,
    'therapist_index' => $therapistIndex,
    'therapist' => $therapist['name'],
    'status' => 'Pending',
    'created_at' => new MongoDB\BSON\UTCDateTime()
]);

// Redirect to confirmation
header("Location: bookingConfirmation.php");
exit();
