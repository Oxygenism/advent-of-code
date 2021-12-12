<?php

namespace App\Advent\Days;

use JetBrains\PhpStorm\Pure;
use App\Advent\Utility\DataService;

class Day11
{
    private DataService $dataService;
    public const DIRECTIONS = [
        "up" => [-1, 0],
        "down" => [1, 0],
        "left" => [0, -1],
        "right" => [0, 1],
        "upLeft" => [-1, -1],
        "upRight" => [-1, 1],
        "downLeft" => [1, -1],
        "downRight" => [1, 1],
    ];

    public $flashies = 0;
    public $octopuses = [];
    public $octopiLength = 0;
    public $octopiWidth = 0;
    public $timesFlashed = 0;
    public $duplicateForRunB;

    public $x = 0;
    public $y = 0;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $handle = $this->dataService->read("day11_test.txt");
        $this->octopiLength = $handle->count();
        $this->octopiWidth = count(str_split($this->sanitizeInput(($handle->current()))));
        foreach ($handle as $lineOfOctopi) {
            $sanitizedString = $this->sanitizeInput($lineOfOctopi);
            $inputArray = str_split($sanitizedString);
            $lineOfOctopuses = DataService::getIntegerArray($inputArray);

            $setData = function(&$item, $key) {
                $item = ["x" => $this->x, "y" => $this->y,"energy" => $item, "flashed" => false];
                $this->y += 1;
            };
            array_walk($lineOfOctopuses, $setData);
            array_push($this->octopuses, ...$lineOfOctopuses);

            $this->x += 1;
            $this->y = 0;
        }

        $this->duplicateForRunB = $this->octopuses;

        $totalOctopi = count($this->octopuses);
        $this->echoOutEnergies();
        for ($i = 0; $i < 2; $i++) {
            $increaseEnergy = function(&$item) {
                $item["energy"] += 1;
            };
            array_walk($this->octopuses, $increaseEnergy);

            for ($key = 0; $key < $totalOctopi; $key++) {
                $this->areFlashing($key);
            }

            $resetHasFlashed = function(&$item) {
                $item["flashed"] = false;
            };
            array_walk($this->octopuses, $resetHasFlashed);
        }

        return $this->flashies;
    }

    public function areFlashing($key)
    {
        if ($this->octopuses[$key]["flashed"] === false && $this->octopuses[$key]["energy"] > 9) {
            $this->flashies += 1;
            $this->octopuses[$key]["flashed"] = true;
            $this->octopuses[$key]["energy"] = 0;
            $this->increaseNeighbourEnergy($key);
            return true;
        }

        return false;
    }

    public function increaseNeighbourEnergy($key)
    {
        if ($this->octopuses[$key]["flashed"] === true) {
            foreach (self::DIRECTIONS as $direction) {
                $x = $this->octopuses[$key]['x'] + $direction[0];
                $y = $this->octopuses[$key]['y'] + $direction[1];
                $arrayPos = $this->getArrayPos($x, $y);
                if ($arrayPos !== false) {
                    if ($this->octopuses[$arrayPos]["flashed"] == false) {
                        $this->octopuses[$arrayPos]["energy"] += 1;
                    }
                    $this->areFlashing($arrayPos);
                }
            }
        }
    }

    public function getArrayPos($x, $y) {
        if ($y < 0 || $x < 0 || $x >= $this->octopiLength || $y >= $this->octopiWidth ) {
            return false;
        }
        return ($x * $this->octopiWidth) + $y;
    }

    public function runB()
    {
        $this->octopuses = $this->duplicateForRunB;
        $totalOctopi = count($this->octopuses);
        for ($i = 0; $i < 1000; $i++) {
            $increaseEnergy = function(&$item) {
                $item["energy"] += 1;
            };
            array_walk($this->octopuses, $increaseEnergy);

            for ($key = 0; $key < $totalOctopi; $key++) {
                $this->areFlashing($key);
            }

            $resetHasFlashed = function(&$item) {
                if ($item["flashed"]) {
                    $this->timesFlashed += 1;
                }
                $item["flashed"] = false;
            };

            if ($this->timesFlashed === $totalOctopi) {
                $this->echoOutEnergies();
                return $i;
            }else {
                $this->timesFlashed = 0;
            }
            array_walk($this->octopuses, $resetHasFlashed);
        }

        return "There is no synchronization, Just a bad programmer";
    }

    public function sanitizeInput($input) {
        //https://www.w3schools.com/php/php_ref_filter.asp might help.. someday
        //https://www.w3schools.com/php/php_ref_string.asp string manipulation
        //https://www.w3schools.com/php/php_ref_array.asp array stuff
        //https://www.w3schools.com/php/php_ref_math.asp math stuff


        $trimmed = ltrim($input); //left trim
        return rtrim($trimmed); //right trim
    }

    /**
     * Just a simple printout of the current octopus table.
     * Not actually code that is needed.
     */
    public function echoOutEnergies() {
        $count = 0;
        $mask = "| %2.5s ";
        for ($i = 0; $i < $this->octopiWidth; $i++) {
            printf($mask, "P$i");
        }
        echo PHP_EOL;
        foreach ($this->octopuses as $octopus) {
            printf($mask, $octopus["energy"]);
            $count++;
            if ($count == $this->octopiWidth) {
                $count = 0;
                echo PHP_EOL;
            }
        }

        echo PHP_EOL;
    }
}