<?php

namespace App\Advent\Days;

use JetBrains\PhpStorm\Pure;
use App\Advent\Utility\DataService;
use App\Advent\Utility\Logger;

class day0
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
        $this->logger->log("=== DAY 0 A ===");
        $handle = $this->dataService->read("day0_test.txt");



//        $this->logger->log($count);
        $this->logger->log('');
    }

    public function runB()
    {
        $this->logger->log("=== DAY 0 B ===");
        $handle = $this->dataService->read("day0_test.txt");



//        $this->logger->log($count);
        $this->logger->log('');
    }
}