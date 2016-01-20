<?php

include('settings/globals.php');

if(in_array(THERMOSTAT_TYPE,$valid_thermostats)){
	require('thermostats/'.THERMOSTAT_TYPE.'/scrape.php');   
    require('thermostats/'.THERMOSTAT_TYPE.'/logininfo.php');      
} else{
	die("Invalid THERMOSTAT_TYPE set in settings/globals.php");
}



// dorfl added simple loop
for ($i = 0; $i <enumerate_thermostats(); $i++) { // all thermostats

            $data = getCurrentInfo($i); // changed info!

            $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            // Check connection
            if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
            }

            $good_columns = array("coolLowerSetpLimit","coolNextPeriod","coolSetpoint","coolUpperSetptLimit","deviceID","dispTemperature","displayedUnits","heatLowerSetptLimit","heatNextPeriod","heatSetpoint","heatUpperSetptLimit","indoorHumidity","isInVacationHoldMode","schedCoolSp","schedHeatSp","scheduleCapable","statusCool","statusHeat","systemSwitchPosition","weatherHumidity","weatherPhrase","weatherTemperature");
  
  
            $clean_data = array();


            $clean_data = array();
            foreach($good_columns as $col){
                if(isset($data[$col])){
                    $clean_data[$col] = $data[$col];
                }
            }
            $columns = implode(", ",array_keys($clean_data));
            $values  = implode(", ", array_values($clean_data));

            $sql = "INSERT INTO `stat`(" . $columns . ") VALUES (" . $values.")";
            $result = $conn->query($sql);
            echo $sql;
            echo "\n";
            echo $result;
            if (!$result) {
                printf("Errormessage: %s\n", $conn->error);
            }
} // end for thermostats

$conn->close();


?>
