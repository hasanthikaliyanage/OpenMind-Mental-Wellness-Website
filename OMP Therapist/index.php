<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Therapist Portal | Open Mind</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      color: white;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
    }

    .hero-container {
      max-width: 700px;
    }

    h1 {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 20px;
    }

    p {
      font-size: 1.2rem;
      margin-bottom: 40px;
    }

    .btn-hero {
      border-radius: 50px;
      padding: 12px 35px;
      font-size: 1.1rem;
      font-weight: 600;
      transition: all 0.3s ease;
      margin: 0 10px;
    }

    .btn-login {
      background: white;
      color: #2575fc;
    }

    .btn-login:hover {
      background: #2575fc;
      color: white;
    }

    .btn-register {
      background: transparent;
      border: 2px solid white;
      color: white;
    }

    .btn-register:hover {
      background: white;
      color: #2575fc;
      border: 2px solid white;
    }

    @media (max-width: 576px) {
      h1 { font-size: 2rem; }
      .btn-hero { font-size: 1rem; padding: 10px 25px; }
    }
  </style>
</head>
<body>

  <div class="hero-container">
    <h1>Welcome to Therapist Portal</h1>
    <p>Manage your sessions, track bookings, and connect with clients efficiently.</p>
    <div>
      <!-- Correct absolute path for XAMPP subfolder -->
      <a href="/OMP Therapist/therapistLogin.php" class="btn btn-hero btn-login">
        <i class="fas fa-sign-in-alt"></i> Login
      </a>
      <a href="/OMP Therapist/therapistRegister.php" class="btn btn-hero btn-register">
        <i class="fas fa-user-plus"></i> Register
      </a>
    </div>
  </div>

</body>
</html>
