<?php
session_start();
require 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    $exists = $admins->findOne(['email' => $email]);

    if ($exists) {
        $message = "❌ This email is already registered!";
    } else {
        $admins->insertOne([
            "username" => $username,
            "email" => $email,
            "password" => $password,
            "created_at" => new MongoDB\BSON\UTCDateTime()
        ]);
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff6a00, #ee0979);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            width: 400px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2 class="mb-3 text-center">🔑 Admin Register</h2>
        <?php if($message): ?>
            <div class="alert alert-danger"><?= $message ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-success w-100">Register</button>
        </form>
        <p class="mt-3 text-center">Already registered? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
