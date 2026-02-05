<?php
session_start();
require 'vendor/autoload.php';

// Check if admin logged in
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$adminName = $_SESSION['admin'];

// MongoDB Connection
$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->OMP;
$podcastsCollection = $db->podcasts;

// Handle Add Podcast
if (isset($_POST['add_podcast'])) {
    $videoUrl = $_POST['youtube_url'];

    // Convert YouTube URLs to embed format
    if (strpos($videoUrl, "watch?v=") !== false) {
        $videoUrl = str_replace("watch?v=", "embed/", $videoUrl);
    } elseif (strpos($videoUrl, "youtu.be/") !== false) {
        $videoUrl = str_replace("youtu.be/", "www.youtube.com/embed/", $videoUrl);
    }

    // Extract video ID for thumbnail
    preg_match('/embed\/([a-zA-Z0-9_-]+)/', $videoUrl, $matches);
    $videoId = $matches[1] ?? '';
    $thumbnail = $videoId ? "https://img.youtube.com/vi/$videoId/hqdefault.jpg" : 'default-thumbnail.jpg';

    $podcastsCollection->insertOne([
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'youtube_url' => $videoUrl,
        'thumbnail' => $thumbnail,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);

    header("Location: managePodcast.php");
    exit;
}

// Handle Delete Podcast
if (isset($_GET['delete'])) {
    $podcastsCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_GET['delete'])]);
    header("Location: managePodcast.php");
    exit;
}

// Fetch all podcasts
$podcasts = $podcastsCollection->find([], ['sort' => ['created_at' => -1]]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Podcasts - Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
body { background:#f4f6f9; font-family: 'Segoe UI', sans-serif; }
.sidebar { height:100vh; background:#2c3e50; padding-top:20px; position:fixed; left:0; top:0; width:250px; overflow-y:auto; }
.sidebar h4 { font-weight:bold; margin-bottom:30px; color:#fff; text-align:center; }
.sidebar a { color:white; display:block; padding:12px 20px; text-decoration:none; transition:0.3s; font-size:15px; }
.sidebar a:hover { background:#1abc9c; padding-left:25px; }
.sidebar .active { background:#16a085; }
.content { margin-left:250px; padding:20px; }
.navbar { margin-left:250px; }
.card { border-radius:15px; box-shadow:0 4px 15px rgba(0,0,0,0.1); }
.card img { border-radius:15px; width:100%; height:auto; }
.dark-mode { background-color:#1e1e1e; color:#f5f5f5; }
body.dark-mode .sidebar { background:#111; }
body.dark-mode .sidebar a { color:#f5f5f5; }
body.dark-mode .sidebar a:hover { background:#ffb74d; }
body.dark-mode .navbar { background:#2c2c2c; color:#f5f5f5; }
body.dark-mode .card { background-color:#2c2c2c; color:#f5f5f5; }
.dark-toggle-btn { border:none; background:none; font-size:1.3rem; cursor:pointer; color:#333; transition:color 0.3s; }
body.dark-mode .dark-toggle-btn { color:#f5f5f5; }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4>Admin Panel</h4>
  <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="manageUsers.php"><i class="bi bi-people"></i> Manage Users</a>
  <a href="manageBookings.php"><i class="bi bi-calendar-check"></i> Bookings</a>
  <a href="manageStories.php"><i class="bi bi-journal-text"></i> Blog & Stories</a>
  <a href="manageTherapy.php"><i class="bi bi-film"></i> Audio/Video Therapy</a>
  <a href="manageArticles.php"><i class="bi bi-file-earmark-text"></i> Articles</a>
  <a href="manageDetox.php"><i class="bi bi-phone-off"></i> Digital Detox</a>
  <a href="managePodcast.php" class="active"><i class="bi bi-mic"></i> Podcast</a>
  <a href="manageYoga.php"><i class="bi bi-heart"></i> Yoga</a>
  <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Manage Podcasts</a>
    <div class="ms-auto text-white"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($adminName) ?></div>
    <button id="darkToggle" class="dark-toggle-btn ms-3"><i class="bi bi-moon-fill"></i></button>
  </div>
</nav>

<div class="content">
  <h2>Podcast Management</h2>
  <p class="text-muted">Add or remove YouTube podcasts for users.</p>

  <!-- Add Podcast -->
  <div class="card p-4 mb-4">
    <h5>Add New Podcast (YouTube)</h5>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3" required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">YouTube URL</label>
        <input type="url" name="youtube_url" class="form-control" placeholder="https://www.youtube.com/watch?v=xxxx" required>
      </div>
      <button type="submit" name="add_podcast" class="btn btn-success">Add Podcast</button>
    </form>
  </div>

  <!-- Existing Podcasts -->
  <div class="card p-4 mb-4">
    <h5>Existing Podcasts</h5>
    <div class="row g-4">
      <?php foreach($podcasts as $p): ?>
        <div class="col-md-4">
          <div class="card">
            <img src="<?= htmlspecialchars($p['thumbnail'] ?? 'default-thumbnail.jpg') ?>" alt="Video Thumbnail">
            <div class="p-3">
              <h6><?= htmlspecialchars($p['title']) ?></h6>
              <p><?= htmlspecialchars($p['description']) ?></p>
              <a href="<?= htmlspecialchars($p['youtube_url']) ?>" target="_blank" class="btn btn-sm btn-primary w-100 mb-2"><i class="bi bi-play-circle"></i> Watch Video</a>
              <a href="?delete=<?= $p['_id'] ?>" onclick="return confirm('Delete this podcast?')" class="btn btn-sm btn-danger w-100">Delete</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if(empty($podcasts->toArray())): ?>
        <p class="text-muted">No podcasts available yet.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const toggleBtn = document.getElementById("darkToggle");
toggleBtn.addEventListener("click", ()=>{
    document.body.classList.toggle("dark-mode");
    const icon = toggleBtn.querySelector("i");
    icon.classList.toggle("bi-moon-fill");
    icon.classList.toggle("bi-sun-fill");
});
</script>
</body>
</html>
