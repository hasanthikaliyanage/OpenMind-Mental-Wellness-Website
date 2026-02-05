<?php
session_start();
require 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $admin = $admins->findOne(['email' => $email]);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin'] = $admin['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "❌ Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
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
        <h2 class="mb-3 text-center">🔐 Admin Login</h2>
        <?php if($message): ?>
            <div class="alert alert-danger"><?= $message ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100">Login</button>
        </form>
        <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register</a></p>
    </div>
</body>
</html>
