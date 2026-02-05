<?php
require 'vendor/autoload.php';
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->OMP->stories;

$id = $_GET['id'];
$story = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $collection->updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($id)],
    ['$set' => [
      'name' => $_POST['name'],
      'title' => $_POST['title'],
      'story' => $_POST['story'],
    ]]
  );
  header('Location: blogs.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Story</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
  <h2>Edit Your Story</h2>
  <form method="POST" class="card p-4 shadow">
    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="name" class="form-control" value="<?= $story['name'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Title</label>
      <input type="text" name="title" class="form-control" value="<?= $story['title'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Story</label>
      <textarea name="story" class="form-control" rows="5" required><?= $story['story'] ?></textarea>
    </div>
    <button class="btn btn-success">Update</button>
    <a href="blogs.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

</body>
</html>
