<!-- Buat delete user -->
<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

include '../db.php';

if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    header('Location: view_users.php');
    exit;
}

$user_id = $_GET['user_id'];

try {
    $koneksi->beginTransaction();
    
    // Ini buat Hapus semua registrasi event yang punya kaitan sama user
    $stmt = $koneksi->prepare("DELETE FROM registrations WHERE user_id = ?");
    $stmt->execute([$user_id]);

    // Ini buat Hapus user dari tabel users
    $stmt = $koneksi->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    $koneksi->commit();

    header('Location: view_users.php');
    exit;
} catch (Exception $e) {
    $koneksi->rollBack();
    echo "Failed to delete user: " . $e->getMessage();
}
?>
