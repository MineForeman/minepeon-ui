<?php
/****************************************************************
*            _                                                  *
*           (_)  http://minepeon.com                            *
*  _ __ ___  _ _ __   ___ _ __   ___  ___  _ __                 *
* |  _   _ \| |  _ \ / _ \  _ \ / _ \/ _ \|  _ \                *
* | | | | | | | | | |  __/ |_) |  __/ (_) | | | |               *  
* |_| |_| |_|_|_| |_|\___|  __/ \___|\___/|_| |_|               *
*                        | |                                    *
*                        |_| 12Ui8w9q6eq6TxZmow8H9VHbZdfEnsLDsB *
*****************************************************************
STANDARDS!!!  

Before you make any changes to push be sure to read the README.md
avaible in the root of this repo.

*****************************************************************/

require_once('miner.inc.php');
require_once('functions.inc.php');
require_once('settings.inc.php');

// Change the pool if requested 

if (isset($_POST['url'])) {
        
  $pools = miner('pools','')['POOLS'];
  $pool = 0;

  foreach ($pools as $key => $value) {
    if(isset($value['User']) && $value['URL']==$_POST['url']) {
      miner('switchpool',$pool);
    }
    $pool = $pool + 1;
  }
}

// Static head page element
include('static/head.php');
include('static/menu.php');

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
 <div id="devices1"><?php include_once("ajax/devices.php"); ?></div>
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

<script type="text/javascript">
    $(document).ready(function () {

        // Reload the ajax elements every seccond
        setInterval(function () {
            $("#title1").load("ajax/title.php");
            $("#status1").load("ajax/status.php");
            $("#devices1").load("ajax/devices.php");
            $("#pools1").load("ajax/pools.php");
           }, <?php echo $settings['uiDataUpdate'] * 1000; ?>);

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
           }, <?php echo $settings['uiGraphUpdate'] * 1000; ?>);

        // Chart Toggle
        $( "#chartToggle" ).click(function() {
            $('.chartMore').slideToggle('slow', function() {
            });
        });
    });
</script>

<?php
include('static/foot.php');

