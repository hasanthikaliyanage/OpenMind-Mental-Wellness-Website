<?php
require 'vendor/autoload.php'; // Composer autoload for MongoDB

try {
    // Connect to MongoDB
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->OMP->bookings;

    // Get form data
    $name    = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone   = $_POST['phone'] ?? '';
    $email   = $_POST['email'] ?? '';
    $date    = $_POST['date'] ?? '';
    $time    = $_POST['time'] ?? '';

    $therapists = [
        ['name' => 'Dr. Sarah Williams', 'specialty' => 'Child Therapist'],
        ['name' => 'Mr. John Doe', 'specialty' => 'Marriage Counselor'],
        ['name' => 'Ms. Emily Brown', 'specialty' => 'Anxiety Specialist']
    ];

    $index = (int) ($_POST['therapist_index'] ?? 0);
    $therapist = $therapists[$index] ?? ['name' => 'Unknown', 'specialty' => 'Unknown'];

    // Generate a unique patient number
    $patientNumber = 'PT-' . strtoupper(uniqid());

    // Fixed session price
    $sessionPrice = 5000;

    // Insert booking data into MongoDB
    $result = $collection->insertOne([
        'name' => $name,
        'address' => $address,
        'phone' => $phone,
        'email' => $email,
        'date' => $date,
        'time' => $time,
        'therapist_name' => $therapist['name'],
        'therapist_specialty' => $therapist['specialty'],
        'patient_number' => $patientNumber,
        'session_price' => $sessionPrice,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);

    // If insert successful, store data in session and redirect to generate receipt
    if ($result->getInsertedCount() > 0) {
        session_start();
        $_SESSION['receipt'] = [
            'name' => $name,
            'address' => $address,
            'phone' => $phone,
            'email' => $email,
            'date' => $date,
            'time' => $time,
            'therapist_name' => $therapist['name'],
            'therapist_specialty' => $therapist['specialty'],
            'patient_number' => $patientNumber,
            'session_price' => $sessionPrice
        ];

        header("Location: generateReceipt.php");
        exit();
    } else {
        echo "❌ Booking failed. Please try again.";
    }

} catch (Exception $e) {
    echo "MongoDB Error: " . $e->getMessage();
    exit();
}
