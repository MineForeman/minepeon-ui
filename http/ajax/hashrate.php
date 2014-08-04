#!/usr/bin/php
<?php

include('settings.inc.php');
require('miner.inc.php');

// Save the hashrate to minepeon.conf


$return = miner("summary", "");

$hashrate = $return['SUMMARY'][0]['MHSav'] * 1000;

// Save the hashrate to minepeon.conf

$settings['current_hashrate'] = $hashrate;

writeSettings($settings);

$hashrate = time() . ':' . $hashrate;

$update = array(
        $hashrate
);

// ###############################

// $ret = rrd_update($RRD, $update);

echo $hashrate;

?>
