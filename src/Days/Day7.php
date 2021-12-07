<?php

namespace App\Advent\Days;

use JetBrains\PhpStorm\Pure;
use App\Advent\Utility\DataService;

class Day7
{
    private DataService $dataService;

    #[Pure] public function __construct()
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
        $countPositions = array_count_values($positions);

        $func = function(&$item, $key, $mostFreqPosition) {
            if ($item > $mostFreqPosition) {
                $diff = abs($item - $mostFreqPosition);
            } else {
                $diff = abs($mostFreqPosition - $item);
            }

            $item = $diff;

        };

        $leastFuel = PHP_INT_MAX;
        print_r($countPositions);
        foreach ($countPositions as $check) {
            $tempPositions = $positions;
            array_walk($tempPositions, $func, $check);
            $sum = array_sum($tempPositions);
            if ($sum < $leastFuel) {
                $leastFuel = $sum;
            }
        }

        return $leastFuel;
    }

    public function runB()
    {
        $handle = $this->dataService->read("day7_test.txt");
        $inputString = $handle->current();
        $sanitizedString = $this->sanitizeInput($inputString);
        $inputArray = explode(',', $sanitizedString);
        $positions = DataService::getIntegerArray($inputArray);
        $countPositions = array_count_values($positions);
        $maxKey = array_keys($countPositions, max($countPositions));
        $mostFreqPosition = $maxKey[0];

        $func = function(&$item, $key, $mostFreqPosition) {
            if ($item > $mostFreqPosition) {
                $diff = abs($item - $mostFreqPosition);
            } else {
                $diff = abs($mostFreqPosition - $item);
            }

            $item = $diff;

        };
        array_walk($positions, $func, $mostFreqPosition);
        return array_sum($positions);
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