<?php
$therapists = [
    [
        'name' => 'Dr. Sarah Williams',
        'specialty' => 'Child Therapist',
        'bio' => 'Specializes in early childhood development and emotional well-being.',
        'image' => 'img/sarah.jpeg'
    ],
    [
        'name' => 'Mr. John Doe',
        'specialty' => 'Marriage Counselor',
        'bio' => 'Experienced in couples therapy and conflict resolution.',
        'image' => 'img/johnn.jpeg'
    ],
    [
        'name' => 'Ms. Emily Brown',
        'specialty' => 'Anxiety Specialist',
        'bio' => 'Focuses on anxiety disorders and mindfulness-based therapy.',
        'image' => 'img/emily.jpeg'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Find Support - Open Mind</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<!-- AOS -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>
body { font-family: 'Poppins', sans-serif; background-color: #f7f9fc; color: #333; transition: all 0.4s; }
.navbar { background-color: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: background-color 0.4s; }
.navbar-brand h1 { background: linear-gradient(45deg, #6a11cb, #2575fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.nav-link { color: #333 !important; font-weight: 500; transition: color 0.3s; }
.nav-link:hover, .nav-item.active .nav-link { color: #6a11cb !important; }
.card { border: none; border-radius: 1rem; box-shadow: 0 8px 24px rgba(0,0,0,0.05); transition: transform 0.3s ease; }
.card:hover { transform: translateY(-6px); }
.card-title { font-size: 1.1rem; font-weight: 600; }
footer { background: #111; color: #bbb; padding: 30px 0; text-align: center; }
footer a { color: #bbb; margin: 0 8px; text-decoration: none; }
footer a:hover { color: #fff; }
.dark-toggle-btn { border: none; background: none; font-size: 1.3rem; cursor: pointer; color: #333; transition: color 0.3s; }
.hero-header { background: linear-gradient(135deg, #6a11cb, #2575fc); color: white; padding: 80px 0; text-align: center; }
.hero-header h1 { font-size: 2.8rem; }
.hero-header p { font-size: 1.1rem; }

/* Dark mode */
body.dark-mode { background-color: #1e1e1e; color: #f5f5f5; }
body.dark-mode .navbar { background-color: #2c2c2c; }
body.dark-mode .nav-link { color: #f5f5f5 !important; }
body.dark-mode .nav-link:hover { color: #ffb74d !important; }
body.dark-mode .dropdown-menu { background-color: #2c2c2c; color: #f5f5f5; }
body.dark-mode .dropdown-item { color: #f5f5f5; }
body.dark-mode .dropdown-item:hover { background-color: #444; color: #ffb74d; }
body.dark-mode .card { background-color: #2c2c2c; color: #f5f5f5; }
body.dark-mode .form-control, body.dark-mode .form-select { background-color: #2c2c2c; color: #f5f5f5; border-color: #555; }
body.dark-mode footer { background: #111; color: #bbb; }

/* Dark mode for modals */
body.dark-mode .modal-content { background-color: #2c2c2c; color: #f5f5f5; border: none; }
body.dark-mode .modal-header, body.dark-mode .modal-footer { border-color: #444; }
body.dark-mode .modal-title { color: #f5f5f5; }
body.dark-mode .btn-close { filter: invert(1); }
body.dark-mode .btn-secondary { background-color: #444; border-color: #555; color: #f5f5f5; }
body.dark-mode .btn-secondary:hover { background-color: #555; color: #ffb74d; }

@media (max-width: 768px) { .hero-header h1 { font-size: 2rem; } }
</style>
</head>
<body>

<!-- Navbar -->
<nav id="mainNavbar" class="navbar navbar-expand-lg navbar-light sticky-top px-4 px-lg-5 py-3">
<div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php"><h1 class="m-0">Open Mind</h1></a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link active" href="home.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="moodTracker.php">Mood Tracker</a></li>
            <li class="nav-item"><a class="nav-link" href="findSupport.php">Find Support</a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="relaxDropdown" role="button" data-bs-toggle="dropdown">Relaxation</a>
                <ul class="dropdown-menu rounded shadow-sm" aria-labelledby="relaxDropdown">
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
        <button class="dark-toggle-btn" id="darkToggle"><i class="fas fa-moon"></i></button>
    </div>
</div>
</nav>

<!-- Therapists Section -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Meet Our Therapists</h2>
    <div class="row">
        <?php foreach ($therapists as $index => $therapist): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm text-center">
                <img src="<?= $therapist['image'] ?>" class="card-img-top" alt="<?= $therapist['name'] ?>" style="height:250px; object-fit:cover;">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($therapist['name']) ?></h5>
                    <p class="card-text text-muted"><?= htmlspecialchars($therapist['specialty']) ?></p>
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?= $index ?>">View Details</button>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modal<?= $index ?>" tabindex="-1" aria-labelledby="modalLabel<?= $index ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel<?= $index ?>"><?= $therapist['name'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="<?= $therapist['image'] ?>" class="img-fluid mb-3 rounded" alt="<?= $therapist['name'] ?>" style="max-height:250px;">
                        <h6><?= $therapist['specialty'] ?></h6>
                        <p><?= $therapist['bio'] ?></p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <hr class="my-5">

    <!-- Booking Form -->
    <h2 class="text-center mb-4">Book a Session</h2>
    <form action="processBooking.php" method="POST" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Address</label>
            <input type="text" class="form-control" name="address" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="tel" class="form-control" name="phone" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Preferred Date</label>
            <input type="date" class="form-control" name="date" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Preferred Time</label>
            <input type="time" class="form-control" name="time" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Choose Therapist</label>
            <select name="therapist_index" class="form-select" required>
                <option value="" disabled selected>Select a therapist</option>
                <?php foreach ($therapists as $index => $therapist): ?>
                    <option value="<?= $index ?>"><?= $therapist['name'] ?> (<?= $therapist['specialty'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary px-4">Confirm Booking</button>
        </div>
    </form>
</div>

<!-- Footer -->
<footer class="footer mt-5">
    <p>© <?= date('Y') ?> Open Mind | All Rights Reserved</p>
    <p><a href="home.php">Home</a> | <a href="about.php">About Us</a> | <a href="findSupport.php">Find Support</a> | <a href="talkWithRoob.php">Talk With Roob</a></p>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init();</script>
<script>
const toggleBtn = document.getElementById("darkToggle");
const navbar = document.getElementById("mainNavbar");

toggleBtn.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");

    // Navbar dark/light swap
    if(document.body.classList.contains("dark-mode")) {
        navbar.classList.remove("navbar-light");
        navbar.classList.add("navbar-dark");
    } else {
        navbar.classList.remove("navbar-dark");
        navbar.classList.add("navbar-light");
    }

    // Toggle icon
    const icon = toggleBtn.querySelector("i");
    if(document.body.classList.contains("dark-mode")){
        icon.classList.replace("fa-moon","fa-sun");
    } else {
        icon.classList.replace("fa-sun","fa-moon");
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