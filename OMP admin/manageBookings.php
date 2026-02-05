<?php
session_start();
require 'vendor/autoload.php';

// Admin name from session or default
$adminName = $_SESSION['admin_name'] ?? 'Admin';

// MongoDB Connection
$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->OMP;
$collection = $db->bookings;

// Handle deletion
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    header("Location: manageBookings.php");
    exit();
}

// Handle status update
if(isset($_POST['update_status'])){
    $id = $_POST['booking_id'] ?? null;
    $newStatus = $_POST['status'] ?? null;

    if($id && $newStatus){
        $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id)],
            ['$set' => ['status' => $newStatus]]
        );
    }
    header("Location: manageBookings.php");
    exit();
}

// Fetch all bookings
$bookings = iterator_to_array($collection->find([], ['sort' => ['date' => 1]]));
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Bookings - Admin Dashboard</title>

<!-- Bootstrap & FontAwesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
body { font-family: 'Poppins', sans-serif; background: #f7f9fc; margin:0; }
.sidebar { height:100vh; background:#2c3e50; padding-top:20px; position:fixed; left:0; top:0; width:250px; overflow-y:auto; transition:0.3s; }
.sidebar h4 { color:#fff; text-align:center; margin-bottom:30px; }
.sidebar a { color:white; display:block; padding:12px 20px; text-decoration:none; transition:0.3s; font-size:15px; }
.sidebar a:hover, .sidebar a.active { background:#16a085; padding-left:25px; }
.content { margin-left:250px; padding:20px; transition:0.3s; }
.navbar { margin-left:250px; transition:0.3s; }
.card { border-radius:15px; box-shadow:0 4px 15px rgba(0,0,0,0.1); transition:0.2s; }
.card:hover { transform: translateY(-5px); box-shadow:0 8px 20px rgba(0,0,0,0.15); }
table { background: #fff; border-radius: 1rem; box-shadow:0 8px 24px rgba(0,0,0,0.05); }
th, td { vertical-align: middle !important; }
.btn-status { min-width: 90px; }
.toggle-btn { display:none; cursor:pointer; }
@media (max-width: 768px){
    .sidebar { position:absolute; left:-250px; }
    .sidebar.active { left:0; }
    .content, .navbar { margin-left:0; }
    .toggle-btn { display:inline-block; margin-right:15px; }
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h4>Admin Panel</h4>
    <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="manageUsers.php"><i class="bi bi-people"></i> Manage Users</a>
    <a href="manageBookings.php" class="active"><i class="bi bi-calendar-check"></i> Bookings</a>
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
        <span class="toggle-btn text-white" onclick="document.getElementById('sidebar').classList.toggle('active');"><i class="bi bi-list fs-3"></i></span>
        <a class="navbar-brand ms-2" href="#">Manage Bookings</a>
        <div class="ms-auto text-white"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($adminName) ?></div>
    </div>
</nav>

<!-- Content -->
<div class="content container my-5">
    <h2 class="mb-4">Manage User Bookings</h2>

    <?php if(!empty($bookings)): ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Therapist</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($bookings as $i => $booking): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= htmlspecialchars($booking['name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($booking['email'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($booking['phone'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($booking['date'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($booking['time'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($booking['therapist'] ?? 'N/A') ?></td>
                    <td>
                        <form method="POST" class="d-flex gap-1">
                            <input type="hidden" name="booking_id" value="<?= $booking['_id'] ?? '' ?>">
                            <select class="form-select form-select-sm btn-status" name="status" onchange="this.form.submit()">
                                <?php 
                                    $statuses = ['Pending','Confirmed','Completed','Cancelled'];
                                    $currentStatus = $booking['status'] ?? 'Pending';
                                    foreach($statuses as $status): 
                                ?>
                                    <option value="<?= $status ?>" <?= $currentStatus==$status ? 'selected' : '' ?>><?= $status ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="update_status">
                        </form>
                    </td>
                    <td>
                        <a href="?delete=<?= $booking['_id'] ?? '' ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this booking?')">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <p class="text-muted">No bookings found.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
