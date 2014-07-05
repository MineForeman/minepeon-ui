#!/usr/bin/php
<?php

include('settings.inc.php');
require('miner.inc.php');

$return = miner("summary", "");

$hashrate = $return['SUMMARY'][0]['MHSav'] * 1000;

$hashrate = time() . ':' . $hashrate;

$update = array(
        $hashrate
);

// ###############################

// $ret = rrd_update($RRD, $update);

echo $hashrate;

?>
