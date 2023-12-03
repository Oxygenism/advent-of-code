<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

use App\Advent\Utility\DataService;
use App\Advent\Utility\Logger;
use App\Advent\Utility\Timer;

$logger = new Logger();
$dataService = new DataService();
$timer = new Timer();
$namespace = "App\\Advent\\Year_%d\\Days\\Day%d";

if ($argv[1] === "all") {
    $years = range(2015, 2030);
    $days = range(1, 25);
    foreach ($years as $year) {
        foreach ($days as $day) {
            try {
                $GLOBALS['YEAR'] = (int) $year;
                $GLOBALS['DAY'] = (int) $day;
                $GLOBALS['USE_TESTFILE'] = false;
                $dayNamespace = sprintf("App\\Advent\\Year_%d\\Days\\Day%d", $year, $day);
                if (class_exists($dayNamespace)) {
                    $day = new $dayNamespace;
                    runDay($day, $logger, $timer);
                }
            } catch (Exception $e) {
                echo "Day {$day} of year {$year} done goofed. Run individually to see error";
            }
        }
    }

} else {
    $clearLog = isset($argv[4]) ?? filter_var($argv[4], FILTER_VALIDATE_BOOLEAN);
    if ($clearLog) {
        $logger->unlink();
    }

    $GLOBALS['YEAR'] = (int) $argv[1];
    $GLOBALS['DAY'] = (int) $argv[2];
    $GLOBALS['USE_TESTFILE'] = filter_var($argv[3], FILTER_VALIDATE_BOOLEAN);

    $dayNamespace = sprintf("App\\Advent\\Year_%d\\Days\\Day%d", $GLOBALS['YEAR'], $GLOBALS['DAY']);
    $day = new $dayNamespace;
    runDay($day, $logger, $timer);
}

function runDay($day, $logger, $timer) {
    $logger->log("Ran at: " . Date("[Y-m-d H:i:s]"));

    $timer->run($day, 'RunA');
    $timer->run($day, 'RunB');

    $logger->log("--------------------");
}