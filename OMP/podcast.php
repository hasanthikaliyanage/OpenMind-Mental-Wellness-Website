<?php
require 'vendor/autoload.php';

// MongoDB Connection
$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->OMP;
$podcasts = iterator_to_array($db->podcasts->find([], ['sort' => ['created_at' => -1]]));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Open Mind | Podcast</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- AOS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    body { font-family: 'Poppins', sans-serif; background: #f7f9fc; transition: background 0.3s, color 0.3s; }
    .navbar { background-color: #fff; box-shadow:0 5px 15px rgba(0,0,0,0.05); transition: background 0.3s; }
    .navbar-brand h1 { font-weight: bold; background: linear-gradient(45deg,#6a11cb,#2575fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .nav-link { color: #333 !important; font-weight: 500; transition: color 0.3s; }
    .nav-link:hover, .nav-item.active .nav-link { color: #6a11cb !important; }

    .card { border: none; border-radius: 1rem; box-shadow:0 8px 24px rgba(0,0,0,0.05); transition: transform 0.3s ease, background 0.3s, color 0.3s; }
    .card:hover { transform: translateY(-6px); }
    .card img { border-radius: 1rem; width: 100%; height: auto; }

    footer { background:#111; color:#bbb; padding:30px 0; text-align:center; transition: background 0.3s, color 0.3s; }
    footer a { color:#bbb; margin:0 8px; text-decoration:none; }
    footer a:hover { color:#fff; }

    /* Dark mode */
    body.dark-mode { background-color:#1e1e1e; color:#f5f5f5; }
    body.dark-mode .navbar { background-color:#2c2c2c; }
    body.dark-mode .nav-link { color:#f5f5f5 !important; }
    body.dark-mode .nav-link:hover { color:#ffb74d !important; }
    body.dark-mode .dropdown-menu { background-color:#2c2c2c; color:#f5f5f5; }
    body.dark-mode .dropdown-item { color:#f5f5f5; }
    body.dark-mode .dropdown-item:hover { background-color:#444; color:#ffb74d; }
    body.dark-mode .card { background-color:#2c2c2c; color:#f5f5f5; }
    body.dark-mode footer { background:#111; color:#bbb; }

    .dark-toggle-btn { border:none; background:none; font-size:1.3rem; cursor:pointer; color:#333; transition:color 0.3s; }
    body.dark-mode .dark-toggle-btn { color:#f5f5f5; }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top px-4 px-lg-5 py-3">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-gradient" href="index.php">
      <h1 class="m-0">Open Mind</h1>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="talkWithRoob.php">Talk With Roob</a></li>
        <li class="nav-item"><a class="nav-link" href="moodTracker.php">Mood Tracker</a></li>
        <li class="nav-item"><a class="nav-link" href="findSupport.php">Find Support</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle active" href="#" data-bs-toggle="dropdown">Relaxation</a>
          <ul class="dropdown-menu rounded shadow-sm">
            <li><a class="dropdown-item" href="blogs.php">Blog & Stories</a></li>
            <li><a class="dropdown-item" href="audioVideoTheropy.php">Audio/Video Therapy</a></li>
            <li><a class="dropdown-item" href="articles.php">Articles</a></li>
            <li><a class="dropdown-item" href="degitalDetox.php">Digital Detox</a></li>
            <li><a class="dropdown-item active" href="podcast.php">Podcast</a></li>
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
<section class="hero-section" style="background: url('img/podcast.jpg') no-repeat center center/cover; height: 400px; display: flex; align-items: center;">
  <div class="container text-center">
    <h1 class="text-gradient" data-aos="fade-down">Podcast</h1>
    <p class="lead text-white" data-aos="fade-up" data-aos-delay="200">
Let's get cozy and talk about the little things.    </p>
  </div>
</section>

<!-- Content -->
<div class="container py-5">
  <h2 data-aos="fade-right">Podcasts</h2>
  <div class="row g-4">
    <?php if(!empty($podcasts) && count($podcasts) > 0): ?>
      <?php foreach($podcasts as $p): ?>
        <?php 
          // Extract YouTube video ID
          preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $p['youtube_url'], $matches);
          $videoId = $matches[1] ?? '';
          $thumbnail = $videoId ? "https://img.youtube.com/vi/$videoId/hqdefault.jpg" : 'default-thumbnail.jpg';
        ?>
        <div class="col-md-4" data-aos="zoom-in">
          <div class="card">
            <img src="<?= htmlspecialchars($thumbnail) ?>" alt="Video Thumbnail">
            <div class="p-3">
              <h5><?= htmlspecialchars($p['title']) ?></h5>
              <p><?= htmlspecialchars($p['description']) ?></p>
              <a href="<?= htmlspecialchars($p['youtube_url']) ?>" target="_blank" class="btn btn-primary w-100"><i class="bi bi-play-circle"></i> Watch Video</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-muted">No podcasts available yet.</p>
    <?php endif; ?>
  </div>
</div>

<!-- Footer -->
<footer class="footer mt-5">
  <p>© <?= date('Y') ?> Open Mind | All Rights Reserved</p>
  <p>
    <a href="home.php">Home</a> | 
    <a href="aboutUs.php">About Us</a> | 
    <a href="findSupport.php">Find Support</a> | 
    <a href="talkWithRoob.php">Talk With Roob</a>
  </p>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();
  const toggleBtn = document.getElementById("darkToggle");
  toggleBtn.addEventListener("click", ()=>{
    document.body.classList.toggle("dark-mode");
    const icon = toggleBtn.querySelector("i");
    icon.classList.toggle("fa-moon");
    icon.classList.toggle("fa-sun");
  });
</script>
</body>
</html>
