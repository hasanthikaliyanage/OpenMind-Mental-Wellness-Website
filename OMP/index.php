<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mage Project | Mental Wellness</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css">

  <!-- Custom CSS -->
  <style>
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #7b2ff7, #f107a3);
      overflow: hidden;
      color: #fff;
    }

    /* Floating circles background */
    .bg-shape {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.15);
      animation: float 6s ease-in-out infinite;
    }
    .shape1 { width: 180px; height: 180px; top: 10%; left: 15%; }
    .shape2 { width: 250px; height: 250px; bottom: 15%; right: 10%; }
    .shape3 { width: 120px; height: 120px; bottom: 20%; left: 25%; }

    @keyframes float {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-20px); }
      100% { transform: translateY(0px); }
    }

    /* Hero content */
    .hero {
      position: relative;
      z-index: 2;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 20px;
    }

    .hero h1 {
      font-size: 3rem;
      font-weight: 700;
      text-shadow: 2px 2px 15px rgba(0, 0, 0, 0.3);
    }

    .hero p {
      font-size: 1.3rem;
      margin: 15px 0 25px;
      opacity: 0.9;
    }

    .btn-glow {
      padding: 14px 34px;
      border-radius: 50px;
      font-size: 1.1rem;
      border: none;
      background: #fff;
      color: #7b2ff7;
      font-weight: bold;
      box-shadow: 0px 5px 15px rgba(255, 255, 255, 0.3);
      transition: all 0.3s ease;
    }

    .btn-glow:hover {
      transform: translateY(-4px) scale(1.05);
      box-shadow: 0px 8px 25px rgba(255, 255, 255, 0.6);
      color: #f107a3;
    }
  </style>
</head>
<body>

  <!-- Floating Shapes -->
  <div class="bg-shape shape1"></div>
  <div class="bg-shape shape2"></div>
  <div class="bg-shape shape3"></div>

  <!-- Hero Section -->
  <div class="hero">
    <div>
      <h1 class="animate__animated animate__fadeInDown">Welcome to <span style="color:#ffe066;">Open Mind</span></h1>
      <p class="animate__animated animate__fadeInUp">Find balance, peace, and support for your mental wellness journey</p>
      <div class="mt-4 animate__animated animate__zoomIn">
        <a href="login.php" class="btn btn-glow me-3">Login</a>
        <a href="register.php" class="btn btn-glow">Register</a>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
