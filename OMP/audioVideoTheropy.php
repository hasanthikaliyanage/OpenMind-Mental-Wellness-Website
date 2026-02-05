<?php
session_start();
require 'vendor/autoload.php'; // Composer dependencies

use MongoDB\Client;

try {
    // Connect to MongoDB
    $client = new Client("mongodb://localhost:27017");
    $collection = $client->OMP->therapy;

    // Fetch all therapy sessions (most recent first)
    $sessionsCursor = $collection->find([], ['sort' => ['created_at' => -1]]);
    $sessions = iterator_to_array($sessionsCursor);
} catch (Exception $e) {
    die("Error connecting to MongoDB: " . $e->getMessage());
}

// Helper: Convert stored path into accessible URL
function getMediaUrl($path) {
    // If it's already a full URL, just return it
    if (preg_match('/^https?:\/\//', $path)) {
        return $path;
    }
    // Otherwise, use the path as-is (uploads/audio/... is already correct)
    return $path;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Audio & Video Therapy - Open Mind</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- AOS -->
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
body { font-family: 'Poppins', sans-serif; background: #f7f9fc; }
.navbar { background-color: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
.navbar-brand h1 { font-weight: bold; background: linear-gradient(45deg, #6a11cb, #2575fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.nav-link { color: #333 !important; font-weight: 500; }
.nav-link:hover, .nav-item.active .nav-link { color: #6a11cb !important; }
.hero-section { background: linear-gradient(135deg, #6a11cb, #2575fc); color: white; padding: 80px 20px; text-align: center; }
.hero-section h1 { font-size: 3rem; font-weight: 700; }
.hero-section p { font-size: 1.2rem; }
.therapy-card { border-radius: 16px; overflow: hidden; box-shadow: 0 4px 14px rgba(0,0,0,0.1); background: white; transition: transform 0.3s; text-decoration: none; color: inherit; }
.therapy-card:hover { transform: translateY(-5px); }
.therapy-card img { width: 100%; height: 200px; object-fit: cover; border-bottom: 1px solid #eee; }
.therapy-card audio, .therapy-card iframe, .therapy-card video { width: 100%; border-radius: 8px; }
.section-title { text-align: center; margin: 50px 0 30px; font-size: 2.4rem; font-weight: 600; color: #333; }
.empty-message { text-align: center; font-size: 1.2rem; color: #555; margin: 30px 0; }
body.dark-mode { background-color: #1e1e1e; color: #f5f5f5; }
body.dark-mode .navbar { background-color: #2c2c2c; }
body.dark-mode .nav-link { color: #f5f5f5 !important; }
body.dark-mode .nav-link:hover { color: #ffb74d !important; }
body.dark-mode .card { background-color: #2c2c2c; color: #f5f5f5; }
.dark-toggle-btn { border: none; background: none; font-size: 1.3rem; cursor: pointer; color: #333; }
body.dark-mode .dark-toggle-btn { color: #f5f5f5; }
footer { background: #111; color: #bbb; padding: 30px 0; text-align: center; }
footer a { color: #bbb; margin: 0 8px; text-decoration: none; }
footer a:hover { color: #fff; }
@media (max-width: 768px) { .hero-section h1 { font-size: 2rem; } }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light sticky-top px-4 px-lg-5 py-3">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="home.php"><h1 class="m-0">Open Mind</h1></a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
       
        <li class="nav-item"><a class="nav-link" href="moodTracker.php">Mood Tracker</a></li>
        <li class="nav-item"><a class="nav-link" href="findSupport.php">Find Support</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle active" href="#" id="relaxDropdown" role="button" data-bs-toggle="dropdown">Relaxation</a>
          <ul class="dropdown-menu rounded shadow-sm">
            <li><a class="dropdown-item" href="blogs.php">Blog & Stories</a></li>
            <li><a class="dropdown-item active" href="audioVideoTheropy.php">Audio/Video Therapy</a></li>
            <li><a class="dropdown-item" href="articles.php">Articles</a></li>
            <li><a class="dropdown-item" href="degitalDetox.php">Digital Detox</a></li>
            <li><a class="dropdown-item" href="podcast.php">Podcast</a></li>
            <li><a class="dropdown-item" href="yoga.php">Yoga</a></li>
            <li><a class="dropdown-item" href="dreamAnalyzer.php">Dream Analyzer</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="aboutUs.php">About Us</a></li>
      </ul>
      <div class="d-flex gap-2">
        <button id="darkToggle" class="dark-toggle-btn"><i class="fas fa-moon"></i></button>
      </div>
    </div>
  </div>
</nav>


<!-- Hero -->
<section class="hero-section" style="background: url('img/audio.jpg') no-repeat center center/cover; height: 400px; display: flex; align-items: center;">
  <div class="container text-center">
    <h1 class="text-gradient" data-aos="fade-down">Audio & Video Therapy</h1>
    <p class="lead text-white" data-aos="fade-up" data-aos-delay="200">
      Relax, Reflect, and Heal at Your Own Pace

    </p>
  </div>
</section>


<div class="container py-5">

  <!-- Audio Therapy -->
  <h2 class="section-title" data-aos="fade-up">Audio Therapy Sessions</h2>
 
<div class="row g-4">
    <?php
    $video_found = false;
    foreach ($sessions as $session) {
        $session = (array)$session;

        if ($session['type'] === 'video') {
            $video_found = true;
            $src = getMediaUrl($session['src']);

            // If no thumbnail, try YouTube preview
            $thumbnail = $session['thumbnail'] ?? '';
            if (empty($thumbnail)) {
                if (preg_match('/youtu\.be\/([^\?&]+)/', $src, $matches) || 
                    preg_match('/youtube\.com\/watch\?v=([^\?&]+)/', $src, $matches)) {
                    $videoId = $matches[1];
                    $thumbnail = "https://img.youtube.com/vi/$videoId/hqdefault.jpg";
                }
            }
            ?>
            <div class="col-md-6 col-lg-4" data-aos="zoom-in">
                <div class="therapy-card p-2">
                    <?php if (!empty($thumbnail)): ?>
                        <img src="<?= htmlspecialchars(getMediaUrl($thumbnail)) ?>" alt="<?= htmlspecialchars($session['title']) ?>">
                    <?php else: ?>
                        <iframe src="<?= htmlspecialchars($src) ?>" title="<?= htmlspecialchars($session['title']) ?>" allowfullscreen></iframe>
                    <?php endif; ?>
                    <h6 class="mt-2"><?= htmlspecialchars($session['title']) ?></h6>
                </div>
            </div>
            <?php
        }
    }
    if (!$video_found) {
        echo '<div class="col-12 empty-message">No video therapy sessions available yet.</div>';
    }
    ?>
  </div>
</div>
  <!-- Video Therapy -->
  <h2 class="section-title mt-5" data-aos="fade-up">Video Therapy Guides</h2>
  <div class="row g-4">
    <?php
    $video_found = false;
    foreach ($sessions as $session) {
        $session = (array)$session;

        if ($session['type'] === 'video') {
            $video_found = true;
            $src = getMediaUrl($session['src']);

            // If no thumbnail, try YouTube preview
            $thumbnail = $session['thumbnail'] ?? '';
            if (empty($thumbnail)) {
                if (preg_match('/youtu\.be\/([^\?&]+)/', $src, $matches) || 
                    preg_match('/youtube\.com\/watch\?v=([^\?&]+)/', $src, $matches)) {
                    $videoId = $matches[1];
                    $thumbnail = "https://img.youtube.com/vi/$videoId/hqdefault.jpg";
                }
            }
            ?>
            <div class="col-md-6 col-lg-4" data-aos="zoom-in">
                <div class="therapy-card p-2">
                    <?php if (!empty($thumbnail)): ?>
                        <img src="<?= htmlspecialchars(getMediaUrl($thumbnail)) ?>" alt="<?= htmlspecialchars($session['title']) ?>">
                    <?php else: ?>
                        <iframe src="<?= htmlspecialchars($src) ?>" title="<?= htmlspecialchars($session['title']) ?>" allowfullscreen></iframe>
                    <?php endif; ?>
                    <h6 class="mt-2"><?= htmlspecialchars($session['title']) ?></h6>
                </div>
            </div>
            <?php
        }
    }
    if (!$video_found) {
        echo '<div class="col-12 empty-message">No video therapy sessions available yet.</div>';
    }
    ?>
  </div>
</div>

<footer class="footer mt-5">
  <p>© <?= date('Y') ?> Open Mind | All Rights Reserved</p>
  <p><a href="home.php">Home</a> | <a href="about.php">About Us</a> | <a href="findSupport.php">Find Support</a> | <a href="talkWithRoob.php">Talk With Roob</a></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({ once: true });</script>
<script>
const toggleBtn = document.getElementById("darkToggle");
toggleBtn.addEventListener("click", () => {
  document.body.classList.toggle("dark-mode");
  const icon = toggleBtn.querySelector("i");
  if(document.body.classList.contains("dark-mode")) icon.classList.replace("fa-moon","fa-sun");
  else icon.classList.replace("fa-sun","fa-moon");
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