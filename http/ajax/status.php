<?php
require_once('miner.inc.php');
include_once('functions.inc.php');
include_once("../lang/en/lang.en.php");
include_once('settings.inc.php');
$mpTemp = round(exec('cat /sys/class/thermal/thermal_zone0/temp') / 1000, 2);
$version = exec('cat /opt/minepeon/etc/version');
$mpCPULoad = sys_getloadavg();
$stats = miner("devs", "");
$status = $stats['STATUS'];
$devs = $stats['DEVS'];
$summary = miner("summary", "");
$pools = miner("pools", "");

?>
  <div class="row">
    <div class="col-lg-4">
      <dl class="dl-horizontal">
      <dt><?php echo $lang["MPtemp"]; ?></dt>
        <dd><?php echo $mpTemp; ?> <small>&deg;C</small> | <?php echo $mpTemp*9/5+32; ?> <small>&deg;F</small></dd>
        <dt><?php echo $lang["MPcpu"]; ?></dt>
        <dd><?php echo $mpCPULoad[0]; ?> <small>[1 min]</small></dd>
        <dd><?php echo $mpCPULoad[1]; ?> <small>[5 min]</small></dd>
        <dd><?php echo $mpCPULoad[2]; ?> <small>[15 min]</small></dd>
      </dl>
    </div>
    <div class="col-lg-4">
      <dl class="dl-horizontal">
        <dt><?php echo $lang["bestshare"]; ?></dt>
        <dd><?php echo $summary['SUMMARY'][0]['BestShare']; ?></dd>
        <dt><?php echo $lang["MPuptime"]; ?></dt>
        <dd><?php echo secondsToWords(round($uptime[0])); ?></dd>
        <dt><?php echo $lang["mineruptime"]; ?></dt>
        <dd><?php echo secondsToWords($summary['SUMMARY'][0]['Elapsed']); ?></dd>
 </dl>
    </div>
    <div class="col-lg-4">
      <dl class="dl-horizontal">
        <dt><?php echo $lang["MPversion"]; ?></dt>
        <dd><?php echo $version; ?></dd>
        <dt><?php echo $lang["minerversion"]; ?></dt>
        <dd><?php echo $summary['STATUS'][0]['Description']; ?></dd>
        <dt><?php echo $lang["donationmin"]; ?></dt>
        <dd><?php ?>
      </dl>
    </div>
  </div>
