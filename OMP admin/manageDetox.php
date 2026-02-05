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
$challengesCollection = $db->detoxChallenges;
$videosCollection = $db->detoxVideos;

// Handle Add Challenge
if (isset($_POST['add_challenge'])) {
    $challengesCollection->insertOne([
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'difficulty' => $_POST['difficulty'],
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);
    header("Location: manageDetox.php");
    exit;
}

// Handle Add Video
if (isset($_POST['add_video'])) {
    $videoUrl = $_POST['video_url'];

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

    $videosCollection->insertOne([
        'title' => $_POST['video_title'],
        'description' => $_POST['video_desc'],
        'url' => $videoUrl,
        'thumbnail' => $thumbnail,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);
    header("Location: manageDetox.php");
    exit;
}

// Handle Delete
if (isset($_GET['delete_challenge'])) {
    $challengesCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_GET['delete_challenge'])]);
    header("Location: manageDetox.php");
    exit;
}
if (isset($_GET['delete_video'])) {
    $videosCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_GET['delete_video'])]);
    header("Location: manageDetox.php");
    exit;
}

// Fetch all data
$challenges = $challengesCollection->find([], ['sort' => ['created_at' => -1]]);
$videos = $videosCollection->find([], ['sort' => ['created_at' => -1]]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Digital Detox - Admin Dashboard</title>
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
  <a href="manageDetox.php" class="active"><i class="bi bi-phone-off"></i> Digital Detox</a>
  <a href="managePodcast.php"><i class="bi bi-mic"></i> Podcast</a>
  <a href="manageYoga.php"><i class="bi bi-heart"></i> Yoga</a>
  
  <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Manage Digital Detox</a>
    <div class="ms-auto text-white"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($adminName) ?></div>
  </div>
</nav>

<div class="content">
  <h2>Digital Detox Management</h2>
  <p class="text-muted">Add or remove Detox Challenges and Meditation Videos.</p>

  <!-- Add Challenge -->
  <div class="card p-4 mb-4">
    <h5>Add Detox Challenge</h5>
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
        <label class="form-label">Difficulty</label>
        <select name="difficulty" class="form-select" required>
          <option>Easy</option>
          <option>Medium</option>
          <option>Hard</option>
        </select>
      </div>
      <button type="submit" name="add_challenge" class="btn btn-success">Add Challenge</button>
    </form>
  </div>

  <!-- Add Video -->
  <div class="card p-4 mb-4">
    <h5>Add Meditation Video</h5>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Video Title</label>
        <input type="text" name="video_title" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="video_desc" class="form-control" rows="3" required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">YouTube URL</label>
        <input type="url" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=xxxx" required>
      </div>
      <button type="submit" name="add_video" class="btn btn-success">Add Video</button>
    </form>
  </div>

  <!-- Challenges Table -->
  <div class="card p-4 mb-4">
    <h5>Existing Challenges</h5>
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Title</th>
          <th>Description</th>
          <th>Difficulty</th>
          <th>Created At</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($challenges as $ch): ?>
          <tr>
            <td><?= htmlspecialchars($ch['title']) ?></td>
            <td><?= htmlspecialchars($ch['description']) ?></td>
            <td><span class="badge bg-info"><?= htmlspecialchars($ch['difficulty']) ?></span></td>
            <td><?= date('Y-m-d H:i', $ch['created_at']->toDateTime()->getTimestamp()) ?></td>
            <td><a href="?delete_challenge=<?= $ch['_id'] ?>" onclick="return confirm('Delete this challenge?')" class="btn btn-sm btn-danger">Delete</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Videos Table -->
  <div class="card p-4 mb-4">
    <h5>Existing Meditation Videos</h5>
    <div class="row g-3">
      <?php foreach ($videos as $vid): ?>
        <div class="col-md-4">
          <div class="card h-100">
            <img src="<?= isset($vid['thumbnail']) ? htmlspecialchars($vid['thumbnail']) : 'default-thumbnail.jpg'; ?>" class="card-img-top" alt="Video Thumbnail">
            <div class="card-body">
              <h6 class="card-title"><?= htmlspecialchars($vid['title']) ?></h6>
              <p class="card-text small text-muted"><?= htmlspecialchars($vid['description']) ?></p>
              <button class="btn btn-sm btn-primary w-100" data-bs-toggle="modal" data-bs-target="#videoModal<?= $vid['_id'] ?>">
                <i class="bi bi-play-circle"></i> Watch Video
              </button>
            </div>
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="videoModal<?= $vid['_id'] ?>" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title"><?= htmlspecialchars($vid['title']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <div class="ratio ratio-16x9">
                  <iframe src="<?= htmlspecialchars($vid['url']) ?>" allowfullscreen></iframe>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
