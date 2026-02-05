<?php
session_start();
require 'vendor/autoload.php';
use MongoDB\Client;

try {
    $client = new Client("mongodb://localhost:27017");
    $yogaCollection = $client->OMP->yoga;
    $yogas = $yogaCollection->find([], ['sort' => ['created_at' => -1]]);
} catch (Exception $e) {
    die("Error connecting to MongoDB: " . $e->getMessage());
}

function getYoutubeThumbnail($url) {
    $videoId = '';
    if (preg_match('/youtu\.be\/([^\?&]+)/', $url, $matches) || 
        preg_match('/youtube\.com\/watch\?v=([^\?&]+)/', $url, $matches)) {
        $videoId = $matches[1];
    }
    if($videoId) return "https://img.youtube.com/vi/$videoId/hqdefault.jpg";
    return 'uploads/default-thumbnail.jpg';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Yoga | Open Mind</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
body { font-family: 'Poppins', sans-serif; background-color: #f7f9fc; }
.navbar { box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
.navbar-brand h1 { font-weight: bold; background: linear-gradient(45deg,#6a11cb,#2575fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.nav-link { color: #333 !important; font-weight: 500; }
.nav-link:hover, .nav-item.active .nav-link { color: #6a11cb !important; }
.card { border: none; border-radius: 1rem; box-shadow: 0 8px 24px rgba(0,0,0,0.05); transition: transform 0.3s ease; }
.card:hover { transform: translateY(-6px); }
.modal-body img { max-height: 250px; object-fit: cover; }
.btn-outline-primary { border-radius: 50px; border-width: 2px; }
@media(max-width:768px){.hero-header h1{font-size:2rem;}}
footer { background: #111; color: #bbb; padding: 30px 0; text-align: center; }
footer a { color: #bbb; margin: 0 8px; text-decoration: none; }
footer a:hover { color: #fff; }
.dark-toggle-btn { background: none; border: none; font-size: 1.2rem; color: inherit; cursor: pointer; }

/* Dark mode */
body.dark-mode { background-color: #1e1e1e; color: #f5f5f5; }
body.dark-mode .navbar { background-color: #2c2c2c; }
body.dark-mode .nav-link { color: #f5f5f5 !important; }
body.dark-mode .nav-link:hover { color: #ffb74d !important; }
body.dark-mode .dropdown-menu { background-color: #2c2c2c; color: #f5f5f5; }
body.dark-mode .dropdown-item { color: #f5f5f5; }
body.dark-mode .dropdown-item:hover { background-color: #444; color: #ffb74d; }
body.dark-mode .card { background-color: #2c2c2c; color: #f5f5f5; }
body.dark-mode .form-control, body.dark-mode .form-select { background-color: #2c2c2c; color: #f5f5f5; border-color: #555; }
body.dark-mode footer { background: #111; color: #bbb; }
/* Fix navbar brand text in dark mode */
body.dark-mode .navbar-brand h1 { -webkit-background-clip: unset !important; -webkit-text-fill-color: #ffb74d !important; }
</style>
</head>
<body>

<!-- Navbar -->
<div class="container-xxl position-relative p-0">
  <nav id="mainNavbar" class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top px-4 px-lg-5 py-3">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold text-gradient" href="index.php">
        <h1 class="m-0">Open Mind</h1>
      </a>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link active" href="home.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="talkWithRoob.php">Talk With Roob</a></li>
          <li class="nav-item"><a class="nav-link" href="moodTracker.php">Mood Tracker</a></li>
          <li class="nav-item"><a class="nav-link" href="findSupport.php">Find Support</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="relaxDropdown" role="button" data-bs-toggle="dropdown">Relaxation</a>
            <ul class="dropdown-menu rounded shadow-sm" aria-labelledby="relaxDropdown">
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
        <div class="d-flex gap-2 align-items-center">
          <button id="darkToggle" class="dark-toggle-btn"><i class="bi bi-moon"></i></button>
        </div>
      </div>
    </div>
  </nav>
</div>

<!-- Yoga Hero -->
<section class="hero-section" style="background: url('img/yoga.jpg') no-repeat center center/cover; height: 400px; display: flex; align-items: center; justify-content: center; text-align: center;">
  <div class="container text-white">
    <h2 class="display-3 fw-bold" data-aos="fade-down">Yoga Sessions</h2>
  </div>
</section>

<!-- Yoga Cards -->
<div class="container py-4">
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach($yogas as $yoga): ?>
        <div class="col">
            <div class="card h-100 video-card">
                <?php
                    $thumbnail = !empty($yoga['thumbnail']) ? $yoga['thumbnail'] : '';
                    if(empty($thumbnail) && !empty($yoga['video_url'])) $thumbnail = getYoutubeThumbnail($yoga['video_url']);
                ?>
                <img src="<?= htmlspecialchars($thumbnail) ?>" alt="<?= htmlspecialchars($yoga['title']) ?>" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($yoga['title']) ?></h5>
                    <p><?= htmlspecialchars($yoga['description']) ?></p>
                    <?php if(!empty($yoga['benefits'])): ?>
                        <strong>Benefits:</strong>
                        <ul>
                        <?php foreach(explode("\n", $yoga['benefits']) as $b) echo "<li>".htmlspecialchars($b)."</li>"; ?>
                        </ul>
                    <?php endif; ?>
                    <?php if(!empty($yoga['video_url'])): ?>
                        <a href="<?= htmlspecialchars($yoga['video_url']) ?>" target="_blank" class="btn btn-primary w-100 mt-2">
                            <i class="bi bi-play-circle"></i> Watch Video
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Footer -->
<footer class="footer mt-5">
  <p>© <?= date('Y') ?> Open Mind | All Rights Reserved</p>
  <p><a href="home.php">Home</a> | <a href="about.php">About Us</a> | <a href="findSupport.php">Find Support</a> | <a href="talkWithRoob.php">Talk With Roob</a></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init();</script>
<script>
// Dark Mode Toggle
const toggleBtn = document.getElementById("darkToggle");
const navbar = document.getElementById("mainNavbar");

toggleBtn.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");

    // Swap navbar classes
    if(document.body.classList.contains("dark-mode")) {
        navbar.classList.remove("navbar-light","bg-white");
        navbar.classList.add("navbar-dark","bg-dark");
    } else {
        navbar.classList.remove("navbar-dark","bg-dark");
        navbar.classList.add("navbar-light","bg-white");
    }

    // Toggle icon
    const icon = toggleBtn.querySelector("i");
    if(document.body.classList.contains("dark-mode")){
        icon.classList.replace("bi-moon","bi-sun");
    } else {
        icon.classList.replace("bi-sun","bi-moon");
    }
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