<!-- (Penanda)konek databasenya -->
<?php
$host = 'localhost';
$dbname = 'event_registration';
$user = 'root';
$pass = '';

try {
    $koneksi = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>
