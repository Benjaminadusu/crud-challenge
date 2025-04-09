<?php
require 'crud.php';

$servername = "localhost";
$database = "crud challenge";
$username = "root";
$password = "";

$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

$id = isset($_GET['id']) ? $_GET['id'] : null;
$row = [];

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM late_report WHERE late_report_id = ?");
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = $_POST['Naam_student'];
    $class = $_POST['klas'];
    $minutes_late = $_POST['minuten_te_laat'];
    $reason = $_POST['reden_te_laat'];

   
    if (empty($student_name) || empty($class) || empty($minutes_late) || empty($reason) || !is_numeric($minutes_late) || $minutes_late < 0) {
        $error = "Please fill all fields correctly.";
    } else {
        $update_stmt = $conn->prepare("UPDATE late_report SET Naam_student = ?, klas = ?, minuten_te_laat = ?, reden_te_laat = ? WHERE late_report_id = ?");
        $update_stmt->bindParam(1, $student_name);
        $update_stmt->bindParam(2, $class);
        $update_stmt->bindParam(3, $minutes_late);
        $update_stmt->bindParam(4, $reason);
        $update_stmt->bindParam(5, $id);

        if ($update_stmt->execute()) {
            header("Location:index.php ");
            exit();
        } else {
            $error = "Error: " . $update_stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Late Report</title>
    <link href="benjamin.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Nieuwe melding te late student</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form action="update.php?id=<?= htmlspecialchars($id) ?>" method="post">
            <div class="form-group">
                <label for="Naam_student">Student Name</label>
                <input type="text" class="form-control" id="Naam_student" name="Naam_student" value="<?= isset($row['Naam_student']) ? htmlspecialchars($row['Naam_student']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="klas">Klas</label>
                <input type="text" class="form-control" id="klas" name="klas" value="<?= isset($row['klas']) ? htmlspecialchars($row['klas']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="minuten_te_laat">Aantal minuteen te laat</label>
                <input type="number" class="form-control" id="minuten_te_laat" name="minuten_te_laat" value="<?= isset($row['minuten_te_laat']) ? htmlspecialchars($row['minuten_te_laat']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="reden_te_laat">Reden te laat komen</label>
                <textarea class="form-control" id="reden_te_laat" name="reden_te_laat"><?= isset($row['reden_te_laat']) ? htmlspecialchars($row['reden_te_laat']) : '' ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Opslaan</button>
        </form>
    </div>
</body>
</html>


