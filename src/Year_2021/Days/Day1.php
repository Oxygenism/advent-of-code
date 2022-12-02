<?php

namespace App\Advent\Year_2021\Days;

use App\Advent\Utility\DataService;

class Day1
{
    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $values = $this->dataService->read();

        $prev = $values->current();
        $count = 0;

        $values->next();
        foreach ($values as $value) {
            if ($value > $prev) {
                $count++;
            }

            $prev = $value;
        }

        return $count;
    }

    /**
     * Hi, I like to overcomplicate things. I am still learning, mmkay.  You try PHP arrays.
     */
    public function runB()
    {
        $handle = $this->dataService->read();

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

        return $count;
    }
}