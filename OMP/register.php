<?php
require 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $existingUser = $collection->findOne(['email' => $email]);

    if ($existingUser) {
        $message = "User already exists!";
    } else {
        $collection->insertOne([
            'fullname' => $fullname,
            'email' => $email,
            'password' => $password
        ]);
        header("Location: login.php?registered=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | Mage Project</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #667eea, #764ba2); /* same purple-blue gradient as login page */
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Poppins', sans-serif;
      overflow: hidden;
    }

    /* bubbles animation */
    .bubbles {
      position: absolute;
      inset: 0;
      overflow: hidden;
      z-index: 0;
    }
    .bubble {
      position: absolute;
      bottom: -100px;
      border-radius: 50%;
      opacity: 0.1;
      animation: rise linear infinite;
      background: rgba(255,255,255,0.3);
    }
    .bubble.small { width: 30px; height: 30px; animation-duration: 12s; left: 15%; }
    .bubble.medium { width: 60px; height: 60px; animation-duration: 18s; left: 45%; }
    .bubble.large { width: 100px; height: 100px; animation-duration: 25s; left: 70%; }
    @keyframes rise {
      from { transform: translateY(0); opacity: 0.2; }
      to { transform: translateY(-120vh); opacity: 0; }
    }

    .card {
      border-radius: 1.5rem;
      padding: 2rem;
      box-shadow: 0 0 35px rgba(0,0,0,0.2);
      background: rgba(255,255,255,0.95);
      animation: fadeInUp 1s ease;
      width: 100%;
      max-width: 400px;
      position: relative;
      z-index: 2;
    }

    h3 {
      font-weight: 600;
      color: #1a1a1a;
    }

    .form-control {
      border-radius: 0.75rem;
      padding: 0.75rem;
      border: 1px solid #ccc;
    }

    .btn-primary {
      border-radius: 0.75rem;
      padding: 0.6rem 1.5rem;
      background: linear-gradient(to right, #667eea, #764ba2); /* same gradient */
      border: none;
      color: #fff;
      font-weight: 500;
      transition: transform 0.2s, opacity 0.2s;
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      opacity: 0.9;
    }

    .msg {
      margin-bottom: 1rem;
      font-weight: 500;
      color: #1a1a1a;
    }

    .login-link {
      margin-top: 10px;
      font-size: 14px;
      text-align: center;
    }
  </style>
</head>
<body>

<div class="bubbles">
  <div class="bubble small"></div>
  <div class="bubble medium"></div>
  <div class="bubble large"></div>
</div>

<div class="card animate__animated animate__fadeInUp">
  <h3 class="text-center mb-4">Create Account</h3>

  <?php if ($message): ?>
    <div class="alert alert-info text-center msg animate__animated animate__fadeInDown">
      <?= $message ?>
    </div>
  <?php endif; ?>

  <form method="POST" class="animate__animated animate__fadeInUp">
    <div class="mb-3">
      <input type="text" name="fullname" class="form-control" placeholder="Full Name" required>
    </div>
    <div class="mb-3">
      <input type="email" name="email" class="form-control" placeholder="Email Address" required>
    </div>
    <div class="mb-3">
      <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Register</button>
  </form>

  <div class="login-link">
    Already have an account? <a href="login.php">Login</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
