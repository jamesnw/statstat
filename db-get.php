<?php

$q = '';
$device_index = '';
include('settings/globals.php');



if(in_array(THERMOSTAT_TYPE,$valid_thermostats)){
	require('thermostats/'.THERMOSTAT_TYPE.'/scrape.php');    
    require('thermostats/'.THERMOSTAT_TYPE.'/logininfo.php');  
} else{
	die("Invalid THERMOSTAT_TYPE set in settings/globals.php");
}

if(isset($_GET)){
	if(isset($_GET['q'])){
		$q = $_GET['q'];
        if(isset($_GET['id'])){
           $d = $device_array[$_GET['id']];
            
            //$d="1488017";
  
            header('Content-Type: application/json');
            
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
            if (!$result) {
                printf("Errormessage: %s\n", $conn->error);
            }
            $rows = array();
            while($r = mysqli_fetch_assoc($result)) {
                    $row = array_map('floatval',array_values($r));
                    $row[0] = $row[0]*1000;
                    $rows[] = $row;
            }
            print json_encode($rows);


            $conn->close();
        }
	}
}

?>