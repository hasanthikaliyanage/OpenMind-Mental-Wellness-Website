<?php
require 'vendor/autoload.php'; 
session_start();

// MongoDB connection
$client = new MongoDB\Client("mongodb://localhost:27017");
$thoughtCollection = $client->OMP->userThoughts;

$message = "";

// Current user
$currentUser = $_SESSION['username'] ?? 'Guest';

// Add Thought
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_thought']) && !isset($_POST['edit_id'])) {
    $thought = trim($_POST['user_thought']);
    if(!empty($thought)){
        $thoughtCollection->insertOne([
            'user' => $currentUser,
            'thought' => $thought,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]);
        $message = "Your thought has been added!";
    } else {
        $message = "Please write something!";
    }
}

// Edit Thought
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_id'])) {
    $editId = new MongoDB\BSON\ObjectId($_POST['edit_id']);
    $updatedThought = trim($_POST['user_thought']);
    if(!empty($updatedThought)){
        $thoughtCollection->updateOne(
            ['_id' => $editId, 'user' => $currentUser],
            ['$set' => ['thought' => $updatedThought]]
        );
        $message = "Your thought has been updated!";
    }
}

// Delete Thought
if (isset($_GET['delete'])) {
    $deleteId = new MongoDB\BSON\ObjectId($_GET['delete']);
    $thoughtCollection->deleteOne(['_id' => $deleteId, 'user' => $currentUser]);
    $message = "Your thought has been deleted!";
}

// Fetch all thoughts
$thoughts = $thoughtCollection->find([], ['sort' => ['created_at' => -1]]);

// Check edit mode
$editThought = null;
if(isset($_GET['edit'])){
    $editId = new MongoDB\BSON\ObjectId($_GET['edit']);
    $doc = $thoughtCollection->findOne(['_id'=>$editId, 'user'=>$currentUser]);
    if($doc) $editThought = $doc;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>About Us | Open Mind</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; transition: background 0.3s, color 0.3s; }
.navbar { background: #fff; box-shadow: 0 3px 15px rgba(0,0,0,0.05); }
.navbar-brand h1 { font-weight: 700; background: linear-gradient(45deg, #6a11cb, #2575fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.nav-link { font-weight: 500; color: #333 !important; }
.nav-link:hover { color: #6a11cb !important; }
.hero-section { background: linear-gradient(135deg, #6a11cb, #2575fc); color: white; padding: 100px 0; text-align:center;}
.hero-section h1 { font-size: 3rem; font-weight: 700; }
.btn-rounded { border-radius: 50px; padding: 10px 30px; font-weight: 600; }
.section-title { font-size: 2.2rem; font-weight: 700; margin-bottom: 30px; text-align: center; color: #333; }
.text-gradient { background: linear-gradient(45deg, #6a11cb, #2575fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.mission, .vision { padding: 30px; border-radius: 15px; background: #fff; box-shadow: 0 5px 20px rgba(0,0,0,0.08); transition: transform 0.3s ease; }
.mission:hover, .vision:hover { transform: translateY(-8px); }
.review-card { background: #fff; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.06); padding: 20px; transition: 0.3s; position: relative; }
.review-card:hover { transform: scale(1.03); }
.review-card .actions { position: absolute; top: 10px; right: 15px; }
.review-card .actions a { margin-left: 5px; font-size: 0.9rem; color: #555; }
.review-card .actions a:hover { color: #000; }
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
body.dark-mode .mission, body.dark-mode .vision { background-color: #2c2c2c; color:#f5f5f5; }
body.dark-mode .review-card { background-color: #2c2c2c; color: #f5f5f5; }
body.dark-mode footer { background: #111; color: #bbb; }
body.dark-mode .form-control, body.dark-mode .form-select { background-color: #2c2c2c; color: #f5f5f5; border-color: #555; }

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
<section class="hero-section" style="background: url('img/aboutus.jpg') no-repeat center center/cover; height: 400px; display: flex; align-items: center;">
  <div class="container text-center">
    <h1 class="text-gradient" data-aos="fade-down">About Open Mind</h1>
    <p class="lead text-white" data-aos="fade-up" data-aos-delay="200">
      We’re dedicated to helping you on your journey toward mental clarity, emotional strength, and inner peace.
    </p>
  </div>
</section>


<!-- Who we are -->
<section class="container py-5">
  <h2 class="section-title text-gradient">Who We Are</h2>
  <p class="lead text-center px-md-5" data-aos="fade-up">
    Open Mind is a digital mental wellness platform offering AI-powered therapy tools, mood tracking, relaxation resources, and more.
    Our mission is to make emotional support accessible, stigma-free, and tailored to each individual.
  </p>
</section>

<!-- Mission / Vision -->
<section class="container pb-5">
  <div class="row g-4">
    <div class="col-md-6" data-aos="fade-right">
      <div class="mission">
        <h4 class="text-gradient">Our Mission</h4>
        <p>To empower individuals with affordable, private, and personalized mental wellness support through innovative technology and compassionate care.</p>
      </div>
    </div>
    <div class="col-md-6" data-aos="fade-left">
      <div class="vision">
        <h4 class="text-gradient">Our Vision</h4>
        <p>To create a world where every mind has the tools and support to thrive. We envision a future free of mental health stigma, built on awareness and community.</p>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials / Thoughts -->
<section class="container pb-5">
  <h2 class="section-title">What People Say</h2>
  <div class="row g-4">
    <?php foreach($thoughts as $t): ?>
    <div class="col-md-4" data-aos="zoom-in">
      <div class="review-card">
        <p><?= htmlspecialchars($t['thought']) ?></p>
        <strong>- <?= htmlspecialchars($t['user']) ?></strong>
        <?php if($t['user'] === $currentUser): ?>
        <div class="actions">
          <a href="?edit=<?= $t['_id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
          <a href="?delete=<?= $t['_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this thought?')">Delete</a>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Message -->
  <?php if($message): ?>
    <div class="alert alert-success text-center mt-4"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <!-- Add/Edit Thought Form -->
  <form method="POST" class="card p-4 mb-5 shadow-sm mt-4">
    <div class="mb-3">
      <label for="user_thought" class="form-label"><?= $editThought ? 'Edit Your Thought' : 'Share Your Thought' ?></label>
      <textarea name="user_thought" id="user_thought" class="form-control" rows="3" placeholder="Write something..." required><?= $editThought ? htmlspecialchars($editThought['thought']) : '' ?></textarea>
    </div>
    <?php if($editThought): ?>
      <input type="hidden" name="edit_id" value="<?= $editThought['_id'] ?>">
    <?php endif; ?>
    <button class="btn btn-primary w-100 btn-rounded"><?= $editThought ? 'Update' : 'Submit' ?></button>
  </form>
</section>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init();</script>
<script>
const toggleBtn = document.getElementById("darkToggle");
const navbar = document.getElementById("mainNavbar");

toggleBtn.addEventListener("click", ()=>{
    document.body.classList.toggle("dark-mode");

    // Toggle navbar
    if(document.body.classList.contains("dark-mode")){
        navbar.classList.remove("navbar-light");
        navbar.classList.add("navbar-dark");
    } else {
        navbar.classList.remove("navbar-dark");
        navbar.classList.add("navbar-light");
    }

    // Toggle icon
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