<?php
require 'vendor/autoload.php';

// MongoDB Connection
$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->OMP;

// Collections
$dreamsCollection = $db->dreamAnalyzer;

// Fetch dreams
$dreams = iterator_to_array($dreamsCollection->find([], ['sort' => ['created_at' => -1]]));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Open Mind | Dream Analyzer</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- AOS -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
body {
  font-family: 'Poppins', sans-serif;
  margin: 0;
  overflow-x: hidden;
  background: linear-gradient(to bottom, #87CEEB 0%, #f0f9ff 100%);
  padding-top: 90px; /* space for navbar */
}

/* Clouds Container */
.clouds { position: fixed; top:0; left:0; width:100%; height:100%; pointer-events:none; z-index:0; }
.cloud { position: absolute; background: #fff; border-radius: 50%; opacity: 0.6; animation-name: floatClouds; animation-timing-function: linear; animation-iteration-count: infinite; }
.cloud:before, .cloud:after { content: ''; position: absolute; background: #fff; border-radius: 50%; }

/* Individual clouds */
.cloud1 { width: 250px; height: 120px; top: 50px; animation-duration: 60s; opacity:0.7; }
.cloud1:before { width:120px; height:120px; top:-40px; left:20px; }
.cloud1:after { width:160px; height:160px; top:-30px; left:90px; }

.cloud2 { width:180px; height:90px; top:150px; animation-duration:100s; opacity:0.5; }
.cloud2:before { width:90px; height:90px; top:-30px; left:15px; }
.cloud2:after { width:110px; height:110px; top:-20px; left:70px; }

.cloud3 { width:200px; height:100px; top:250px; animation-duration:80s; opacity:0.6; }
.cloud3:before { width:100px; height:100px; top:-40px; left:20px; }
.cloud3:after { width:120px; height:120px; top:-25px; left:80px; }

.cloud4 { width:160px; height:80px; top:350px; animation-duration:120s; opacity:0.4; }
.cloud4:before { width:80px; height:80px; top:-20px; left:15px; }
.cloud4:after { width:100px; height:100px; top:-15px; left:60px; }

.cloud5 { width:220px; height:110px; top:420px; animation-duration:140s; opacity:0.5; }
.cloud5:before { width:110px; height:110px; top:-40px; left:20px; }
.cloud5:after { width:130px; height:130px; top:-20px; left:90px; }

@keyframes floatClouds { 0%{transform:translateX(-300px);} 100%{transform:translateX(120vw);} }

/* Navbar */
.navbar { backdrop-filter: blur(15px); background-color: rgba(255,255,255,0.95); box-shadow: 0 8px 25px rgba(0,0,0,0.08); transition: all 0.3s ease; position: fixed; top:0; width:100%; z-index:1000; }
.navbar-brand h1 { font-weight:bold; font-size:1.8rem; background:linear-gradient(45deg,#6a11cb,#2575fc); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
.nav-link { color:#333 !important; font-weight:500; }
.nav-link:hover, .nav-item.active .nav-link { color:#6a11cb !important; }

/* Hero */
.hero-header { background: linear-gradient(transparent); color:#fff; padding:100px 0 80px 0; text-align:center; position: relative; z-index:1; border-radius:0 0 30px 30px; }
.hero-header h1 { font-size:2.8rem; }
.hero-header p { font-size:1.1rem; }

/* Form & Cards */
.dream-form, .dream-card { background:#fff; border-radius:1rem; box-shadow:0 5px 20px rgba(0,0,0,0.05); padding:25px; margin-bottom:30px; position: relative; z-index:2; }
.dream-card:hover { transform: translateY(-5px); transition:0.3s; }
.btn-primary { background: linear-gradient(45deg,#6a11cb,#2575fc); border:none; }

footer { background: #111; color: #bbb; padding: 30px 0; text-align: center; }
footer a { color: #bbb; margin: 0 8px; text-decoration: none; }
footer a:hover { color: #fff; }
@media(max-width:768px){ .hero-header h1 { font-size:2rem; } }
</style>
</head>
<body>

<!-- Clouds -->
<div class="clouds">
  <div class="cloud cloud1"></div>
  <div class="cloud cloud2"></div>
  <div class="cloud cloud3"></div>
  <div class="cloud cloud4"></div>
  <div class="cloud cloud5"></div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-gradient" href="index.php"><h1>Open Mind</h1></a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
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
        <li class="nav-item"><a class="nav-link" href="aboutUs.php">About Us</a></li>
      </ul>
      <div class="d-flex gap-2">
        
      </div>
    </div>
  </div>
</nav>

<!-- Hero -->
<section class="hero-header" data-aos="fade-down">
  <div class="container">
    <h1>Dream Analyzer</h1>
    <p class="lead">Share your dreams and uncover their meanings with our mindfulness tool.</p>
  </div>
</section>

<!-- Dream Form -->
<div class="container py-5">
  <div class="dream-form" data-aos="fade-up">
    <h4 class="mb-3">Submit Your Dream</h4>
    <form action="submitDream.php" method="post">
      <div class="mb-3">
        <label for="dreamTitle" class="form-label">Dream Title</label>
        <input type="text" class="form-control" id="dreamTitle" name="title" required>
      </div>
      <div class="mb-3">
        <label for="dreamDesc" class="form-label">Dream Description</label>
        <textarea class="form-control" id="dreamDesc" name="description" rows="4" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Analyze Dream</button>
    </form>
  </div>

  <h4 class="mb-4" data-aos="fade-up">Recent Dreams</h4>
  <div class="row">
    <?php if(count($dreams) > 0): ?>
      <?php foreach($dreams as $dream): ?>
  <div class="col-md-6 col-lg-4" data-aos="fade-up">
    <div class="dream-card">
      <h6><?= htmlspecialchars($dream['title']) ?></h6>
      <p><?= htmlspecialchars($dream['description']) ?></p>
      <?php if(!empty($dream['analysis'])): ?>
        <div class="alert alert-info small mt-2">
          <strong>Analysis:</strong> <?= htmlspecialchars($dream['analysis']) ?>
        </div>
      <?php endif; ?>
      <small class="text-muted">Submitted on: <?= date('M d, Y', strtotime($dream['created_at'])) ?></small>
    </div>
  </div>
<?php endforeach; ?>

    <?php else: ?>
      <p class="text-muted">No dreams submitted yet.</p>
    <?php endif; ?>
  </div>
</div>

<!-- Footer -->
<footer class="footer mt-5">
  <p>© <?= date('Y') ?> Open Mind | All Rights Reserved</p>
  <p><a href="home.php">Home</a> | <a href="about.php">About Us</a> | <a href="findSupport.php">Find Support</a>|<a href="talkWithRoob.php">Talk With Roob</a></p>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init();

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