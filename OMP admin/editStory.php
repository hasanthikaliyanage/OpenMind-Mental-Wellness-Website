<?php
session_start();
require 'vendor/autoload.php';
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->OMP->stories;

$id = $_GET['id'] ?? null;
if (!$id) die("Invalid story ID");

$story = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $collection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($id)],
        ['$set' => [
            'name' => $_POST['name'],
            'title' => $_POST['title'],
            'story' => $_POST['story']
        ]]
    );
    header("Location: manageStories.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Story</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Story</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($story['name']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($story['title']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Story</label>
            <textarea name="story" rows="6" class="form-control" required><?= htmlspecialchars($story['story']) ?></textarea>
        </div>
        <button class="btn btn-success">Update Story</button>
        <a href="manageStories.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
