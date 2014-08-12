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

// Generic function to communicate with cg/bfg miner

function miner($command, $parameter = "", $host = "127.0.0.1", $port = 4028) {
  // $command- Required
  // $parameter- optional
  // $host- optional
  // $port- optional

  $responce = "";

  // Construct miner command
  $command = array (
    "command"  => $command,
    "parameter" => $parameter
  );

  // Encode command as JSON
  $jsonCmd = json_encode($command);

  // Create streaming socket to miner

  if ($client = @stream_socket_client("tcp://$host:$port", $errno, $errorMessage)) {
  
    // Write JSON to socket, read the rsponce and close the socket
    fwrite($client, $jsonCmd);
    $response = stream_get_contents($client);
    fclose($client);

    // Sanitise the responce
    $response = preg_replace("/[^[:alnum:][:punct:]]/","",$response);
    $response = json_decode($response, true);
  }

  return $response;

}

// find a pool
function findPool($poolURL, $poolUser){
  $pools = miner('pools','')['POOLS'];
  $pool = false;
  $pooln = 0;
  foreach ($pools as $key => $value) {
    if($value['URL'] == $poolURL and $value['User'] == $poolUser){
      $pool = $pooln;

    }
      $pooln = $pooln + 1;
  }
  return $pool;
}


