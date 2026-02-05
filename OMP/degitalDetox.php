<?php
require 'vendor/autoload.php';

// MongoDB Connection
$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->OMP;

// Collections
$videosCollection = $db->detoxVideos;
$challengesCollection = $db->detoxChallenges;

// Fetch challenges & videos
$challenges = iterator_to_array($challengesCollection->find([], ['sort' => ['created_at' => -1]]));
$videos = iterator_to_array($videosCollection->find([], ['sort' => ['created_at' => -1]]));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Open Mind | Digital Detox</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- AOS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    body { font-family: 'Poppins', sans-serif; background:#f7f9fc; transition: background 0.3s, color 0.3s; }
    .navbar { background:#fff; box-shadow:0 5px 15px rgba(0,0,0,0.05); transition: background 0.3s; }
    .navbar-brand h1 {
      font-weight: bold;
      background: linear-gradient(45deg, #6a11cb, #2575fc);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .nav-link { color:#333 !important; font-weight:500; transition: color 0.3s; }
    .nav-link:hover, .nav-item.active .nav-link { color:#6a11cb !important; }

    .hero-section {
      background: url('img/aboutus.jpg') no-repeat center center/cover;
      height: 400px;
      display: flex;
      align-items: center;
      color: white;
    }
    .hero-section h1 { font-size: 2.8rem; font-weight:bold; }

    .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 8px 24px rgba(0,0,0,0.05);
      transition: transform 0.3s ease, background 0.3s, color 0.3s;
    }
    .card:hover { transform: translateY(-6px); }

    .video-card iframe {
      border-radius: 1rem 1rem 0 0;
      width: 100%;
      height: 220px;
    }

    footer { background: #111; color: #bbb; padding: 30px 0; text-align: center; }
    footer a { color: #bbb; margin: 0 8px; text-decoration: none; }
    footer a:hover { color: #fff; }

    /* Dark mode */
    body.dark-mode { background-color: #1e1e1e; color: #f5f5f5; }
    body.dark-mode .navbar { background-color: #2c2c2c; }
    body.dark-mode .nav-link { color: #f5f5f5 !important; }
    body.dark-mode .nav-link:hover { color: #ffb74d !important; }
    body.dark-mode .dropdown-menu { background-color: #2c2c2c; color: #f5f5f5; }
    body.dark-mode .dropdown-item { color: #f5f5f5; }
    body.dark-mode .dropdown-item:hover { background-color: #444; color: #ffb74d; }
    body.dark-mode .card { background-color: #2c2c2c; color:#f5f5f5; }
    body.dark-mode footer { background: #111; color: #bbb; }
    body.dark-mode .form-control { background-color: #2c2c2c; color: #f5f5f5; border-color: #555; }

    .dark-toggle-btn { border: none; background: none; font-size: 1.3rem; cursor: pointer; color: #333; transition: color 0.3s; }
    body.dark-mode .dark-toggle-btn { color: #f5f5f5; }

    @media(max-width:768px){ .hero-section h1 { font-size: 2rem; } }
  </style>
</head>
<body>

<!-- Navbar -->
<nav id="mainNavbar" class="navbar navbar-expand-lg navbar-light sticky-top px-4 px-lg-5 py-3">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <h1 class="m-0">Open Mind</h1>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="moodTracker.php">Mood Tracker</a></li>
        <li class="nav-item"><a class="nav-link" href="findSupport.php">Find Support</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">Relaxation</a>
          <ul class="dropdown-menu rounded shadow-sm">
            <li><a class="dropdown-item" href="blogs.php">Blog & Stories</a></li>
            <li><a class="dropdown-item" href="audioVideoTheropy.php">Audio/Video Therapy</a></li>
            <li><a class="dropdown-item" href="articles.php">Articles</a></li>
            <li><a class="dropdown-item active" href="degitalDetox.php">Digital Detox</a></li>
            <li><a class="dropdown-item" href="podcast.php">Podcast</a></li>
            <li><a class="dropdown-item" href="yoga.php">Yoga</a></li>
            <li><a class="dropdown-item" href="dreamAnalyzer.php">Dream Analyzer</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="aboutUs.php">About Us</a></li>
      </ul>
      <button id="darkToggle" class="dark-toggle-btn"><i class="fas fa-moon"></i></button>
    </div>
  </div>
</nav>

<!-- Hero -->
<section class="hero-section" style="background: url('img/detox.jpg') no-repeat center center/cover; height: 400px; display: flex; align-items: center;">
  <div class="container text-center">
    <h1 class="text-gradient" data-aos="fade-down">Digital Detox Journey</h1>
    <p class="lead text-white" data-aos="fade-up" data-aos-delay="200">
      Take challenges, track your progress, and unlock mindfulness content.
    </p>
  </div>
</section>






<!-- Content -->
<div class="container py-5">

  <!-- Detox Challenges -->
  <h2 class="mb-4" data-aos="fade-right">Detox Challenges</h2>
  <div class="row g-4">
    <?php if(count($challenges) > 0): ?>
      <?php foreach($challenges as $ch): ?>
        <div class="col-md-4" data-aos="zoom-in">
          <div class="card h-100 p-3">
            <h5><?= htmlspecialchars($ch['title']) ?></h5>
            <p><?= htmlspecialchars($ch['description']) ?></p>
            <small class="text-muted">Added on: <?= date('M d, Y', strtotime($ch['created_at'])) ?></small>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-muted">No challenges available.</p>
    <?php endif; ?>
  </div>

  <!-- Videos -->
  <h2 class="mt-5 mb-4" data-aos="fade-right">Mindfulness Videos</h2>
  <div class="row g-4">
    <?php if(count($videos) > 0): ?>
      <?php foreach($videos as $vid): ?>
        <div class="col-md-4" data-aos="zoom-in">
          <div class="card video-card h-100">
            <iframe src="<?= htmlspecialchars($vid['url']) ?>" frameborder="0" allowfullscreen></iframe>
            <div class="card-body">
              <h6><?= htmlspecialchars($vid['title']) ?></h6>
              <p class="text-muted small"><?= htmlspecialchars($vid['description']) ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-muted">No videos uploaded yet.</p>
    <?php endif; ?>
  </div>
</div>

<!-- Footer -->
<footer>
  <p>&copy; <?= date('Y') ?> Open Mind. All rights reserved.</p>
  <div>
    <a href="#">Privacy</a> | <a href="#">Terms</a> | <a href="#">Contact</a>
  </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init();</script>
<script>
const toggleBtn = document.getElementById("darkToggle");
const navbar = document.getElementById("mainNavbar");

toggleBtn.addEventListener("click", ()=>{
    document.body.classList.toggle("dark-mode");

    if(document.body.classList.contains("dark-mode")){
        navbar.classList.remove("navbar-light");
        navbar.classList.add("navbar-dark");
    } else {
        navbar.classList.remove("navbar-dark");
        navbar.classList.add("navbar-light");
    }

    const icon = toggleBtn.querySelector("i");
    if(document.body.classList.contains("dark-mode")) icon.classList.replace("fa-moon","fa-sun");
    else icon.classList.replace("fa-sun","fa-moon");
});
</script>

<!-- Chatbot -->
<script src="/mental-wellness-chatbot/chat-widget.js"></script>
</body>
</html>
