<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['serialNumber'])) {
    $serialNumber = trim($_GET['serialNumber']);

    $stmt = $conn->prepare("SELECT ApplianceID, ApplianceType, Brand, ModelNumber FROM Appliance WHERE SerialNumber = ? LIMIT 1");
    $stmt->bind_param("s", $serialNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $data = $result->fetch_assoc();
    } else {
        echo "<p style='margin:30px;'>Nenhum registro encontrado com esse número de série.</p>";
        echo "<a href='index.html' style='margin-left:30px;'>Voltar</a>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Confirmr delete</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4">Are you sure you want to delete this item?</h3>
    <form action="delete_confirm.php" method="POST" class="bg-white p-4 rounded shadow">
        <input type="hidden" name="applianceID" value="<?= $data['ApplianceID'] ?>">

        <p><strong>type:</strong> <?= $data['ApplianceType'] ?></p>
        <p><strong>brand:</strong> <?= $data['Brand'] ?></p>
        <p><strong>Model</strong> <?= $data['ModelNumber'] ?></p>

        <div class="text-center mt-4">
            <button type="submit" name="delete" class="btn btn-danger">Yes</button>
            <a href="index.html" class="btn btn-secondary">cancel</a>
        </div>
    </form>
</div>
</body>
</html>