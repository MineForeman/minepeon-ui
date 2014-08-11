<?php

$statusFile = "/tmp/status.json";

$status = json_decode(file_get_contents($statusFile, true), true);


function writeStatus($status) {
  file_put_contents("/tmp/status.json", json_encode($status, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK));
}
