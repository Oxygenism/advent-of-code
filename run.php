<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

use App\Advent\Utility\DataService;
use App\Advent\Utility\Logger;
use App\Advent\Days\Day1;
use App\Advent\Days\Day2;


$logger = new Logger();
$dataService = new DataService();
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
    $day->RunA();
    $day->RunB();
}

