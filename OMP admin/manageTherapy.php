<?php
session_start();
require 'vendor/autoload.php';

// Check admin login
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->OMP->therapy;

// ✅ Handle Delete
if (isset($_GET['delete_id'])) {
    $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_GET['delete_id'])]);
    header("Location: manageTherapy.php");
    exit;
}

// ✅ Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_therapy'])) {
    $type = $_POST['type'];
    $title = $_POST['title'];
    $src = "";

    // Audio upload
    if ($type === "audio" && isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] === 0) {
        $uploadDir = "uploads/audio/";
        if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
        $fileName = time() . "_" . basename($_FILES['audio_file']['name']);
        $targetPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['audio_file']['tmp_name'], $targetPath)) {
            $src = $targetPath;
        }
    }

    // Video url
    if ($type === "video" && !empty($_POST['video_url'])) {
        $src = $_POST['video_url'];
    }

    if (!empty($src)) {
        $collection->insertOne([
            'type' => $type,
            'title' => $title,
            'src' => $src,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]);
    }
    header("Location: manageTherapy.php");
    exit;
}

// ✅ Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_therapy'])) {
    $id = new MongoDB\BSON\ObjectId($_POST['therapy_id']);
    $type = $_POST['type'];
    $title = $_POST['title'];
    $src = $_POST['old_src']; // default to old src

    if ($type === "audio" && isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] === 0) {
        $uploadDir = "uploads/audio/";
        if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
        $fileName = time() . "_" . basename($_FILES['audio_file']['name']);
        $targetPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['audio_file']['tmp_name'], $targetPath)) {
            $src = $targetPath;
        }
    }

    if ($type === "video" && !empty($_POST['video_url'])) {
        $src = $_POST['video_url'];
    }

    $collection->updateOne(
        ['_id' => $id],
        ['$set' => [
            'type' => $type,
            'title' => $title,
            'src' => $src
        ]]
    );

    header("Location: manageTherapy.php");
    exit;
}

// Fetch all sessions
$sessions = $collection->find([], ['sort' => ['created_at' => -1]]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin | Manage Therapy</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; }
.sidebar { height: 100vh; background: #2c3e50; padding-top: 20px; position: fixed; left: 0; top: 0; width: 250px; }
.sidebar h4 { color: white; text-align: center; margin-bottom: 30px; }
.sidebar a { color: white; display: block; padding: 12px 20px; text-decoration: none; font-size: 15px; }
.sidebar a:hover { background: #1abc9c; padding-left: 25px; }
.sidebar .active { background: #16a085; }
.content { margin-left: 250px; padding: 20px; }
.table td, .table th { vertical-align: middle; }
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
    <a href="manageTherapy.php" class="active"><i class="bi bi-film"></i> Audio/Video Therapy</a>
    <a href="manageArticles.php"><i class="bi bi-file-earmark-text"></i> Articles</a>
    <a href="manageDetox.php"><i class="bi bi-phone-off"></i> Digital Detox</a>
    <a href="managePodcast.php"><i class="bi bi-mic"></i> Podcast</a>
    <a href="manageYoga.php"><i class="bi bi-heart"></i> Yoga</a>
   
    <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<div class="content">
    <h2>Manage Audio & Video Therapy</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addTherapyModal">
        <i class="bi bi-plus-circle"></i> Add Session
    </button>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Source</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php $i=1; foreach($sessions as $session): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($session['type']) ?></td>
                    <td><?= htmlspecialchars($session['title']) ?></td>
                    <td>
                        <?php if ($session['type'] === 'audio'): ?>
                            <audio controls>
                                <source src="<?= htmlspecialchars($session['src']) ?>" type="audio/mpeg">
                            </audio>
                        <?php else: ?>
                            <a href="<?= htmlspecialchars($session['src']) ?>" target="_blank"><?= htmlspecialchars($session['src']) ?></a>
                        <?php endif; ?>
                    </td>
                    <td><?= date('Y-m-d H:i', $session['created_at']->toDateTime()->getTimestamp()) ?></td>
                    <td>
                        <!-- Edit -->
                        <button class="btn btn-sm btn-warning" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editTherapyModal<?= $session['_id'] ?>">Edit</button>
                        <!-- Delete -->
                        <a href="manageTherapy.php?delete_id=<?= $session['_id'] ?>" 
                           onclick="return confirm('Delete this session?')" 
                           class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editTherapyModal<?= $session['_id'] ?>" tabindex="-1">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="therapy_id" value="<?= $session['_id'] ?>">
                        <input type="hidden" name="old_src" value="<?= htmlspecialchars($session['src']) ?>">
                        <div class="modal-header">
                          <h5 class="modal-title">Edit Therapy Session</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          <div class="mb-3">
                            <label>Type</label>
                            <select name="type" class="form-select" required>
                              <option value="audio" <?= ($session['type']==='audio')?'selected':'' ?>>Audio</option>
                              <option value="video" <?= ($session['type']==='video')?'selected':'' ?>>Video</option>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label>Title</label>
                            <input type="text" name="title" value="<?= htmlspecialchars($session['title']) ?>" class="form-control" required>
                          </div>
                          <div class="mb-3">
                            <label>Replace Audio (if audio)</label>
                            <input type="file" name="audio_file" accept="audio/*" class="form-control">
                          </div>
                          <div class="mb-3">
                            <label>Video URL (if video)</label>
                            <input type="text" name="video_url" value="<?= ($session['type']==='video')?htmlspecialchars($session['src']):'' ?>" class="form-control">
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" name="update_therapy" class="btn btn-primary">Update</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addTherapyModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Add Therapy Session</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Type</label>
            <select name="type" id="typeSelect" class="form-select" required onchange="toggleInputs()">
              <option value="">Select type</option>
              <option value="audio">Audio</option>
              <option value="video">Video</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="mb-3" id="audioInput" style="display:none;">
            <label>Upload Audio File</label>
            <input type="file" name="audio_file" accept="audio/*" class="form-control">
          </div>
          <div class="mb-3" id="videoInput" style="display:none;">
            <label>Video URL</label>
            <input type="text" name="video_url" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_therapy" class="btn btn-primary">Add Session</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleInputs() {
    const type = document.getElementById('typeSelect').value;
    document.getElementById('audioInput').style.display = (type === 'audio') ? 'block' : 'none';
    document.getElementById('videoInput').style.display = (type === 'video') ? 'block' : 'none';
}
</script>
</body>
</html>
