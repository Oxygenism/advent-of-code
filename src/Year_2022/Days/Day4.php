<?php

namespace App\Advent\Year_2022\Days;

use App\Advent\Utility\DataService;

class Day4
{
    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        return $this->run();
    }

    public function runB()
    {
        return $this->run(true);
    }

    public function run($state = false) {
        $handle = $this->dataService->read();
        $count = 0;
        while ($handle->valid()) {
            $input = trim($handle->current());
            $pairs = explode(',', $input);
            $instructions = [];
            foreach ($pairs as $instructionSet) {
                $instruction = explode('-', $instructionSet);
                $instructions[] = $instruction;
            }

            if ($state) {
                $count += !empty(array_intersect(
                    range($instructions[0][0], $instructions[0][1]),
                    range($instructions[1][0], $instructions[1][1]))
                ) ? 1 : 0;
            } else {
                if ($instructions[0][0] >= $instructions[1][0] && $instructions[0][1] <= $instructions[1][1]) {
                    $count++;
                } elseif ($instructions[1][0] >= $instructions[0][0] && $instructions[1][1] <= $instructions[0][1]) {
                    $count++;
                }
            }

            $handle->next();
        }

        return $count;
    }
}