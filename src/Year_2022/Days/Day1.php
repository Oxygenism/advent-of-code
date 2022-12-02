<?php

namespace App\Advent\Year_2022\Days;

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
        $caloriesForElves = $this->getCaloriesForElves();
        return max($caloriesForElves);
    }

    public function runB()
    {
        $caloriesForElves = $this->getCaloriesForElves();

        rsort($caloriesForElves);

        $sum = 0;
        for ($i = 0; $i < 3; $i++) {
            $sum += $caloriesForElves[$i];
        }

        return $sum;
    }

    public function getCaloriesForElves(): array
    {
        $handle = $this->dataService->read();
        $caloriesForElf = [];
        $caloriesForElves = [];
        while ($handle->valid()){
            if ($handle->current() === PHP_EOL) {
                $caloriesForElves[] = array_sum($caloriesForElf);
                $caloriesForElf = [];
            } else {
                $input = trim(str_replace("\r\n",'', $handle->current()));
                $caloriesForElf[] = $input;
            }

            $handle->next();
        }

        return $caloriesForElves;
    }
}