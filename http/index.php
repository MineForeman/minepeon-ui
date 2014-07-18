<?php

require_once('miner.inc.php');
require_once('functions.inc.php');
require_once('settings.inc.php');


// Change the pool if requested 

if (isset($_POST['url'])) {
        
$pools = miner('pools','')['POOLS'];
  $pool = 0;
  foreach ($pools as $key => $value) {
    if(isset($value['User']) && $value['URL']==$_POST['url']){
	  miner('switchpool',$pool);
    }
    $pool = $pool + 1;
  }
}

// Static head page element
include('static/head.php');

?>
   <script type="text/javascript">
    $(document).ready(function () {

	// Reload the ajax elements every seccond
        setInterval(function () {
            $("#title1").load("ajax/title.php");
            $("#status1").load("ajax/status.php");
            $("#miners1").load("ajax/miners.php");
            $("#pools1").load("ajax/pools.php");
           }, 1000);

	// reload the graph images every minute
	setInterval(function () {
            var mhsavhourIMG = document.getElementById('mhsavhour');
            var mhsavdayIMG = document.getElementById('mhsavday');
            var mhsavweekIMG = document.getElementById('mhsavweek');
            var mhsavmonthIMG = document.getElementById('mhsavmonth');
            var mhsavyearIMG = document.getElementById('mhsavyear');

            mhsavhourIMG.src = 'rrd/mhsav-hour.png?rand=' + Math.random();
            mhsavdayIMG.src = 'rrd/mhsav-day.png?rand=' + Math.random();
            mhsavweekIMG.src = 'rrd/mhsav-week.png?rand=' + Math.random();
            mhsavmonthIMG.src = 'rrd/mhsav-month.png?rand=' + Math.random();
            mhsavyearIMG.src = 'rrd/mhsav-year.png?rand=' + Math.random();
           }, 60000);

	// Chart Toggle
	$( "#chartToggle" ).click(function() {
	    $('.chartMore').slideToggle('slow', function() {
	    });
	});

    });
   </script>
<?php
include('menu.php');
?>

<div class="container">
  <h2>Status</h2>
  <?php
  if (file_exists('/opt/minepeon/http/rrd/mhsav-hour.png')) {
  ?>
  <p class="text-center">
    <img src="rrd/mhsav-hour.png" alt="mhsav.png" id="mhsavhour" />
  </p>
  <p class="text-center">
    <a href="#" id="chartToggle">Toggle extended charts</a>
  </p>
  <p class="text-center collapse chartMore">
    <img src="rrd/mhsav-day.png" alt="mhsav.png" id="mhsavday" />
  </p>
  <p class="text-center collapse chartMore">
    <img src="rrd/mhsav-week.png" alt="mhsav.png" id="mhsavweek" />
  </p>
  <p class="text-center collapse chartMore">
    <img src="rrd/mhsav-month.png" alt="mhsav.png" id="mhsavmonth" />
  </p>
  <p class="text-center collapse chartMore">
    <img src="rrd/mhsav-year.png" alt="mhsav.png" id="mhsavyear" />
  </p>
  <?php
  } else {
  ?>
  <center><h1>Graphs not ready yet</h1></center>
  <center><h2>Please wait upto 5 minutes</h2></center>
  <?php
  }
  ?>

 <div id="title1"><?php include_once("ajax/title.php"); ?></div>

 <div id="status1"><?php include_once("ajax/status.php"); ?></div>

  <center>
    <a class="btn btn-default" href='/restart.php'><?php echo $lang["restartminer"]; ?></a>  
    <a class="btn btn-default" href='/reboot.php'><?php echo $lang["reboot"]; ?></a> 
    <a class="btn btn-default" href='/halt.php'><?php echo $lang["shutdown"]; ?></a>
  </center>
  <h3><?php echo $lang["pools"]; ?></h3>

      <div id="pools1"><?php include_once("ajax/pools.php"); ?></div>


  <h3><?php echo $lang["devices"]; ?></h3>
 <div id="miners1"><?php include_once("ajax/miners.php"); ?></div>
  <?php
  if ($debug == true) {
	
	echo "<pre>";
	print_r($pools['POOLS']);
	print_r($devs);
	echo "<pre>";
	
  }
  ?>

</div>
 
<?php 
 
// Change screen colour test for alerts
 
if ($settings['donateAmount'] < 1) {
	echo 'document.body.style.background = "#FFFFCF"';
}

?>

</script>
<?php
include('foot.php');


