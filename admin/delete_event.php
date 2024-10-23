<!-- Buat delete event -->
<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

include '../db.php';

$event_id = $_GET['event_id'];

$stmt = $koneksi->prepare("DELETE FROM events WHERE id = ?");
$stmt->execute([$event_id]);

header('Location: dashboard_admin.php');
exit();
?>
