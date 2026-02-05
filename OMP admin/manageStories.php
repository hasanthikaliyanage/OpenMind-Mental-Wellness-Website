<?php
session_start();
require 'vendor/autoload.php';

// Check admin login
if (!isset($_SESSION['admin'])) {
    header('Location: login.php'); exit;
}

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->OMP->stories;

// Handle Delete
if (isset($_GET['delete_id'])) {
    $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_GET['delete_id'])]);
    header("Location: manageStories.php"); exit;
}

// Handle Add Story
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_story'])) {
    $collection->insertOne([
        'name' => $_POST['name'],
        'title' => $_POST['title'],
        'story' => $_POST['story'],
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);
    header("Location: manageStories.php"); exit;
}

// Fetch all stories (most recent first)
$stories = $collection->find([], ['sort' => ['created_at' => -1]]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin | Manage Stories</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f9;
    margin: 0;
}
.sidebar {
    height: 100vh;
    background: #2c3e50;
    padding-top: 20px;
    position: fixed;
    left: 0;
    top: 0;
    width: 250px;
}
.sidebar h4 {
    color: white;
    text-align: center;
    margin-bottom: 30px;
}
.sidebar a {
    color: white;
    display: block;
    padding: 12px 20px;
    text-decoration: none;
    font-size: 15px;
}
.sidebar a:hover { background: #1abc9c; padding-left: 25px; }
.sidebar .active { background: #16a085; }
.content { margin-left: 250px; padding: 20px; }
.table td, .table th { vertical-align: middle; }
@media (max-width: 768px) {
    .sidebar { position: absolute; left: -250px; }
    .sidebar.active { left: 0; }
    .content { margin-left: 0; }
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h4>Admin Panel</h4>
    <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="manageUsers.php"><i class="bi bi-people"></i> Manage Users</a>
    <a href="manageBookings.php"><i class="bi bi-calendar-check"></i> Bookings</a>
    <a href="manageStories.php" class="active"><i class="bi bi-journal-text"></i> Blog & Stories</a>
    <a href="manageTherapy.php"><i class="bi bi-film"></i> Audio/Video Therapy</a>
    <a href="manageArticles.php"><i class="bi bi-file-earmark-text"></i> Articles</a>
    <a href="manageDetox.php"><i class="bi bi-phone-off"></i> Digital Detox</a>
    <a href="managePodcast.php"><i class="bi bi-mic"></i> Podcast</a>
    <a href="manageYoga.php"><i class="bi bi-heart"></i> Yoga</a>
    
    <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Content -->
<div class="content">
    <h2>Manage Stories</h2>
    
    <!-- Add Story Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addStoryModal">
        <i class="bi bi-plus-circle"></i> Add Story
    </button>

    <!-- Stories Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php $i=1; foreach($stories as $story): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($story['title']) ?></td>
                    <td><?= htmlspecialchars($story['name']) ?></td>
                    <td><?= date('Y-m-d H:i', $story['created_at']->toDateTime()->getTimestamp()) ?></td>
                    <td>
                        <a href="editStory.php?id=<?= $story['_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="manageStories.php?delete_id=<?= $story['_id'] ?>" onclick="return confirm('Delete this story?')" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Story Modal -->
<div class="modal fade" id="addStoryModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Add New Story</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" required class="form-control">
          </div>
          <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" required class="form-control">
          </div>
          <div class="mb-3">
            <label>Story</label>
            <textarea name="story" rows="5" required class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_story" class="btn btn-primary">Add Story</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
