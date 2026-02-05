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
$articlesCollection = $db->articles;

// Handle Add Article
if (isset($_POST['add_article'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $content = $_POST['content'];
    $reading_time = $_POST['reading_time'];
    $created_at = new MongoDB\BSON\UTCDateTime();

    $articlesCollection->insertOne([
        'title' => $title,
        'author' => $author,
        'content' => $content,
        'reading_time' => $reading_time,
        'created_at' => $created_at
    ]);

    header('Location: manageArticles.php');
    exit;
}

// Handle Delete Article
if (isset($_GET['delete'])) {
    $id = new MongoDB\BSON\ObjectId($_GET['delete']);
    $articlesCollection->deleteOne(['_id' => $id]);
    header('Location: manageArticles.php');
    exit;
}

// Fetch all articles and convert cursor to array
$articlesCursor = $articlesCollection->find([], ['sort' => ['created_at' => -1]]);
$articles = $articlesCursor->toArray();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Articles - Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; margin:0; }
.sidebar { height:100vh; background:#2c3e50; padding-top:20px; position:fixed; left:0; top:0; width:250px; transition: all 0.3s ease; overflow-y:auto; }
.sidebar h4 { font-weight:bold; margin-bottom:30px; color:#fff; text-align:center; }
.sidebar a { color:white; display:block; padding:12px 20px; text-decoration:none; transition:0.3s; font-size:15px; }
.sidebar a:hover { background:#1abc9c; padding-left:25px; }
.sidebar .active { background:#16a085; }
.content { margin-left:250px; padding:20px; }
.navbar { margin-left:250px; transition: all 0.3s; }
.card { border-radius:15px; box-shadow:0 4px 15px rgba(0,0,0,0.1); transition: transform 0.2s, box-shadow 0.2s; }
.card:hover { transform: translateY(-5px); box-shadow:0 8px 20px rgba(0,0,0,0.15); }
.toggle-btn { display:none; cursor:pointer; }
@media (max-width: 768px){ .sidebar{position:absolute;left:-250px;} .sidebar.active{left:0;} .content,.navbar{margin-left:0;} .toggle-btn{display:inline-block; margin-right:15px;} }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h4>Admin Panel</h4>
    <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="manageUsers.php"><i class="bi bi-people"></i> Manage Users</a>
    <a href="manageBookings.php"><i class="bi bi-calendar-check"></i> Bookings</a>
    <a href="manageStories.php"><i class="bi bi-journal-text"></i> Blog & Stories</a>
    <a href="manageTherapy.php"><i class="bi bi-film"></i> Audio/Video Therapy</a>
    <a href="manageArticles.php" class="active"><i class="bi bi-file-earmark-text"></i> Articles</a>
    <a href="manageDetox.php"><i class="bi bi-phone-off"></i> Digital Detox</a>
    <a href="managePodcast.php"><i class="bi bi-mic"></i> Podcast</a>
    <a href="manageYoga.php"><i class="bi bi-heart"></i> Yoga</a>
    <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="toggle-btn text-white" onclick="toggleSidebar()"><i class="bi bi-list fs-3"></i></span>
        <a class="navbar-brand ms-2" href="#">Manage Articles</a>
        <div class="ms-auto text-white"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($adminName) ?></div>
    </div>
</nav>

<!-- Content -->
<div class="content">
    <h2>Articles Management</h2>
    <p class="text-muted">Add, Edit, or Delete wellness articles.</p>

    <!-- Add Article Form -->
    <div class="card p-4 mb-4">
        <h5>Add New Article</h5>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Author</label>
                <input type="text" name="author" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Reading Time (mins)</label>
                <input type="number" name="reading_time" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" name="add_article" class="btn btn-success">Add Article</button>
        </form>
    </div>

    <!-- Articles Table -->
    <div class="card p-4">
        <h5>Existing Articles</h5>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Reading Time</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($articles)): ?>
                        <?php foreach($articles as $article): ?>
                            <tr>
                                <td><?= htmlspecialchars($article['title']) ?></td>
                                <td><?= htmlspecialchars($article['author']) ?></td>
                                <td><?= htmlspecialchars($article['reading_time']) ?> min</td>
                                <td><?= date('Y-m-d H:i', $article['created_at']->toDateTime()->getTimestamp()) ?></td>
                                <td>
                                    <a href="editArticle.php?id=<?= $article['_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="?delete=<?= $article['_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">No articles found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("active");
}
</script>

</body>
</html>
