<?php

namespace App\Advent\Days;

use JetBrains\PhpStorm\Pure;
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
        $handle = $this->dataService->read("day6.txt");
        $fishString = $handle->current();
        $fishString = $this->sanitizeInput($fishString);
        $fishArray = explode(',', $fishString);
        $fishArray = DataService::getIntegerArray($fishArray);

        return $this->fishDuplication($fishArray, 80);
    }

    public function runB()
    {
        $handle = $this->dataService->read("day6.txt");
        $fishString = $handle->current();
        $fishString = $this->sanitizeInput($fishString);
        $fishArray = explode(',', $fishString);
        $fishArray = DataService::getIntegerArray($fishArray);

        return $this->fishDuplication($fishArray, 256);
    }

    public function fishDuplication($array, $days) {
        $template = array_fill(0, 9, 0);
        $fishAtAge = $template;
        foreach ($array as $fish) {
            $fishAtAge[$fish] += 1;
        }

        $newFishAge = $template;
        for ($i = 0; $i < $days; $i++) {
            foreach ($fishAtAge as $age=>$fish) {
                if ($age == 0) {
                    $newFishAge[8] += $fish;
                    $newFishAge[6] += $fish;
                } else {
                    $newFishAge[$age - 1] += $fish;
                }
            }

            $fishAtAge = $newFishAge;
            $newFishAge = $template;
        }

        return array_sum($fishAtAge);
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