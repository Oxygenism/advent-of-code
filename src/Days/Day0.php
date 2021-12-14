<?php

namespace App\Advent\Days;

use App\Advent\Utility\DataService;

class Day0
{
    private DataService $dataService;

    public function __construct()
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