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

// ✅ Get counts (removed status filter for users)
$usersCount      = $db->users->countDocuments();
$bookingsCount   = $db->bookings->countDocuments();
$storiesCount    = $db->stories->countDocuments();
$mediaCount      = $db->media->countDocuments();
$therapyCount    = $db->therapy->countDocuments();
$articlesCount   = $db->articles->countDocuments();
$detoxCount      = $db->detox->countDocuments();
$podcastCount    = $db->podcasts->countDocuments();
$yogaCount       = $db->yoga->countDocuments();
$dreamsCount     = $db->dreams->countDocuments();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
    body {
        background: #f4f6f9;
        font-family: 'Segoe UI', sans-serif;
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
        transition: all 0.3s ease;
    }
    .sidebar h4 {
        font-weight: bold;
        margin-bottom: 30px;
    }
    .sidebar a {
        color: white;
        display: block;
        padding: 12px 20px;
        text-decoration: none;
        transition: 0.3s;
        font-size: 15px;
    }
    .sidebar a:hover {
        background: #1abc9c;
        padding-left: 25px;
    }
    .sidebar .active {
        background: #16a085;
    }
    .content {
        margin-left: 250px;
        padding: 20px;
    }
    .navbar {
        margin-left: 250px;
        transition: all 0.3s;
    }
    .card {
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    @media (max-width: 992px) {
        .sidebar { width: 200px; }
        .content, .navbar { margin-left: 200px; }
    }
    @media (max-width: 768px) {
        .sidebar { position: absolute; left: -250px; }
        .sidebar.active { left: 0; }
        .content, .navbar { margin-left: 0; }
    }
    .toggle-btn { display: none; cursor: pointer; }
    @media (max-width: 768px) {
        .toggle-btn { display: inline-block; margin-right: 15px; }
    }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h4 class="text-center text-white">Admin Panel</h4>
    <a href="dashboard.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="manageUsers.php"><i class="bi bi-people"></i> Manage Users</a>
    <a href="manageBookings.php"><i class="bi bi-calendar-check"></i> Bookings</a>
    <a href="manageStories.php"><i class="bi bi-journal-text"></i> Blog & Stories</a>
    <a href="manageTherapy.php"><i class="bi bi-film"></i> Audio/Video Therapy</a>
    <a href="manageArticles.php"><i class="bi bi-file-earmark-text"></i> Articles</a>
    <a href="manageDetox.php"><i class="bi bi-phone-off"></i> Digital Detox</a>
    <a href="managePodcast.php"><i class="bi bi-mic"></i> Podcast</a>
    <a href="manageYoga.php"><i class="bi bi-heart"></i> Yoga</a>
    
    <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="toggle-btn text-white" onclick="toggleSidebar()"><i class="bi bi-list fs-3"></i></span>
        <a class="navbar-brand ms-2" href="#">Dashboard</a>
        <div class="ms-auto text-white">
            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($adminName) ?>
        </div>
    </div>
</nav>

<!-- Content -->
<div class="content">
    <h2>Welcome, <?= htmlspecialchars($adminName); ?> 👋</h2>
    <p class="text-muted">Here's an overview of your platform activity.</p>

    <div class="row mt-4 g-4">
        <div class="col-md-3">
            <div class="card text-center p-3 bg-primary text-white">
                <i class="bi bi-people display-5"></i>
                <h5 class="mt-2">Users</h5>
                <p><?= $usersCount ?> Total</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 bg-success text-white">
                <i class="bi bi-calendar-check display-5"></i>
                <h5 class="mt-2">Bookings</h5>
                <p><?= $bookingsCount ?> Total</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 bg-warning text-white">
                <i class="bi bi-journal-text display-5"></i>
                <h5 class="mt-2">Stories</h5>
                <p><?= $storiesCount ?> Shared</p>
            </div>
        </div>
        

        <!-- Additional Sections -->
        <div class="col-md-3">
            <div class="card text-center p-3 bg-info text-white">
                <i class="bi bi-film display-5"></i>
                <h5 class="mt-2">Therapy</h5>
                <p><?= $therapyCount ?> Sessions</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 bg-secondary text-white">
                <i class="bi bi-file-earmark-text display-5"></i>
                <h5 class="mt-2">Articles</h5>
                <p><?= $articlesCount ?> Posts</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 bg-dark text-white">
                <i class="bi bi-phone-off display-5"></i>
                <h5 class="mt-2">Digital Detox</h5>
                <p><?= $detoxCount ?> Tips</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 bg-warning text-dark">
                <i class="bi bi-mic display-5"></i>
                <h5 class="mt-2">Podcast</h5>
                <p><?= $podcastCount ?> Episodes</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 bg-success text-white">
                <i class="bi bi-heart display-5"></i>
                <h5 class="mt-2">Yoga</h5>
                <p><?= $yogaCount ?> Sessions</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 bg-primary text-white">
                <i class="bi bi-moon-stars display-5"></i>
                <h5 class="mt-2">Dream Analyze</h5>
                <p><?= $dreamsCount ?> Records</p>
            </div>
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
