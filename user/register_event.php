<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if (!isset($_GET['event_id'], $_GET['ticket_count'], $_GET['payment_method'], $_GET['phone'], $_GET['amount'])) {
    header('Location: dashboard_user.php?error=missing_data');
    exit;
}

$event_id = $_GET['event_id'];
$user_id = $_SESSION['user_id'];
$ticket_count = $_GET['ticket_count'];
$payment_method = $_GET['payment_method'];
$phone_number = $_GET['phone'];
$payment_amount = $_GET['amount'];

$stmt = $koneksi->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
$stmt->execute([$user_id, $event_id]);
$registration = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$registration) {
    $stmt = $koneksi->prepare("INSERT INTO registrations (user_id, event_id, ticket_count, payment_method, phone, amount_paid) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $event_id, $ticket_count, $payment_method, $phone_number, $payment_amount]);

    if ($stmt->errorCode() != '00000') {
        echo "Database Error: " . implode(", ", $stmt->errorInfo());
        exit; 
    }

    header('Location: dashboard_user.php?success=registration_completed');
    exit;
} else {
    header('Location: dashboard_user.php?error=already_registered');
    exit;
}
?>
