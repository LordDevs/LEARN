<<?php 

require 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $applianceID = $_POST['applianceID'];
    $applianceType = trim($_POST['applianceType']);
    $brand = trim($_POST['brand']);
    $modelNumber = trim($_POST['modelNumber']);
    $purchaseDate = trim($_POST['purchaseDate']);
    $warrantyExpirationDate = trim($_POST['warrantyExpirationDate']);
    $cost = trim($_POST['cost']);

    $stmt = $conn->prepare("UPDATE Appliance SET ApplianceType=?, Brand=?, ModelNumber=?, PurchaseDate=?, WarrantyExpirationDate=?, Cost=? WHERE ApplianceID=?");
    $stmt->bind_param("ssssssi", $applianceType, $brand, $modelNumber, $purchaseDate, $warrantyExpirationDate, $cost, $applianceID);

    if ($stmt->execute()) {
        echo "<p style='margin:30px;'>Appliance update succefull!</p>";
    } else {
        echo "Error to update: " . $stmt->error;
    }

    echo "<a href='index.html' style='margin-left:30px;'>Voltar</a>";
    $stmt->close();
}


 ?>