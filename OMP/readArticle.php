<?php
require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->OMP->articles;

$id = $_GET['id'] ?? '';
if (!$id) die("Article not found.");

$article = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
if (!$article) die("Article not found.");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($article['title']) ?> | Open Mind</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <li class="nav-item"><a class="nav-link" href="talkWithRoob.php">Talk With Roob</a></li>
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


<!-- Article Content -->
<div class="container py-5">
  <div class="card mx-auto" style="max-width:800px;">
    <h1 class="mb-3"><?= htmlspecialchars($article['title']) ?></h1>
    <p class="text-muted mb-4">By <?= htmlspecialchars($article['author']) ?> | <?= $article['reading_time'] ?> mins read</p>
    <hr>
    <p><?= nl2br(htmlspecialchars($article['content'])) ?></p>
    <div class="mt-4">
      <a href="articles.php" class="btn btn-secondary btn-rounded">Back to Articles</a>

      <!-- Social Share Buttons -->
      <a href="https://www.facebook.com/sharer/sharer.php?u=https://yourdomain.com/readArticle.php?id=<?= $article['_id'] ?>" target="_blank" class="btn btn-primary btn-rounded ms-2">
        <i class="fab fa-facebook-f"></i> Share
      </a>
      <a href="https://twitter.com/intent/tweet?url=https://yourdomain.com/readArticle.php?id=<?= $article['_id'] ?>" target="_blank" class="btn btn-info btn-rounded ms-2 text-white">
        <i class="fab fa-twitter"></i> Tweet
      </a>
    </div>
  </div>
</div>

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