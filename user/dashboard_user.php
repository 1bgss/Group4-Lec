<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $koneksi->prepare("SELECT name, profile_photo FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $koneksi->prepare("SELECT events.event_name, events.event_description, events.event_date, events.location, events.id FROM registrations JOIN events ON registrations.event_id = events.id WHERE registrations.user_id = ?");
$stmt->execute([$user_id]);
$registered_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $koneksi->prepare("SELECT * FROM events WHERE status = 'open'");
$stmt->execute();
$available_events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
body {
    background-color: #f7f7f7; 
}

.navbar {
    background-color: #ff8c00; 
}

.navbar-brand, .nav-link {
    color: #fff; 
}

.nav-link:hover {
    color: #ffd700; 
}

.profile-photo {
    width: 40px; 
    height: 40px;
    border-radius: 50%; 
    margin-right: 10px;
}

.container {
    max-width: 900px; 
    margin: auto; 
    padding: 20px; 
    background-color: #fff; 
    border-radius: 8px; 
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
}

h1, h2 {
    color: #ff8c00; 
    margin-bottom: 20px; 
    font-family: 'Arial', sans-serif; 
}

.card {
    border: none; 
    border-radius: 8px; 
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
    transition: transform 0.3s; 
}

.card:hover {
    transform: translateY(-5px); 
}

.card-title {
    font-weight: bold; 
}

.card-text {
    color: #555; 
}

.btn-primary {
    background-color: #ff8c00; 
    border: none; 
}

.btn-primary:hover {
    background-color: #ff6f00; 
}

.btn-info {
    background-color: #17a2b8; 
}

.btn-info:hover {
    background-color: #138496; 
}

.list-group-item {
    border: none; 
    border-radius: 8px; 
    margin-bottom: 10px;
    background-color: #f8f9fa; 
}

.list-group-item h5 {
    margin: 0; 
}

.modal-content {
    border-radius: 8px; 
}

.modal-header, .modal-footer {
    border: none; 
}

.modal-title {
    color: #ff8c00; 
}

@media (max-width: 768px) {
    .navbar-brand {
        display: flex; 
        align-items: center; 
    }
    
    .container {
        padding: 10px; 
    }
}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">
        <img src="../uploads/<?= htmlspecialchars($user['profile_photo']) ?>" alt="Profile Photo" class="profile-photo"> 
        Eve-Fun
    </a>
    <!-- Hamburger -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="profile.php">View Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h1>Welcome, <?= htmlspecialchars($user['name']) ?>!</h1>
    <!-- (Penanda)Section event browsing -->
    <h2>Available Events</h2>
    <?php if (count($available_events) > 0): ?>
        <?php foreach ($available_events as $event): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($event['event_name']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($event['event_description']) ?></p>
                    <p><strong>Date:</strong> <?= $event['event_date'] ?></p>
                    <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
                    <button class="btn btn-primary register-btn" data-event-id="<?= $event['id'] ?>">Register</button>
                    <a href="event_details.php?event_id=<?= $event['id'] ?>" class="btn btn-info">Event Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No events available at the moment.</p>
    <?php endif; ?>

    <!-- (Penanda)Section registered event -->
    <h2>Your Registered Events</h2>
    <?php if (count($registered_events) > 0): ?>
        <ul class="list-group">
            <?php foreach ($registered_events as $event): ?>
                <li class="list-group-item">
                    <h5><?= htmlspecialchars($event['event_name']) ?></h5>
                    <p><?= htmlspecialchars($event['event_description']) ?></p>
                    <p><strong>Date:</strong> <?= $event['event_date'] ?></p>
                    <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
                    <button class="btn btn-warning cancel-btn" data-event-id="<?= $event['id'] ?>">Cancel Registration</button> <!-- Updated to button for consistency -->
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>You have not registered for any events.</p>
    <?php endif; ?>
</div>

<!-- (Penanda)Modal for ticket purchase -->
<div class="modal fade" id="ticketPurchaseModal" tabindex="-1" role="dialog" aria-labelledby="ticketPurchaseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ticketPurchaseModalLabel">Buy Tickets</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ticketForm">
                    <div class="form-group">
                        <label for="ticketCount">Number of Tickets (Max 3)</label>
                        <input type="number" class="form-control" id="ticketCount" name="ticketCount" min="1" max="3" required>
                    </div>
                    <div class="form-group">
                        <label for="paymentMethod">Payment Method</label>
                        <select class="form-control" id="paymentMethod" name="paymentMethod" required>
                            <option value="ovo">OVO</option>
                            <option value="gopay">GoPay</option>
                            <option value="dana">Dana</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phoneNumber">Phone Number</label>
                        <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" required pattern="[0-9]{10,13}">
                    </div>
                    <div class="form-group">
                        <label for="paymentAmount">Payment Amount</label>
                        <input type="number" class="form-control" id="paymentAmount" name="paymentAmount" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmPurchase">Confirm Purchase</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let selectedEventId = null;

    document.querySelectorAll('.register-btn').forEach(button => {
        button.addEventListener('click', function() {
            selectedEventId = this.getAttribute('data-event-id');
            $('#ticketPurchaseModal').modal('show');
        });
    });

    document.getElementById('confirmPurchase').addEventListener('click', function() {
    const ticketCount = document.getElementById('ticketCount').value;
    const paymentMethod = document.getElementById('paymentMethod').value;
    const phoneNumber = document.getElementById('phoneNumber').value;
    const paymentAmount = document.getElementById('paymentAmount').value;

    if (ticketCount && paymentMethod && phoneNumber && paymentAmount) {
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we process your payment.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        setTimeout(() => {
            Swal.fire({
                icon: 'success',
                title: 'Payment Successful',
                text: 'You have successfully registered for the event!',
                confirmButtonText: 'Awesome!'
            }).then(() => {
                window.location.href = 'register_event.php?event_id=' + selectedEventId + '&ticket_count=' + ticketCount + '&payment_method=' + paymentMethod + '&phone=' + phoneNumber + '&amount=' + paymentAmount;
            });
        }, 2000); 
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Missing Information',
            text: 'Please fill in all required fields.'
        });
    }
});


    document.querySelectorAll('.cancel-btn').forEach(button => {
        button.addEventListener('click', function() {
            const eventId = this.getAttribute('data-event-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'cancel_registration.php?event_id=' + eventId;
                }
            });
        });
    });
</script>
</body>
</html>
