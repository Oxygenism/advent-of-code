<?php

namespace App\Advent\Days;

use JetBrains\PhpStorm\Pure;
use App\Advent\Utility\DataService;

class Day3
{
    private DataService $dataService;

    #[Pure] public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $handle = $this->dataService->read("day3.txt");
        $bitLength = strlen(trim($handle->current()));

        $gamma = $epsilon = "";

        $count0 = array_fill(0, $bitLength, 0);
        $count1 = $count0;
        foreach ($handle as $bits) {
            $splitBits = str_split(trim($bits));
            foreach ($splitBits as $key=>$bit) {
                if ($bit == 1) {
                    $count0[$key] += 1;
                } else {
                    $count1[$key] += 1;
                }
            }
        }

        for ($i = 0; $i < count($count1); $i++) {
            if ($count0[$i] < $count1[$i]) {
                $gamma .= "1";
                $epsilon .= "0";
            } else {
                $epsilon .= "1";
                $gamma .= "0";
            }
        }

        return bindec($gamma) * bindec($epsilon);
    }

    public function runB()
    {
        $handle = $this->dataService->read("day3.txt");

        $oxyGen = $this->getBitsFor(0, $handle);
        $co2Scrub = $this->getBitsFor(0, $handle, true);

        return bindec($oxyGen[0]) * bindec($co2Scrub[0]);
    }

    function getCommonAt($pos, $bits) {
        $arrayLength = count($bits);
        $count = 0;
        foreach ($bits as $bit) {
            if ($bit[$pos] == 1) {
                $count++;
            }
        }

        if ($count == ($arrayLength/2)) {
            return -1;
        }

        return ($count > ($arrayLength/2)) ? 0 : 1;
    }

    function getBitsFor($pos, $bits, $opposite = false)
    {
        $common = $this->getCommonAt($pos, $bits);

        if ($opposite) {
            $common = ($common == 1) ? 0 : 1;
        }

        if ($common == -1) {
            $common = ($opposite) ? 1 : 0;
        }

        $result = [];
        foreach ($bits as $bit) {
            if ($bit[$pos] == $common || $common == -1) {
                $result[] = $bit;
            }
        }

        if (count($result) !== 1) {
            $result = $this->getBitsFor($pos + 1, $result, $opposite);
        }

        return $result;
    }
}