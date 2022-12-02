<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

use App\Advent\Utility\DataService;
use App\Advent\Utility\Logger;
use App\Advent\Utility\Timer;

$logger = new Logger();
$dataService = new DataService();
$timer = new Timer();

$clearLog = filter_var($argv[4], FILTER_VALIDATE_BOOLEAN);
if ($clearLog) {
    $logger->unlink();
}

$GLOBALS['YEAR'] = (int) $argv[1];
$GLOBALS['DAY'] = (int) $argv[2];
$GLOBALS['USE_TESTFILE'] = filter_var($argv[3], FILTER_VALIDATE_BOOLEAN);

$dayNamespace = sprintf("App\\Advent\\Year_%d\\Days\\Day%d", $GLOBALS['YEAR'], $GLOBALS['DAY']);
$day = new $dayNamespace;

$logger->log("Ran at: " . Date("[Y-m-d H:i:s]"));

$timer->run($day, 'RunA');
$timer->run($day,  'RunB');

$logger->log("--------------------");
