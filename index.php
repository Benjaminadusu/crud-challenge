<?php
// Database connection
$servername = "localhost";
$database = "crud challenge";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$sql = "SELECT * FROM late_report";
$stmt = $conn->prepare($sql);
$stmt->execute();
$lateReports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Validation
foreach ($lateReports as $report) {
    if ($report['minuten_te_laat'] < 0) {
        die("Error: Foutmelding");
    }
}

$totalReports = count($lateReports);
$totalMinutesLate = 0;
$maxMinutesLate = 0;

foreach ($lateReports as $report) {
    $totalMinutesLate += $report['minuten_te_laat'];
    if ($report['minuten_te_laat'] > $maxMinutesLate) {
        $maxMinutesLate = $report['minuten_te_laat'];
    }
}

$averageMinutesLate = $totalReports ? $totalMinutesLate / $totalReports : 0;

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overzicht studenten die te laat waren</title>
    <link rel="stylesheet" href="benjamin.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Overzicht studenten die te laat waren</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Klas</th>
                    <th>Minuten te laat</th>
                    <th>Reden te laat</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($totalReports > 0): ?>
                    <?php foreach ($lateReports as $row): ?>
                        <tr>
                            <td><?= $row['late_report_id'] ?></td>
                            <td><?= $row['Naam_student'] ?></td>
                            <td><?= $row['klas'] ?></td>
                            <td><?= $row['minuten_te_laat'] ?></td>
                            <td><?= $row['reden_te_laat'] ?></td>
                            <td class="table-actions">
                                <a href="update.php?id=<?= $row['late_report_id'] ?>" class="btn btn-primary">Update</a>
                                <a href="delete.php?id=<?= $row['late_report_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No late reports found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="create.php" class="btn btn-success mb-3">Weer eentje te laat!</a>
        <div class="statistics mt-4">
            <h2>Statistieken</h2>
            <p><strong>Totaal aantal minuten te laat:</strong> <?= $totalMinutesLate ?></p>
            <p><strong>Hoogste aantal minuten te laat:</strong> <?= $maxMinutesLate ?></p>
            <p><strong>Gemiddeld aantal minuten te laat:</strong> <?= number_format($averageMinutesLate, 2) ?></p>
        </div>
    </div>
</body>
</html>
