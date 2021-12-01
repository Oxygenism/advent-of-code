<?php

namespace App\Advent\Days;

use JetBrains\PhpStorm\Pure;
use App\Advent\Utility\DataService;
use App\Advent\Utility\Logger;

class day1
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
        $this->logger->log("=== DAY 1 A ===");
        $values = $this->dataService->read("day1.txt");

        $prev = $values->current();
        $count = 0;

        $values->next();
        foreach ($values as $value) {
            if ($value > $prev) {
                $count++;
            }

            $prev = $value;
        }

        $this->logger->log($count);
        $this->logger->log('');
    }

    /**
     * Hi, I like to overcomplicate things. I am still learning, mmkay.  You try PHP arrays.
     */
    public function runB()
    {
        $this->logger->log("=== DAY 1 B ===");
        $handle = $this->dataService->read("day1.txt");

        $prev = [];
        $current = [];

        for ($i = 0; $i < 3; $i++) {
            $current[$i] = $handle->current();
            $handle->next();
        }

        $count = 0;
        while ($handle->valid()) {
            $prev = $current;

            array_shift($current);
            $current[2] = $handle->current();

            if (array_sum($current) > array_sum($prev)) {
                $count++;
            }

            $handle->next();
        }

        $this->logger->log($count);
        $this->logger->log('');
    }
}