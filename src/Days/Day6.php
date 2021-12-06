<?php

namespace App\Advent\Days;

use JetBrains\PhpStorm\Pure;
use App\Advent\Utility\DataService;

class Day6
{
    private DataService $dataService;

    #[Pure] public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $handle = $this->dataService->read("day6_test.txt");
        $fishString = $handle->current();
        $fishString = $this->sanitizeInput($fishString);
        $fishArray = explode(',', $fishString);
        $fishArray = DataService::getIntegerArray($fishArray);

        $newFishCount = 0;
        for ($i = 0; $i < 18; $i++) {
            foreach ($fishArray as $key=>$lanternFish) {
                if ($lanternFish === 0) {
                    $fishArray[] = 8;
                    $key=>
                } else {}
            }
//            foreach ($lanternFishTracker as $lanternFish) {
//                $newFish = $lanternFish->countDown();
//                if ($newFish === true) {
//                    $newFishCount++;
//                }
//            }
//
//            for ($i = 0; $i < $newFishCount; $i++) {
//                $lanternFishTracker[] = new LanternFish(9);
//            }
//            $newFishCount = 0;
        }
        print_r($lanternFishTracker);
        return count($lanternFishTracker);
    }

    public function runB()
    {
        $handle = $this->dataService->read("day6_test.txt");



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

class LanternFish {
    public $internalTimer = 0;

    /**
     * @param int $internalTimer
     */
    public function __construct(int $internalTimer)
    {
        $this->internalTimer = $internalTimer;
    }

    public function countDown() {
        $this->internalTimer -= 1;
        if ($this->internalTimer === 0) {
            $this->internalTimer = 7;
            return true;
        }

        return false;
    }

}