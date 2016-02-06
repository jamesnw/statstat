<!DOCTYPE html>
<html lang="en">
<?php
        include('settings/globals.php');
        $valid_thermostats = ["honeywell"];
        if(in_array(THERMOSTAT_TYPE,$valid_thermostats)){
            require('thermostats/'.THERMOSTAT_TYPE.'/scrape.php');    
            require('thermostats/'.THERMOSTAT_TYPE.'/logininfo.php');  
        } else{
            die("Invalid THERMOSTAT_TYPE set in settings/globals.php");
        }
        $id = $_GET['id']; //id # of thermostat
?>
<head>
  <title> Household Temperature Tracker</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.5.0/bootstrap-table.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script src="http://code.highcharts.com/stock/highstock.js"></script>
	<script src="http://code.highcharts.com/stock/modules/exporting.js"></script>
	<script type="text/javascript">
		$(function () {
				var d = new Date();
				var timezoneOffset = d.getTimezoneOffset();
				Highcharts.setOptions({
						global: {
								timezoneOffset: timezoneOffset
						}
				});
				var names = ['inside','outside','set_heat','set_cool','in_humidity','out_humidity'];
						seriesOptions = [],
						seriesCounter = 0,
						createChart = function () {

								$('#container').highcharts('StockChart', {

										rangeSelector: {
												selected: 4
										},
									

										yAxis: {
												 title: {
														text: 'Temperature' 
												},
												labels: {
														formatter: function () {
																return this.value;
														}
												},
												plotLines: [{
														value: 0,
														width: 2,
														color: 'silver'
												}]
										},

										

										tooltip: {
												pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
												valueDecimals: 0
										},

										series: seriesOptions,
										legend: {
											enabled: true,
											borderWidth: 1,
											backgroundColor: '#FFFFFF',
											shadow: true
										}
								});
						};
						
				$.each(names, function (i, name) {
					$.getJSON('db-get.php?id=<?php echo $id ?>&q='+name, function (data) {

								seriesOptions[i] = {
										name: name,
										data: data
								};
							 seriesCounter += 1;

							if (seriesCounter === names.length) {
									createChart();
							}
						
						});
					});
		});
	</script>
	</head>
<body>
  <?php
   //  navbar
     include('navbar.php');
  ?>
<h1>

<?php
  // title!
  echo $device_name_array[$id];
?>

</h1>
<div id="container" style="height: 600px; min-width: 310px"></div>

</body>