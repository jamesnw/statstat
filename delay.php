<?php
include('settings/globals.php');
header('Content-Type: application/json');
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}
$sql = 'SELECT UNIX_TIMESTAMP(date),id,dispTemperature,weatherTemperature,heatSetPoint,coolSetPoint,systemSwitchPosition from stat';

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

//Start at index 1 so index 0 is the prior one
for($i = 1; $i < count($rows); $i++){
	$now = $rows[$i];
	$last= $rows[$i-1];
// 	echo $startTime;
//   print_r($now);
  switch ($now['systemSwitchPosition']){
    case 1:
      // heating
      $setPoint = 'heatSetPoint';
      break;
    case 3:
      // cooling
      $setPoint = 'coolSetPoint';
      break;
    default:
      //AUTOCOOL: 5, AUTOHEAT: 4, EMHEAT: 0, OFF: 2, SOUTHERN_AWAY: 6, UNKNOWN: 7
      $setPoint = NULL;
      break;
  }
  if($setPoint){
    if($startTime == -1){
      if($now[$setPoint] <> $last[$setPoint]){
        if($setPoint == 'heatSetPoint' && $now[$setPoint] > $now['dispTemperature'] ||
          $setPoint == 'coolSetPoint' && $now[$setPoint] < $now['dispTemperature']){
//           print "Temperature started to change and is different!";
          $startTime = $now['date'];
          $startTemp = $now['dispTemperature'];
          $startSetPoint = $last[$setPoint];	
          $targetSetPoint = $now[$setPoint];
          if($now[$setPoint] > $last[$setPoint]){
            $type = "up";
          } else {
            $type = "down";
          }
        }
      } 
    } else{
      //looking for end
      if($now['dispTemperature'] == $targetSetPoint || $now[$setPoint] <> $targetSetPoint){
        $changeTime = ($now['date']-$startTime)/60;
        $changeTemp = abs($startTemp - $now['dispTemperature']);
        $outside = $now['weatherTemperature'];
        $minperdeg = $changeTime/$changeTemp;
        if(!$minperdeg){
          $minperdeg = 0;
        } 
        if($setPoint == 'heatSetPoint'){
          if($type == "up"){
            $heat_up[] = [$minperdeg,intval($outside)];
          } else{
            $heat_down[] = [$minperdeg,intval($outside)];
          }		
        } else if($setPoint == 'coolSetPoint'){
          if($type == "up"){
            $cool_up[] = [$minperdeg,intval($outside)];
          } else{
            $cool_down[] = [$minperdeg,intval($outside)];
          }		
        }
        $startTime = -1;
      }
    }
	}
}

$return = [$heat_up, $heat_down, $cool_up, $cool_down];

print json_encode($return);


$conn->close();

?>