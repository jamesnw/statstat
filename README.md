# Stat stat
A set of tools to record and analyze data from a smart thermostat.

##Thermostat support
Currently, only Honeywell Thermostats controllable through their Total Connect system are supported. Additional thermostats can be added as plugins using the plugin structure outlined here- https://github.com/jamesnw/statstat/wiki/Plugin-structure

##Installation
* Add your database info to globals.php.example. Rename to globals.php
* Add your Honeywell Total Connect info to logininfo.php.example. (The device_number is the number that appears in the URL at https://mytotalconnectcomfort.com/portal/NUMBERHERE/Zones). Rename to logininfo.php
* Run dbsetup.php
* Add a cron job to run cron.php every 5 minutes. Example line in cron- `*/5 * * * * /usr/bin/php5 /full/path/to/stat/cron.php`
* Wait for useful data to come in

##Upgrade
A change merged in on 2016-02-06 changed the settings.php structure to support multiple thermostats- see the example file. Also, there's a new field in the database- indoorHumidity.

##Analysis
###Graph
See your temperatures fluctuate and change.
![](http://jamesnw.github.io/statstat/images/graph.png)

###Delay
See how long it takes for your house to heat and cool based on outside temperature
![](http://jamesnw.github.io/statstat/images/delay.png)

##Security & Privacy
As this does not allow anyone (including you) to control your thermostat via the web interface, security concerns are minimal. However, you do need to store your password in plain text on the server, so anyone with access to that would have access to control your thermostat.

For privacy- if you have privacy concerns, I'd suggest using htpasswd to put the info behind a password.
