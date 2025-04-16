<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['serialNumber'])) {
	$serialNumber = trim($_GET['serialNumber']);
	//verify if something typed
	if(empty($serialNumber)){
		echo "Please: insert the serial number of your product";
		exit;
	}

	//consult
	$stmt = $conn->prepare("SELECT a.ApplianceType, a.Brand, a.ModelNumber, a.SerialNumber, a.PurchaseDate, a.WarrantyExpirationDate, a.Cost, u.FirstName, u.LastName, u.Email FROM Appliance a JOIN User u ON a.UserID = u.UserID WHERE a.SerialNumber = ? LIMIT 1" );

	if(!$stmt){
		echo "ERROR" . $conn->error;
	}

	$stmt->bind_param("s", $serialNumber);
	$stmt->execute();
	$result = $stmt->get_result();

	if($result->num_rows > 0){
		$data= $result->fetch_assoc();

		echo "<div style='margin: 30px; padding: 20px; border: 1px solid #ccc; background-color: #fefefe; max-width: 600px'>";
        echo "<h2>Appliance Details</h2>";
        echo "<strong>Type:</strong> " . $data['ApplianceType'] . "<br>";
        echo "<strong>Brand:</strong> " . $data['Brand'] . "<br>";
        echo "<strong>Model:</strong> " . $data['ModelNumber'] . "<br>";
        echo "<strong>Serial Number:</strong> " . $data['SerialNumber'] . "<br>";
        echo "<strong>Purchase Date:</strong> " . $data['PurchaseDate'] . "<br>";
        echo "<strong>Guarantee until:</strong> " . $data['WarrantyExpirationDate'] . "<br>";
        echo "<strong>Coast:</strong> €" . $data['Cost'] . "<br><br>";

        echo "<h4>Registered by:</h4>";
        echo $data['FirstName'] . " " . $data['LastName'] . "<br>";
        echo "Email: " . $data['Email'] . "<br>";
        echo "<br><a href='index.html'>Back</a>";
        echo "</div>";
    } else {
        echo "<p style='margin:30px; font-weight:bold'>No appliances found with this serial number.</p>";
        echo "<a href='index.html' style='margin-left:30px'>Back</a>";
    }

    $stmt->close();
}
?>
	}
