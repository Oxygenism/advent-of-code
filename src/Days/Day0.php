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
        $this->logger->log("aaaaaaaaa");
        $this->dataService->read("day1.txt");
    }

    public function runB()
    {
        $this->logger->log("bbbbbbbbb");
    }
}