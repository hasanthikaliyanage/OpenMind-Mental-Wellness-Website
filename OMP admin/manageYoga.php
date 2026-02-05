<?php
session_start();
require 'vendor/autoload.php';

// Admin check
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$adminName = $_SESSION['admin'];

$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->OMP;
$yogaCollection = $db->yoga;

// Helper function to get YouTube thumbnail
function getYoutubeThumbnail($url) {
    $videoId = '';
    if (preg_match('/youtu\.be\/([^\?&]+)/', $url, $matches) || 
        preg_match('/youtube\.com\/watch\?v=([^\?&]+)/', $url, $matches)) {
        $videoId = $matches[1];
    }
    if($videoId) return "https://img.youtube.com/vi/$videoId/hqdefault.jpg";
    return 'uploads/default-thumbnail.jpg';
}

// Handle Add Yoga Session
if (isset($_POST['add_yoga'])) {
    $title = $_POST['title'];
    $video_url = $_POST['video_url'];
    $description = $_POST['description'];
    $benefits = $_POST['benefits'];

    $yogaCollection->insertOne([
        'title' => $title,
        'video_url' => $video_url,
        'description' => $description,
        'benefits' => $benefits,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);

    header("Location: manageYoga.php");
    exit;
}

// Handle Edit Yoga Session
if (isset($_POST['edit_yoga'])) {
    $id = $_POST['yoga_id'];
    $title = $_POST['title'];
    $video_url = $_POST['video_url'];
    $description = $_POST['description'];
    $benefits = $_POST['benefits'];

    $yogaCollection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($id)],
        ['$set' => [
            'title' => $title,
            'video_url' => $video_url,
            'description' => $description,
            'benefits' => $benefits
        ]]
    );
    header("Location: manageYoga.php");
    exit;
}

// Handle Delete Yoga Session
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $yogaCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    header("Location: manageYoga.php");
    exit;
}

// Fetch all yoga sessions
$yogas = $yogaCollection->find([], ['sort' => ['created_at' => -1]]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Yoga | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; margin:0; padding:0; }
.sidebar { height:100vh; width:250px; position:fixed; top:0; left:0; background:#2c3e50; padding-top:20px; overflow-y:auto; }
.sidebar h4 { color:#fff; text-align:center; margin-bottom:30px; font-weight:bold; }
.sidebar a { display:block; color:white; padding:12px 20px; text-decoration:none; transition:0.3s; }
.sidebar a:hover { background:#1abc9c; padding-left:25px; }
.sidebar .active { background:#16a085; }
.content { margin-left:250px; padding:20px; }
.navbar { margin-left:250px; }
.card { border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1); transition:0.2s; }
.card:hover { transform: translateY(-4px); box-shadow:0 8px 25px rgba(0,0,0,0.15); }
.video-thumb { width:100%; height:180px; object-fit:cover; border-radius:10px; margin-bottom:10px; }
.modal textarea { resize:none; }
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
  <a href="managePodcast.php"><i class="bi bi-mic"></i> Podcast</a>
  <a href="manageYoga.php" class="active"><i class="bi bi-heart"></i> Yoga</a>
  
  <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Manage Yoga</a>
    <div class="ms-auto text-white"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($adminName) ?></div>
  </div>
</nav>

<div class="content">
    <h2 class="mb-4">Yoga Sessions</h2>
    <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addYogaModal">
        <i class="bi bi-plus-circle"></i> Add New Yoga Session
    </button>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($yogas as $yoga): ?>
        <?php $id = (string)$yoga['_id']; ?>
        <div class="col">
            <div class="card h-100 p-3">
                <?php $thumb = !empty($yoga['thumbnail']) ? $yoga['thumbnail'] : getYoutubeThumbnail($yoga['video_url']); ?>
                <img src="<?= htmlspecialchars($thumb) ?>" alt="<?= htmlspecialchars($yoga['title']) ?>" class="video-thumb">
                <h5><?= htmlspecialchars($yoga['title']) ?></h5>
                <p><?= htmlspecialchars($yoga['description']) ?></p>
                <strong>Benefits:</strong>
                <ul>
                    <?php foreach(explode("\n", $yoga['benefits']) as $b) echo "<li>".htmlspecialchars($b)."</li>"; ?>
                </ul>
                <a href="<?= htmlspecialchars($yoga['video_url']) ?>" target="_blank" class="btn btn-sm btn-primary w-100 mb-2">
                    <i class="bi bi-play-circle"></i> Watch Video
                </a>
                <button class="btn btn-sm btn-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#editYogaModal<?= $id ?>">
                    <i class="bi bi-pencil-square"></i> Edit
                </button>
                <a href="?delete=<?= $id ?>" class="btn btn-sm btn-danger w-100" onclick="return confirm('Are you sure to delete this session?')">
                    <i class="bi bi-trash"></i> Delete
                </a>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editYogaModal<?= $id ?>" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Yoga Session</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="yoga_id" value="<?= $id ?>">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($yoga['title']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Video URL</label>
                                <input type="text" name="video_url" class="form-control" value="<?= htmlspecialchars($yoga['video_url']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" rows="3" class="form-control" required><?= htmlspecialchars($yoga['description']) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Benefits (one per line)</label>
                                <textarea name="benefits" rows="3" class="form-control" required><?= htmlspecialchars($yoga['benefits']) ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="edit_yoga" class="btn btn-success">Update Session</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Add Yoga Modal -->
<div class="modal fade" id="addYogaModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Yoga Session</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST">
      <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Video URL</label>
            <input type="text" name="video_url" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" rows="3" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Benefits (one per line)</label>
            <textarea name="benefits" rows="3" class="form-control" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_yoga" class="btn btn-success">Add Session</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
