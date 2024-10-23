<!-- Buat bagian view events -->
<?php
session_start();
include 'db.php';

$stmt = $koneksi->prepare("SELECT * FROM events WHERE status = 'open'");
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Events</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Available Events</h1>
    <?php foreach ($events as $event): ?>
        <div class="card mb-4">
            <img class="card-img-top" src="uploads/<?= $event['image'] ?>" alt="Event Image">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($event['event_name']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($event['event_description']) ?></p>
                <p><strong>Date:</strong> <?= $event['event_date'] ?></p>
                <p><strong>Max Participants:</strong> <?= $event['max_participants'] ?></p>
                <a href="register_event.php?event_id=<?= $event['id'] ?>" class="btn btn-primary">Register</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
