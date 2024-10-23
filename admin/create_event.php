<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = htmlspecialchars($_POST['event_name']);
    $event_description = htmlspecialchars($_POST['event_description']);
    $event_date = $_POST['event_date'];
    $location = htmlspecialchars($_POST['location']);
    $max_participants = (int)$_POST['max_participants'];
    $total_tickets = (int)$_POST['total_tickets']; 
    $ticket_price = (float)$_POST['ticket_price']; 
    $status = $_POST['status'];

    $image = $_FILES['event_image']['name'];
    $target = "../uploads/" . basename($image);
    move_uploaded_file($_FILES['event_image']['tmp_name'], $target);

    $stmt = $koneksi->prepare("INSERT INTO events (event_name, event_description, event_date, location, max_participants, total_tickets, ticket_price, status, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$event_name, $event_description, $event_date, $location, $max_participants, $total_tickets, $ticket_price, $status, $image]);

    // Set session status untuk menampilkan alert
    $_SESSION['event_created'] = true;

    // Redirect to dashboard
    header('Location: dashboard_admin.php');
    exit; // Pastikan untuk keluar setelah pengalihan
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 700px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
        }
        h1::after {
            content: "";
            width: 60px;
            height: 5px;
            background-color: #007bff;
            display: block;
            margin: 10px auto 0;
            border-radius: 3px;
        }
        .form-group label {
            font-weight: bold;
            color: #555;
        }
        .form-control, .form-control-file {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.03);
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
        }
        .btn {
            background-color: #007bff;
            border: none;
            padding: 12px 20px;
            font-size: 1.1rem;
            border-radius: 8px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        /* Specific styling for file input */
        .form-control-file {
            background-color: #f9f9f9;
            padding: 10px;
        }
        .form-control-file:hover {
            background-color: #eef3f7;
        }
        /* Custom styles for the select box */
        select.form-control {
            appearance: none;
            background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEgNS41TDEwIDE0LjVMMTkgNS41IiBzdHJva2U9IiM3MDdiZmYiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo=') no-repeat right 12px center;
            background-color: #f9f9f9;
            padding-right: 40px;
        }
        .select-container {
            position: relative;
        }
        .select-container::after {
            content: '\25BC'; /* Down arrow symbol */
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            pointer-events: none;
            color: #555;
        }
        /* Media queries for responsiveness */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Create Event</h1>
    <form action="create_event.php" method="POST" enctype="multipart/form-data" class="form-group">
        <div class="form-group">
            <label for="event_name">Event Name</label>
            <input type="text" class="form-control" id="event_name" name="event_name" required>
        </div>
        <div class="form-group">
            <label for="event_description">Event Description</label>
            <textarea class="form-control" id="event_description" name="event_description" required></textarea>
        </div>
        <div class="form-group">
            <label for="event_date">Event Date</label>
            <input type="date" class="form-control" id="event_date" name="event_date" required>
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" class="form-control" id="location" name="location" required> 
        </div>
        <div class="form-group">
            <label for="max_participants">Maximum Participants</label>
            <input type="number" class="form-control" id="max_participants" name="max_participants" required>
        </div>
        <div class="form-group">
            <label for="total_tickets">Total Tickets</label>
            <input type="number" class="form-control" id="total_tickets" name="total_tickets" required>
        </div>
        <div class="form-group">
            <label for="ticket_price">Ticket Price</label>
            <input type="number" class="form-control" id="ticket_price" name="ticket_price" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status">
                <option value="open">Open</option>
                <option value="closed">Closed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        <div class="form-group">
            <label for="event_image">Event Image</label>
            <input type="file" class="form-control-file" id="event_image" name="event_image" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Event</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Cek jika session event_created ada dan true
    <?php if (isset($_SESSION['event_created']) && $_SESSION['event_created']): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Event successfully created!',
            confirmButtonText: 'OK'
        });
        // Hapus session setelah menampilkan alert
        <?php unset($_SESSION['event_created']); ?>
    <?php endif; ?>
</script>
</body>
</html>
