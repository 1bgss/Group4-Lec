<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $event_id = $_POST['event_id'];
    $ticket_quantity = $_POST['ticket_quantity'];
    $payment_method = $_POST['payment_method'];
    $phone_number = $_POST['phone_number']; 
    $payment_amount = $_POST['payment_amount'];

    if ($ticket_quantity < 1 || $ticket_quantity > 3) {
        echo json_encode(['success' => false, 'message' => 'Invalid ticket quantity.']);
        exit;
    }

    $encrypted_phone = openssl_encrypt($phone_number, 'aes-256-cbc', 'encryptionkey', 0, 'iv_value');

    $stmt = $koneksi->prepare("INSERT INTO registrations (user_id, event_id, ticket_quantity, payment_method, phone_number, payment_amount) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $event_id, $ticket_quantity, $payment_method, $encrypted_phone, $payment_amount]);

    echo json_encode(['success' => true]);
}
?>
