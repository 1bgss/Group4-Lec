<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require '../vendor/autoload.php'; 
include '../db.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!isset($_GET['event_id']) || empty($_GET['event_id'])) {
    echo "Event ID is missing!";
    exit;
}

$event_id = $_GET['event_id'];

$stmt = $koneksi->prepare("SELECT users.name, users.email, registrations.ticket_count, registrations.amount_paid FROM registrations JOIN users ON registrations.user_id = users.id WHERE registrations.event_id = ?");
$stmt->execute([$event_id]);
$registrants = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['export']) && $_GET['export'] === 'xlsx') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Registrants');

    $sheet->setCellValue('A1', 'Name');
    $sheet->setCellValue('B1', 'Email');
    $sheet->setCellValue('C1', 'Jumlah Tiket');
    $sheet->setCellValue('D1', 'Total Pembayaran (IDR)');

    $row = 2; 
    foreach ($registrants as $registrant) {
        $sheet->setCellValue('A' . $row, $registrant['name']);
        $sheet->setCellValue('B' . $row, $registrant['email']);
        $sheet->setCellValue('C' . $row, $registrant['ticket_count']);
        $sheet->setCellValue('D' . $row, $registrant['amount_paid']);
        $row++;
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="registrants.xlsx"');
    header('Cache-Control: max-age=0'); 

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit; 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Registrants</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Registrants for Event ID <?= htmlspecialchars($event_id) ?></h1>
    <?php if (count($registrants) > 0): ?>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Jumlah Tiket</th>
                <th>Total Pembayaran (IDR)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registrants as $registrant): ?>
                <tr>
                    <td><?= htmlspecialchars($registrant['name']) ?></td>
                    <td><?= htmlspecialchars($registrant['email']) ?></td>
                    <td><?= htmlspecialchars($registrant['ticket_count']) ?></td>
                    <td><?= htmlspecialchars($registrant['amount_paid']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <a href="dashboard_admin.php" class="btn btn-success">Back to Dashboard</a>
    
    <a href="view_registrants.php?event_id=<?= htmlspecialchars($event_id) ?>&export=xlsx" class="btn btn-success">Export to Excel</a>
    <?php else: ?>
        <p>No registrants for this event.</p>
    <?php endif; ?>
</div>
</body>
</html>
