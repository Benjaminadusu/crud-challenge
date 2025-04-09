<?php
require 'connectie.php'; 

// a PDO instance
try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = isset($_POST['Naam_student']) ? $_POST['Naam_student'] : '';
    $class = isset($_POST['klas']) ? $_POST['klas'] : '';
    $minutes_late = isset($_POST['minuten_te_laat']) ? $_POST['minuten_te_laat'] : '';
    $reason = isset($_POST['reden_te_laat']) ? $_POST['reden_te_laat'] : '';

    // Input validation
    if (empty($student_name) || empty($class) || empty($minutes_late) || empty($reason) || !is_numeric($minutes_late) || $minutes_late < 0) {
        $error = "Please fill all fields correctly.";
    } else {
        $stmt = $conn->prepare("INSERT INTO late_report (Naam_student, klas, minuten_te_laat, reden_te_laat) VALUES (:Naam_student, :klas, :minuten_te_laat, :reden_te_laat)");
        $stmt->bindParam(':Naam_student', $student_name);
        $stmt->bindParam(':klas', $class);
        $stmt->bindParam(':minuten_te_laat', $minutes_late, PDO::PARAM_INT);
        $stmt->bindParam(':reden_te_laat', $reason);

        if ($stmt->execute()) {
            header("Location:index.php ");
            exit();
        } else {
            $error = "Error: " . $stmt->errorInfo()[2];
        }
    }
}

$conn = null; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe melding te late student</title>
    <link href="benny.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Nieuwe melding te late student</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form action="create.php" method="post">
            <div class="form-group">
                <label for="Naam_student">Naam student</label>
                <input type="text" class="form-control" id="Naam_student" name="Naam_student" value="<?= isset($_POST['Naam_student']) ? htmlspecialchars($_POST['Naam_student']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="klas">Klas</label>
                <input type="text" class="form-control" id="klas" name="klas" value="<?= isset($_POST['klas']) ? htmlspecialchars($_POST['klas']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="minuten_te_laat">Aantal minuten te laat</label>
                <input type="number" class="form-control" id="minuten_te_laat" name="minuten_te_laat" value="<?= isset($_POST['minuten_te_laat']) ? htmlspecialchars($_POST['minuten_te_laat']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="reden_te_laat">Reden te laat komen</label>
                <textarea class="form-control" id="reden_te_laat" name="reden_te_laat"><?= isset($_POST['reden_te_laat']) ? htmlspecialchars($_POST['reden_te_laat']) : '' ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Opslaan</button>
        </form>
    </div>
</body>
</html>
