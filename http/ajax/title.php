<?php
require_once('miner.inc.php');

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

echo $hrateT . "|" . $devices . " DEV|" . $version;
