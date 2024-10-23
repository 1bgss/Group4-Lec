<?php
include 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $koneksi->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Invalid or expired token.";
        exit();
    }
} else {
    echo "No token provided.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $stmt = $koneksi->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
    $stmt->execute([$new_password, $user['id']]);

    echo "Your password has been reset successfully. You can now <a href='login.php'>login</a>.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Reset Password</h1>
    <form action="reset_password.php?token=<?= htmlspecialchars($token) ?>" method="POST" class="form-group">
        <div class="form-group">
            <label for="new_password">Enter your new password</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
</div>
</body>
</html>
