<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if (!isset($_GET['event_id'])) {
    echo "Event ID is missing!";
    exit;
}

$event_id = $_GET['event_id'];
$user_id = $_SESSION['user_id'];

$stmt = $koneksi->prepare("DELETE FROM registrations WHERE user_id = ? AND event_id = ?");
$stmt->execute([$user_id, $event_id]);

header('Location: dashboard_user.php');
exit;
?>
