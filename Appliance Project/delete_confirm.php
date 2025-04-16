<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $applianceID = $_POST['applianceID'];

    $stmt = $conn->prepare("DELETE FROM Appliance WHERE ApplianceID = ?");
    $stmt->bind_param("i", $applianceID);

    if ($stmt->execute()) {
        echo "<p style='margin:30px;'>Appliance successfully deleted!</p>";
    } else {
        echo "Error deleting: " . $stmt->error;
    }

    echo "<a href='index.html' style='margin-left:30px;'>Voltar</a>";
    $stmt->close();
}
?>
