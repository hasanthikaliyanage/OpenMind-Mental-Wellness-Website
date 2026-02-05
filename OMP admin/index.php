<?php
session_start();
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
            overflow: hidden;
        }

        /* animated background glow */
        body::before {
            content: "";
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at top left, rgba(255,255,255,0.15), transparent 60%),
                        radial-gradient(circle at bottom right, rgba(0,0,0,0.25), transparent 60%);
            animation: rotate 20s linear infinite;
            z-index: 0;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .card {
            position: relative;
            z-index: 1;
            border-radius: 20px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            color: white;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-8px);
        }

        .btn-custom {
            border-radius: 50px;
            padding: 12px 25px;
            font-size: 18px;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-custom:hover {
            transform: scale(1.1);
        }

        h1 {
            font-weight: bold;
        }

        p {
            font-size: 16px;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="card shadow-lg">
        <h1 class="mb-3">🚀 Welcome to Admin Panel</h1>
        <p class="mb-4">Manage your site easily and securely.</p>
        <div>
            <a href="login.php" class="btn btn-primary btn-custom me-3">Login</a>
            <a href="register.php" class="btn btn-success btn-custom">Register</a>
        </div>
    </div>
</body>
</html>
