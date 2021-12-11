<?php

namespace App\Advent\Days;

use JetBrains\PhpStorm\Pure;
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

    public function sanitizeInput($input) {
        //https://www.w3schools.com/php/php_ref_filter.asp might help.. someday
        //https://www.w3schools.com/php/php_ref_string.asp string manipulation
        //https://www.w3schools.com/php/php_ref_array.asp array stuff
        //https://www.w3schools.com/php/php_ref_math.asp math stuff


        $trimmed = ltrim($input); //left trim
        return rtrim($trimmed); //right trim
    }
}