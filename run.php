<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

use App\Advent\Utility\DataService;
use App\Advent\Utility\Logger;
use App\Advent\Utility\Timer;
use App\Advent\Days\Day1;
use App\Advent\Days\Day2;


$logger = new Logger();
$dataService = new DataService();
$timer = new Timer();
$logger->unlink();

$handle = $dataService->read('introduction.txt');
foreach ($handle as $line) {
    $logger->log($line);
}

$days = [
    new Day1(),
    new Day2()
];

foreach ($days as $day) {
    $timer->run($day, 'RunA');
    $timer->run($day, 'RunB');
}

