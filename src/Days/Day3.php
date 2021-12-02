<?php

namespace App\Advent\Days;

use JetBrains\PhpStorm\Pure;
use App\Advent\Utility\DataService;

class Day3
{
    private DataService $dataService;

    #[Pure] public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $handle = $this->dataService->read("day0_test.txt");

//        return $count;
    }

    public function runB()
    {
        $handle = $this->dataService->read("day0_test.txt");



//        return $count;
    }
}