function statsTable($devs) {
  if(count($devs)==0){
    return "</tbody></table><div class='alert alert-danger'>No devices running</div>";
  }

  $devices = 0;
  $MHSav = 0;
  $Accepted = 0;
  $Rejected = 0;
  $HardwareErrors = 0;
  $Utility = 0;

  $tableRow = '<table id="stats" class="tablesorter table table-striped table-hover stats">
    <thead>
      <tr>
        <th>Name</th>
        <th>ID</th>
        <th>Temp</th>
        <th>Hash Rate</th>
        <th>Accept</th>
        <th>Reject</th>
        <th>Error</th>
        <th>Utility</th>
        <th>Last Share</th>
      </tr>
    </thead>
    <tbody>';

 	$hwErrorPercent = 0;
	$DeviceRejected = 0;

  foreach ($devs as $dev) {
  
	// Sort out valid deceives
	
	$validDevice = true;
 
	if(!isset($dev['MHS5s'])) {
    		$dev['MHS5s'] = 0;
	}

       if(!isset($dev['MHS20s'])) {
               $dev['MHS20s'] = 0;
        }

	
       if (!$dev['MHS5s'] > 1 || !$dev['MHS20s'] > 1) {
		// not mining, not a valid device
		$validDevice = false;
	}
        
	//if ((time() - $dev['LastShareTime']) > 1000) {
	//	// Only show devices that have returned a share in the past 5 minutes
	//	$validDevice = false;
	//}
	
	if (isset($dev['Temperature'])) {
		$temperature = $dev['Temperature'];
	} else {
		$temperature = "N/A";
	}
        
        if ($dev['MHSav'] > 999){
          $hrate = $dev['MHSav'] / 1000;
	  $hrate = number_format((float)$hrate, 2, '.', '');
          $hrate = $hrate . " GH/s";
        }else{
	  $hrate = $dev['MHSav'] . " MH/s";
        }
	
	if ($validDevice) {

		if ($dev['DeviceHardware%'] >= 10 || $dev['DeviceRejected%'] > 5) {
			$tableRow = $tableRow . "<tr class=\"error\">";
		} else {
			$tableRow = $tableRow . "<tr class=\"success\">";
		}
		
	$tableRow = $tableRow . "<td>" . $dev['Name'] . "</td>
      <td>" . $dev['ID'] . "</td>
      <td>" . $temperature . "</td>
      <td><a href='http://mineforeman.com/bitcoin-mining-calculator/?hash=" . $dev['MHSav'] . "' target='_blank'>" . $hrate . "</a></td>
      <td>" . $dev['Accepted'] . "</td>
      <td>" . $dev['Rejected'] . " [" . round($dev['DeviceRejected%'], 2) . "%]</td>
      <td>" . $dev['HardwareErrors'] . " [" . round($dev['DeviceHardware%'], 2) . "%]</td>
      <td>" . $dev['Utility'] . "</td>
      <td>" . date('H:i:s', $dev['LastShareTime']) . "</td>
      </tr>";

		$devices++;
		$MHSav = $MHSav + $dev['MHSav'];
		$Accepted = $Accepted + $dev['Accepted'];
		$Rejected = $Rejected + $dev['Rejected'];
		$HardwareErrors = $HardwareErrors + $dev['HardwareErrors'];
		$DeviceRejected = $DeviceRejected + $dev['DeviceRejected%'];
		$hwErrorPercent = $hwErrorPercent + $dev['DeviceHardware%'];
		$Utility = $Utility + $dev['Utility'];



	}
  }

        if ($MHSav > 999){
          $hrateT = $MHSav / 1000;
	  $hrateT = number_format((float)$hrateT, 2, '.', '');
          $hrateT = $hrateT . " GH/s";
        }else{
	  $hrateT = $MHSav . " MH/s";
        }
  $GLOBALS['G_MHSav'] = $hrateT . "|" . $devices . " DEV";
  $totalShares = $Accepted + $Rejected + $HardwareErrors;
  $tableRow = $tableRow . "
  </tbody>
  <tfoot>
  <tr>
  <th>Totals</th>
  <th>" . $devices . "</th>
  <th></th>
  <th><a href='http://mineforeman.com/bitcoin-mining-calculator/?hash=" . $MHSav . "' target='_blank'>" . $hrateT . "</a></th>
  <th>" . $Accepted . "</th>
  <th>" . $Rejected . " [" . round(($DeviceRejected / $devices), 2) . "%]</th>
  <th>" . $HardwareErrors . " [" . round(($hwErrorPercent / $devices), 2) . "%]</th>
  <th>" . $Utility . "</th>
  <th></th>
  </tr>
  </tfoot>
  </tbody>
  </table>
  ";

  return $tableRow;
}

