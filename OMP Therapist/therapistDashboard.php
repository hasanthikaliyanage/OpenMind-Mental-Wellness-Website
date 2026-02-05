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
body { font-family: 'Poppins', sans-serif; background: #f4f6f9; }
.navbar { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
h2 { font-weight: 600; }
.card { border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
.table { background: #fff; border-radius: 12px; overflow: hidden; }
.table th { background: #2575fc; color: white; font-weight: 500; }
.table td { vertical-align: middle; }
.badge { font-size: 0.85rem; padding: 0.5em 0.7em; }
.status-pending { background: #ffc107; }
.status-confirmed { background: #28a745; }
.status-completed { background: #17a2b8; }
.status-cancelled { background: #dc3545; }
.btn-status { min-width: 120px; }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#"><i class="fas fa-calendar-check"></i> Manage Bookings</a>
        
    </div>
</nav>

<!-- Content -->
<div class="container my-5">
    <div class="card p-4">
        <h2 class="mb-4"><i class="fas fa-clipboard-list"></i> User Bookings</h2>

        <?php if(!empty($bookings)): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Therapist</th>
                        <th>Status</th>
                        <th>Update</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($bookings as $i => $booking): 
                        $currentStatus = $booking['status'] ?? 'Pending';
                        $statusClass = strtolower($currentStatus);
                    ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= htmlspecialchars($booking['name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($booking['email'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($booking['phone'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($booking['date'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($booking['time'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($booking['therapist'] ?? 'N/A') ?></td>
                        <td>
                            <span class="badge 
                                <?= $currentStatus=='Pending' ? 'status-pending' : '' ?>
                                <?= $currentStatus=='Confirmed' ? 'status-confirmed' : '' ?>
                                <?= $currentStatus=='Completed' ? 'status-completed' : '' ?>
                                <?= $currentStatus=='Cancelled' ? 'status-cancelled' : '' ?>">
                                <?= $currentStatus ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" class="d-flex">
                                <input type="hidden" name="booking_id" value="<?= $booking['_id'] ?? '' ?>">
                                <select class="form-select form-select-sm me-2" name="status" onchange="this.form.submit()">
                                    <?php 
                                        $statuses = ['Pending','Confirmed','Completed','Cancelled'];
                                        foreach($statuses as $status): 
                                    ?>
                                        <option value="<?= $status ?>" <?= $currentStatus==$status ? 'selected' : '' ?>>
                                            <?= $status ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="update_status">
                            </form>
                        </td>
                        <td>
                            <a href="?delete=<?= $booking['_id'] ?? '' ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to delete this booking?')">
                                <i class="fas fa-trash-alt"></i> Delete
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
