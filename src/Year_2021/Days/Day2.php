<?php

namespace App\Advent\Year_2021\Days;

use App\Advent\Utility\DataService;

class Day2
{
    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }
    public function runA()
    {
        $handle = $this->dataService->read("day2.txt", 'Year_2021/');

        $horizontal = 0;
        $depth = 0;

        foreach ($handle as $instruction) {
            $splitInstruction = explode(' ', $instruction);
            if ($splitInstruction[0] === 'forward') {
                $horizontal +=  (int) $splitInstruction[1];
            } else if ($splitInstruction[0] === 'up') {
                $depth -= (int) $splitInstruction[1];
            } else if ($splitInstruction[0] === 'down') {
                $depth += (int) $splitInstruction[1];
            }
        }

        return $horizontal * $depth;
    }

    public function runB()
    {
        $handle = $this->dataService->read("day2.txt", 'Year_2021/');

        $horizontal = 0;
        $depth = 0;
        $aim = 0;

        foreach ($handle as $instruction) {
            $splitInstruction = explode(' ', $instruction);
            if ($splitInstruction[0] === 'forward') {
                $horizontal = $horizontal + (int) $splitInstruction[1];
                $depth += (int) $splitInstruction[1] * $aim;
            } else if ($splitInstruction[0] === 'up') {
                $aim -= (int) $splitInstruction[1];
            } else if ($splitInstruction[0] === 'down') {
                $aim  += (int) $splitInstruction[1];
            }
        }

        $h = $d = $a = 0;

        return $horizontal * $depth;
    }
}