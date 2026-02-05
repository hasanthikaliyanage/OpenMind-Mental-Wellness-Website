<?php
session_start();
require __DIR__ . '/vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->OMP->therapists;

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $specialty = $_POST['specialty'];

    // Check if email exists
    if ($collection->findOne(['email' => $email])) {
        $error = "Email already registered!";
    } else {
        $collection->insertOne([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'specialty' => $specialty
        ]);
        $_SESSION['therapist'] = [
            'name' => $name,
            'email' => $email,
            'specialty' => $specialty
        ];
        header("Location: therapistDashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Therapist Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card p-4 shadow">
        <h3>Therapist Registration</h3>
        <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        <form method="POST">
            <input type="text" name="name" class="form-control mb-3" placeholder="Full Name" required>
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <input type="text" name="specialty" class="form-control mb-3" placeholder="Specialty" required>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
        <p class="mt-3">Already registered? <a href="therapistLogin.php">Login here</a></p>
    </div>
</div>
</body>
</html>
