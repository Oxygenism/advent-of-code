<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

use App\Advent\Days\Day0;
use App\Advent\Days\Day1;

////Day0
//$day0 = new Day0();
//$day0->runA();
//$day0->runB();

//Day0
$day0 = new Day1();
$day0->runA();
$day0->runB();
