<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

include '../db.php';

$event_id = $_GET['event_id'];

$stmt = $koneksi->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

$updateSuccess = false; // Flag to check if update is successful

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = htmlspecialchars($_POST['event_name']);
    $event_description = htmlspecialchars($_POST['event_description']);
    $event_date = $_POST['event_date'];
    $max_participants = (int)$_POST['max_participants'];
    $status = $_POST['status'];
    $location = htmlspecialchars($_POST['location']); 
    $total_tickets = (int)$_POST['total_tickets']; 
    $ticket_price = (float)$_POST['ticket_price']; 

    if ($_FILES['event_image']['name']) {
        $image = $_FILES['event_image']['name'];
        $target = "../uploads/" . basename($image);
        move_uploaded_file($_FILES['event_image']['tmp_name'], $target);
    } else {
        $image = $event['image']; 
    }

    $stmt = $koneksi->prepare("UPDATE events SET event_name = ?, event_description = ?, event_date = ?, max_participants = ?, status = ?, location = ?, image = ?, total_tickets = ?, ticket_price = ? WHERE id = ?");
    $stmt->execute([$event_name, $event_description, $event_date, $max_participants, $status, $location, $image, $total_tickets, $ticket_price, $event_id]);

    $updateSuccess = true; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="editpage.css">
</head>
<body>
<div class="container mt-5">
    <h1>Edit Event</h1>
    <form action="edit_event.php?event_id=<?= $event_id ?>" method="POST" enctype="multipart/form-data" class="form-group">
        <div class="form-group">
            <label for="event_name">Event Name</label>
            <input type="text" class="form-control" id="event_name" name="event_name" value="<?= htmlspecialchars($event['event_name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="event_description">Event Description</label>
            <textarea class="form-control" id="event_description" name="event_description" required><?= htmlspecialchars($event['event_description']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="event_date">Event Date</label>
            <input type="date" class="form-control" id="event_date" name="event_date" value="<?= $event['event_date'] ?>" required>
        </div>
        <div class="form-group">
            <label for="max_participants">Maximum Participants</label>
            <input type="number" class="form-control" id="max_participants" name="max_participants" value="<?= $event['max_participants'] ?>" required>
        </div>
        <div class="form-group">
            <label for="total_tickets">Total Tickets</label>
            <input type="number" class="form-control" id="total_tickets" name="total_tickets" value="<?= $event['total_tickets'] ?>" required>
        </div>
        <div class="form-group">
            <label for="ticket_price">Ticket Price</label>
            <input type="number" class="form-control" id="ticket_price" name="ticket_price" value="<?= $event['ticket_price'] ?>" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status">
                <option value="open" <?= $event['status'] === 'open' ? 'selected' : '' ?>>Open</option>
                <option value="closed" <?= $event['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
                <option value="cancelled" <?= $event['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" class="form-control" id="location" name="location" value="<?= htmlspecialchars($event['location']) ?>" required>
        </div>
        <div class="form-group">
            <label for="event_image">Event Image</label>
            <input type="file" class="form-control-file" id="event_image" name="event_image">
            <p>Current Image: <img src="../uploads/<?= htmlspecialchars($event['image']) ?>" alt="Event Image" style="width: 100px;"></p>
        </div>
        <button type="submit" class="btn btn-primary">Update Event</button>
    </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if ($updateSuccess): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Event anda sukses ter-update!!!',
            showConfirmButton: false,
            timer: 1500
        }).then(function() {
            window.location.href = 'dashboard_admin.php';
        });
    </script>
<?php endif; ?>

</body>
</html>
