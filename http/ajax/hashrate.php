#!/usr/bin/php
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
require_once('settings.inc.php');
require_once('status.inc.php');

// Grab the summary and pool data
$summary = miner("summary", "");
$pools = miner("pools", "");

// Get the hashrate and echo it for the update script
$hashrate = $summary['SUMMARY'][0]['MHSav'] * 1000;
$hashrate = time() . ':' . $hashrate;
$update = array($hashrate);
echo $hashrate;

// Write the pool and summary data
writeStatus(array_merge($pools['POOLS'][0], $summary['SUMMARY'][0]));


?>
