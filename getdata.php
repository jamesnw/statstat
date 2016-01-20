<?php

$q = '';
if(isset($_GET)){
	if(isset($_GET['q'])){
		$q = $_GET['q'];

    $d = 1488017; // device ID number
    
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
			$sql = "SELECT UNIX_TIMESTAMP(date),dispTemperature from stat";
			break;
		case 'outside':
			$sql = "SELECT UNIX_TIMESTAMP(date),weatherTemperature from stat";
			break;
		case 'set_heat':
			$sql = "SELECT UNIX_TIMESTAMP(date),heatSetpoint from stat";
			break;
		case 'set_cool':
			$sql = "SELECT UNIX_TIMESTAMP(date),coolSetpoint from stat";
			break;
		case 'humidity':
			$sql = "SELECT UNIX_TIMESTAMP(date),weatherHumidity from stat";
			break;
		default:
			die("No query");
		
	}
	$sql = $sql . " WHERE deviceID=" . $d;
	$result = $conn->query($sql);
    
    echo $sql;
    $rows = array();
	while($r = mysqli_fetch_assoc($result)) {
			$row = array_map('floatval',array_values($r));
			$row[0] = $row[0]*1000;
			$rows[] = $row;
            echo $row[1];
            
	
    }
    
	$conn->close();

	}
}

?>