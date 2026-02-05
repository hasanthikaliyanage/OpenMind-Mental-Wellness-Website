<?php
// moodTracker.php
require 'vendor/autoload.php';
session_start();

// MongoDB init
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->OMP->moods;

$message = "";

// Current user (if logged admin) otherwise guest
$user = $_SESSION['admin'] ?? 'guest';

// Add daily mood + note
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mood'])) {
    $mood = trim($_POST['mood']);
    $note = trim($_POST['note'] ?? '');
    $date = date("Y-m-d");

    $existing = $collection->findOne([
        'user' => $user,
        'date' => $date
    ]);

    if ($existing) {
        $message = "You already added mood for today!";
    } else {
        $collection->insertOne([
            'user' => $user,
            'mood' => $mood,
            'note' => $note,
            'date' => $date,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]);
        $message = "Mood added successfully!";
    }
}

// Fetch moods
$cursor = $collection->find(['user' => $user], ['sort' => ['created_at' => 1]]);
$all = iterator_to_array($cursor);

// Filters
$filterType = $_GET['filter'] ?? 'daily';

$moodToVal = function($m) {
    return match($m) {
        'Happy' => 5,
        'Excited' => 4,
        'Relaxed' => 3,
        'Stressed' => 2,
        'Sad' => 1,
        'Angry' => 0,
        default => 0,
    };
};

$moodColors = [
    'Happy'=>'#4caf50',
    'Excited'=>'#ffca28',
    'Relaxed'=>'#29b6f6',
    'Stressed'=>'#ff9800',
    'Sad'=>'#9c27b0',
    'Angry'=>'#f44336'
];
$moodEmojis = [
    'Happy'=>'😊',
    'Excited'=>'🤩',
    'Relaxed'=>'😌',
    'Stressed'=>'😓',
    'Sad'=>'😢',
    'Angry'=>'😡'
];

// Chart arrays
$chartLabels = [];
$chartData = [];
$chartEmojis = [];
$notesList = $all;

