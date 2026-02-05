<?php
require 'vendor/autoload.php';
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->OMP->stories;

// Handle search
$search = $_GET['search'] ?? '';
$filter = [];
if (!empty($search)) {
    $regex = new MongoDB\BSON\Regex($search, 'i');
    $filter = ['$or' => [['title' => $regex], ['story' => $regex], ['name' => $regex]]];
}
$stories = $collection->find($filter, ['sort' => ['created_at' => -1]]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Blog & Stories | Open Mind</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
body {
  font-family: 'Poppins', sans-serif;
  background-color: #f7f9fc;
  transition: background 0.3s, color 0.3s;
}
.navbar {
  background-color: #fff;
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}
.navbar-brand h1 {
  font-weight: bold;
  background: linear-gradient(45deg, #6a11cb, #2575fc);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.nav-link {
  color: #333 !important;
  font-weight: 500;
}
.nav-link:hover, .nav-item.active .nav-link {
  color: #6a11cb !important;
}
.hero {
  background: linear-gradient(135deg, #6a11cb, #2575fc);
  color: white;
  text-align: center;
  padding: 80px 20px;
}
.hero h1 { font-weight: 700; font-size: 3rem; }
.search-bar { max-width: 500px; margin: -30px auto 40px auto; position: relative; }
.search-bar input { border-radius: 50px; padding-left: 40px; width: 100%; }
.search-bar .fa-search { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #888; }
.story-card {
  border: none;
  border-radius: 1rem;
  box-shadow: 0 8px 24px rgba(0,0,0,0.08);
  transition: transform 0.3s, box-shadow 0.3s;
  background: #fff;
  height: 100%;
}
.story-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(0,0,0,0.12); }
.story-card h5 { color: #6a11cb; font-weight: 600; }
.floating-btn {
  position: fixed; bottom: 100px; right: 30px;
  background: linear-gradient(135deg, #6a11cb, #2575fc);
  border: none; border-radius: 50%; width: 60px; height: 60px;
  color: white; font-size: 1.5rem; box-shadow: 0 8px 20px rgba(0,0,0,0.2); transition: transform 0.2s;
  z-index: 999;
}
.floating-btn:hover { transform: scale(1.1); }

/* Chatbot widget */
#chatbot-widget {
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 1000;
}

footer {
  background: #111; color: #bbb; padding: 30px 0; text-align: center;
}
footer a { color: #bbb; margin: 0 8px; text-decoration: none; }
footer a:hover { color: #fff; }

/* Dark Mode */
body.dark-mode { background-color: #1e1e1e; color: #f5f5f5; }
body.dark-mode .navbar { background-color: #2c2c2c; }
body.dark-mode .nav-link { color: #f5f5f5 !important; }
body.dark-mode .nav-link:hover { color: #ffb74d !important; }
body.dark-mode .dropdown-menu { background-color: #2c2c2c; }
body.dark-mode .dropdown-item { color: #f5f5f5; }
body.dark-mode .dropdown-item:hover { background-color: #444; color: #ffb74d; }
body.dark-mode .story-card { background-color: #2c2c2c; color: #f5f5f5; }
body.dark-mode .search-bar input { background-color: #2c2c2c; color: #f5f5f5; border-color: #555; }
body.dark-mode footer { background: #111; color: #bbb; }
.dark-toggle-btn { border: none; background: none; font-size: 1.3rem; cursor: pointer; color: #333; }
body.dark-mode .dark-toggle-btn { color: #f5f5f5; }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light sticky-top px-4 px-lg-5 py-3">
  <a class="navbar-brand fw-bold text-gradient" href="index.php"><h1 class="m-0">Open Mind</h1></a>
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
    <button class="dark-toggle-btn" id="darkToggle"><i class="fas fa-moon"></i></button>
  </div>
</nav>

<!-- Hero -->
<section class="hero">
  <h1 data-aos="fade-down">Blog & Stories</h1>
  <p data-aos="fade-up" data-aos-delay="200">Share your journey, inspire others ✨</p>
</section>

<!-- Search -->
<div class="search-bar">
  <i class="fa fa-search"></i>
  <form method="GET">
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search stories...">
  </form>
</div>

<!-- Stories -->
<div class="container pb-5">
  <div class="row g-4">
    <?php foreach ($stories as $story): ?>
    <div class="col-md-6 col-lg-4" data-aos="fade-up">
      <div class="card story-card p-3">
        <div class="card-body">
          <h5><?= htmlspecialchars($story['title']) ?></h5>
          <h6 class="text-muted mb-2">By <?= htmlspecialchars($story['name']) ?></h6>
          <p><?= nl2br(htmlspecialchars($story['story'])) ?></p> <!-- FULL STORY -->
          <a href="editStory.php?id=<?= $story['_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="deleteStory.php?id=<?= $story['_id'] ?>" onclick="return confirm('Delete this story?')" class="btn btn-sm btn-danger">Delete</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Floating Button -->
<button class="floating-btn" data-bs-toggle="modal" data-bs-target="#shareModal">
  <i class="fa fa-plus"></i>
</button>

<!-- Share Story Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3">
      <div class="modal-header border-0">
        <h5 class="modal-title">Share Your Story</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="submitStory.php" method="POST">
          <div class="mb-3"><label>Name</label><input type="text" name="name" required class="form-control"></div>
          <div class="mb-3"><label>Title</label><input type="text" name="title" required class="form-control"></div>
          <div class="mb-3"><label>Your Story</label><textarea name="story" required rows="5" class="form-control"></textarea></div>
          <button class="btn btn-primary w-100">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="footer mt-5">
  <p>© <?= date('Y') ?> Open Mind | All Rights Reserved</p>
  <p><a href="home.php">Home</a> | <a href="about.php">About Us</a> | <a href="findSupport.php">Find Support</a> | <a href="talkWithRoob.php">Talk With Roob</a></p>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init();</script>
<script>
const toggleBtn = document.getElementById("darkToggle");
toggleBtn.addEventListener("click", () => {
  document.body.classList.toggle("dark-mode");
  const icon = toggleBtn.querySelector("i");
  icon.classList.toggle("fa-moon");
  icon.classList.toggle("fa-sun");
});
</script>

<!-- Chatbot widget -->
<div id="chatbot-widget">
  <script src="/mental-wellness-chatbot/chat-widget.js"></script>
</div>

</body>
</html>
