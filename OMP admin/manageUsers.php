<?php
session_start();
require 'vendor/autoload.php';

// Check if admin logged in
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$adminName = $_SESSION['admin']; // from login session

// MongoDB Connection
$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->OMP;
$usersCollection = $db->users;

// Handle search
$search = $_GET['search'] ?? '';
$filter = [];
if (!empty($search)) {
    $regex = new MongoDB\BSON\Regex($search, 'i');
    $filter = ['$or' => [
        ['name' => $regex],
        ['email' => $regex],
        ['role' => $regex]
    ]];
}

// Get users from MongoDB
$users = $usersCollection->find($filter, ['sort' => ['created_at' => -1]]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users | Admin Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <a href="manageUsers.php" class="active"><i class="bi bi-people"></i> Manage Users</a>
    <a href="manageBookings.php"><i class="bi bi-calendar-check"></i> Bookings</a>
    <a href="manageStories.php"><i class="bi bi-journal-text"></i> Blog & Stories</a>
    <a href="manageTherapy.php"><i class="bi bi-film"></i> Audio/Video Therapy</a>
    <a href="manageArticles.php"><i class="bi bi-file-earmark-text"></i> Articles</a>
    <a href="manageDetox.php"><i class="bi bi-phone-off"></i> Digital Detox</a>
    <a href="managePodcast.php"><i class="bi bi-mic"></i> Podcast</a>
    <a href="manageYoga.php"><i class="bi bi-heart"></i> Yoga</a>
  
    <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Content -->
<div class="content">
    <h2>Manage Users</h2>
    <p class="text-muted">View, edit, block or delete users.</p>

    <!-- Search -->
    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search users..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary"><i class="bi bi-search"></i></button>
        </div>
    </form>

    <!-- Users Table -->
    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; foreach($users as $user): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($user['name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($user['role'] ?? 'User') ?></td>
                    <td>
                        <?php
                        $status = $user['status'] ?? 'blocked';
                        if($status == 'active'): ?>
                            <span class="badge bg-success">Active</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Blocked</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($status == 'active'): ?>
                            <a href="blockUser.php?id=<?= $user['_id'] ?>" class="btn btn-sm btn-warning">Block</a>
                        <?php else: ?>
                            <a href="unblockUser.php?id=<?= $user['_id'] ?>" class="btn btn-sm btn-success">Unblock</a>
                        <?php endif; ?>
                        <a href="deleteUser.php?id=<?= $user['_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("active");
}
</script>

</body>
</html>
