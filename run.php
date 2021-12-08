<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

use App\Advent\Utility\DataService;
use App\Advent\Utility\Logger;
use App\Advent\Utility\Timer;

$logger = new Logger();
$dataService = new DataService();
$timer = new Timer();
$logger->unlink();

$handle = $dataService->read('introduction.txt');
foreach ($handle as $line) {
    $logger->log($line);
}

$days = [
    new App\Advent\Days\Day1(),
    new App\Advent\Days\Day2(),
    new App\Advent\Days\Day3(),
    new App\Advent\Days\Day4(),
    new App\Advent\Days\Day5(),
    new App\Advent\Days\Day6(),
    new App\Advent\Days\Day7(),
    new App\Advent\Days\Day8(),
];

foreach ($days as $day) {
    $timer->run($day, 'RunA');
    $timer->run($day,  'RunB');
}

