
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
        
        $id = $_GET['id']; // device ID
        
?>
<head>
  <title>Stat delay</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.5.0/bootstrap-table.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">
$(function () {
	console.log('load');
	var seriesOptions = [];
	var createChart = function(){
		console.log('create');
    	$('#container').highcharts({
        chart: {
            type: 'scatter',
            zoomType: 'xy'
        },
        title: {
            text: 'Time to heat or cool vs outside temperature'
        },
        subtitle: {
            text: 'Because science'
        },
        xAxis: {
            title: {
                enabled: true,
                text: 'Minutes per degree change'
            },
            startOnTick: true,
            endOnTick: true,
            showLastLabel: true
        },
        yAxis: {
            title: {
                text: 'Outside temp'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'top',
            x: 100,
            y: 70,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF',
            borderWidth: 1
        },
        plotOptions: {
            scatter: {
                marker: {
                    radius: 5,
                    states: {
                        hover: {
                            enabled: true,
                            lineColor: 'rgb(100,100,100)'
                        }
                    }
                },
                states: {
                    hover: {
                        marker: {
                            enabled: false
                        }
                    }
                },
                tooltip: {
                    headerFormat: '<b>{series.name}</b><br>',
                    pointFormat: '{point.x} min per degree, {point.y} degrees'
                }
            }
        },
        series: seriesOptions,
        
    });
    }
    $.getJSON('calc_delay.php?id=<?php echo $id ?>', function (data) {
    	console.log("data");
								seriesOptions[0] = {
										name: "Heat, Heating",
										data: data[0]
								};
								seriesOptions[1] = {
										name: "Heat, down",
										data: data[1]
								};
								seriesOptions[2] = {
										name: "Cool, Cooling",
										data: data[2]
								};
								seriesOptions[3] = {
										name: "Cool, up",
										data: data[3]
								};
								createChart();
							
						
						});
});

</script>


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
<div id="container" style="min-width: 310px; height: 400px; max-width: 800px; margin: 0 auto"></div>



</body></html>