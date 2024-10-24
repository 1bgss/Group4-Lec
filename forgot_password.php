<?php
require 'vendor/autoload.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);

    $stmt = $koneksi->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token = bin2hex(random_bytes(50));

        $stmt = $koneksi->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
        $stmt->execute([$token, $email]);

        $reset_link = "http://yourwebsite.com/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Click the following link to reset your password: $reset_link";
        
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@gmail.com'; 
            $mail->Password = 'your-email-password'; 
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('no-reply@yourwebsite.com', 'Your Website'); 
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = "<p>Click the following link to reset your password:</p><a href='$reset_link'>$reset_link</a>";
            $mail->AltBody = $message; 

            $mail->send();
            $success_message = "An email has been sent with instructions to reset your password.";
        } catch (Exception $e) {
            $error_message = "Failed to send email. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $error_message = "No user found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Forgot Password</h1>
    <form action="forgot_password.php" method="POST" class="form-group">
        <div class="form-group">
            <label for="email">Enter your email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Send Reset Link</button>
    </form>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger mt-3"><?= $error_message ?></div>
    <?php elseif (isset($success_message)): ?>
        <div class="alert alert-success mt-3"><?= $success_message ?></div>
    <?php endif; ?>
</div>
</body>
</html>
