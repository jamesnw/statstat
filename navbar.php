
  <!-- Fixed navbar -->
    <nav class="navbar navbar-inverse">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Household Temperature Tracker</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index.php">Home</a></li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Graph
                <span class="caret"></span></a>
                <ul class="dropdown-menu">
                <?php
                // create drop-down entries
                for ($i = 0; $i <enumerate_thermostats(); $i++) {
                    echo '<li><a href="graph.php?id=';
                    echo $i;
                    echo '">';
                    echo $device_name_array[$i];
                    echo '</a></li>';
                    
                }                    
                ?> 
                </ul>
            </li>    <!--/graph -->
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Delay
                <span class="caret"></span></a>
                <ul class="dropdown-menu">
                <?php
                // create drop-down entries
                for ($i = 0; $i <enumerate_thermostats(); $i++) {
                    echo '<li><a href="delay.php?id=';
                    echo $i;
                    echo '">';
                    echo $device_name_array[$i];
                    echo '</a></li>';
                    
                }                    
                ?> 
                </ul> 
            </li>    <!--delay -->
           </ul> <!-- navbar -->
        </div><!--/.nav-collapse -->
      </div>
    </nav>