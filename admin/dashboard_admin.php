<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

include '../db.php';

$stmt = $koneksi->prepare("SELECT * FROM events");
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Admin Dashboard</a>

    <!-- Hamburger -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAdmin" aria-controls="navbarNavAdmin" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar -->
    <div class="collapse navbar-collapse" id="navbarNavAdmin">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="create_event.php">Create Event</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="view_users.php">Manage Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h1>Welcome Admin!</h1>
    <h2>Manage Your Events</h2>
    <?php foreach ($events as $event): ?>
        <div class="card mb-4">
            <img class="card-img-top" src="../uploads/<?= htmlspecialchars($event['image']) ?>" alt="Event Image">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($event['event_name']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($event['event_description']) ?></p>
                <p><strong>Date:</strong> <?= htmlspecialchars($event['event_date']) ?></p>
                <p><strong>Max Participants:</strong> <?= htmlspecialchars($event['max_participants']) ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($event['status']) ?></p>
                <a href="edit_event.php?event_id=<?= $event['id'] ?>" class="btn btn-warning">Edit</a>
                <a href="#" class="btn btn-danger delete-btn" data-id="<?= $event['id'] ?>">Delete</a>
                <a href="view_registrants.php?event_id=<?= $event['id'] ?>" class="btn btn-primary">View Registrants</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('.delete-btn').on('click', function(e) {
            e.preventDefault(); 
            const eventId = $(this).data('id');

            Swal.fire({
                title: 'Apakah anda yakin ingin menghapusnya?',
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete_event.php?event_id=' + eventId;
                }
            });
        });
    });
</script>
</body>
</html>
