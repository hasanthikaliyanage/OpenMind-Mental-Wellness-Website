<?php
session_start();

// Fix: Correct path to Composer autoload
require __DIR__ . '/vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->OMP->therapists;

$error = "";

// Define therapist specialties & indexes
$therapistIndexes = [
    'Child Therapist' => 0,
    'Marriage Counselor' => 1,
    'Anxiety Specialist' => 2
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $therapist = $collection->findOne(['email' => $email]);

    if ($therapist && password_verify($password, $therapist['password'])) {
        // Store therapist info in session, including index
        $_SESSION['therapist'] = [
            'id' => (string)$therapist['_id'],
            'name' => $therapist['name'],
            'email' => $therapist['email'],
            'specialty' => $therapist['specialty'],
            'index' => $therapistIndexes[$therapist['specialty']] ?? null
        ];
        header("Location: therapistDashboard.php");
        exit;
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Therapist Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card p-4 shadow">
        <h3 class="mb-3">Therapist Login</h3>
        <?php if($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <p class="mt-3">Not registered? <a href="therapistRegister.php">Register here</a></p>
    </div>
</div>
</body>
</html>
