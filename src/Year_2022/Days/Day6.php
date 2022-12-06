<?php

namespace App\Advent\Year_2022\Days;

use App\Advent\Utility\DataService;

class Day6
{
    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        return $this->run(4);
    }

    public function runB()
    {
        return $this->run(14);
    }

    public function run($length) {
        $count = 0;
        $handle = $this->dataService->read();
        $input = str_split($handle->current());
        $stack = [];
        foreach ($input as $char) {
            $count++;
            if (count($stack) >= $length) {
                array_shift($stack);
            }

            $stack[] = $char;

            if (count(array_unique($stack)) >= $length) {
                return $count;
            }
        }
    }
}