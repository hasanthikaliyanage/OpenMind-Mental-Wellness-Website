<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Open Mind | Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Animate.css -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <!-- AOS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    body { font-family: 'Poppins', sans-serif; font-size: 0.95rem; background-color: #f7f9fc; color: #333; transition: background-color 0.4s, color 0.4s; }
    body.dark-mode { background-color: #1e1e1e; color: #f5f5f5; }

    .navbar { background-color: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: background-color 0.4s; }
    .navbar-brand h1 { font-weight: bold; background: linear-gradient(45deg, #6a11cb, #2575fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .nav-link { color: #333 !important; font-weight: 500; transition: color 0.3s; font-size: 0.95rem; }
    .nav-link:hover, .nav-item.active .nav-link { color: #6a11cb !important; }

    body.dark-mode .navbar { background-color: #2c2c2c; }
    body.dark-mode .nav-link { color: #f5f5f5 !important; }
    body.dark-mode .nav-link:hover { color: #ffb74d !important; }
    .dark-toggle-btn { border: none; background: none; font-size: 1.2rem; cursor: pointer; color: #333; }
    body.dark-mode .dark-toggle-btn { color: #f5f5f5; }

    .hero-slide { height: 80vh; background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; }
    .text-shadow { text-shadow: 2px 2px 8px rgba(0,0,0,0.5); }

    .testimonial-card, .card { background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 5px 25px rgba(0,0,0,0.05); transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .testimonial-card:hover, .card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
    body.dark-mode .testimonial-card, body.dark-mode .card { background-color: #2c2c2c; color: #f5f5f5; }

    footer { background: #111; color: #bbb; padding: 20px 0; text-align: center; font-size: 0.85rem; }
    footer a { color: #bbb; margin: 0 5px; text-decoration: none; }
    footer a:hover { color: #fff; }

    @media (max-width: 768px) { .hero-header h1 { font-size: 1.8rem; } .hero-header p { font-size: 0.95rem; } .navbar-brand h1 { font-size: 1.4rem; } }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container">
    <a class="navbar-brand" href="home.php"><h1>Open Mind</h1></a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="home.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="moodTracker.php">Mood Tracker</a></li>
        <li class="nav-item"><a class="nav-link" href="findSupport.php">Find Support</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Relaxation</a>
          <ul class="dropdown-menu rounded shadow-sm">
            <li><a class="dropdown-item" href="blogs.php">Blog & Stories</a></li>
            <li><a class="dropdown-item" href="audioVideoTheropy.php">Audio/Video Therapy</a></li>
            <li><a class="dropdown-item" href="articles.php">Articles</a></li>
            <li><a class="dropdown-item" href="degitalDetox.php">Digital Detox</a></li>
            <li><a class="dropdown-item" href="podcast.php">Podcast</a></li>
            <li><a class="dropdown-item" href="yoga.php">Yoga</a></li>
            <li><a class="dropdown-item" href="dreamAnalyzer.php">Dream Analyzer</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="aboutUs.php">About Us</a></li>
      </ul>
      <button class="dark-toggle-btn" id="darkToggle"><i class="fas fa-moon"></i></button>
    </div>
  </div>
</nav>

<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
  </div>
  <div class="carousel-inner">

    <div class="carousel-item active">
      <div class="hero-slide" style="background-image: url('img/home1.jpg');">
        <div class="container text-center text-white">
          <h1 class="display-5 fw-bold text-shadow" data-aos="fade-right">Welcome to Open Mind</h1>
          <p class="lead mb-4" data-aos="fade-right">Discover tools to relax, grow, and heal.</p>
          <a href="findSupport.php" class="btn btn-light btn-lg rounded-pill px-4 py-2" data-aos="fade-right">Book Session</a>
        </div>
      </div>
    </div>

    <div class="carousel-item">
      <div class="hero-slide" style="background-image: url('img/home2.jpg');">
        <div class="container text-center text-white">
          <h1 class="display-5 fw-bold text-shadow" data-aos="fade-right">Embrace Mental Peace</h1>
          <p class="lead mb-4" data-aos="fade-right">Your safe space for healing, mindfulness, and emotional balance starts here.</p>
          <a href="findSupport.php" class="btn btn-light btn-lg rounded-pill px-4 py-2" data-aos="fade-right">Book Session</a>
        </div>
      </div>
    </div>

    <div class="carousel-item">
      <div class="hero-slide" style="background-image: url('img/home3.jpg');">
        <div class="container text-center text-white">
          <h1 class="display-5 fw-bold text-shadow" data-aos="fade-right">Maintain Your Fitness</h1>
          <p class="lead mb-4" data-aos="fade-right">Meet your AI companion, designed to listen, respond, and support your journey.</p>
          <a href="yoga.php" class="btn btn-light btn-lg rounded-pill px-4 py-2" data-aos="fade-right">Ready for Yoga</a>
        </div>
      </div>
    </div>

  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<!-- About Section -->
<section class="py-5">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
        <h2 class="fw-bold mb-3">About Us</h2>
        <p>At Open Mind, we support your mental well-being with AI tools, guided therapy, yoga, and more. We make mental wellness accessible to everyone.</p>
        <a href="aboutUs.php" class="btn btn-primary rounded-pill mt-3 px-4">Read More</a>
      </div>
      <div class="col-lg-6 text-center" data-aos="fade-left">
        <img src="img/about.jpeg" class="img-fluid rounded shadow" alt="About Us" style="max-height: 350px;">
      </div>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center mb-4" data-aos="fade-up">What Our Users Say</h2>
    <div class="row g-4">
      <div class="col-md-4" data-aos="flip-left">
        <div class="testimonial-card h-100">
          <p>"Open Mind helped me cope with anxiety. The tools are powerful and easy to use."</p>
          <strong>- Sarah K.</strong>
        </div>
      </div>
      <div class="col-md-4" data-aos="flip-up">
        <div class="testimonial-card h-100">
          <p>"The AI therapist is surprisingly helpful. It’s like talking to someone who really listens."</p>
          <strong>- Kevin L.</strong>
        </div>
      </div>
      <div class="col-md-4" data-aos="flip-right">
        <div class="testimonial-card h-100">
          <p>"Yoga and meditation guides are amazing. This site is my go-to relaxation space."</p>
          <strong>- Amara D.</strong>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer>
  <p>© <?= date('Y') ?> Open Mind | All Rights Reserved</p>
  <p><a href="home.php">Home</a> | <a href="about.php">About Us</a> | <a href="findSupport.php">Find Support</a> | <a href="talkWithRoob.php">Talk With Roob</a></p>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();
  const toggleBtn = document.getElementById("darkToggle");
  toggleBtn.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");
    const icon = toggleBtn.querySelector("i");
    icon.classList.toggle("fa-moon");
    icon.classList.toggle("fa-sun");
  });
</script>
</body>
</html>
<!-- Your existing page content -->
<html>
<head>
    <title>Your Website</title>
    <!-- Your existing head content -->
</head>
<body>
    <!-- Your existing page content -->
    
    <!-- Add this before closing </body> tag -->
    <script src="/mental-wellness-chatbot/chat-widget.js"></script>
</body>
</html>