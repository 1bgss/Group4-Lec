<?php
session_start();

$host = 'localhost';
$dbname = 'event_registration';
$user = 'root';
$pass = '';

try {
    $koneksi = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}

$query = "SELECT * FROM events WHERE status = 'open'";
$result = $koneksi->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Festival Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styleindex.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .slider-container {
            position: relative;
            width: 80%;
            margin: auto;
            overflow: hidden;
            border: 2px solid #f4f4f4;
        }
        .slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .slide {
            min-width: 100%;
            box-sizing: border-box;
            text-align: center;
        }
        .slide img {
            width: auto;
            height: auto;
            max-height: 400px;
            max-width: 100%;
            object-fit: cover;
            margin: auto;
        }
        .navigation {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }
        .navigation button {
            background-color: rgba(0, 0, 0, 0.5);
            border: none;
            color: white;
            padding: 10px;
            cursor: pointer;
            z-index: 10;
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg festival-navbar">
    <a class="navbar-brand festival-logo" href="#">Eve-Fun!</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon">&#9776;</span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link festival-link" href="admin/dashboard_admin.php">Admin Dashboard</a>
                </li>
            <?php elseif (isset($_SESSION['role'])): ?>
                <li class="nav-item">
                    <a class="nav-link festival-link" href="user/dashboard_user.php">User Dashboard</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link festival-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link festival-link" href="user/register_user.php">Register</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<!-- Welcoming -->
<div class="container mt-5 text-center festival-container">
    <div class="festival-hero">
        <h1 class="festival-title">Welcome to the Ultimate Event Festival</h1>
        <p class="festival-subtitle">Join us for an unforgettable experience with the best events, music, and culture.</p>
        <button class="btn btn-primary btn-lg festival-btn" id="exploreBtn">Explore Events</button>
    </div>
    <!-- Top Picks Section -->
    <div class="top-picks mt-5">
        <h2>Today's Event</h2>
        <div class="slider-container">
            <div class="slider" id="eventSlider">
                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="slide">
                        <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['event_name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['event_name']; ?></h5>
                            <p class="card-text"><?php echo $row['event_description']; ?></p>
                            <p class="card-text"><?php echo $row['event_date']; ?> | <?php echo $row['location']; ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="navigation">
                <button id="prev">&#10094;</button>
                <button id="next">&#10095;</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('exploreBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'You need to register first!',
            icon: 'warning',
            confirmButtonText: 'Register Now',
            preConfirm: () => {
                window.location.href = 'user/register_user.php';
            }
        });
    });

    const slider = document.getElementById('eventSlider');
    const slides = document.querySelectorAll('.slide');
    let currentIndex = 0;

    document.getElementById('next').addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % slides.length;
        updateSlider();
    });

    document.getElementById('prev').addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        updateSlider();
    });

    function updateSlider() {
        slider.style.transform = `translateX(-${currentIndex * 100}%)`;
    }
</script>
</body>
</html>
