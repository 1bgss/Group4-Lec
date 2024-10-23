<!-- Buat ADMIN BIAR BISA CEK USER -->
<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

include '../db.php';

$stmt = $koneksi->prepare("SELECT users.id, users.name, users.email, GROUP_CONCAT(events.event_name) AS registered_events 
    FROM users 
    LEFT JOIN registrations ON users.id = registrations.user_id 
    LEFT JOIN events ON registrations.event_id = events.id 
    GROUP BY users.id");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="dashboard_admin.php">Dashboard</a>
            </li>
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
    <h1>Manage Users</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Registered Events</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['registered_events']) ?: 'No events registered' ?></td>
                    <td>
                        <a href="delete_user.php?user_id=<?= $user['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
