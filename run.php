<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

use App\Advent\Utility\DataService;
use App\Advent\Utility\Logger;
use App\Advent\Utility\Timer;

$logger = new Logger();
$dataService = new DataService();
$timer = new Timer();

if (isset($argv[3]) && (bool) $argv[3] === true) {
    $logger->unlink();
}

$yearInput = (int) $argv[1];
$dayInput = (int) $argv[2];
$dayNamespace = sprintf("App\\Advent\\Year_%d\\Days\\Day%d", $yearInput, $dayInput);
$day = new $dayNamespace;

$logger->log("Ran at: " . Date("[Y-m-d H:i:s]"));

$timer->run($day, 'RunA');
$timer->run($day,  'RunB');

$logger->log("--------------------");
