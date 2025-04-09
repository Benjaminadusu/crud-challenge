<?php
require 'crud.php';

$servername = "localhost";
$database = "crud challenge";
$username = "root";
$password = "";

$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM late_report WHERE late_report_id = ?");
    $stmt->bindParam(1, $id);

    if ($stmt->execute()) {
        header("Location:index.php ");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<?php $conn->close(); ?>
