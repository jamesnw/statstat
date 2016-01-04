<?php

$q = '';
if(isset($_GET)){
	if(isset($_GET['q'])){
		$q = $_GET['q'];

	header('Content-Type: application/json');
	include('settings/globals.php');
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	// Check connection
	if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
	}
	$sql = '';
	switch($q){
		case "inside":
			$sql = "SELECT dispTemperature from stat ORDER BY id DESC LIMIT 1";
			break;
		case 'outside':
			$sql = "SELECT weatherTemperature from stat ORDER BY id DESC LIMIT 1";
			break;
		case 'set_heat':
			$sql = "SELECT heatSetpoint from stat ORDER BY id DESC LIMIT 1";
			break;
		case 'set_cool':
			$sql = "SELECT coolSetpoint from stat ORDER BY id DESC LIMIT 1";
			break;
		case 'in_humidity':
			$sql = "SELECT indoorHumidity from stat ORDER BY id DESC LIMIT 1";
			break;
		case 'out_humidity':
			$sql = "SELECT weatherHumidity from stat ORDER BY id DESC LIMIT 1";
			break;
		default:
			die("No query");

	}

	$result = $conn->query($sql);
	if (!$result) {
		printf("Errormessage: %s\n", $conn->error);
	}
	$rows = array();
	while($r = mysqli_fetch_assoc($result)) {
		print array_values($r)[0];
	}



	$conn->close();

	}
}

?>
