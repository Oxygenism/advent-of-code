<?php

namespace App\Advent\Days;

use JetBrains\PhpStorm\Pure;
use App\Advent\Utility\DataService;
use App\Advent\Utility\Logger;

class Day2
{
    private DataService $dataService;
    private Logger $logger;

    #[Pure] public function __construct()
    {
        $this->dataService = new DataService();
        $this->logger = new Logger();
    }

    public function runA()
    {
        $this->logger->log("=== DAY 2 A ===");
        $handle = $this->dataService->read("day2_test.txt");



//        $this->logger->log($count);
        $this->logger->log('');
    }

    public function runB()
    {
        $this->logger->log("=== DAY 2 B ===");
        $handle = $this->dataService->read("day2_test.txt");



//        $this->logger->log($count);
        $this->logger->log('');
    }
}