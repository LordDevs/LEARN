<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {

    // Coletando os dados do formulário
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $address   = trim($_POST['address']);
    $mobile    = trim($_POST['mobile']);
    $email     = trim($_POST['email']);
    $eircode   = trim($_POST['eircode']);
    $applianceType = trim($_POST['applianceType']);
    $brand         = trim($_POST['brand']);
    $modelNumber   = trim($_POST['modelNumber']);
    $serialNumber  = trim($_POST['serialNumber']);
    $purchaseDate  = trim($_POST['purchaseDate']);
    $warrantyExpirationDate = trim($_POST['warrantyExpirationDate']);
    $cost          = trim($_POST['cost']);

    // Validação básica
    if (empty($firstName) || empty($lastName) || empty($serialNumber)) {
        echo "Please, fill all the required fields.";
        exit;
    }

    // VERIFICAR SE O USUÁRIO JÁ EXISTE
    $stmt = $conn->prepare("SELECT UserID FROM User WHERE Email = ? LIMIT 1");
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userID);
        $stmt->fetch();
    } else {
        // Inserir novo usuário
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO User (FirstName, LastName, Address, Mobile, Email, Eircode) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            echo "Error preparing insert user: " . $conn->error;
            exit;
        }
        $stmt->bind_param("ssssss", $firstName, $lastName, $address, $mobile, $email, $eircode);
        if (!$stmt->execute()) {
            echo "Error inserting user: " . $stmt->error;
            exit;
        }
        $userID = $stmt->insert_id;
    }
    $stmt->close();

    // VERIFICAR SE O ELETRODOMÉSTICO JÁ EXISTE
    $stmt = $conn->prepare("SELECT ApplianceID FROM Appliance WHERE SerialNumber = ? LIMIT 1");
    if (!$stmt) {
        echo "Error preparing check appliance: " . $conn->error;
        exit;
    }
    $stmt->bind_param("s", $serialNumber);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "This appliance already exists.";
        exit;
    }
    $stmt->close();

    // INSERIR ELETRODOMÉSTICO
    $stmt = $conn->prepare("INSERT INTO Appliance (UserID, ApplianceType, Brand, ModelNumber, SerialNumber, PurchaseDate, WarrantyExpirationDate, Cost) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "Error preparing insert appliance: " . $conn->error;
        exit;
    }
    $stmt->bind_param("issssssd", $userID, $applianceType, $brand, $modelNumber, $serialNumber, $purchaseDate, $warrantyExpirationDate, $cost);
    if ($stmt->execute()) {
        echo "Appliance added successfully!";
    } else {
        echo "Error inserting appliance: " . $stmt->error;
    }
    $stmt->close();
}
?>
