<?php
require 'vendor/autoload.php';
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->OMP->articles;

$searchQuery = $_GET['search'] ?? '';
$filter = $searchQuery ? ['title' => new MongoDB\BSON\Regex($searchQuery, 'i')] : [];
$articles = $collection->find($filter, ['sort' => ['created_at' => -1]]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Open Mind | Articles</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Animate.css -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <!-- AOS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
   

 body { font-family: 'Poppins', sans-serif; background-color: #f7f9fc; color: #333; transition: background 0.3s, color 0.3s; }
.navbar { background-color: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
.navbar-brand h1 { font-weight: bold; background: linear-gradient(45deg, #6a11cb, #2575fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.nav-link { color: #333 !important; font-weight: 500; }
.nav-link:hover { color: #6a11cb !important; }
.card { border-radius: 1rem; padding: 20px; background: #fff; box-shadow: 0 5px 25px rgba(0,0,0,0.05); }
.btn-rounded { border-radius: 50px; }
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
  </style>
</head>
<body>

<!-- Navbar -->
<nav id="mainNavbar" class="navbar navbar-expand-lg navbar-light sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php"><h1>Open Mind</h1></a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
       
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
        <li class="nav-item"><a class="nav-link active" href="aboutUs.php">About Us</a></li>
      </ul>
      <div class="d-flex gap-2">
        <button id="darkToggle" class="dark-toggle-btn"><i class="fas fa-moon"></i></button>
      </div>
    </div>
  </div>
</nav>


<!-- Hero -->
<section class="hero-section" style="background: url('img/article.jpg') no-repeat center center/cover; height: 400px; display: flex; align-items: center;">
  <div class="container text-center">
    <h1 class="text-gradient" data-aos="fade-down">About Open Mind</h1>
    <p class="lead text-white" data-aos="fade-up" data-aos-delay="200">
      We’re dedicated to helping you on your journey toward mental clarity, emotional strength, and inner peace.
    </p>
  </div>
</section>

<!-- Articles Section -->
<div class="container py-5">
  <h1 class="mb-4">Wellness Articles</h1>

  <!-- Search -->
  <form class="mb-4" method="GET">
    <input type="text" name="search" placeholder="Search articles..." class="form-control" value="<?= htmlspecialchars($searchQuery) ?>">
  </form>

  <div class="row">
    <?php foreach ($articles as $article): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($article['title']) ?></h5>
            <h6 class="card-subtitle text-muted mb-2">By <?= $article['author'] ?> | <?= $article['reading_time'] ?> mins</h6>
            <p><?= htmlspecialchars(substr($article['content'], 0, 100)) ?>...</p>
            <a href="readArticle.php?id=<?= $article['_id'] ?>" class="btn btn-sm btn-primary">Read More</a>
            <div class="mt-2">
              <a href="https://www.facebook.com/sharer/sharer.php?u=https://yourdomain.com/readArticle.php?id=<?= $article['_id'] ?>" class="btn btn-sm btn-outline-secondary">Share</a>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<footer class="footer mt-5">
  <p>© <?= date('Y') ?> Open Mind | All Rights Reserved</p>
  <p><a href="home.php">Home</a> | <a href="about.php">About Us</a> | <a href="findSupport.php">Find Support</a>|<a href="talkWithRoob.php">Talk With Roob</a></p>
</footer>
<!-- Bootstrap JS (required for dropdown) -->


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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