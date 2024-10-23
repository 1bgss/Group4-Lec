<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $koneksi->prepare("SELECT name, email, profile_photo FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $koneksi->prepare("SELECT events.event_name, events.event_date, events.location FROM registrations JOIN events ON registrations.event_id = events.id WHERE registrations.user_id = ?");
$stmt->execute([$user_id]);
$registered_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    
    // Update Profile Photo
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $photo_name = $_FILES['profile_photo']['name'];
        $photo_tmp = $_FILES['profile_photo']['tmp_name'];
        $photo_ext = pathinfo($photo_name, PATHINFO_EXTENSION);
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($photo_ext, $allowed_exts)) {
            $new_photo_name = uniqid() . '.' . $photo_ext;
            move_uploaded_file($photo_tmp, '../uploads/' . $new_photo_name);
            $stmt = $koneksi->prepare("UPDATE users SET profile_photo = ? WHERE id = ?");
            $stmt->execute([$new_photo_name, $user_id]);
        }
    }

    // Update Name and Email
    $stmt = $koneksi->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->execute([$name, $email, $user_id]);

    // Update Password
    if (!empty($_POST['current_password']) && !empty($_POST['new_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        
        // Cek password saat ini
        $stmt = $koneksi->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verifikasi password saat ini
        if (password_verify($current_password, $user_data['password'])) {
            // Enkripsi password baru
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $koneksi->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $user_id]);
            $_SESSION['message'] = "Password berhasil diperbarui!";
        } else {
            $_SESSION['message'] = "Password saat ini tidak valid.";
        }
    }

    $_SESSION['user_name'] = $name;
    header('Location: profile.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>User Profile</h1>
    
    <!-- Update Profile Form -->
    <form action="profile.php" method="POST" enctype="multipart/form-data" class="form-group">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="form-group">
            <label for="profile_photo">Profile Photo</label>
            <?php if (!empty($user['profile_photo'])): ?>
                <img src="../uploads/<?= htmlspecialchars($user['profile_photo']) ?>" alt="Profile Photo" style="width: 100px; height: 100px; border-radius: 50%;">
            <?php endif; ?>
            <input type="file" class="form-control" id="profile_photo" name="profile_photo">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>

    <!-- Update Password Form -->
    <form action="profile.php" method="POST" class="form-group mt-5">
        <h2>Update Password</h2>
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <button type="submit" class="btn btn-warning">Change Password</button>
    </form>

    <!-- Event Registration History -->
    <h2>Your Event Registration History</h2>
    <?php if (count($registered_events) > 0): ?>
        <ul class="list-group">
            <?php foreach ($registered_events as $event): ?>
                <li class="list-group-item">
                    <h5><?= htmlspecialchars($event['event_name']) ?></h5>
                    <p><strong>Date:</strong> <?= $event['event_date'] ?></p>
                    <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>You have not registered for any events.</p>
    <?php endif; ?>

    <a href="dashboard_user.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
</div>
</body>
</html>
