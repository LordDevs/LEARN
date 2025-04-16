<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['serialNumber'])) {
    $serialNumber = trim($_GET['serialNumber']);

    $stmt = $conn->prepare("SELECT ApplianceID, ApplianceType, Brand, ModelNumber, PurchaseDate, WarrantyExpirationDate, Cost FROM Appliance WHERE SerialNumber = ? LIMIT 1");
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
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Appliance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">
    <div class="container mt-5">
    <h2 class="mb-4">Edit Appliance</h2>
    <form action="update_save.php" method="POST" class="bg-white p-4 rounded shadow">
        <input type="hidden" name="applianceID" value="<?= $data['ApplianceID'] ?>">

        <div class="mb-3">
            <label class="form-label">Type</label>
            <input type="text" name="applianceType" value="<?= $data['ApplianceType'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Brand</label>
            <input type="text" name="brand" value="<?= $data['Brand'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Model</label>
            <input type="text" name="modelNumber" value="<?= $data['ModelNumber'] ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Date to purchas</label>
            <input type="date" name="purchaseDate" value="<?= $data['PurchaseDate'] ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Guarantee until</label>
            <input type="date" name="warrantyExpirationDate" value="<?= $data['WarrantyExpirationDate'] ?>" class="form-control">
        </div>

        <div class="mb-4">
            <label class="form-label">Custo (€)</label>
            <input type="number" step="0.01" name="cost" value="<?= $data['Cost'] ?>" class="form-control">
        </div>

        <div class="text-center">
            <button type="submit" name="update" class="btn btn-success">Save Changes</button>
            <a href="index.html" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>