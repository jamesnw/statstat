<?php

include('settings/globals.php'); 

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
// Check connection
if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
}

$sql  = "CREATE TABLE IF NOT EXISTS `stat` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`coolLowerSetpLimit` float NOT NULL,
	`coolNextPeriod` int(11) NOT NULL,
	`coolSetpoint` float NOT NULL,
	`coolUpperSetptLimit` float NOT NULL,
	`deviceID` int(11) NOT NULL,
	`dispTemperature` float NOT NULL,
	`displayedUnits` varchar(1) COLLATE latin1_german2_ci NOT NULL,
	`heatLowerSetptLimit` float NOT NULL,
	`heatNextPeriod` int(11) NOT NULL,
	`heatSetpoint` float NOT NULL,
	`heatUpperSetptLimit` float NOT NULL,
	`isInVacationHoldMode` tinyint(1) NOT NULL,
	`schedCoolSp` float NOT NULL,
	`schedHeatSp` float NOT NULL,
	`scheduleCapable` tinyint(1) NOT NULL,
	`statusCool` int(11) NOT NULL,
	`statusHeat` int(11) NOT NULL,
	`systemSwitchPosition` int(11) NOT NULL,
	`weatherHumidity` int(11) NOT NULL,
	`weatherPhrase` varchar(32) COLLATE latin1_german2_ci NOT NULL,
	`weatherTemperature` int(11) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1 ;";

$result = $conn->query($sql);
if (!$result) {
	printf("Errormessage: %s\n", $conn->error);
} else{
	print "success";
}
	
?>