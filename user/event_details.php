<!-- Buat event details -->
<?php
session_start();
include '../db.php';

if (!isset($_GET['event_id'])) {
    echo "Event ID is missing!";
    exit;
}

$event_id = $_GET['event_id'];

$stmt = $koneksi->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Event not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container mt-5">
    <h1>Event Details</h1>
    <div class="card mb-4">
        <img class="card-img-top" src="../uploads/<?= htmlspecialchars($event['image']) ?>" alt="Event Image">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($event['event_name']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($event['event_description']) ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($event['event_date']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p> 
            <p><strong>Max Participants:</strong> <?= htmlspecialchars($event['max_participants']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($event['status']) ?></p>
            <p><strong>Total Tickets:</strong> <?= htmlspecialchars($event['total_tickets']) ?></p> 
            <p><strong>Ticket Price:</strong> <?= htmlspecialchars($event['ticket_price']) ?> IDR</p> 
        </div>
    </div>
    <a href="dashboard_user.php" class="btn btn-primary">Back to Dashboard</a>
</div>
</body>
</html>
