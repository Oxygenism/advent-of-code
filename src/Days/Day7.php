<?php

namespace App\Advent\Days;

use JetBrains\PhpStorm\Pure;
use App\Advent\Utility\DataService;

class Day7
{
    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $handle = $this->dataService->read("day7.txt");
        $inputString = $handle->current();
        $sanitizedString = $this->sanitizeInput($inputString);
        $inputArray = explode(',', $sanitizedString);
        $positions = DataService::getIntegerArray($inputArray);

        $func = function(&$item, $key, $mostFreqPosition) {
            $diff = abs($item - $mostFreqPosition);
            $item = $diff;
        };

        return $this->getLeastFuelConsumption($positions, $func);
    }

    public function runB()
    {
        $handle = $this->dataService->read("day7.txt");
        $inputString = $handle->current();
        $sanitizedString = $this->sanitizeInput($inputString);
        $inputArray = explode(',', $sanitizedString);
        $positions = DataService::getIntegerArray($inputArray);
        $avg = floor(array_sum($positions) / count($positions));

        $func = function(&$item, $key, $avg) {
            $diff = abs($item - $avg);
            $item = array_sum(range(0, $diff));
        };

        array_walk($positions, $func, $avg);
        return array_sum($positions);
    }

    function getLeastFuelConsumption($positions, $func) {
        $arrayMax = max($positions);
        $leastFuel = PHP_INT_MAX;
        $foundPos = 0;
        for ($i = 0; $i < $arrayMax; $i++) {
            $tempPositions = $positions;
            array_walk($tempPositions, $func, $i);
            $sum = array_sum($tempPositions);
            if ($sum < $leastFuel) {
                $leastFuel = $sum;
                $foundPos = $i;
            }
        }

        return $leastFuel;
    }

    public function sanitizeInput($input) {
        //https://www.w3schools.com/php/php_ref_filter.asp might help.. someday
        //https://www.w3schools.com/php/php_ref_string.asp string manipulation
        //https://www.w3schools.com/php/php_ref_array.asp array stuff
        //https://www.w3schools.com/php/php_ref_math.asp math stuff


        $trimmed = ltrim($input); //left trim
        return rtrim($trimmed); //right trim
    }
}