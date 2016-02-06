<?php
include('settings/globals.php');

if(in_array(THERMOSTAT_TYPE,$valid_thermostats)){
    require('thermostats/'.THERMOSTAT_TYPE.'/scrape.php');    
    require('thermostats/'.THERMOSTAT_TYPE.'/logininfo.php');  
} else{
    die("Invalid THERMOSTAT_TYPE set in settings/globals.php");
}

$d = $device_array[$_GET['id']]; // device ID we are calculating this for

include('settings/globals.php');
header('Content-Type: application/json');
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

$sql = 'SELECT UNIX_TIMESTAMP(date),dispTemperature,weatherTemperature,heatSetPoint,systemSwitchPosition from stat WHERE deviceID=' . $d; // note filter for ID

$result = $conn->query($sql);
if (!$result) {
printf("Errormessage: %s\n", $conn->error);
}
$rows = array();
while($r = mysqli_fetch_assoc($result)) {
	$r['date'] = $r['UNIX_TIMESTAMP(date)'];
	unset($r['UNIX_TIMESTAMP(date)']);
	$rows[] = $r;
}
// print("<pre>");
// print_r($rows);
$startTime = -1;
$startTemp = NULL;
$startSetPoint = NULL;
$targetSetPoint = NULL;

$heat_up = NULL;
$heat_down = NULL;
$cool_up = NULL;
$cool_down = NULL;
$type = NULL;

for($i = 1; $i < count($rows); $i++){
	$now = $rows[$i];
	$last= $rows[$i-1];
// 	echo $startTime;
// print_r($now);
	if($startTime == -1){
// 		echo "no start <br/>";
		if($now['heatSetPoint'] <> $last['heatSetPoint']){
			//Down!
// 			echo "down<br/>";
			$startTime = $now['date'];
			$startTemp = $now['dispTemperature'];
			$startSetPoint = $last['heatSetPoint'];	
			$targetSetPoint = $now['heatSetPoint'];
			if($now['heatSetPoint'] > $last['heatSetPoint']){
				$type = "up";
			} else {
				$type = "down";
			}
		} 
	} else{
		//looking for end
// 		echo "yes start <br/>";
		if($now['dispTemperature'] == $targetSetPoint || $now['heatSetPoint'] <> $targetSetPoint){
			$changeTime = ($now['date']-$startTime)/60;
			$changeTemp = abs($startTemp - $now['dispTemperature']);
			$outside = $now['weatherTemperature'];
			$minperdeg = $changeTime/$changeTemp;
			
			if($type == "up"){
				$heat_up[] = [$minperdeg,intval($outside)];
			} else{
				$heat_down[] = [$minperdeg,intval($outside)];
			}
						
			$startTime = -1;
		}
	}
	
}

$return = [$heat_up, $heat_down];

print json_encode($return);


$conn->close();

?>