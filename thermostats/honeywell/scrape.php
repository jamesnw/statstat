<?php

$ch = curl_init();

function login($usr, $pwd){
	$url = "https://mytotalconnectcomfort.com/portal/";

	GLOBAL $ch;	
	
	curl_setopt($ch, CURLOPT_NOBODY, false);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	
	$headers = array(
		'Content-Type' => 'application/x-www-form-urlencoded',
		'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
		'Accept-Encoding' => 'sdch',
		'Host' => 'mytotalconnectcomfort.com',
		'DNT' => '1',
		'Origin' => 'https://mytotalconnectcomfort.com/portal/',
		'Connection' => 'Keep-Alive',
    	'Keep-Alive' => '300'
	);
	
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
	$cookie_file_path = 'cookies.txt';

	if (!file_exists($cookie_file_path)){
		 fopen($cookie_file_path, "w");
		 if (!file_exists($cookie_file_path)){
		 		echo "Cookie file missing and can't be created.\n";
        die;
		 }
	}
	if(!is_writable($cookie_file_path)){
		echo 'Cookie file not writable- chmod to the user writing the script\n.';
		die;
  }

	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	curl_setopt($ch, CURLOPT_USERAGENT,
		"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.95 Safari/537.36");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_REFERER,'https://mytotalconnectcomfort.com/portal/');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_POST, 1);
	
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);

	$postinfo = array(
		'timeOffset' => '300', 
		'UserName' => $usr, 
		'Password' => $pwd, 
		'RememberMe' => 'false'
	);
	$postinfo = http_build_query($postinfo);
	
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
	$html = curl_exec($ch);
	//print $html;
	// $sentHeaders = curl_getinfo($ch, CURLINFO_HEADER_OUT);
// print "<pre>Sent headers: <br />";
// var_dump($sentHeaders);
// print "</pre><br />";

}

function getStatus($device){
	// print "<hr><br>Get Status<br><br><pre>";

	$time = round(microtime(true) * 1000);
  	$url = "https://mytotalconnectcomfort.com/portal/Device/Control/".$device;
// 	$url = 'http://up.jamesnweber.com/_sandbox/stat/post.php';
	GLOBAL $ch;
	
	
	curl_setopt($ch, CURLOPT_NOBODY, false);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 

	
	$headers = array(
		'Accept' => '*/*',
		'Accept-Encoding' => '*/*',
		'Host' => 'mytotalconnectcomfort.com',
		'DNT' => '1',
		'X-Requested-With' => 'XMLHttpRequest',
		'Connection' => 'keep-alive',
		'Accept-Language' => 'en-US,en;q=0.8',
		'Connection' => 'Keep-Alive',
    	'Keep-Alive' => '300',
    	'checkCookie' => 'checkValue'
	);
	
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
	$cookie_file_path = 'cookies.txt';
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	curl_setopt($ch, CURLOPT_USERAGENT,	"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36");
	curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate, sdch');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_REFERER,'https://mytotalconnectcomfort.com/portal/Device/Control/'.$device);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	
	
	$html = curl_exec($ch);
	
	$re = "/Control.Model.Property\\.(.*), (.*)\\)/"; 
	preg_match_all($re, $html, $matches);
	$data = array();
	foreach($matches[1] as $key => $match){
		if(strpos($matches[1][$key], ',') == FALSE){
			$data[$matches[1][$key]] = $matches[2][$key];
		}
	}
	
	curl_close($ch);
	
	return $data;
	
	

}

function cleanCookies(){
	$cookie_file_path = 'cookies.txt';
	$file_contents = file_get_contents($cookie_file_path);
	$file_contents = str_replace("#HttpOnly_","",$file_contents);
	file_put_contents($cookie_file_path,$file_contents);
	curl_close($ch);
}

function add_data($data){
	include('globals.php');
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	// Check connection
	if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
	}
	
	$good_columns = array("coolLowerSetpLimit","coolNextPeriod","coolSetpoint","coolUpperSetptLimit","deviceID","dispTemperature","displayedUnits","heatLowerSetptLimit","heatNextPeriod","heatSetpoint","heatUpperSetptLimit","isInVacationHoldMode","schedCoolSp","schedHeatSp","scheduleCapable","statusCool","statusHeat","systemSwitchPosition","weatherHumidity","weatherPhrase","weatherTemperature");
	
	$clean_data = array();
	foreach($good_columns as $col){
			$clean_data[$col] = $data[$col];
	}
	//$clean_data['weatherPhrase'] = $conn->real_escape_string($clean_data['weatherPhrase']);
	$columns = implode(", ",array_keys($clean_data));
	$values  = implode(", ", array_values($clean_data));
	
	$sql = "INSERT INTO `stat`(".$columns.") VALUES (".$values.")";
  $result = $conn->query($sql);
	echo $sql;
	echo "\n";
	echo $result;
	if (!$result) {
    printf("Errormessage: %s\n", $conn->error);
}
	
	$conn->close();
	
}

function getCurrentInfo(){

	require_once('logininfo.php');
	login($username, $password);
	$data = getStatus($device_number);
	
	return $data;
}



?>