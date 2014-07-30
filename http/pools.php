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


// set the number of extra empty rows for adding pools
$extraPools = 2;

// read the miner config file
$minerConf = file_get_contents("/opt/minepeon/etc/miner.conf", true);

// decode the json
$data = json_decode($minerConf, true);

// check whether there is POST data to handle
if (!empty($_POST)) {

  // unset the pools
  unset($data['pools']);

  // initialize the POST data counter
  $j = 0;

  //initialize a limit to the number of pools that are added to the miner config file. is there an official limit?
  $poolLimit = 20;

  // as long as the POST URL, USER and PASS data are present and the count is under the poolLimit, process the POST data
  while ($j<$poolLimit) {

    // Is the pool data empty?
    if (!empty($_POST['URL'.$j.'']) and !empty($_POST['USER'.$j.'']) and !empty($_POST['PASS'.$j.''])) {
      // Construct pool at j
      $pool = array(
        "url" => $_POST['URL'.$j.''],
        "user" => $_POST['USER'.$j.''],
        "pass" => $_POST['PASS'.$j.''],
      );

      // Set pool at j
      array_push($data['pools'][] = $pool);
    }
    // increment count
    $j++;
  }

  // Recode into JSON and save, restart the miner and then re-read the pools
  file_put_contents("/opt/minepeon/etc/miner.conf", json_encode($data, JSON_PRETTY_PRINT));
  miner("quit");
  $minerConf = file_get_contents("/opt/minepeon/etc/miner.conf", true);
  $data = json_decode($minerConf, true);
}

include('static/head.php');
include('static/menu.php');

?>
<div class="container">
  <p class="alert">
    <b>WARNING:</b>
    There is very little validation on these settings at the moment so make sure your settings are correct!
  </p>
  <h1>Pools</h1>
  <p>MinePeon will use the following pools. Change it to your mining accounts or leave it to donate.</p>
  <form id="formpools" name="input" action="/pools.php" method="post">
<?php

// set the number of populated pools
$countOfPools = count($data['pools']);

for ($i = 0; $i < $countOfPools; $i++) {
  if (!empty($data['pools'][$i]['url']) and !empty($data['pools'][$i]['user']) and !empty($data['pools'][$i]['pass'])) {

    poolRow($i, 
      $data['pools'][$i]['url'], 
      $data['pools'][$i]['user'], 
      $data['pools'][$i]['pass']);

  }
}

//output extra empty rows to accomodate adding more pools

for ($k = $countOfPools; $k < $countOfPools+$extraPools; $k++) { 
  // Populate empty pool row's
  poolRow($k);
}


?>
    <p>After saving, the miner will restart with the new configuration. This takes about 10 seconds.</p>
    <p><input type="submit" value="Submit"></p>
  </form>
</div>

<?php

include('static/foot.php');


function poolRow($rowNumber, $rowURL = "", $rowUser = "", $rowPass = "") {
  // $rowNumber- Required
  // $rowURL- optional
  // $rowUser- optional
  // $rowPass- optional
?>  
<!--Begin empty block for blank pools-->
<div class="form-group row">
  <div class="col-lg-5">
    URL: 
    <input type="text" class="form-control" type="text" value="<?php echo $rowURL; ?>" name="URL<?php echo $rowNumber; ?>">
  </div>
  <div class="col-lg-5">
    Username: 
    <input type="text" class="form-control" type="text" value="<?php echo $rowUser; ?>" name="USER<?php echo $rowNumber; ?>">
  </div>
  <div class="col-lg-2">
    Password: 
    <input type="text" class="form-control" type="text" value="<?php echo $rowPass; ?>" name="PASS<?php echo $rowNumber; ?>">
  </div>
</div>
<?php
}
