<?php

$statusFile = "/tmp/status.json";

$status = json_decode(file_get_contents($statusFile, true), true);


function writeStatus($status) {
  file_put_contents($statusFile, json_encode($settings, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK));
}