if ($filterType === 'daily') {
    foreach ($all as $r) {
        $label = $r['date'] ?? (isset($r['created_at']) ? date('Y-m-d', $r['created_at']->toDateTime()->getTimestamp()) : '');
        $chartLabels[] = $label;
        $chartData[] = $moodToVal($r['mood'] ?? '');
        $chartEmojis[] = $moodEmojis[$r['mood'] ?? ''] ?? '';
    }
} elseif ($filterType === 'monthly') {
    $groups = [];
    foreach ($all as $r) {
        $dt = $r['date'] ?? date('Y-m-d', $r['created_at']->toDateTime()->getTimestamp());
        $ym = date('Y-m', strtotime($dt));
        if (!isset($groups[$ym])) $groups[$ym] = ['sum'=>0,'count'=>0];
        $groups[$ym]['sum'] += $moodToVal($r['mood'] ?? '');
        $groups[$ym]['count']++;
    }
    foreach ($groups as $ym => $g) {
        $chartLabels[] = $ym;
        $chartData[] = $g['count'] ? round($g['sum'] / $g['count'], 2) : 0;
        $chartEmojis[] = '';
    }
} else {
    $groups = [];
    foreach ($all as $r) {
        $dt = $r['date'] ?? date('Y-m-d', $r['created_at']->toDateTime()->getTimestamp());
        $y = date('Y', strtotime($dt));
        if (!isset($groups[$y])) $groups[$y] = ['sum'=>0,'count'=>0];
        $groups[$y]['sum'] += $moodToVal($r['mood'] ?? '');
        $groups[$y]['count']++;
    }
    foreach ($groups as $y => $g) {
        $chartLabels[] = $y;
        $chartData[] = $g['count'] ? round($g['sum'] / $g['count'], 2) : 0;
        $chartEmojis[] = '';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Mood Tracker — Open Mind</title>
<meta name="viewport" content="width=device-width,initial-scale=1">

<!-- Bootstrap & Chart.js -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #f7f9fc;
    transition: background-color 0.4s, color 0.4s;
  }
  .navbar {
    background-color: #fff;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: background-color 0.4s;
  }
  .navbar-brand h1 {
    font-weight: bold;
    background: linear-gradient(45deg, #6a11cb, #2575fc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }
footer { background: #111; color: #bbb; padding: 30px 0; text-align: center; }
footer a { color: #bbb; margin: 0 8px; text-decoration: none; }
footer a:hover { color: #fff; }
  .nav-link { color: #333 !important; font-weight: 500; transition: color 0.3s; }
  .nav-link:hover, .nav-item.active .nav-link { color: #6a11cb !important; }

  body.dark-mode { background-color: #1e1e1e; color: #f5f5f5; }
  body.dark-mode .navbar { background-color: #2c2c2c; }
  body.dark-mode .nav-link { color: #f5f5f5 !important; }
  body.dark-mode .nav-link:hover { color: #ffb74d !important; }
  .dark-toggle-btn { border: none; background: none; font-size: 1.3rem; cursor: pointer; color: #333; }
  body.dark-mode .dark-toggle-btn { color: #f5f5f5; }

  .mood-card { background:#fff; border-radius:14px; padding:22px; margin-top:20px; }
  body.dark-mode .mood-card { background:#2c2c2c; }
  #chart-container { background:#fff; padding:18px; border-radius:14px; margin-top:20px; }
  body.dark-mode #chart-container { background:#2c2c2c; }
  .note-card { background:#eef2f7; border-radius:12px; padding:12px; margin-bottom:10px; }
  body.dark-mode .note-card { background:#3a3a3a; }
</style>
</head>
<body>

<!-- Navbar -->
<div class="container-xxl position-relative p-0">
  <nav class="navbar navbar-expand-lg navbar-light shadow-sm sticky-top px-4 px-lg-5 py-3">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold text-gradient" href="index.php">
        <h1 class="m-0">Open Mind</h1>
      </a>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
          <li class="nav-item"><a class="nav-link active" href="moodTracker.php">Mood Tracker</a></li>
          <li class="nav-item"><a class="nav-link" href="findSupport.php">Find Support</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="relaxDropdown" role="button" data-bs-toggle="dropdown">Relaxation</a>
            <ul class="dropdown-menu rounded shadow-sm">
              <li><a class="dropdown-item" href="blogs.php">Blog & Stories</a></li>
              <li><a class="dropdown-item" href="audioVideoTheropy.php">Audio/Video Therapy</a></li>
              <li><a class="dropdown-item" href="articles.php">Articles</a></li>
              <li><a class="dropdown-item" href="degitalDetox.php">Digital Detox</a></li>
              <li><a class="dropdown-item" href="podcast.php">Podcast</a></li>
              <li><a class="dropdown-item" href="yoga.php">Yoga</a></li>
              <li><a class="dropdown-item" href="dreamAnalyzer.php">Dream Analyzer</a></li>
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link" href="aboutUs.php">About Us</a></li>
        </ul>
        <div class="d-flex gap-2 align-items-center">
          
          <button class="dark-toggle-btn" id="darkToggle"><i class="fas fa-moon"></i></button>
        </div>
      </div>
    </div>
  </nav>
</div>


<!-- Hero -->
<section class="hero-section" style="background: url('img/mood.jpg') no-repeat center center/cover; height: 400px; display: flex; align-items: center;">
  <div class="container text-center">
    <h1 class="text-gradient" data-aos="fade-down">About Open Mind</h1>
    <p class="lead text-white" data-aos="fade-up" data-aos-delay="200">
      We’re dedicated to helping you on your journey toward mental clarity, emotional strength, and inner peace.
    </p>
  </div>
</section>



<div class="container py-4">
  <?php if ($message): ?>
    <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <div class="mood-card">
    <h4>Add Your Mood Today</h4>
    <form method="POST" class="row g-3">
      <div class="col-md-4">
        <select name="mood" class="form-select" required>
          <option value="">-- Select Mood --</option>
          <option value="Happy">😊 Happy</option>
          <option value="Excited">🤩 Excited</option>
          <option value="Relaxed">😌 Relaxed</option>
          <option value="Stressed">😓 Stressed</option>
          <option value="Sad">😢 Sad</option>
          <option value="Angry">😡 Angry</option>
        </select>
      </div>
      <div class="col-md-6">
        <input name="note" class="form-control" placeholder="Add a note (optional)" />
      </div>
      <div class="col-md-2 d-grid">
        <button class="btn btn-primary">Add Mood</button>
      </div>
    </form>
  </div>

  <div class="mt-4 mb-3">
    <a href="?filter=daily" class="btn btn-outline-primary btn-sm">Daily</a>
    <a href="?filter=monthly" class="btn btn-outline-primary btn-sm">Monthly</a>
    <a href="?filter=yearly" class="btn btn-outline-primary btn-sm">Yearly</a>
  </div>

  <div id="chart-container">
    <canvas id="moodChart" height="120"></canvas>
  </div>

  <div class="mt-4">
    <h5>Your Mood Notes</h5>
    <?php foreach ($notesList as $n): 
      $noteText = htmlspecialchars($n['note'] ?? '');
      $moodName = $n['mood'] ?? 'Unknown';
      $badgeColor = $moodColors[$moodName] ?? '#6b7280';
      $emoji = $moodEmojis[$moodName] ?? '';
      $dateStr = $n['date'] ?? date('Y-m-d', $n['created_at']->toDateTime()->getTimestamp());
    ?>
      <div class="note-card">
        <span style="color:<?= htmlspecialchars($badgeColor) ?>;font-weight:700;"><?= $emoji ?> <?= htmlspecialchars($moodName) ?></span>
        <div>
          <div><strong><?= htmlspecialchars($dateStr) ?></strong></div>
          <div><?= $noteText ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

 <footer class="footer mt-5">
  <p>© <?= date('Y') ?> Open Mind | All Rights Reserved</p>
  <p><a href="home.php">Home</a> | <a href="about.php">About Us</a> | <a href="findSupport.php">Find Support</a>|<a href="talkWithRoob.php">Talk With Roob</a></p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = <?= json_encode($chartLabels) ?>;
const dataVals = <?= json_encode($chartData) ?>;
const emojis = <?= json_encode($chartEmojis) ?>;
const ctx = document.getElementById('moodChart').getContext('2d');
new Chart(ctx, {
  type: 'line',
  data: { labels: labels, datasets: [{ label: 'Mood Trend', data: dataVals, borderColor: '#6a11cb', fill: true }] },
  options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { min: 0, max: 5 } } }
});

// Dark mode toggle
const toggleBtn = document.getElementById("darkToggle");
toggleBtn.addEventListener("click", () => {
  document.body.classList.toggle("dark-mode");
  const icon = toggleBtn.querySelector("i");
  if (document.body.classList.contains("dark-mode")) {
    icon.classList.replace("fa-moon", "fa-sun");
  } else {
    icon.classList.replace("fa-sun", "fa-moon");
  }
});
</script>
</body>
</html>
<!-- Your existing page content -->
<html>
<head>
    <title>Your Website</title>
    <!-- Your existing head content -->
</head>
<body>
    <!-- Your existing page content -->
    
    <!-- Add this before closing </body> tag -->
    <script src="/mental-wellness-chatbot/chat-widget.js"></script>
</body>
</html>