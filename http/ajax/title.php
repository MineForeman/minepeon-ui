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

$stats   = miner("devs", "");
$devs    = $stats['DEVS'];

if (count($devs) == 0) {
    echo "No devices running";
} else {
    
    $devices        = 0;
    $MHSav          = 0;
    $Accepted       = 0;
    $Rejected       = 0;
    $HardwareErrors = 0;
    $Utility        = 0;
    
    $hwErrorPercent = 0;
    $DeviceRejected = 0;
    
    foreach ($devs as $dev) {
        
        // Sort out valid devices
        
        $validDevice = true;
        
        if (!isset($dev['MHS5s'])) {
            $dev['MHS5s'] = 0;
        }
        
        if (!isset($dev['MHS20s'])) {
            $dev['MHS20s'] = 0;
        }
        
        if (!$dev['MHS5s'] > 1 || !$dev['MHS20s'] > 1) {
            // not mining, not a valid device
            $validDevice = false;
        }
        
        if ($validDevice) {
            $devices++;
            $MHSav          = $MHSav + $dev['MHSav'];
            $Accepted       = $Accepted + $dev['Accepted'];
            $Rejected       = $Rejected + $dev['Rejected'];
            $HardwareErrors = $HardwareErrors + $dev['HardwareErrors'];
            $DeviceRejected = $DeviceRejected + $dev['DeviceRejected%'];
            $hwErrorPercent = $hwErrorPercent + $dev['DeviceHardware%'];
            $Utility        = $Utility + $dev['Utility'];            
        }
    }
    
    if ($MHSav > 999) {
        $hrateT = $MHSav / 1000;
        $hrateT = number_format((float) $hrateT, 2, '.', '');
        $hrateT = $hrateT . " GH/s";
    } else {
        $hrateT = $MHSav . " MH/s";
    }
}

$title = $hrateT . "|" . $devices . " DEV"; // |" . $version;

echo '
<script language="javascript" type="text/javascript"> 
	document.title = "' . $title . '";
</script>';

